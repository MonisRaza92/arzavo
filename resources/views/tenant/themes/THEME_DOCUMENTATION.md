# Arzavo Theme System (ATS) Developer Guide & Blueprint

Welcome to the **Arzavo Theme System (ATS)** blueprint manual. This guide is a comprehensive reference for frontend developers. It describes every directory, metadata setting, global variable, helper method, CSS utility class, and JSON configuration schema in detail. 

By following this guide, developers can build fully customizable, style-aware, and responsive themes for educational institutions on the Arzavo platform without needing to understand the underlying database schemas or backend PHP systems.

---

## 1. The ATS Philosophy

ATS is built on a strict separation of concerns:
1. **Developers define the layout structures, style bounds, and configuration options.**
2. **Institutional Administrators control the actual content, layout order, colors, and asset values.**

Themes are self-contained folders that are dynamically parsed by the system. All templates are coded in raw HTML/CSS/JS and Blade, with zero compilation (Vite/Webpack) required. This ensures themes can be zipped, uploaded, and activated instantly.

---

## 2. Directory & File Structure Blueprint

Every theme folder resides inside the directory path. The folder name must match the slug exactly (e.g., `nucleus`).

```text
theme_slug/
├── theme.json          # Root metadata declaration (defines theme identity, assets, & features)
├── assets/             # Static files served directly to the browser
│   ├── global.css      # Custom styling rules scoped to the theme namespace
│   └── global.js       # Dynamic interactivity scripts (observers, slide-decks, tabs)
├── config/             # Global configurations
│   ├── settings.json   # Global customizer control defaults (typography sizes, shapes, widths)
│   └── schemes.json    # Standard color scheme mappings (hex codes for backgrounds, buttons)
├── layouts/            # Global layouts
│   └── global.json     # Declarative mapping of header, footer, and basic sections
├── pages/              # Preconfigured default page layouts
│   ├── home.json       # Section composition and blocks configuration for the Home page
│   └── about.json      # Section composition and blocks configuration for the About page
├── sections/           # Modular top-level horizontal page regions
│   ├── *.blade.php     # Section markup template (renders HTML and wraps blocks)
│   └── *.json          # Section schema (defines layout choices, constraints, and field controls)
├── blocks/             # Nested child components placed within sections
│   ├── *.blade.php     # Block markup template (renders headings, buttons, links)
│   └── *.json          # Block schema (defines the individual input fields for the block)
└── templates/          # Bundled configurations
    └── *.json          # Prefilled combinations of sections and blocks for one-click insertion
```

---

## 3. The Theme Identity File (`theme.json`)

The `theme.json` file in the theme root defines critical metadata that the Arzavo engine uses to discover, load, and manage the theme. Below is the exact file layout, explained line-by-line.

```json
{
    "name": "Nucleus",
    "folder": "nucleus",
    "version": "2.0.0",
    "engine_version": "1.0",
    "author": "Arzavo",
    "description": "A modern and sleek theme for Arzavo-powered websites.",
    "category": "modern",
    "is_paid": false,
    "price": 0,
    "source": "system",
    "is_active": true,
    "preview_image": "/themes/nucleus/preview.jpg",
    "assets": {
        "css": ["assets/global.css"],
        "js": ["assets/global.js"]
    },
    "supports": ["sections", "blocks", "templates", "color_schemes"]
}
```

### Line-by-Line Property Breakdown:
* **`name`** (string): The display name shown to institutional users in the theme selector dashboard.
* **`folder`** (string): The directory name inside the theme directory. This must match the folder name exactly and use lowercase alphanumeric characters and underscores.
* **`version`** (string): The version of your theme (e.g. `2.0.0`), used to track theme updates.
* **`engine_version`** (string): The minimum version of the Arzavo Theme System required to parse this theme.
* **`author`** (string): The developer or organization name that built the theme.
* **`description`** (string): A short summary explaining the design style and target audience of the theme.
* **`category`** (string): The category filter tag for organizing themes (e.g., `modern`, `classic`, `creative`).
* **`is_paid`** (boolean): Set to `true` if the theme is premium; otherwise `false`.
* **`price`** (number): The cost of the theme. Set to `0` if `is_paid` is false.
* **`source`** (string): Internal origin identifier (typically `system` for core themes or `marketplace` for third-party uploads).
* **`is_active`** (boolean): Dictates if the theme is available for selection.
* **`preview_image`** (string): The relative path to a thumbnail image representing the theme.
* **`assets`** (object): An object containing arrays of files to load:
  * `css`: Array of CSS paths inside your theme directory that will be injected into all pages.
  * `js`: Array of JavaScript paths inside your theme directory that will be injected into all pages.
