import "./bootstrap";
import Alpine from "alpinejs";
import "@hotwired/turbo";
import { Editor, Extension } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import { TextStyle } from "@tiptap/extension-text-style";
import { Color } from "@tiptap/extension-color";
import { Link } from "@tiptap/extension-link";
import { Underline } from "@tiptap/extension-underline";
import { Highlight } from "@tiptap/extension-highlight";
import { TextAlign } from "@tiptap/extension-text-align";
import { Subscript } from "@tiptap/extension-subscript";
import { Superscript } from "@tiptap/extension-superscript";
import { Image } from "@tiptap/extension-image";
import { Table } from "@tiptap/extension-table";
import { TableRow } from "@tiptap/extension-table-row";
import { TableCell } from "@tiptap/extension-table-cell";
import { TableHeader } from "@tiptap/extension-table-header";
import { FontFamily } from "@tiptap/extension-font-family";
import { TaskList } from "@tiptap/extension-task-list";
import { TaskItem } from "@tiptap/extension-task-item";
import { Youtube } from "@tiptap/extension-youtube";

// Custom FontSize Extension
const FontSize = Extension.create({
    name: "fontSize",
    addOptions() {
        return {
            types: ["textStyle"],
        };
    },
    addGlobalAttributes() {
        return [
            {
                types: this.options.types,
                attributes: {
                    fontSize: {
                        default: null,
                        parseHTML: (element) => element.style.fontSize?.replace(/['"]+/g, ""),
                        renderHTML: (attributes) => {
                            if (!attributes.fontSize) {
                                return {};
                            }
                            return {
                                style: `font-size: ${attributes.fontSize}`,
                            };
                        },
                    },
                },
            },
        ];
    },
    addCommands() {
        return {
            setFontSize: (fontSize) => ({ chain }) => {
                return chain().setMark("textStyle", { fontSize }).run();
            },
            unsetFontSize: () => ({ chain }) => {
                return chain().setMark("textStyle", { fontSize: null }).run();
            },
        };
    },
});

// Tip Tap Script
window.initRichText = initRichText;
function initRichText() {
    document.querySelectorAll(".tiptap-editor").forEach((el) => {
        if (el.editor) return;

        const wrapper = el.closest(".richtext-wrapper");
        if (!wrapper) return;

        const hidden = wrapper.querySelector("input[type=hidden]");
        const wordCountEl = wrapper.querySelector(".word-count-val");
        const charCountEl = wrapper.querySelector(".char-count-val");

        let rawContent = el.dataset.content ? el.dataset.content.trim() : "";

        // Decode HTML entities if escaped by Blade or HTML attributes
        if (rawContent.includes("&lt;") || rawContent.includes("&gt;") || rawContent.includes("&amp;")) {
            const txt = document.createElement("textarea");
            txt.innerHTML = rawContent;
            rawContent = txt.value;
        }

        if (rawContent === "<p></p>" || rawContent === "<p>&nbsp;</p>" || rawContent === "&lt;p&gt;&lt;/p&gt;") {
            rawContent = "";
        }

        const updateCounters = (editor) => {
            const text = editor.getText().trim();
            const words = text ? text.split(/\s+/).length : 0;
            const chars = text.length;
            if (wordCountEl) wordCountEl.textContent = words;
            if (charCountEl) charCountEl.textContent = chars;
        };

        const editor = new Editor({
            element: el,
            content: rawContent,
            extensions: [
                StarterKit.configure({
                    link: false,
                }),
                TextStyle,
                Color,
                Link.configure({
                    openOnClick: false,
                }),
                Underline,
                Highlight.configure({
                    multicolor: true,
                }),
                TextAlign.configure({
                    types: ["heading", "paragraph"],
                }),
                Subscript,
                Superscript,
                Image.configure({
                    inline: true,
                }),
                Table.configure({
                    resizable: true,
                }),
                TableRow,
                TableHeader,
                TableCell,
                FontFamily,
                FontSize,
                TaskList,
                TaskItem.configure({
                    nested: true,
                }),
                Youtube.configure({
                    controls: true,
                    nocookie: true,
                }),
            ],
            onUpdate({ editor }) {
                if (hidden) {
                    hidden.value = editor.getHTML();
                    hidden.dispatchEvent(new Event("input", { bubbles: true }));
                    hidden.dispatchEvent(new Event("change", { bubbles: true }));
                }
                updateCounters(editor);
            },
            onCreate({ editor }) {
                updateCounters(editor);
            },
        });

        el.editor = editor;

        const rawHtmlEditor = wrapper.querySelector(".raw-html-editor");
        if (rawHtmlEditor) {
            rawHtmlEditor.oninput = () => {
                const val = rawHtmlEditor.value;
                if (hidden) {
                    hidden.value = val;
                    hidden.dispatchEvent(new Event("input", { bubbles: true }));
                    hidden.dispatchEvent(new Event("change", { bubbles: true }));
                }
                try {
                    editor.commands.setContent(val, false);
                } catch (e) {}
                updateCounters(editor);
            };
        }

        // Click anywhere inside the editor container focuses the editor
        el.onclick = (e) => {
            if (e.target === el || e.target.classList.contains("tiptap-editor")) {
                editor.chain().focus().run();
            }
        };

        // Toolbar actions
        wrapper.querySelectorAll("[data-action]").forEach((btn) => {
            btn.onclick = (e) => {
                e.preventDefault();
                const action = btn.dataset.action;

                if (action === "toggleHtml" && rawHtmlEditor) {
                    const isRawVisible = !rawHtmlEditor.classList.contains("hidden");
                    if (isRawVisible) {
                        // Switch back to Visual Mode
                        editor.commands.setContent(rawHtmlEditor.value, false);
                        rawHtmlEditor.classList.add("hidden");
                        el.classList.remove("hidden");
                        btn.classList.remove("bg-accent", "text-white");
                    } else {
                        // Switch to Raw HTML Code Mode
                        rawHtmlEditor.value = editor.getHTML();
                        el.classList.add("hidden");
                        rawHtmlEditor.classList.remove("hidden");
                        btn.classList.add("bg-accent", "text-white");
                        rawHtmlEditor.focus();
                    }
                    return;
                }

                if (action === "bold") editor.chain().focus().toggleBold().run();
                if (action === "italic") editor.chain().focus().toggleItalic().run();
                if (action === "underline") editor.chain().focus().toggleUnderline().run();
                if (action === "strike") editor.chain().focus().toggleStrike().run();
                if (action === "subscript") editor.chain().focus().toggleSubscript().run();
                if (action === "superscript") editor.chain().focus().toggleSuperscript().run();
                if (action === "code") editor.chain().focus().toggleCode().run();
                if (action === "codeBlock") editor.chain().focus().toggleCodeBlock().run();
                if (action === "blockquote") editor.chain().focus().toggleBlockquote().run();
                if (action === "hr") editor.chain().focus().setHorizontalRule().run();
                if (action === "bulletList") editor.chain().focus().toggleBulletList().run();
                if (action === "orderedList") editor.chain().focus().toggleOrderedList().run();
                if (action === "taskList") editor.chain().focus().toggleTaskList().run();

                if (action === "alignLeft") editor.chain().focus().setTextAlign("left").run();
                if (action === "alignCenter") editor.chain().focus().setTextAlign("center").run();
                if (action === "alignRight") editor.chain().focus().setTextAlign("right").run();
                if (action === "alignJustify") editor.chain().focus().setTextAlign("justify").run();

                // Table operations
                if (action === "table") editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run();
                if (action === "addRowAfter") editor.chain().focus().addRowAfter().run();
                if (action === "deleteRow") editor.chain().focus().deleteRow().run();
                if (action === "addColumnAfter") editor.chain().focus().addColumnAfter().run();
                if (action === "deleteColumn") editor.chain().focus().deleteColumn().run();
                if (action === "deleteTable") editor.chain().focus().deleteTable().run();

                // Text Transforms
                if (action === "uppercase") {
                    const { from, to } = editor.state.selection;
                    const selectedText = editor.state.doc.textBetween(from, to, " ");
                    if (selectedText) editor.chain().focus().insertContent(selectedText.toUpperCase()).run();
                }
                if (action === "lowercase") {
                    const { from, to } = editor.state.selection;
                    const selectedText = editor.state.doc.textBetween(from, to, " ");
                    if (selectedText) editor.chain().focus().insertContent(selectedText.toLowerCase()).run();
                }

                if (action === "clear") {
                    editor.chain().focus().unsetAllMarks().clearNodes().run();
                }

                if (action === "undo") editor.chain().focus().undo().run();
                if (action === "redo") editor.chain().focus().redo().run();

                if (action === "link") {
                    const linkBar = wrapper.querySelector(".link-bar");
                    const linkInput = wrapper.querySelector(".link-url");
                    if (linkBar) {
                        linkBar.classList.toggle("hidden");
                        if (linkInput) linkInput.focus();
                    }
                }

                if (action === "image") {
                    const imageBar = wrapper.querySelector(".image-bar");
                    const imageInput = wrapper.querySelector(".image-url");
                    if (imageBar) {
                        imageBar.classList.toggle("hidden");
                        if (imageInput) imageInput.focus();
                    }
                }

                if (action === "youtube") {
                    const youtubeBar = wrapper.querySelector(".youtube-bar");
                    const youtubeInput = wrapper.querySelector(".youtube-url");
                    if (youtubeBar) {
                        youtubeBar.classList.toggle("hidden");
                        if (youtubeInput) youtubeInput.focus();
                    }
                }

                if (action === "fullscreen") {
                    wrapper.classList.toggle("fixed");
                    wrapper.classList.toggle("inset-4");
                    wrapper.classList.toggle("z-50");
                    wrapper.classList.toggle("shadow-2xl");
                }
            };
        });

        // Symbol Insertion buttons
        wrapper.querySelectorAll("[data-symbol]").forEach((btn) => {
            btn.onclick = (e) => {
                e.preventDefault();
                editor.chain().focus().insertContent(btn.dataset.symbol).run();
            };
        });

        // Font Family selector
        const fontFamilySelect = wrapper.querySelector(".font-family-selector");
        if (fontFamilySelect) {
            fontFamilySelect.onchange = (e) => {
                const val = e.target.value;
                if (val) editor.chain().focus().setFontFamily(val).run();
                else editor.chain().focus().unsetFontFamily().run();
            };
        }

        // Font Size selector
        const fontSizeSelect = wrapper.querySelector(".font-size-selector");
        if (fontSizeSelect) {
            fontSizeSelect.onchange = (e) => {
                const val = e.target.value;
                if (val) editor.chain().focus().setFontSize(val).run();
                else editor.chain().focus().unsetFontSize().run();
            };
        }

        // Quick Preset Text Color buttons
        wrapper.querySelectorAll("[data-color]").forEach((btn) => {
            btn.onclick = (e) => {
                e.preventDefault();
                editor.chain().focus().setColor(btn.dataset.color).run();
            };
        });

        // Quick Preset Highlight buttons
        wrapper.querySelectorAll("[data-highlight]").forEach((btn) => {
            btn.onclick = (e) => {
                e.preventDefault();
                editor.chain().focus().toggleHighlight({ color: btn.dataset.highlight }).run();
            };
        });

        // Heading selector change
        const headingSelect = wrapper.querySelector(".heading-selector");
        if (headingSelect) {
            headingSelect.onchange = (e) => {
                const val = e.target.value;
                if (val === "h1") editor.chain().focus().toggleHeading({ level: 1 }).run();
                else if (val === "h2") editor.chain().focus().toggleHeading({ level: 2 }).run();
                else if (val === "h3") editor.chain().focus().toggleHeading({ level: 3 }).run();
                else if (val === "h4") editor.chain().focus().toggleHeading({ level: 4 }).run();
                else editor.chain().focus().setParagraph().run();
            };
        }

        // Custom Color pickers
        const textColorPicker = wrapper.querySelector(".text-color-picker");
        if (textColorPicker) {
            textColorPicker.oninput = (e) => {
                editor.chain().focus().setColor(e.target.value).run();
            };
        }

        const highlightColorPicker = wrapper.querySelector(".highlight-color-picker");
        if (highlightColorPicker) {
            highlightColorPicker.oninput = (e) => {
                editor.chain().focus().toggleHighlight({ color: e.target.value }).run();
            };
        }

        // YouTube bar
        const youtubeBar = wrapper.querySelector(".youtube-bar");
        const youtubeInput = wrapper.querySelector(".youtube-url");
        const youtubeApply = wrapper.querySelector(".youtube-apply");

        if (youtubeApply && youtubeInput) {
            youtubeApply.onclick = (e) => {
                e.preventDefault();
                const url = youtubeInput.value;
                if (url) {
                    editor.chain().focus().setYoutubeVideo({ src: url }).run();
                    youtubeInput.value = "";
                    if (youtubeBar) youtubeBar.classList.add("hidden");
                }
            };
        }

        // Image bar
        const imageBar = wrapper.querySelector(".image-bar");
        const imageInput = wrapper.querySelector(".image-url");
        const imageApply = wrapper.querySelector(".image-apply");

        if (imageApply && imageInput) {
            imageApply.onclick = (e) => {
                e.preventDefault();
                const url = imageInput.value;
                if (url) {
                    editor.chain().focus().setImage({ src: url }).run();
                    imageInput.value = "";
                    if (imageBar) imageBar.classList.add("hidden");
                }
            };
        }

        // Link bar
        const linkBar = wrapper.querySelector(".link-bar");
        const linkInput = wrapper.querySelector(".link-url");
        const linkApply = wrapper.querySelector(".link-apply");
        const linkRemove = wrapper.querySelector(".link-remove");

        if (linkBar) {
            editor.on("selectionUpdate", () => {
                const href = editor.getAttributes("link").href;
                if (href && linkInput) {
                    linkBar.classList.remove("hidden");
                    linkInput.value = href;
                }
            });

            if (linkApply) {
                linkApply.onclick = (e) => {
                    e.preventDefault();
                    const url = linkInput.value;
                    if (url) {
                        editor.chain().focus().setLink({ href: url }).run();
                    }
                };
            }

            if (linkRemove) {
                linkRemove.onclick = (e) => {
                    e.preventDefault();
                    editor.chain().focus().unsetLink().run();
                    if (linkBar) linkBar.classList.add("hidden");
                };
            }
        }
    });
}

document.addEventListener("turbo:load", () => {
    setTimeout(initRichText, 50);
});
document.addEventListener("DOMContentLoaded", () => {
    setTimeout(initRichText, 50);
});

document.addEventListener("turbo:before-cache", () => {
    document.querySelectorAll(".tiptap-editor").forEach((el) => {
        if (el.editor) {
            el.editor.destroy();
            el.editor = null;
        }
    });
});

const richObserver = new MutationObserver(mutations => {

    mutations.forEach(m => {

        m.addedNodes.forEach(node => {

            if (node.nodeType !== 1) return;

            if (
                node.classList?.contains('tiptap-editor') ||
                node.querySelector?.('.tiptap-editor')
            ) {
                initRichText();
            }

        });

    });

});

richObserver.observe(document.body, {
    childList: true,
    subtree: true
});






document.addEventListener('arzavo:navbar-change', e => {

    const state = e.detail.state;

    document
        .querySelectorAll('.arzavo-logo-wrapper')
        .forEach(wrapper => {

            const normal =
                wrapper.querySelector('.arzavo-logo-normal');

            const invert =
                wrapper.querySelector('.arzavo-logo-invert');

            if (!normal || !invert) return;

            if (state === 'transparent') {

                normal.style.opacity = 0;
                invert.style.opacity = 1;

            } else {

                normal.style.opacity = 1;
                invert.style.opacity = 0;
            }
        });

});
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.arzavo-navbar')
        .forEach(navbar => {
            const state = navbar.dataset.state || 'normal';

            navbar.dispatchEvent(
                new CustomEvent('arzavo:navbar-change', {
                    detail: { state },
                    bubbles: true
                })
            );
        });

});







