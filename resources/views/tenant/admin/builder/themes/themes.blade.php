<div class="themes-container">
    <!-- Theme Categories -->
    <div class="theme-categories mb-4">
        <div class="p-4 border-bottom">
            <h3 class="font-semibold text-lg text-primary mb-2">
                <i class="fa-solid fa-palette"></i> Themes
            </h3>
            <p class="text-sm text-secondary">Choose a theme to instantly transform your website</p>
        </div>
        
        <!-- Search and Filter -->
        <div class="p-4 border-bottom">
            <div class="mb-3">
                <input type="text" id="themeSearch" placeholder="Search themes..." class="w-full p-2 border border-primary rounded-md text-sm">
            </div>
            <div class="flex gap-2 flex-wrap">
                <button type="button" class="category-filter active px-3 py-1 text-xs rounded-full bg-primary text-primary border border-primary" data-category="all">All</button>
                <button type="button" class="category-filter px-3 py-1 text-xs rounded-full bg-secondary text-secondary border border-secondary" data-category="Business">Business</button>
                <button type="button" class="category-filter px-3 py-1 text-xs rounded-full bg-secondary text-secondary border border-secondary" data-category="Creative">Creative</button>
                <button type="button" class="category-filter px-3 py-1 text-xs rounded-full bg-secondary text-secondary border border-secondary" data-category="Portfolio">Portfolio</button>
                <button type="button" class="category-filter px-3 py-1 text-xs rounded-full bg-secondary text-secondary border border-secondary" data-category="E-Commerce">E-Commerce</button>
                <button type="button" class="category-filter px-3 py-1 text-xs rounded-full bg-secondary text-secondary border border-secondary" data-category="Technology">Technology</button>
            </div>
        </div>
    </div>

    <!-- Themes Grid -->
    <div id="themesGrid" class="themes-grid p-4">
        <div class="loading-themes text-center py-8">
            <i class="fa-solid fa-spinner fa-spin text-2xl text-primary mb-2"></i>
            <p class="text-sm text-secondary">Loading themes...</p>
        </div>
    </div>
</div>

