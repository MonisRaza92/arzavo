@extends('layouts.admin')
@section('title', 'Curriculum Architecture Flow')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

{{-- Alpine Tree Component Logic (Defined at top to ensure availability on initial mount & Turbo visits) --}}
<script>
    window.curriculumTreeData = function curriculumTreeData() {
        return {
            categories: @json($categories),
            teachers: @json($teachers),
            isDragging: false,

            // Active selections for cascading tree
            selectedCategoryId: {{ $selectedCategoryId ?: ($categories->first()?->id ?? 'null') }},
            selectedClassId: {{ $selectedClassId ?: ($selectedCategory?->classCourses->first()?->id ?? 'null') }},
            selectedSubjectId: {{ $selectedSubjectId ?: ($selectedClass?->subjects->first()?->id ?? 'null') }},

            // Dynamic SVG dashed connectors
            connectors: {
                catToClass: { path: '', circles: [] },
                classToSubject: { path: '', circles: [] }
            },

            // Modals: Add
            categoryAddModalOpen: false,
            classAddModalOpen: false,
            subjectAddModalOpen: false,

            // Modals: Edit
            categoryEditModalOpen: false,
            classEditModalOpen: false,
            subjectEditModalOpen: false,

            // Editing items state
            editingCategory: { id: null, name: '', description: '', image: '', status: true },
            editingClass: { id: null, academic_category_id: null, class_teacher_id: '', name: '', description: '', image: '', status: true },
            editingSubject: { id: null, class_courses_id: null, teacher_id: '', name: '', description: '', start_time: '', end_time: '', days: '', image: '', status: true },

            // Delete modal
            deleteModalOpen: false,
            deleteActionUrl: '',
            deleteItemType: '',
            deleteItemName: '',
            deleteEnrolledCount: 0,

            init() {
                this.initSelections();

                this.$nextTick(() => {
                    this.initSortables();
                    this.updateConnectors();
                    setTimeout(() => this.updateConnectors(), 100);
                    setTimeout(() => this.updateConnectors(), 300);
                    setTimeout(() => this.updateConnectors(), 600);
                });

                window.addEventListener('resize', () => this.updateConnectors());
                window.addEventListener('scroll', () => this.updateConnectors(), { passive: true });

                if (window.ResizeObserver) {
                    const ro = new ResizeObserver(() => this.updateConnectors());
                    const canvas = document.getElementById('curriculum-canvas');
                    if (canvas) ro.observe(canvas);
                }
            },

            // Restore user selection from URL query params or localStorage
            initSelections() {
                const urlParams = new URLSearchParams(window.location.search);
                const urlCat = urlParams.get('category') || urlParams.get('category_id');
                const urlCls = urlParams.get('class') || urlParams.get('class_id');
                const urlSub = urlParams.get('subject') || urlParams.get('subject_id');

                const storageCat = localStorage.getItem('arzavo_curriculum_selected_category');
                const storageCls = localStorage.getItem('arzavo_curriculum_selected_class');
                const storageSub = localStorage.getItem('arzavo_curriculum_selected_subject');

                const targetCatId = parseInt(urlCat || storageCat) || null;
                const targetClsId = parseInt(urlCls || storageCls) || null;
                const targetSubId = parseInt(urlSub || storageSub) || null;

                // 1. Resolve Category
                let activeCat = null;
                if (targetCatId) {
                    activeCat = this.categories.find(c => c.id === targetCatId);
                }
                if (!activeCat && this.categories.length > 0) {
                    activeCat = this.categories[0];
                }
                this.selectedCategoryId = activeCat ? activeCat.id : null;

                // 2. Resolve Class
                if (activeCat && activeCat.class_courses && activeCat.class_courses.length > 0) {
                    let activeCls = null;
                    if (targetClsId) {
                        activeCls = activeCat.class_courses.find(cls => cls.id === targetClsId);
                    }
                    if (!activeCls) {
                        activeCls = activeCat.class_courses[0];
                    }
                    this.selectedClassId = activeCls ? activeCls.id : null;

                    // 3. Resolve Subject
                    if (activeCls && activeCls.subjects && activeCls.subjects.length > 0) {
                        let activeSub = null;
                        if (targetSubId) {
                            activeSub = activeCls.subjects.find(sub => sub.id === targetSubId);
                        }
                        if (!activeSub) {
                            activeSub = activeCls.subjects[0];
                        }
                        this.selectedSubjectId = activeSub ? activeSub.id : null;
                    } else {
                        this.selectedSubjectId = null;
                    }
                } else {
                    this.selectedClassId = null;
                    this.selectedSubjectId = null;
                }

                this.persistSelections();
            },

            // Save user selection to localStorage and URL search params
            persistSelections() {
                if (this.selectedCategoryId) {
                    localStorage.setItem('arzavo_curriculum_selected_category', this.selectedCategoryId);
                }
                if (this.selectedClassId) {
                    localStorage.setItem('arzavo_curriculum_selected_class', this.selectedClassId);
                }
                if (this.selectedSubjectId) {
                    localStorage.setItem('arzavo_curriculum_selected_subject', this.selectedSubjectId);
                }

                try {
                    const url = new URL(window.location.href);
                    if (this.selectedCategoryId) url.searchParams.set('category', this.selectedCategoryId);
                    if (this.selectedClassId) url.searchParams.set('class', this.selectedClassId);
                    if (this.selectedSubjectId) url.searchParams.set('subject', this.selectedSubjectId);
                    window.history.replaceState({}, '', url.pathname + url.search);
                } catch (e) {}
            },

            get selectedCategory() {
                return this.categories.find(c => c.id === this.selectedCategoryId) || null;
            },
            get selectedClass() {
                if (!this.selectedCategory) return null;
                return (this.selectedCategory.class_courses || []).find(cls => cls.id === this.selectedClassId) || null;
            },
            get selectedSubject() {
                if (!this.selectedClass) return null;
                return (this.selectedClass.subjects || []).find(sub => sub.id === this.selectedSubjectId) || null;
            },

            // Cascade selectors
            selectCategory(cat) {
                if (this.selectedCategoryId === cat.id) {
                    return;
                }
                this.selectedCategoryId = cat.id;
                const firstClass = (cat.class_courses && cat.class_courses.length > 0) ? cat.class_courses[0] : null;
                this.selectedClassId = firstClass ? firstClass.id : null;
                const firstSub = (firstClass && firstClass.subjects && firstClass.subjects.length > 0) ? firstClass.subjects[0] : null;
                this.selectedSubjectId = firstSub ? firstSub.id : null;

                this.persistSelections();
                this.$nextTick(() => {
                    this.initSortables();
                    this.updateConnectors();
                    setTimeout(() => this.updateConnectors(), 100);
                    setTimeout(() => this.updateConnectors(), 300);
                });
            },
            selectClass(cls) {
                if (this.selectedClassId === cls.id) {
                    return;
                }
                this.selectedClassId = cls.id;
                const firstSub = (cls.subjects && cls.subjects.length > 0) ? cls.subjects[0] : null;
                this.selectedSubjectId = firstSub ? firstSub.id : null;

                this.persistSelections();
                this.$nextTick(() => {
                    this.initSortables();
                    this.updateConnectors();
                    setTimeout(() => this.updateConnectors(), 100);
                    setTimeout(() => this.updateConnectors(), 300);
                });
            },
            selectSubject(sub) {
                if (this.selectedSubjectId === sub.id) {
                    return;
                }
                this.selectedSubjectId = sub.id;

                this.persistSelections();
                this.$nextTick(() => {
                    this.initSortables();
                    this.updateConnectors();
                    setTimeout(() => this.updateConnectors(), 100);
                    setTimeout(() => this.updateConnectors(), 300);
                });
            },

            // Initialize SortableJS instances for horizontal card dragging across all 4 tiers
            initSortables() {
                if (typeof Sortable === 'undefined') return;

                const self = this;

                const setupTier = (rowId, type, getArray) => {
                    const rowEl = document.getElementById(rowId);
                    if (!rowEl) return;

                    if (rowEl._sortableInstance) {
                        try {
                            rowEl._sortableInstance.destroy();
                        } catch (e) {}
                        rowEl._sortableInstance = null;
                    }

                    rowEl._sortableInstance = new Sortable(rowEl, {
                        draggable: '.tier-card-draggable',
                        handle: '.drag-handle',
                        animation: 150,
                        ghostClass: 'opacity-40',
                        chosenClass: 'ring-2',
                        dragClass: 'shadow-2xl',
                        direction: 'horizontal',
                        filter: '.tier-add-card',
                        onStart: function(evt) {
                            self.isDragging = true;
                        },
                        onMove: function (evt) {
                            if (evt.related) {
                                // Block dropping after the inline add card
                                if (evt.related.classList.contains('tier-add-card') && evt.willInsertAfter) {
                                    return false;
                                }
                                // Block dropping before the template tag
                                if (evt.related.tagName === 'TEMPLATE' && !evt.willInsertAfter) {
                                    return false;
                                }
                            }
                        },
                        onEnd: function(evt) {
                            setTimeout(() => { self.isDragging = false; }, 300);

                            // Guard: Ensure evt.item didn't accidentally get dropped past the add card
                            const addCard = rowEl.querySelector('.tier-add-card');
                            if (addCard && evt.item && (evt.item.compareDocumentPosition(addCard) & Node.DOCUMENT_POSITION_PRECEDING)) {
                                rowEl.insertBefore(evt.item, addCard);
                            }

                            // 1. Capture real cards sequence from DOM (Sortable has placed evt.item in its new dropped position)
                            const cardEls = Array.from(rowEl.children).filter(el => el.classList.contains('tier-card-draggable'));
                            const newOrderedIds = cardEls.map(el => parseInt(el.getAttribute('data-id'))).filter(Boolean);
                            if (!newOrderedIds.length) return;

                            // 2. Check if order actually changed
                            const list = getArray();
                            if (!list || !list.length) return;

                            const currentIds = list.map(item => item.id);
                            const hasChanged = newOrderedIds.some((id, idx) => id !== currentIds[idx]);
                            if (!hasChanged) return;

                            // 3. Synchronize Alpine template's _x_prevKeys to match current DOM sequence
                            // This guarantees Alpine's x-for diff algorithm sees DOM is ALREADY in order,
                            // producing 0 moves and 0 DOM swaps, preventing any card mismatch or jumbling!
                            const templateEl = Array.from(rowEl.children).find(el => el.tagName === 'TEMPLATE');
                            if (templateEl && templateEl._x_prevKeys) {
                                templateEl._x_prevKeys = [...newOrderedIds];
                            }

                            // 4. Update Alpine reactive array in place to match the new order
                            list.sort((a, b) => newOrderedIds.indexOf(a.id) - newOrderedIds.indexOf(b.id));

                            list.forEach((item, idx) => {
                                item.order = idx + 1;
                            });

                            // 5. Update dynamic connectors
                            self.$nextTick(() => {
                                self.updateConnectors();
                                setTimeout(() => self.updateConnectors(), 100);
                            });

                            // 6. Persist order to database
                            self.saveOrder(type, newOrderedIds);
                        }
                    });
                };

                setupTier('tier-categories-row', 'category', () => self.categories);
                setupTier('tier-classes-row', 'class', () => self.selectedCategory ? self.selectedCategory.class_courses : []);
                setupTier('tier-subjects-row', 'subject', () => self.selectedClass ? self.selectedClass.subjects : []);
            },

            // Send reordered IDs to backend
            saveOrder(type, orderedIds) {
                if (!orderedIds || !orderedIds.length) return;

                fetch('{{ route('admin.curriculum.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        type: type,
                        order: orderedIds
                    })
                })
                .then(r => r.json())
                .then(res => {
                    if (!res.success) {
                        console.warn('Reorder response:', res.message);
                    }
                })
                .catch(err => {
                    console.error('Reorder network error:', err);
                });
            },

            // Recompute SVG dashed connecting lines
            updateConnectors() {
                this.$nextTick(() => {
                    this.connectors.catToClass = this.drawTierConnector('cat-card-', this.selectedCategoryId, '#tier-classes .tier-card-item');
                    this.connectors.classToSubject = this.drawTierConnector('class-card-', this.selectedClassId, '#tier-subjects .tier-card-item');
                });
            },

            drawTierConnector(parentIdPrefix, selectedId, childSelector) {
                if (!selectedId) return { path: '', circles: [] };

                const canvas = document.getElementById('curriculum-canvas');
                if (!canvas) return { path: '', circles: [] };
                const containerRect = canvas.getBoundingClientRect();

                const parentEl = document.getElementById(parentIdPrefix + selectedId);
                if (!parentEl || parentEl.offsetParent === null) return { path: '', circles: [] };

                const childEls = Array.from(document.querySelectorAll(childSelector))
                    .filter(el => el.offsetParent !== null);
                if (childEls.length === 0) return { path: '', circles: [] };

                const pRect = parentEl.getBoundingClientRect();
                const startX = pRect.left + (pRect.width / 2) - containerRect.left;
                const startY = pRect.bottom - containerRect.top;

                // Collect child centers and tops (within or near visible canvas bounds)
                const children = childEls.map(el => {
                    const r = el.getBoundingClientRect();
                    return {
                        x: r.left + (r.width / 2) - containerRect.left,
                        y: r.top - containerRect.top,
                        visible: (r.right >= (containerRect.left - 20) && r.left <= (containerRect.right + 20))
                    };
                }).filter(c => c.visible);

                if (children.length === 0) return { path: '', circles: [] };

                const minChildY = Math.min(...children.map(c => c.y));
                const gap = minChildY - startY;
                if (gap <= 0) return { path: '', circles: [] };

                // Exact vertical halfway point between parent cards and child cards
                const midY = startY + (gap / 2);

                const allX = [startX, ...children.map(c => c.x)];
                const minX = Math.min(...allX);
                const maxX = Math.max(...allX);

                // 1. Vertical trunk from parent down to midY
                let d = `M ${startX.toFixed(1)} ${startY.toFixed(1)} V ${midY.toFixed(1)} `;
                // 2. Horizontal bus bar
                d += `M ${minX.toFixed(1)} ${midY.toFixed(1)} H ${maxX.toFixed(1)} `;
                // 3. Drop into each child card top center
                children.forEach(c => {
                    const targetY = (c.y <= minChildY + 30) ? c.y : minChildY;
                    d += `M ${c.x.toFixed(1)} ${midY.toFixed(1)} V ${targetY.toFixed(1)} `;
                });

                const circles = [
                    { cx: startX.toFixed(1), cy: startY.toFixed(1), r: 4 },
                    ...children.map(c => ({ cx: c.x.toFixed(1), cy: ((c.y <= minChildY + 30) ? c.y : minChildY).toFixed(1), r: 3 }))
                ];

                return { path: d, circles };
            },

            // Modals: Add Openers
            openAddCategory() {
                this.categoryAddModalOpen = true;
            },
            openAddClass() {
                this.classAddModalOpen = true;
            },
            openAddSubject() {
                this.subjectAddModalOpen = true;
            },

            // Modals: Edit Openers
            openEditCategory(cat) {
                this.editingCategory = {
                    id: cat.id,
                    name: cat.name || '',
                    description: cat.description || '',
                    image: cat.image || '',
                    status: Boolean(cat.status)
                };
                this.categoryEditModalOpen = true;
                this.$nextTick(() => {
                    this.syncEditImage('edit_cat_img', cat.image);
                });
            },
            openEditClass(cls) {
                this.editingClass = {
                    id: cls.id,
                    academic_category_id: cls.academic_category_id || (this.selectedCategory ? this.selectedCategory.id : null),
                    class_teacher_id: cls.class_teacher_id || (cls.class_teacher ? cls.class_teacher.id : ''),
                    name: cls.name || '',
                    description: cls.description || '',
                    image: cls.image || '',
                    status: Boolean(cls.status)
                };
                this.classEditModalOpen = true;
                this.$nextTick(() => {
                    this.syncEditImage('edit_cls_img', cls.image);
                });
            },
            openEditSubject(sub) {
                this.editingSubject = {
                    id: sub.id,
                    class_courses_id: sub.class_courses_id || (this.selectedClass ? this.selectedClass.id : null),
                    teacher_id: sub.teacher_id || (sub.teacher ? sub.teacher.id : ''),
                    name: sub.name || '',
                    description: sub.description || '',
                    start_time: sub.start_time ? sub.start_time.substring(0, 5) : '',
                    end_time: sub.end_time ? sub.end_time.substring(0, 5) : '',
                    days: sub.days || '',
                    image: sub.image || '',
                    status: Boolean(sub.status)
                };
                this.subjectEditModalOpen = true;
                this.$nextTick(() => {
                    this.syncEditImage('edit_sub_img', sub.image);
                    const daysInput = document.getElementById('edit_sub_days');
                    if (daysInput) {
                        daysInput.value = sub.days || '';
                        daysInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });
            },

            syncEditImage(uid, imagePath) {
                const wrapper = document.querySelector('.image-field-' + uid);
                if (!wrapper) return;
                const input = document.getElementById(uid);
                const preview = wrapper.querySelector('[data-content-preview]');
                const placeholder = wrapper.querySelector('[data-content-placeholder]');

                if (imagePath) {
                    if (input) input.value = imagePath;
                    if (preview) {
                        preview.src = this.resolveMedia(imagePath);
                        preview.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                } else {
                    if (input) input.value = '';
                    if (preview) {
                        preview.src = '';
                        preview.classList.add('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.remove('hidden');
                    }
                }
            },

            confirmDelete(type, id, name, url, enrolledCount = 0) {
                this.deleteItemType = type;
                this.deleteItemName = name;
                this.deleteActionUrl = url;
                this.deleteEnrolledCount = parseInt(enrolledCount) || 0;
                this.deleteModalOpen = true;
            },

            resolveMedia(path) {
                if (!path) return '';
                if (path.startsWith('http://') || path.startsWith('https://')) return path;
                if (path.startsWith('/')) return path;
                return '/storage/' + path;
            },

            formatTime(t) {
                if (!t) return '--:--';
                return t.substring(0, 5);
            }
        };
    };
</script>

<div x-data="curriculumTreeData()" class="space-y-6" x-cloak>

    {{-- Top Explanation Banner --}}
    @include('tenant.admin.curriculum.partials.header')

    {{-- Top Stats Cards --}}
    @include('tenant.admin.curriculum.partials.stats')

    {{-- Canvas: Cascading Hierarchy Tree with SVG Connectors --}}
    <div id="curriculum-canvas" class="relative space-y-16 pb-8">
        {{-- SVG Connectors --}}
        @include('tenant.admin.curriculum.partials.connectors')

        {{-- Tier 1: Academic Categories --}}
        @include('tenant.admin.curriculum.partials.tier-categories')

        {{-- Tier 2: Classes / Courses --}}
        @include('tenant.admin.curriculum.partials.tier-classes')

        {{-- Tier 3: Subjects --}}
        @include('tenant.admin.curriculum.partials.tier-subjects')
    </div>

    {{-- Modals: Add --}}
    @include('tenant.admin.curriculum.partials.modals-add')

    {{-- Modals: Edit --}}
    @include('tenant.admin.curriculum.partials.modals-edit')

    {{-- Modal: Delete Safeguard --}}
    @include('tenant.admin.curriculum.partials.modal-delete')

</div>
@endsection