import "alpine-turbo-drive-adapter";
window.Alpine = Alpine;
Alpine.start();

if ("serviceWorker" in navigator) {
    navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for (let registration of registrations) {
            registration.unregister();
        }
    });
}



function toggleModel(id) {
    const modal = document.getElementById(id);
    if (!modal) return;

    modal.classList.toggle("hidden");

    if (!modal.classList.contains("hidden")) {
        attachOutsideClick(modal);
    }
}

function openModel(id) {
    const modal = document.getElementById(id);
    if (!modal) return;

    modal.classList.remove("hidden");
    attachOutsideClick(modal);
}

function closeModel(id) {
    const modal = document.getElementById(id);
    if (!modal) return;

    modal.classList.add("hidden");
    detachOutsideClick(modal);
}

/* ---------- Outside Click Logic ---------- */

function attachOutsideClick(modal) {
    function outsideHandler(e) {
        const content = modal.querySelector(".modal-content") || modal.firstElementChild;

        if (content && !content.contains(e.target) && modal.contains(e.target)) {
            closeModel(modal.id);
        }
    }

    modal._outsideHandler = outsideHandler;

    setTimeout(() => {
        document.addEventListener("click", outsideHandler);
    }, 10);
}

function detachOutsideClick(modal) {
    if (modal._outsideHandler) {
        document.removeEventListener("click", modal._outsideHandler);
        modal._outsideHandler = null;
    }
}

