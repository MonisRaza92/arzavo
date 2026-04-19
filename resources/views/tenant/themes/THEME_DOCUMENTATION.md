# Arzavo Theme System (ATS) Developer Documentation

Welcome to the **Arzavo Theme System (ATS)**. This document serves as the authoritative guide for building and maintaining themes on the Arzavo multi-tenant platform.

---

## 1. Introduction

The Arzavo Theme System is a **JSON-first, Blade-powered** rendering engine. It transforms structured JSON layouts into dynamic, premium web experiences.

### High-Level Rendering Pipeline
`Route` → `ThemePageController` → `ThemePageDesign (JSON)` → `render.blade.php` → `Section Wrapper` → `Blade Template`.

The system is designed to separate **data (JSON)** from **markup (Blade)**, allowing users to customize their site structure via the Arzavo Builder while ensuring developers maintain full control over the aesthetic.

---

## 2. Folder Structure

Every theme must reside in `resources/views/tenant/themes/[theme_slug]/`.

### Structure Overview
```text
nucleus/
├── assets/             # Theme-specific CSS, JS, and Images
├── blocks/             # Reusable UI components (buttons, icons, etc.)
│   ├── button.json     # Block Schema
│   └── button.blade.php# Block Markup
├── pages/              # Default JSON layouts for system pages
│   └── home.json       # Homepage default sections
├── sections/           # Large page parts (hero, features, navbar)
│   ├── hero.json       # Section Schema
│   └── hero.blade.php  # Section Markup
├── settings/           # Global theme configurations
│   ├── global.json     # Header/Footer/Globals default layout
│   ├── schemes.json    # Default Color Scheme definitions
│   └── settings.json   # Theme-wide setting fields (Typography, etc.)
├── templates/          # Pre-configured section combinations
│   └── hero_minimal.json
└── theme.json          # Theme Metadata
```

---

## 3. theme.json Specification

The identity of your theme.

```json
{
    "name": "Nucleus",
    "folder": "nucleus",
    "version": "1.0.0",
    "author": "Arzavo",
    "description": "A modern and sleek theme.",
    "is_active": true,
    "preview_image": "/themes/nucleus/preview.jpg"
}
```

---

## 4. Global Settings System

Global settings control the "DNA" of the theme.

### settings.json
Defines the structure of the **Theme Settings** sidebar in the admin panel.
*   **Purpose**: Custom CSS, Typography, Layout constraints.
*   **Access**: Values are shared globally to all Blade views via the `$customizes` array.

### schemes.json
Defines the **Color Schemes** available to sections.
*   Each scheme translates to CSS variables: `--arz-bg`, `--arz-heading`, `--arz-btn-bg`, etc.
*   The system uses the `scheme($id)` helper to inject these into the `<style>` tag of the page.

---

## 5. Sections System

Sections are the primary building blocks of a page.

### section.json Schema
| Key | Type | Description |
| :--- | :--- | :--- |
| `type` | string | Unique identifier mismatching file name. |
| `name` | string | Human-readable name. |
| `fields` | array | Settings configurable in the builder. |
| `allowed_blocks` | array | List of block types this section can accept. |
| `max_blocks` | int | Cap on block count. |

### Blade Rendering Rules
Every section receives a `$section` variable (instance of `App\Services\Section\Section`).

**Standard Boilerplate:**
```blade
<section {!! $section->attributes !!} class="{{ $section->scheme }} {{ $section->visibility }}">
    <div class="{{ $section->container }}">
        {!! $section->blocks !!}
    </div>
</section>
```

---

## 6. Blocks System

Blocks are nested components inside sections.

### Block Querying
Use `$section->blocks` to render or filter blocks:
*   `{!! $section->blocks !!}`: Render all active blocks.
*   `{!! $section->blocks->only('icon') !!}`: Render only specific types.
*   `{!! $section->blocks->except('image') !!}`: Filter out types.

### Nesting
Blocks can also contain other blocks (e.g., a `group` block) using the same `$block->blocks` syntax.

---

## 7. Templates vs. Pages

### Templates
Templates are **pre-configured sections**. They exist in the `templates/` folder and allow you to define a complex section with pre-filled content (e.g., a `hero_minimal` is just a `custom_section` with pre-defined blocks).

### Pages
Pages define which sections/templates appear on a specific URL (slug) by default when a theme is first installed.

---

## 8. Pages System

`pages/home.json` example:
```json
{
    "home": [
        { "kind": "section", "name": "navbar" },
        { "kind": "template", "name": "hero_minimal" },
        { "kind": "section", "name": "footer" }
    ]
}
```
*   **kind: section**: Loads the default empty state of a section type.
*   **kind: template**: Loads a specific template file from the `templates/` folder.

---

## 9. Globals (Header/Footer)

The `settings/global.json` defines regions that repeat across the site.
*   **Structure**: `header`, `footer`, and `globals` arrays.
*   **Auto-Apply**: These are merged with page-specific sections during rendering in `render.blade.php`.

---

## 10. Rendering Engine Flow

1.  **Request**: Visitor hits `/about`.
2.  **Controller**: `ThemePageController` fetches `ThemePageDesign` for the active theme.
3.  **Merge**: The system merges `global.json` (Header) + Page Layout + `global.json` (Footer).
4.  **Wrappers**: Each JSON section object is wrapped into the `Section` class.
5.  **Rendering**: `render.blade.php` iterates and `@includeIf` the corresponding Blade file from the theme folder.

---

## 11. Blade Development Rules

1.  **Attributes**: Always use `{!! $section->attributes !!}` on the root element for builder compatibility.
2.  **Spacing**: Use `$section->padding`, `$section->margin`, and `$section->height` magic properties or dedicated Tailwind classes.
3.  **Asset Privacy**: Assets should be loaded through the `theme_asset()` helper.
4.  **No Hardcoding**: Never hardcode colors. Use CSS variables like `var(--arz-heading)` or `var(--arz-bg)`.
5.  **Safe Rendering**: Use `collect($section->blocks)` if you need to manually iterate without the `BlockQuery` renderer.

---

## 12. Do's and Don'ts

| ✅ DO | ❌ DON'T |
| :--- | :--- |
| Use `$section->scheme` to toggle colors. | Hardcode hex codes in Blade files. |
| Use `attributes` for editor selection. | Assign static IDs to section roots. |
| Check `View::exists()` before inclusion. | Assume a file is present. |
| Use `allowed_blocks` to restrict UI. | Allow every block in every section. |

---

## 13. Validation & Fallbacks

*   **Missing Files**: If a section's `.json` exists but `.blade.php` is missing, the engine skips it gracefully.
*   **Missing Data**: The `Section` class returns `null` for missing keys; use `??` for defaults in Blade.
*   **Required Keys**: Every section MUST have a `type` that matches its filename.

---

## 14. Advanced Concepts

### The Condition System
Fields in `section.json` can be conditional:
```json
"conditional": { "key": "background_type", "value": "image" }
```
This hides/shows the field in the builder UI based on other selected values.

### Marketplace Readiness
Themes are stateless. All configurations reside in JSON. To distribute a theme, zip the slug folder. Arzavo handles the database installation via `ThemeInstaller`.

---

© 2026 Arzavo Multi-Tenant Platform. Confidential Developer Documentation.