* **`supports`** (array): Lists the core features utilized by the theme, ensuring compatibility with sections, blocks, layouts, and color schemes.

---

## 4. Global Variables & Contextual Data

When rendering page templates, the ATS engine automatically injects several global variables. You can reference these variables in any Blade file to access customizer styles, tenant configurations, and navigation menus.

### A. `$customizes`
An associative array containing the user's customized values as defined in your theme's `config/settings.json` file. Use this variable to apply global styling parameters (e.g., container width, typography families, border roundness).

> [!TIP]
> Always use PHP's null coalescing operator (`??`) to specify fallbacks for customizer variables to prevent crashes if a setting value is unconfigured.

**Example Usage in Blade:**
```blade
{{-- Sets maximum content width based on customizer choices --}}
<div class="main-wrapper" style="max-width: {{ $customizes['container_width'] ?? '1200' }}px;">
```

### B. `$settings`
An associative array containing institutional configuration details uploaded by the tenant administration. Useful for rendering global site details.

**Key Fields in `$settings`:**
* `$settings['site_name']`: The institution's name (e.g., "Arzavo High School").
* `$settings['site_email']`: The main contact email.
* `$settings['site_phone']`: The primary phone number.
* `$settings['site_address']`: The physical address.
* `$settings['favicon']`: The URL path to the site favicon.

**Example Usage in Blade:**
```blade
<footer class="footer">
    <p>&copy; {{ date('Y') }} {{ $settings['site_name'] }}. All rights reserved.</p>
    <p>Contact us at: <a href="mailto:{{ $settings['site_email'] }}">{{ $settings['site_email'] }}</a></p>
</footer>
```

### C. `$menus`
A collection of all navigation menus configured by the institution. Each menu has a slug or identifier (such as `header` or `footer`) and contains a collection of link items.

**Menu Link Object Properties:**
* `$link->url`: The URL target of the link.
* `$link->label`: The display text of the link.
* `$link->target`: The window target (e.g., `_blank` for new tabs).
* `$link->children`: A collection of child links for multi-level navigation.

**Example Usage in Blade (Recursive Menu):**
```blade
<nav class="navigation">
    <ul>
        @foreach($menus as $menu)
            @if($menu->slug === 'header')
                @foreach($menu->links as $link)
                    <li class="menu-item {{ $link->children->isNotEmpty() ? 'has-dropdown' : '' }}">
                        <a href="{{ $link->url }}" target="{{ $link->target ?? '_self' }}">{{ $link->label }}</a>
                        
                        @if($link->children->isNotEmpty())
                            <ul class="dropdown-menu">
                                @foreach($link->children as $child)
                                    <li>
                                        <a href="{{ $child->url }}" target="{{ $child->target ?? '_self' }}">{{ $child->label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @endif
        @endforeach
    </ul>
</nav>
```

---

## 5. Core Helper Functions

ATS provides helper functions to handle paths, load media, resolve logo variations, and convert raw arrays into model objects.

### `scheme(string $key)`
Generates inline CSS variables mapped to the color values of the selected color scheme in `config/schemes.json`.
* **Input**: Color scheme slug (e.g., `scheme_1`, `scheme_2`).
* **Output**: A string of CSS custom properties.
* **Example in Blade**:
  ```blade
  <div style="{{ scheme($section->color_scheme ?? 'scheme_1') }}">
      <!-- Elements inside this container will inherit color scheme CSS variables -->
  </div>
  ```

### `media(string|null $path)`
Resolves and returns the public URL for a media file (handling local storage paths, AWS S3 storage paths, or absolute URLs).
* **Input**: Relative path or absolute URL.
* **Output**: Fully qualified URL string.
* **Example in Blade**:
  ```blade
  <video src="{{ media($block->settings['video_path'] ?? null) }}" autoplay loop muted></video>
  ```