/* global access */
window.toggleModel = toggleModel;
window.openModel = openModel;
window.closeModel = closeModel;




// Component Js
function openUrlPicker(input) {

    const picker = input.parentElement.querySelector('.url-popup');

    document.querySelectorAll('.url-popup').forEach(p => p.classList.add('hidden'));

    picker.classList.remove('hidden');

    // reset view
    picker.querySelector('.url-back')?.classList.add('hidden');

    picker.querySelectorAll('.url-links').forEach(l => l.classList.add('hidden'));

    picker.querySelector('.url-groups')?.classList.remove('hidden');

    // Calculate available space above and below input
    const rect = input.getBoundingClientRect();
    const spaceBelow = window.innerHeight - rect.bottom;
    const spaceAbove = rect.top;
    const estimatedPopupHeight = 280;

    if (spaceBelow < estimatedPopupHeight && spaceAbove > spaceBelow) {
        picker.classList.remove('top-full', 'mt-1.5');
        picker.classList.add('bottom-full', 'mb-1.5');
    } else {
        picker.classList.remove('bottom-full', 'mb-1.5');
        picker.classList.add('top-full', 'mt-1.5');
    }

}

window.openUrlPicker = openUrlPicker;

function openUrlGroup(btn) {

    const popup = btn.closest('.url-popup');
    const group = btn.dataset.group;

    popup.querySelector('.url-groups').classList.add('hidden');

    popup.querySelector(`.url-links[data-group="${group}"]`).classList.remove('hidden');

    popup.querySelector('.url-back').classList.remove('hidden');

}