<!-- Theme Preview Modal -->
<div id="themePreviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-primary rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-bottom">
            <h3 class="font-semibold text-lg text-primary" id="previewThemeName">Theme Preview</h3>
            <button type="button" id="closePreviewModal" class="text-secondary hover:text-primary">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4 max-h-[70vh] overflow-auto">
            <div id="themePreviewContent">
                <!-- Preview content will be loaded here -->
            </div>
        </div>
        <div class="flex justify-end gap-3 p-4 border-top">
            <button type="button" id="cancelPreview" class="px-4 py-2 text-sm border border-secondary text-secondary rounded-md hover:bg-secondary hover:text-primary">Cancel</button>
            <button type="button" id="applyTheme" class="px-4 py-2 text-sm bg-accent text-white rounded-md hover:bg-accent-dark">Apply Theme</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('turbo:load', function() {
    let currentThemes = [];
    let selectedTheme = null;

    // Load themes when themes tab is activated
    function loadThemes() {
        console.log('Loading themes...');
        console.log('Current URL:', window.location.href);
        console.log('Current domain:', window.location.hostname);
        
        // First try debug endpoint
        fetch('/admin/builder/themes/debug')
            .then(response => response.json())
            .then(data => {
                console.log('Debug info:', data);
            })
            .catch(error => {
                console.error('Debug failed:', error);
            });
        
        fetch('/admin/builder/themes')
            .then(response => {
                console.log('Themes response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Themes loaded:', data);
                currentThemes = data.themes;
                renderThemes(currentThemes);
            })
            .catch(error => {
                console.error('Error loading themes:', error);
                document.getElementById('themesGrid').innerHTML = '<div class="text-center py-8"><p class="text-red-500">Failed to load themes: ' + error.message + '</p></div>';
            });
    }

    function renderThemes(themes) {
        const grid = document.getElementById('themesGrid');
        
        if (themes.length === 0) {
            grid.innerHTML = '<div class="text-center py-8"><p class="text-secondary">No themes found</p></div>';
            return;
        }

        const themesHTML = themes.map(theme => `
            <div class="theme-card border border-primary rounded-lg overflow-hidden hover:shadow-lg transition-shadow cursor-pointer mb-4" data-theme="${theme.key}" data-category="${theme.category}">
                <div class="theme-preview bg-secondary h-32 flex items-center justify-center">
                    ${theme.preview_image ? 
                        `<img src="${theme.preview_image}" alt="${theme.name}" class="w-full h-full object-cover">` :
                        `<i class="${theme.icon} text-3xl text-primary"></i>`
                    }
                </div>
                <div class="p-3">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-primary text-sm">${theme.name}</h4>
                        <span class="text-xs px-2 py-1 bg-secondary text-secondary rounded-full">${theme.category}</span>
                    </div>
                    <p class="text-xs text-secondary mb-3 line-clamp-2">${theme.description}</p>
                    <div class="flex justify-between items-center text-xs text-secondary">
                        <span><i class="fa-solid fa-layer-group"></i> ${theme.template_count} templates</span>
                        <span><i class="fa-solid fa-palette"></i> ${theme.color_scheme_count} schemes</span>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button type="button" class="preview-theme flex-1 px-3 py-1 text-xs border border-primary text-primary rounded-md hover:bg-primary hover:text-primary" data-theme="${theme.key}">
                            Preview
                        </button>
                        <button type="button" class="apply-theme-direct px-3 py-1 text-xs bg-accent text-white rounded-md hover:bg-accent-dark" data-theme="${theme.key}">
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        grid.innerHTML = themesHTML;

        // Add event listeners
        document.querySelectorAll('.preview-theme').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                previewTheme(e.target.dataset.theme);
            });
        });

        document.querySelectorAll('.apply-theme-direct').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                applyThemeDirectly(e.target.dataset.theme);
            });
        });

        document.querySelectorAll('.theme-card').forEach(card => {
            card.addEventListener('click', () => {
                previewTheme(card.dataset.theme);
            });
        });
    }

    function previewTheme(themeKey) {
        console.log('Previewing theme:', themeKey);
        selectedTheme = themeKey;
        const theme = currentThemes.find(t => t.key === themeKey);
        
        if (!theme) {
            console.error('Theme not found:', themeKey);
            return;
        }

        document.getElementById('previewThemeName').textContent = theme.name;
        document.getElementById('themePreviewModal').classList.remove('hidden');
        
        // Load theme preview data
        fetch(`/admin/builder/themes/preview?theme_name=${themeKey}`)
            .then(response => {
                console.log('Preview response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Preview data loaded:', data);
                if (data.status === 'success') {
                    renderThemePreview(data.theme);
                } else {
                    throw new Error(data.message || 'Failed to load preview');
                }
            })
            .catch(error => {
                console.error('Error loading theme preview:', error);
                document.getElementById('themePreviewContent').innerHTML = '<p class="text-red-500">Failed to load preview: ' + error.message + '</p>';
            });
    }

    function renderThemePreview(themeData) {
        const content = document.getElementById('themePreviewContent');
        
        const previewHTML = `
            <div class="theme-preview-details">
                <div class="mb-6">
                    <h4 class="font-semibold text-primary mb-2">Theme Overview</h4>
                    <p class="text-sm text-secondary mb-4">${themeData.description}</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-primary">Category:</span>
                            <span class="text-secondary ml-2">${themeData.category}</span>
                        </div>
                        <div>
                            <span class="font-medium text-primary">Templates:</span>
                            <span class="text-secondary ml-2">${themeData.templates.length}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold text-primary mb-3">Color Schemes</h4>
                    <div class="grid grid-cols-1 gap-3">
                        ${themeData.color_schemes.map(scheme => `
                            <div class="border border-primary rounded-lg p-3">
                                <h5 class="font-medium text-primary mb-2">${scheme.name}</h5>
                                <div class="flex gap-2">
                                    <div class="w-6 h-6 rounded-full border border-primary" style="background-color: ${scheme.colors.scheme_colors.background}" title="Background"></div>
                                    <div class="w-6 h-6 rounded-full border border-primary" style="background-color: ${scheme.colors.scheme_colors.heading}" title="Heading"></div>
                                    <div class="w-6 h-6 rounded-full border border-primary" style="background-color: ${scheme.colors.primary_btn.background}" title="Primary Button"></div>
                                    <div class="w-6 h-6 rounded-full border border-primary" style="background-color: ${scheme.colors.secondary_btn.text}" title="Secondary Button"></div>
                                    <div class="w-6 h-6 rounded-full border border-primary" style="background-color: ${scheme.colors.scheme_colors.link}" title="Link"></div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold text-primary mb-3">Templates Included</h4>
                    <div class="grid grid-cols-1 gap-2">
                        ${themeData.templates.map(template => `
                            <div class="flex items-center gap-3 p-2 border border-primary rounded-md">
                                <i class="${template.icon} text-primary"></i>
                                <div>
                                    <div class="font-medium text-primary text-sm">${template.name}</div>
                                    <div class="text-xs text-secondary">${template.category} • ${template.default_blocks ? template.default_blocks.length : 0} blocks</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;

        content.innerHTML = previewHTML;
    }

    function applyThemeDirectly(themeKey) {
        if (confirm('Are you sure you want to apply this theme? This will replace your current design.')) {
            applyThemeToPage(themeKey);
        }
    }

    function applyThemeToPage(themeKey) {
        // Get page ID from URL or use default
        const urlParams = new URLSearchParams(window.location.search);
        const pageId = urlParams.get('page_id') || 1;
        
        console.log('Applying theme:', themeKey, 'to page:', pageId);
        
        fetch('/admin/builder/themes/apply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                theme_name: themeKey,
                page_id: parseInt(pageId)
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.status === 'success') {
                // Close modal if open
                document.getElementById('themePreviewModal').classList.add('hidden');
                
                // Show success message
                alert('Theme applied successfully!');
                
                // Reload the page to show changes
                if (data.refresh) {
                    window.location.reload();
                }
            } else {
                alert('Failed to apply theme: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error applying theme:', error);
            alert('Failed to apply theme. Please try again.');
        });
    }

    // Search functionality
    document.getElementById('themeSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filteredThemes = currentThemes.filter(theme => 
            theme.name.toLowerCase().includes(searchTerm) ||
            theme.description.toLowerCase().includes(searchTerm) ||
            theme.category.toLowerCase().includes(searchTerm)
        );
        renderThemes(filteredThemes);
    });

    // Category filter functionality
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            document.querySelectorAll('.category-filter').forEach(b => {
                b.classList.remove('active', 'bg-primary', 'text-primary');
                b.classList.add('bg-secondary', 'text-secondary');
            });
            this.classList.add('active', 'bg-primary', 'text-primary');
            this.classList.remove('bg-secondary', 'text-secondary');

            // Filter themes
            const category = this.dataset.category;
            const filteredThemes = category === 'all' ? 
                currentThemes : 
                currentThemes.filter(theme => theme.category === category);
            
            renderThemes(filteredThemes);
        });
    });

    // Modal event listeners
    document.getElementById('closePreviewModal').addEventListener('click', function() {
        document.getElementById('themePreviewModal').classList.add('hidden');
    });

    document.getElementById('cancelPreview').addEventListener('click', function() {
        document.getElementById('themePreviewModal').classList.add('hidden');
    });

    document.getElementById('applyTheme').addEventListener('click', function() {
        if (selectedTheme) {
            applyThemeToPage(selectedTheme);
        }
    });

    // Close modal on outside click
    document.getElementById('themePreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Load themes when themes tab becomes active
    const themesTab = document.querySelector('[data-target="themes"]');
    if (themesTab) {
        themesTab.addEventListener('click', function() {
            // Small delay to ensure tab content is visible
            setTimeout(loadThemes, 100);
        });
    }

    // Load themes immediately if themes tab is already active
    if (document.getElementById('themes') && !document.getElementById('themes').classList.contains('hidden')) {
        loadThemes();
    }
});
</script>

<style>
.theme-card {
    transition: all 0.2s ease;
}

.theme-card:hover {
    transform: translateY(-2px);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.category-filter.active {
    font-weight: 600;
}

.loading-themes {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>