### `image(string|null $path)`
Resolves an image URL. If no image path is passed (or the field is blank), it returns a high-quality placeholder image automatically.
* **Input**: Relative image path.
* **Output**: Fully qualified image URL or placeholder URL.
* **Example in Blade**:
  ```blade
  <img src="{{ image($block->settings['avatar'] ?? null) }}" alt="User Profile">
  ```

### `video(string|null $path)`
Resolves a video URL. If the video path is empty or null, it returns a placeholder loop video.
* **Input**: Relative video path.
* **Output**: Fully qualified video URL or placeholder video URL.

### `render_logo()`
Resolves and returns the URL of the institution's primary header logo. Returns a default system logo if the administrator has not uploaded a custom logo.
* **Output**: Fully qualified logo image URL.

### `render_invert_logo()`
Resolves and returns the inverted/dark logo URL (usually used when sections feature dark background schemes). Falls back to the primary logo if no inverted logo is uploaded.
* **Output**: Fully qualified inverted logo image URL.

### `tenant_name()`
Outputs the sanitized, plain-text name of the active institution.
* **Output**: String representing the institution name.

### `section(array $data)`
Converts a raw data array into a hydrated `Section` model object, providing access to helper methods like `attributes()`, `backgrounds()`, margins, and paddings. Used when rendering raw layout arrays.
* **Input**: Associative array of section data.
* **Output**: `Section` object instance.

### `block(array $data)`
Converts a raw block array into a hydrated `Block` model object, enabling methods like `attributes()` and layout settings bindings.
* **Input**: Associative array of block data.
* **Output**: `Block` object instance.

### `renderBlocks(array|Collection $blocks)`
Renders a list of block elements inside a section or a group block.
* **Input**: Array or Collection of block configurations.
* **Output**: Raw HTML string of the rendered blocks.
* **Example in Blade**:
  ```blade
  <div class="nested-blocks-container">
      {!! renderBlocks($block['blocks'] ?? []) !!}
  </div>
  ```

---

## 6. CSS System & Standard Classes

To keep a theme consistent with the user's global customized choices (buttons, text styling, page margins), developers must use standard utility classes.

### Typography Utility Classes
* **`.arz-h1` to `.arz-h6`**: Styles headings with font families, weights, and sizes defined in the institutional customize panel.
* **`.arz-paragraph`**: Applies standard body/paragraph styles.
* **`.arz-body-text`**: Applies secondary or smaller body text typography.

### Layout & Containers
* **`.container`**: Restricts the maximum width of the content wrap to the `container_width` value specified in the theme settings (e.g. `1200px`) and centers it.
* **`.arz-content`**: Applies standard left and right horizontal padding configurations.
* **`.arz-core`**: A base class for sections, establishing relative positioning.

### Buttons & Interactive Elements
* **`.arz-btn-primary`**: Applies background, text, hover transition, border shapes, and sizing defined in settings for primary actions.
* **`.arz-btn-secondary`**: Applies style, border, and hover parameters for secondary or outline buttons.
* **`.arz-link`**: Applies text link colors, underlines, and hover behaviors.
* **`.arz-border`**, **`.arz-border-t`**, **`.arz-border-b`**: Standard border selectors.

---

## 7. Dynamic Color Variables (CSS Variables)

ATS maps active schemes (Light, Accent, Dark, etc.) to CSS variables automatically. Apply these variables inside your stylesheet (`assets/global.css`) or directly in inline CSS styles.

| CSS Variable | Definition / Application |
| :--- | :--- |
| `var(--arz-bg)` | Background color of the container or section. |
| `var(--arz-heading)` | Text color for primary headings. |
| `var(--arz-subheading)` | Text color for secondary headings or descriptors. |
| `var(--arz-paragraph)` | Main body paragraph text color. |
| `var(--arz-secondary-text)` | Muted metadata, dates, or secondary labels. |
| `var(--arz-border)` | Color for lines, dividers, or card outlines. |
| `var(--arz-link)` | Base color for anchors and inline links. |
| `var(--arz-btn-bg)` | Background color for primary buttons. |
| `var(--arz-btn-text)` | Text color inside primary buttons. |
| `var(--arz-btn-hover-bg)` | Hover background color for primary buttons. |
| `var(--arz-btn-hover-text)` | Hover text color for primary buttons. |