window.openUrlGroup = openUrlGroup;

function urlBack(btn) {

    const popup = btn.closest('.url-popup');

    popup.querySelectorAll('.url-links').forEach(l => l.classList.add('hidden'));

    popup.querySelector('.url-groups').classList.remove('hidden');

    btn.classList.add('hidden');

}

window.urlBack = urlBack;

function selectUrl(el, url) {

    const popup = el.closest('.url-popup');
    const picker = popup.closest('.url-picker');
    const input = picker.querySelector('input[name]');

    // set value
    input.value = url;

    // IMPORTANT: bubble events so global listener catches them
    input.dispatchEvent(new Event('input', { bubbles: true }));
    input.dispatchEvent(new Event('change', { bubbles: true }));

    // FAILSAFE: directly submit block form if exists
    const blockForm = input.closest('.editBlockForm');
    if (blockForm && typeof submitBlockForm === 'function') {
        submitBlockForm(blockForm);
    }

    // also support section form (same component reused)
    const sectionForm = input.closest('.editSectionForm');
    if (sectionForm && window.BuilderSection) {
        BuilderSection.submit(sectionForm);
    }

    popup.classList.add('hidden');
}

window.selectUrl = selectUrl;

// close outside click
document.addEventListener('click', e => {
    if (!e.target.closest('.url-picker')) {
        document.querySelectorAll('.url-popup').forEach(p => p.classList.add('hidden'));
    }
});

// search filter (works inside links)
document.addEventListener('input', e => {

    if (!e.target.classList.contains('url-search')) return;

    const q = e.target.value.toLowerCase().trim();
    const popup = e.target.closest('.url-popup');

    if (q !== '') {
        popup.querySelector('.url-groups')?.classList.add('hidden');
        popup.querySelector('.url-back')?.classList.remove('hidden');

        popup.querySelectorAll('.url-links').forEach(groupEl => {
            let hasMatch = false;
            groupEl.querySelectorAll('button').forEach(btn => {
                const match = btn.textContent.toLowerCase().includes(q);
                btn.style.display = match ? 'flex' : 'none';
                if (match) hasMatch = true;
            });
            groupEl.classList.toggle('hidden', !hasMatch);
        });
    } else {
        const backBtn = popup.querySelector('.url-back');
        if (backBtn && typeof urlBack === 'function') {
            urlBack(backBtn);
        }
    }

});

function deleteSettingsImage(uid) {

    const wrapper = document.querySelector('.image-field-' + uid);
    if (!wrapper) return;

    const preview = wrapper.querySelector('[data-content-preview]');
    const placeholder = wrapper.querySelector('[data-content-placeholder]');
    const input = wrapper.querySelector('#' + uid);

    if (preview) {
        preview.classList.add('hidden');
        preview.removeAttribute('src');
    }

    if (placeholder) {
        placeholder.classList.remove('hidden');
    }

    if (input) {
        input.value = '';

        // trigger builder autosave
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }
}
window.deleteSettingsImage = deleteSettingsImage;

function deleteSettingsVideo(uid) {

    const wrapper = document.querySelector('.video-field-' + uid);
    if (!wrapper) return;

    const preview = wrapper.querySelector('[data-content-preview]');
    const placeholder = wrapper.querySelector('[data-content-placeholder]');
    const input = wrapper.querySelector('#' + uid);

    if (preview) {
        preview.classList.add('hidden');
        preview.removeAttribute('src');
    }

    if (placeholder) {
        placeholder.classList.remove('hidden');
    }

    if (input) {
        input.value = '';

        // trigger autosave
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }
}
window.deleteSettingsVideo = deleteSettingsVideo;

function deleteSettingsContent(uid) {

    const wrapper = document.querySelector('.content-field-' + uid);
    if (!wrapper) return;

    const preview = wrapper.querySelector('[data-content-preview]');
    const filePreview = wrapper.querySelector('[data-content-file-preview]');
    const placeholder = wrapper.querySelector('[data-content-placeholder]');
    const input = wrapper.querySelector('#' + uid);

    if (preview) {
        preview.classList.add('hidden');
        preview.removeAttribute('src');
    }

    if (filePreview) {
        filePreview.classList.add('hidden');
        filePreview.classList.remove('flex');
    }

    if (placeholder) {
        placeholder.classList.remove('hidden');
    }

    if (input) {
        input.value = '';

        // autosave trigger for builder
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }
}
window.deleteSettingsContent = deleteSettingsContent;










window.openSectionEditor = function (sectionId) {
    openEditorTab("sections");

    // 🔴 FORCE CLOSE ALL BLOCKS (HARD RESET)
    document.querySelectorAll(".edit-block-form").forEach((f) => {
        f.classList.add("hidden");
    });
    window.currentOpenSectionId = sectionId;
    window.currentOpenBlockId = null;

    // 🔴 CLEAR ALL PREVIEW HIGHLIGHTS
    clearPreviewHighlights();

    // 🔴 CLOSE ALL SECTION FORMS
    document.querySelectorAll(".section-edit-form").forEach((f) => {
        f.classList.add("hidden");
    });
    // 🔹 1. Sab section edit forms band karo
    document.querySelectorAll(".section-edit-form").forEach((form) => {
        form.classList.add("hidden");
    });

    // 🔹 2. Sab preview highlights hatao
    document
        .getElementById("livePreviewContent")
        ?.contentWindow?.document?.querySelectorAll(".preview-active")
        ?.forEach((el) => el.classList.remove("preview-active"));

    // 🔹 3. Target section ka edit form open karo
    const editForm = document.getElementById("edit-form-" + sectionId);
    if (!editForm) return;

    editForm.classList.remove("hidden");

    // 🔹 4. Blocks open + arrow rotate (same as header click)
    const blocks = document.getElementById("blocks-" + sectionId);
    const arrow = document.getElementById("arrow-" + sectionId);

    blocks?.classList.remove("hidden");
    arrow?.classList.add("rotate-90");

    // 🔹 5. Preview me highlight add karo
    const previewSection = document
        .getElementById("livePreviewContent")
        ?.contentWindow?.document?.querySelector(
            `[data-section-id="${sectionId}"]`,
        );

    if (previewSection) {
        previewSection.classList.add("preview-active");
        scrollPreviewIntoView(previewSection, "center"); // 🔥 ADD THIS
    }

    // 🔹 6. Save blocks state
    blocksState[sectionId] = true;
    localStorage.setItem("SectionBlocksState", JSON.stringify(blocksState));
};

document.addEventListener("click", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;

    if (e.target.closest(".menu, .block-editor-header, button, input, textarea, select, form")) {
        return;
    }

    const section = e.target.closest("[data-section-id]");
    const block = e.target.closest("[data-block-id]");

    if (!section && !block) return;

    e.preventDefault();
    e.stopPropagation();

    if (block) {
        window.parent.openBlockEditor(block.dataset.blockId);
    } else {
        window.parent.openSectionEditor(section.dataset.sectionId);
    }
}, true);


document.addEventListener("mouseover", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    const header = e.target.closest(".section-items");
    if (!header) return;

    const li = header.closest("li[data-id]");
    if (!li) return;

    const sectionId = li.dataset.id;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewSection = previewDoc?.querySelector(
        `[data-section-id="${sectionId}"]`,
    );

    if (!previewSection) return;

    // same class
    previewSection.classList.add("preview-hover");
    scrollPreviewIntoView(previewSection, "nearest");
});
document.addEventListener("mouseout", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    const header = e.target.closest(".section-items");
    if (!header) return;

    const li = header.closest("li[data-id]");
    if (!li) return;

    const sectionId = li.dataset.id;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewSection = previewDoc?.querySelector(
        `[data-section-id="${sectionId}"]`,
    );

    if (!previewSection) return;

    // same class
    previewSection.classList.remove("preview-hover");
});

// Block State Management
let blocksState = {};
const savedState = localStorage.getItem("SectionBlocksState");
if (savedState) {
    blocksState = JSON.parse(savedState);
}

window.openBlockEditor = function (blockId) {
    openEditorTab("sections");

    const blockEl = document.querySelector(
        `[data-block-id="${blockId}"]`
    );
    if (!blockEl) return;

    const sectionId = blockEl.dataset.sectionId;

    // ✅ 1. Section ki block list open karo (MISSING STEP)
    openSectionBlockList(sectionId);

    // ✅ 2. Parent nested blocks open karo
    openParentNestedBlocks(blockId);

    // ✅ 3. Forms reset
    document.querySelectorAll(".edit-block-form").forEach((f) => {
        f.classList.add("hidden");
    });

    document.querySelectorAll(".section-edit-form").forEach((f) => {
        f.classList.add("hidden");
    });

    // ✅ 4. Open target block form
    const editForm = document.getElementById("edit-block-form-" + blockId);
    if (!editForm) return;

    editForm.classList.remove("hidden");
    editForm.scrollTop = 0;

    // ✅ 5. Preview highlight
    clearPreviewHighlights();

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewBlock = previewDoc?.querySelector(
        `[data-block-id="${blockId}"]`
    );

    if (previewBlock) {
        previewBlock.classList.add("preview-active");
        scrollPreviewIntoView(previewBlock, "center");
    }

    window.currentOpenBlockId = blockId;
    window.currentOpenSectionId = null;
};

function openSectionBlockList(sectionId) {
    const blocks = document.getElementById("blocks-" + sectionId);
    const arrow = document.getElementById("arrow-" + sectionId);

    if (blocks && blocks.classList.contains("hidden")) {
        blocks.classList.remove("hidden");
        arrow?.classList.add("rotate-90");

        // save section state
        blocksState[sectionId] = true;
        localStorage.setItem(
            "SectionBlocksState",
            JSON.stringify(blocksState)
        );
    }
}


function openParentNestedBlocks(blockId) {
    let current = document.getElementById(`block-${blockId}`);
    if (!current) return;

    // 🔁 walk up DOM tree
    while (current) {
        // check if we are inside a nested-blocks container
        const nestedContainer = current.closest("div[id^='nested-blocks-']");
        if (!nestedContainer) break;

        const parentBlockLi = nestedContainer.closest("li.block-item");
        if (!parentBlockLi) break;

        const parentBlockId = parentBlockLi.dataset.blockId;

        const container = document.getElementById(
            `nested-blocks-${parentBlockId}`,
        );
        const arrow = document.getElementById(
            `block-btn-arrow-${parentBlockId}`,
        );

        if (container && container.classList.contains("hidden")) {
            container.classList.remove("hidden");
            arrow?.classList.add("rotate-90");

            // save state
            const state = JSON.parse(
                localStorage.getItem("nestedBlocksState") || "{}",
            );
            state[parentBlockId] = true;
            localStorage.setItem("nestedBlocksState", JSON.stringify(state));
        }

        // move one level up
        current = parentBlockLi;
    }
}