**Example Usage inside `assets/global.css`:**
```css
[data-theme="nucleus"] .profile-card {
    background-color: var(--arz-bg);
    border: 1px solid var(--arz-border);
    border-radius: 12px;
}
[data-theme="nucleus"] .profile-card h3 {
    color: var(--arz-heading);
}
[data-theme="nucleus"] .profile-card p {
    color: var(--arz-paragraph);
}
```

---

## 8. Building Sections (The Layout Structure)

A section is a standalone horizontal page element. It requires two files: a markup file (`sections/name.blade.php`) and a configuration schema file (`sections/name.json`).

### A. The Blade Layout File (`sections/promo-banner.blade.php`)

```blade
@php
    // Ensure the section variable is hydrated as a Section service object
    $sectionObj = is_array($section) ? section($section) : $section;
@endphp

{{-- 1. Root Element must output attributes() and visibility classes --}}
<div {!! $sectionObj->attributes() !!} class="{{ $sectionObj->visibility }} relative overflow-hidden my-custom-section">
    
    {{-- 2. System backgrounds --}}
    {!! $sectionObj->backgrounds() !!}

    {{-- 3. Content layout wrapper using standard spacing --}}
    <div class="section-content {{ $sectionObj->container }} arz-content relative z-10">
        
        {{-- 4. Dynamic block rendering --}}
        <div class="blocks-layout-grid">
            {!! $sectionObj->blocks() !!}
        </div>
        
    </div>
</div>

{{-- 5. Scoped custom styling using the section ID --}}
<style>
    /* Scope styles using the section's unique ID class to prevent leaks */
    .arz-{{ $sectionObj->id }} {
        {{ $sectionObj->margin }}
    }
    
    .arz-{{ $sectionObj->id }} .section-content {
        {{ $sectionObj->padding }}
        {{ $sectionObj->flex }}
        {{ $sectionObj->height }}
    }
    
    /* Responsive overrides for mobile viewports */
    @media (max-width: 767px) {
        .arz-{{ $sectionObj->id }} { 
            {{ $sectionObj->marginMobile }} 
        }
        .arz-{{ $sectionObj->id }} .section-content {
            {{ $sectionObj->paddingMobile }}
            {{ $sectionObj->heightMobile }}
        }
    }
</style>
```

#### Line-by-Line Blade Code Breakdown:
* **Line 1-4**: Hydrates `$section` into a `Section` model object if passed as a raw array.
* **Line 7**: `{!! $sectionObj->attributes() !!}` is **MANDATORY**. It outputs the unique `data-section-id` and `class` (e.g. `arz-sec_xyz`) required by the page builder. Without this, the section cannot be selected, selected, or sorted by the administrator.
* **Line 7**: `$sectionObj->visibility` inserts CSS display classes (like `hidden md:block` or `block md:hidden`) if the administrator configures visibility limits.
* **Line 10**: `{!! $sectionObj->backgrounds() !!}` renders the background wrapper. Depending on customizer settings, this outputs background images, background colors, gradients, background videos, and overlay filters.
* **Line 13**: `$sectionObj->container` resolves to `.container` (restricted content width) or `.w-full` (stretched layout) based on the section's custom settings.
* **Line 13**: `.arz-content` adds horizontal gutters to align sections.
* **Line 17**: `{!! $sectionObj->blocks() !!}` loops through and renders each block dropped inside this section.
* **Line 24-44**: Generates custom styling values from settings presets.
  * `margin`/`padding`: Margin and padding values (e.g. `padding-top: 40px;`).
  * `flex`: Display alignment rules (e.g. `display: flex; flex-direction: column; justify-content: center;`).
  * `height`: Layout height rules (e.g. `min-height: 500px;`).
  * `marginMobile`/`paddingMobile`/`heightMobile`: Responsive configurations.

### B. The Section Config File (`sections/promo-banner.json`)

```json
{
    "type": "promo_banner",
    "name": "Promotional Banner",
    "icon": "fa-bullhorn",
    "category": "Marketing",
    "max_blocks": 4,
    "allowed_blocks": [
        "heading",
        "text",
        "button"
    ],
    "default_blocks": [
        {
            "type": "heading",
            "settings": {
                "text": "Enrollment Open",
                "alignment": "center"
            }
        },
        {
            "type": "button",
            "settings": {
                "text": "Apply Now",
                "button_type": "primary"
            }
        }
    ],
    "fields": [
        {
            "key": "color_scheme",
            "label": "Color Scheme Theme",
            "type": "color_scheme_selector"
        },
        {
            "key": "grid_cols",
            "label": "Columns on Desktop",
            "type": "range",
            "min": 1,
            "max": 4,
            "default": 2
        },
        {
            "preset": "section_flex_fields"
        },
        {
            "preset": "section_spacing_fields"
        },
        {
            "preset": "visibility_fields"
        }
    ]
}
```

#### Line-by-Line Schema Breakdown:
* **`type`**: The internal identifier slug. It must match the filenames (`promo-banner.json` / `promo-banner.blade.php`).
* **`name`**: The user-friendly label shown in the builder panel sidebar.
* **`icon`**: A FontAwesome Free icon class (e.g. `fa-bullhorn`, `fa-envelope`) representing the section.
* **`category`**: Sidebar category grouping (e.g., `Marketing`, `Structure`, `Interactive`).
* **`max_blocks`**: Limits how many blocks can be placed within this section. Use `0` or omit to allow unlimited blocks.
* **`allowed_blocks`**: An array of block types (`type` slugs) that can be dropped inside this section.
* **`default_blocks`**: Predefined blocks that instantiate automatically when the section is added to the page.
* **`fields`**: Custom input configuration options:
  * `key`: The parameter name to access in Blade (e.g., `$sectionObj->grid_cols`).
  * `label`: The text label shown above the input.
  * `type`: The field control selector type (e.g., `range`, `text`).
* **`preset`**: Includes modular field bundles provided by the core engine.

---

## 9. Building Blocks (The Component Units)

Blocks reside inside sections (or nested inside group blocks) and act as the content units (e.g., headers, images, button links). They require a blade file (`blocks/name.blade.php`) and schema configuration (`blocks/name.json`).

### A. The Block Blade Layout (`blocks/info-card.blade.php`)

```blade
@php
    // Read block data settings array safely
    $s = $block['settings'] ?? [];
    
    $title = $s['title'] ?? 'Card Title';
    $desc = $s['description'] ?? 'Card description text.';
    $link = $s['link_url'] ?? null;
@endphp

{{-- 1. Wrap block with attributes() and styling helpers --}}
<div {!! block($block)->attributes() !!} class="info-card-wrapper">
    
    <div class="card-inner-box">
        <h3 class="arz-h3">{{ $title }}</h3>
        <p class="arz-paragraph">{{ $desc }}</p>
        
        @if($link)
            <a href="{{ $link }}" class="arz-btn-primary mt-4">
                {{ $s['btn_text'] ?? 'Read More' }}
            </a>
        @endif
    </div>

</div>
```

#### Line-by-Line Block Code Breakdown:
* **Line 3**: Retrieves the customizer field values configured for this block.
* **Line 10**: `block($block)->attributes()` is **MANDATORY**. It renders identifier attributes (e.g. `data-block-id`) that hook into the editor workspace.
* **Line 13**: `.arz-h3` applies global customizer font options for heading-3 styling.
* **Line 14**: `.arz-paragraph` formats the descriptor text.

### B. The Block Config File (`blocks/info-card.json`)

```json
{
    "type": "info_card",
    "name": "Information Card",
    "icon": "fa-id-card",
    "fields": [
        {
            "key": "title",
            "label": "Card Header",
            "type": "text",
            "default": "A Premium Feature"
        },
        {
            "key": "description",
            "label": "Card Details",
            "type": "textarea",
            "default": "Describe your service or institution feature in this block."
        },
        {
            "key": "link_url",
            "label": "Redirect Link",
            "type": "text"
        },
        {
            "key": "btn_text",
            "label": "Button Label",
            "type": "text",
            "default": "Learn More"
        }
    ]
}
```

---

## 10. Form Control Type Catalog

The following field configuration controls can be declared inside the `fields` array of your section and block JSON schemas.

### 1. `text` (Single line text input)
```json
{
    "key": "subheading_text",
    "label": "Subheading Label",
    "type": "text",
    "default": "Enter short text"
}
```