document.addEventListener("mouseover", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    const blockEl = e.target.closest(".block-item");
    if (!blockEl) return;

    const blockId = blockEl.dataset.blockId;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewBlock = previewDoc?.querySelector(
        `[data-block-id="${blockId}"]`,
    );

    if (!previewBlock) return;

    // same class
    previewBlock.classList.add("preview-hover");
    scrollPreviewIntoView(previewBlock, "nearest");
});
document.addEventListener("mouseout", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    const blockEl = e.target.closest(".block-item");
    if (!blockEl) return;

    const blockId = blockEl.dataset.blockId;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewBlock = previewDoc?.querySelector(
        `[data-block-id="${blockId}"]`,
    );

    if (!previewBlock) return;

    // same class
    previewBlock.classList.remove("preview-hover");
});

function clearPreviewHighlights() {
    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    if (!previewDoc) return;

    previewDoc
        .querySelectorAll(".preview-active")
        .forEach((el) => el.classList.remove("preview-active"));
}
window.clearPreviewHighlights = clearPreviewHighlights;

function reapplyPreviewSelection() {
    const iframe = document.getElementById("livePreviewContent");
    if (!iframe) return;

    const previewDoc = iframe.contentWindow?.document;
    if (!previewDoc) return;

    // 🟦 Clear old states
    previewDoc.querySelectorAll(".preview-active").forEach((el) => {
        el.classList.remove("preview-active");
    });

    // 🟩 SECTION ACTIVE
    if (window.currentOpenSectionId) {
        const sectionEl = previewDoc.querySelector(
            `[data-section-id="${window.currentOpenSectionId}"]`,
        );
        sectionEl?.classList.add("preview-active");
    }

    // 🟧 BLOCK ACTIVE (block overrides section)
    if (window.currentOpenBlockId) {
        const blockEl = previewDoc.querySelector(
            `[data-block-id="${window.currentOpenBlockId}"]`,
        );
        blockEl?.classList.add("preview-active");
    }
}
window.reapplyPreviewSelection = reapplyPreviewSelection;

(function attachPreviewReloadHandler() {
    const iframe = document.getElementById("livePreviewContent");
    if (!iframe) return;

    // prevent multiple bindings
    if (iframe.dataset.listenerAttached === "1") return;
    iframe.dataset.listenerAttached = "1";

    iframe.addEventListener("load", () => {
        // DOM settle hone do
        setTimeout(() => {
            window.reapplyPreviewSelection();
        }, 50);
    });
})();

function scrollPreviewIntoView(element, mode = "center") {
    if (!element) return;

    element.scrollIntoView({
        behavior: "smooth",
        block: mode === "center" ? "center" : "nearest",
        inline: "nearest",
    });
}
window.scrollPreviewIntoView = scrollPreviewIntoView;

document.addEventListener("turbo:click", (e) => {
    const btn = e.target.closest("[data-loading]");
    if (!btn) return;

    // store full HTML once
    if (!btn.dataset.originalHtml) {
        btn.dataset.originalHtml = btn.innerHTML;
    }

    btn.classList.add("opacity-80", "pointer-events-none");
    btn.innerHTML = "Loading...";
});

document.addEventListener("turbo:before-cache", () => {
    document.querySelectorAll("[data-loading]").forEach((btn) => {
        if (btn.dataset.originalHtml) {
            btn.innerHTML = btn.dataset.originalHtml;
        }

        btn.classList.remove("opacity-80", "pointer-events-none");
    });
});