### 2. `textarea` (Multi-line text input)
```json
{
    "key": "body_copy",
    "label": "Paragraph Copy",
    "type": "textarea",
    "default": "Write your detailed description here."
}
```

### 3. `range` (Numeric slider control)
```json
{
    "key": "corner_radius",
    "label": "Border Radius (px)",
    "type": "range",
    "min": 0,
    "max": 50,
    "step": 2,
    "default": 8
}
```

### 4. `select` (Drop-down picker selection list)
```json
{
    "key": "aspect_ratio",
    "label": "Image Scale Aspect",
    "type": "select",
    "options": [
        { "label": "Auto aspect", "value": "auto" },
        { "label": "Square (1:1)", "value": "1:1" },
        { "label": "Widescreen (16:9)", "value": "16:9" }
    ],
    "default": "auto"
}
```

### 5. `radio` (Radio button selection options)
```json
{
    "key": "button_size",
    "label": "Button Scale Size",
    "type": "radio",
    "options": [
        { "label": "Small Size", "value": "sm" },
        { "label": "Normal Size", "value": "md" },
        { "label": "Large Size", "value": "lg" }
    ],
    "default": "md"
}
```

### 6. `switch` (Boolean toggle switch)
```json
{
    "key": "show_border",
    "label": "Enable Outer Border",
    "type": "switch",
    "default": true
}
```

### 7. `color` (Hex color code selection wheel)
```json
{
    "key": "custom_tint",
    "label": "Custom Overlay Color",
    "type": "color",
    "default": "#2563eb"
}
```

### 8. `image` (Media library image picker)
```json
{
    "key": "promo_graphic",
    "label": "Banner Graphic Image",
    "type": "image"
}
```

### 9. `video` (Media library video file picker)
```json
{
    "key": "bg_loop_video",
    "label": "Background Video file",
    "type": "video"
}
```

### 10. `color_scheme_selector` (Renders the institutional color schemes palette)
```json
{
    "key": "color_scheme",
    "label": "Active Color Scheme",
    "type": "color_scheme_selector"
}
```

---

## 11. Customizer Preset Fields

Presets are pre-assembled field collections provided by the ATS engine. Instead of manually declaring padding sliders or background type switches for every section, you insert standard presets.

| Preset Key | Included Fields & Blade Output Variables |
| :--- | :--- |
| `section_flex_fields` | Includes vertical layout alignment settings. Binds layout alignment definitions dynamically to `$section->flex`. |
| `section_size_fields` | Includes options for section heights and width structures (e.g., container vs. full-width). Binds output styling to `$section->height` and `$section->container`. |
| `section_bg_type_fields` | Renders background selection parameters (Color, Image, Video, Gradient). Evaluated output is rendered directly via `{!! $section->backgrounds() !!}`. |
| `bg_blur_fields` | Background backdrop-filter blur controls. |
| `bg_overlay_fields` | Background overlay color and opacity configuration values. |
| `section_spacing_fields` | Outputs sliders for margin-top, margin-bottom, padding-top, and padding-bottom. Binds directly to `$section->margin` and `$section->padding`. |
| `section_mobile_spacing_fields` | Adds mobile-specific margin/padding spacing sliders. Binds directly to `$section->marginMobile` and `$section->paddingMobile`. |
| `visibility_fields` | Adds options to show or hide the section on desktop and mobile viewports. Renders class names to `$section->visibility`. |

---

## 12. Theme Customizer Configuration (`config/settings.json`)

Located at `config/settings.json`, this file lists the default values for the theme customizer options (e.g., typography size, button paddings, container width boundaries).

```json
{
    "primary_button_shape": "8",
    "primary_button_padding_vertical": "12",
    "primary_button_padding_horizontal": "24",
    "primary_button_font_size": "16",
    "primary_button_font_weight": "600",
    "primary_button_text_transform": "default",
    "primary_button_border_width": "1",
    "primary_button_font_family": "Outfit",
    "heading_font_family": "Outfit, sans-serif",
    "paragraph_font_family": "Inter, sans-serif",
    "container_width": "1200",
    "smooth_scroll": true,
    "scroll_animations": true
}
```

* These variables map to the `$customizes` array.
* The system automatically generates a dynamic stylesheet rendering these variables as CSS custom properties under the `:root` scope at runtime.

---

## 13. Theme Color Schemes (`config/schemes.json`)

Located at `config/schemes.json`, this contains the color schemes available to the user. Each scheme maps specific keys to hex color codes.

```json
{
    "color_schemes": [
        {
            "key": "scheme_1",
            "colors": [
                {
                    "scheme_colors": {
                        "background": "#ffffff",
                        "heading": "#1a1a1a",
                        "subheading": "#2d2d2d",
                        "paragraph": "#4a4a4a",
                        "secondary_text": "#6b6b6b",
                        "invert_text": "#ffffff",
                        "link": "#000000",
                        "link_hover": "#222222",
                        "border": "rgba(0,0,0,0.1)",
                        "shadow": "rgba(0,0,0,0.05)"
                    },
                    "primary_btn": {
                        "background": "#2563eb",
                        "text": "#ffffff",
                        "hover_background": "#1d4ed8",
                        "hover_text": "#ffffff",
                        "border": "#2563eb",
                        "hover_border": "#1d4ed8"
                    },
                    "secondary_btn": {
                        "background": "transparent",
                        "text": "#2563eb",
                        "hover_background": "rgba(37,99,235,0.1)",
                        "hover_text": "#1d4ed8",
                        "border": "#2563eb",
                        "hover_border": "#1d4ed8"
                    }
                }
            ]
        }
    ]
}
```

* **Applying schemes**: Call the helper `scheme('scheme_1')` on any section element.
* **Variable resolution**: The helper will output the color mapping as inline CSS variables, replacing target variables:
  ```html
  <div style="--arz-bg: #ffffff; --arz-heading: #1a1a1a; ...">
  ```

---

## 14. Theme Scripting & Scoped Events (`assets/global.js`)

Since sections and blocks can be added, deleted, or re-sorted dynamically in the Live Page Editor, standard event handlers (like `document.addEventListener('DOMContentLoaded')`) can fail when new elements are injected.

### JavaScript Guidelines:
1. **Event Delegation**: Bind event listeners to the main document body instead of direct child element listeners:
   ```javascript
   // INCORRECT (Fails on newly added elements in editor)
   document.querySelectorAll('.accordion-header').forEach(header => {
       header.addEventListener('click', toggleAccordion);
   });

   // CORRECT (Works dynamically for both existing and newly added items)
   document.addEventListener('click', function(event) {
       const header = event.target.closest('.accordion-header');
       if (header) {
           toggleAccordion(header);
       }
   });
   ```

2. **Intersection Observers**: When implementing dynamic scroll effects, observe elements continuously and ensure observer instances clean up if elements are removed:
   ```javascript
   const revealObserver = new IntersectionObserver((entries) => {
       entries.forEach(entry => {
           if (entry.isIntersecting) {
               entry.target.classList.add('is-revealed');
           }
       });
   }, { threshold: 0.1 });

   document.querySelectorAll('.scroll-reveal').forEach(el => revealObserver.observe(el));
   ```

3. **Editor Event Listeners**: Keep script executions independent of backend structures. Listen for custom editor hooks if your JS components (like sliders) require re-initialization upon updates.

---

## 15. Theme Packaging & Namespace Isolation Rules

To prepare your theme for the ATS ZIP Upload System, adhere to the following rules:

1. **Namespace Isolation**: Always scope stylesheet selectors under your theme's custom attribute namespace to prevent styles leaking to the admin dashboard:
   ```css
   /* Correct: Scoped selection */
   [data-theme="nucleus"] .custom-navigation {
       display: flex;
   }

   /* Incorrect: Universal class name override */
   .custom-navigation {
       display: flex;
   }
   ```
2. **Compile-Free Assets**: Do not package any local compile files (e.g. `webpack.config.js`, `postcss.config.js`, `package.json`, or a `node_modules` directory). All assets inside `assets/` must be directly readable by standard browsers (raw CSS, plain ES6 JavaScript, static assets).
3. **No Direct System Paths**: Avoid linking assets using hardcoded path prefixes. Always use ATS helpers (such as `media()`, `image()`, and `video()`) or the color variables to ensure paths resolve correctly across different tenant environments.