document.addEventListener("turbo:load", () => {

    document.querySelectorAll("[data-color-picker]").forEach(cp => {

        const hidden = cp.querySelector("input[type=hidden]");
        let initialValue = hidden.value || "#f2f2f2";

        const preview = cp.querySelectorAll("[data-preview]");
        const popup = cp.querySelector("[data-popup]");
        const input = cp.querySelector("[data-input]");
        const trigger = cp.querySelector("[data-trigger]");

        const solidUI = cp.querySelector("[data-solid]");
        const gradientUI = cp.querySelector("[data-gradient-ui]");
        const toggle = cp.querySelector("[data-toggle]");
        const gradientBar = cp.querySelector("[data-gradient-bar]");
        const stopMarkers = cp.querySelector("[data-stop-markers]");
        const opacitySlider = cp.querySelector("[data-opacity]");
        const opacityLabel = cp.querySelector("[data-opacity-value]");

        let mode = "solid";
        let angle = 135;
        let currentColor = initialValue;
        let stops = [
            { color: "#ff6b6b", alpha: 1, pos: 0 },
            { color: "#4ecdc4", alpha: 1, pos: 50 },
            { color: "#45b7d1", alpha: 1, pos: 100 }
        ];

        function openPopup(e) {
            e.preventDefault();
            e.stopPropagation();
            popup.classList.remove("hidden");
        }

        trigger.addEventListener("mousedown", openPopup);
        input.addEventListener("mousedown", openPopup);


        document.addEventListener("mousedown", e => {
            if (!cp.contains(e.target)) {
                popup.classList.add("hidden");
            }
        });


        /* ---------------- CORE UPDATE ---------------- */
        function update(value) {

            hidden.value = value;
            input.value = value;

            preview.forEach(p => {
                p.style.background = value;
            });

            // sync opacity slider
            const parsed = parseColor(value);

            if (opacitySlider) {
                opacitySlider.value =
                    Math.round(parsed.a * 100);

                opacityLabel.textContent =
                    opacitySlider.value + "%";
            }
        }

        /* ---------------- COLOR UTILITIES ---------------- */
        function hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        function rgbToHex(r, g, b) {
            return "#" + [r, g, b].map(x => {
                const hex = x.toString(16);
                return hex.length === 1 ? "0" + hex : hex;
            }).join("");
        }

        function rgbToHsl(r, g, b) {
            r /= 255;
            g /= 255;
            b /= 255;
            const max = Math.max(r, g, b),
                min = Math.min(r, g, b);
            let h, s, l = (max + min) / 2;

            if (max === min) {
                h = s = 0;
            } else {
                const d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch (max) {
                    case r:
                        h = ((g - b) / d + (g < b ? 6 : 0)) / 6;
                        break;
                    case g:
                        h = ((b - r) / d + 2) / 6;
                        break;
                    case b:
                        h = ((r - g) / d + 4) / 6;
                        break;
                }
            }
            return {
                h: h * 360,
                s: s * 100,
                l: l * 100
            };
        }

        function hslToRgb(h, s, l) {
            h /= 360;
            s /= 100;
            l /= 100;
            let r, g, b;

            if (s === 0) {
                r = g = b = l;
            } else {
                const hue2rgb = (p, q, t) => {
                    if (t < 0) t += 1;
                    if (t > 1) t -= 1;
                    if (t < 1 / 6) return p + (q - p) * 6 * t;
                    if (t < 1 / 2) return q;
                    if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
                    return p;
                };
                const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                const p = 2 * l - q;
                r = hue2rgb(p, q, h + 1 / 3);
                g = hue2rgb(p, q, h);
                b = hue2rgb(p, q, h - 1 / 3);
            }
            return {
                r: Math.round(r * 255),
                g: Math.round(g * 255),
                b: Math.round(b * 255)
            };
        }

        function parseColor(color) {

            if (color.startsWith("rgba")) {
                const m = color.match(/rgba?\((\d+),\s*(\d+),\s*(\d+),?\s*([\d\.]+)?\)/);

                return {
                    r: +m[1],
                    g: +m[2],
                    b: +m[3],
                    a: m[4] ? parseFloat(m[4]) : 1
                };
            }

            const rgb = hexToRgb(color);

            return rgb
                ? { ...rgb, a: 1 }
                : { r: 242, g: 242, b: 242, a: 1 };
        }

        /* ---------------- SOLID MODE ---------------- */
        const solidPicker = cp.querySelector("[data-solid-picker]");
        if (solidPicker) {
            solidPicker.addEventListener("input", e => {

                const rgb = hexToRgb(e.target.value);

                const alpha =
                    (opacitySlider?.value || 100) / 100;

                const rgba =
                    `rgba(${rgb.r},${rgb.g},${rgb.b},${alpha})`;

                currentColor = rgba;

                update(rgba);
            });

            opacitySlider?.addEventListener("input", () => {

                const base =
                    parseColor(currentColor);

                const alpha =
                    opacitySlider.value / 100;

                const rgba =
                    `rgba(${base.r},${base.g},${base.b},${alpha})`;

                currentColor = rgba;

                opacityLabel.textContent =
                    opacitySlider.value + "%";

                update(rgba);
            });
        }

        input.addEventListener("input", e => {
            const val = e.target.value;
            if (/^#[0-9A-F]{6}$/i.test(val)) {
                currentColor = val;
                if (solidPicker) solidPicker.value = val;
            }
            update(val);
        });

        /* ---------------- MODE TOGGLE ---------------- */
        toggle?.addEventListener("click", e => {
            const btn = e.target.closest("button");
            if (!btn) return;

            mode = btn.dataset.mode;
            solidUI.classList.toggle("hidden", mode !== "solid");
            gradientUI.classList.toggle("hidden", mode !== "gradient");

            toggle.querySelectorAll("button").forEach(b => {
                if (b === btn) {
                    b.classList.add("bg-white", "shadow-sm", "text-gray-900");
                    b.classList.remove("text-gray-600");
                } else {
                    b.classList.remove("bg-white", "shadow-sm", "text-gray-900");
                    b.classList.add("text-gray-600");
                }
            });

            if (mode === "gradient") {
                buildGradient();
            }
        });

        /* ---------------- ANGLE WHEEL ---------------- */
        const wheel = cp.querySelector("[data-angle-wheel]");
        const indicator = cp.querySelector("[data-angle-indicator]");
        const angleDisplay = cp.querySelector("[data-angle-display]");

        function updateAngle(newAngle) {
            angle = ((newAngle % 360) + 360) % 360;
            if (indicator) indicator.style.transform = `rotate(${angle}deg)`;
            if (angleDisplay) angleDisplay.textContent = `${Math.round(angle)}°`;
            buildGradient();
        }

        wheel?.addEventListener("mousedown", e => {
            e.stopPropagation();
            const moveHandler = (e) => {
                const rect = wheel.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                const newAngle = Math.round((Math.atan2(y, x) * 180) / Math.PI + 90);
                updateAngle(newAngle);
            };

            moveHandler(e);

            const upHandler = () => {
                document.removeEventListener("mousemove", moveHandler);
                document.removeEventListener("mouseup", upHandler);
            };

            document.addEventListener("mousemove", moveHandler);
            document.addEventListener("mouseup", upHandler);
        });

        /* ---------------- QUICK ANGLES ---------------- */
        cp.querySelectorAll("[data-quick-angle]").forEach(btn => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                updateAngle(parseInt(btn.dataset.quickAngle));
            });
        });

        /* ---------------- INTERACTIVE STOP MARKERS ---------------- */
        function renderStopMarkers() {
            if (!stopMarkers) return;
            stopMarkers.innerHTML = '';

            stops.forEach((stop, index) => {
                const marker = document.createElement('div');
                marker.className = 'stop-marker';
                marker.style.left = `${stop.pos}%`;
                marker.setAttribute('data-marker-index', index);
                marker.innerHTML = `<div class="stop-marker-handle" style="background: ${stop.color}"></div>`;

                // DRAG FUNCTIONALITY - PROPERLY IMPLEMENTED
                marker.addEventListener('mousedown', (e) => {
                    e.stopPropagation();
                    e.preventDefault();

                    const rect = stopMarkers.getBoundingClientRect();
                    marker.style.zIndex = '10';
                    document.body.style.cursor = 'grabbing';

                    const moveHandler = (e) => {
                        e.preventDefault();
                        const x = Math.max(0, Math.min(rect.width, e.clientX - rect.left));
                        const percentage = Math.round((x / rect.width) * 100);

                        stop.pos = percentage;
                        marker.style.left = `${percentage}%`;

                        buildGradient();

                        // Update the corresponding range slider
                        const stopRow = stopsBox.children[index];
                        if (stopRow) {
                            const rangeInput = stopRow.querySelector('input[type="range"]');
                            const posDisplay = stopRow.querySelector('.text-xs.font-bold');
                            if (rangeInput) rangeInput.value = percentage;
                            if (posDisplay) posDisplay.textContent = `${percentage}%`;
                        }
                    };

                    const upHandler = () => {
                        marker.style.zIndex = '';
                        document.body.style.cursor = '';
                        document.removeEventListener('mousemove', moveHandler);
                        document.removeEventListener('mouseup', upHandler);
                        renderStops(); // Final update
                    };

                    document.addEventListener('mousemove', moveHandler);
                    document.addEventListener('mouseup', upHandler);
                });

                stopMarkers.appendChild(marker);
            });
        }

        /* ---------------- GRADIENT STOPS ---------------- */
        const stopsBox = cp.querySelector("[data-stops]");
        let sortableInstance = null;

        function renderStops() {
            if (!stopsBox) return;

            stopsBox.innerHTML = "";

            stops.forEach((stop, i) => {
                const row = document.createElement("div");
                row.setAttribute('data-stop-index', i);
                row.className = "flex items-center gap-4 p-2 w-full bg-secondary border-rounded border-primary";

                row.innerHTML = `
<input type="color"
    value="${stop.color}"
    class="w-12 h-12 border-rounded cursor-pointer">

<div class="flex-1">

    <input type="range"
        min="0"
        max="100"
        value="${stop.pos}"
        class="w-full">

    <div class="flex justify-between text-xs mt-1">
        <span>Position</span>
        <span>${stop.pos}%</span>
    </div>

    <div class="mt-2">
        <input type="range"
            min="0"
            max="100"
            value="${Math.round(stop.alpha * 100)}"
            data-alpha
            class="w-full">

        <div class="text-xs text-right">
            Opacity ${Math.round(stop.alpha * 100)}%
        </div>
    </div>

</div>
`;

                const alphaInput =
                    row.querySelector('[data-alpha]');
                const dragHandle = row.querySelector('.drag-handle');
                const colorInput = row.querySelector('input[type="color"]');
                const rangeInput = row.querySelector('input[type="range"]');
                const posDisplay = row.querySelector('.text-xs.font-bold');
                const deleteBtn = row.querySelector('button');

                alphaInput.oninput = () => {

                    stop.alpha =
                        alphaInput.value / 100;

                    buildGradient();
                    renderStopMarkers();
                };

                colorInput.oninput = (e) => {
                    e.stopPropagation();
                    stop.color = colorInput.value;
                    rangeInput.style.color = stop.color;
                    buildGradient();
                    renderStopMarkers();
                };

                rangeInput.oninput = (e) => {
                    e.stopPropagation();
                    stop.pos = parseInt(rangeInput.value);
                    posDisplay.textContent = `${stop.pos}%`;
                    buildGradient();
                    renderStopMarkers();
                };

                if (deleteBtn) {
                    deleteBtn.onclick = (e) => {
                        e.stopPropagation();
                        stops.splice(i, 1);
                        renderStops();
                        buildGradient();
                        renderStopMarkers();
                    };
                }

                stopsBox.appendChild(row);
            });

            // Destroy previous sortable instance if exists
            if (sortableInstance) {
                sortableInstance.destroy();
            }
        }

        cp.querySelector("[data-add-stop]")?.addEventListener("click", (e) => {
            e.stopPropagation();

            // Find the biggest gap between stops
            let maxGap = 0;
            let gapStart = 0;

            const sortedStops = [...stops].sort((a, b) => a.pos - b.pos);

            for (let i = 0; i < sortedStops.length - 1; i++) {
                const gap = sortedStops[i + 1].pos - sortedStops[i].pos;
                if (gap > maxGap) {
                    maxGap = gap;
                    gapStart = sortedStops[i].pos;
                }
            }

            const newPos = gapStart + Math.round(maxGap / 2);

            stops.push({
                color: "#ffffff",
                alpha: 1,
                pos: newPos
            });

            renderStops();
            buildGradient();
            renderStopMarkers();
        });

        function buildGradient() {

            const sorted =
                [...stops].sort((a, b) => a.pos - b.pos);

            const css =
                `linear-gradient(${angle}deg,
        ${sorted.map(s => {

                    const rgb = hexToRgb(s.color);

                    return `rgba(${rgb.r},
                         ${rgb.g},
                         ${rgb.b},
                         ${s.alpha})
                         ${s.pos}%`;

                }).join(',')}
        )`;

            update(css);

            if (gradientBar) {

                gradientBar.style.background =
                    `linear-gradient(90deg,
            ${sorted.map(s => {

                        const rgb = hexToRgb(s.color);

                        return `rgba(${rgb.r},
                             ${rgb.g},
                             ${rgb.b},
                             ${s.alpha})
                             ${s.pos}%`;

                    }).join(',')}
            )`;
            }
        }

        /* ---------------- INITIALIZATION ---------------- */
        updateAngle(angle);
        renderStops();
        renderStopMarkers();

        if (mode === "solid") {
            update(currentColor);
            if (solidPicker) solidPicker.value = currentColor;
        } else {
            buildGradient();
        }
    });
});

// Auto-expand Textarea Helper
function initAutoResizeTextareas() {
    document.querySelectorAll("textarea").forEach((el) => {
        const resize = () => {
            el.style.height = "auto";
            el.style.height = Math.max(el.scrollHeight, 60) + "px";
        };
        resize();
        if (!el.dataset.autoResized) {
            el.dataset.autoResized = "true";
            el.addEventListener("input", resize);
            window.addEventListener("resize", resize);
        }
    });
}
document.addEventListener("DOMContentLoaded", initAutoResizeTextareas);
document.addEventListener("turbo:load", initAutoResizeTextareas);
window.initAutoResizeTextareas = initAutoResizeTextareas;