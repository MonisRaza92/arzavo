# Arzavo Theme System (ATS) Developer Guide

Welcome to the **Arzavo Theme System (ATS)**. This guide is designed for theme developers to help them build, customize, and maintain themes for educational institutions on the Arzavo platform.

> **ATS Philosophy:** Developers define the system (structure and logic), and users control the content (data and arrangement).

---

## 1. Theme Directory Structure

Every theme resides in its own folder under `resources/views/tenant/themes/{theme_slug}/`.

```text
theme_slug/
├── assets/             # Static files (CSS, JS, Images)
├── blocks/             # Reusable UI components (heading.blade.php, heading.json)
├── layouts/            # Global layouts (global.json)
├── pages/              # Default page structures (home.json, about.json)
├── sections/           # Major page building blocks (hero.blade.php, hero.json)
├── settings/           # Global theme configuration (settings.json, schemes.json)
└── templates/          # Pre-designed section bundles
```

---

## 2. Global Variables & Data Access

ATS shares several critical variables with all Blade templates to ensure consistency.

### `$customizes` (The Global Settings Object)
An associative array containing all values defined in `settings/settings.json`. Use this to apply global design choices like typography, container widths, and button styles.
```blade
<div style="max-width: {{ $customizes['container_width'] ?? '1400' }}px;">
```

### `$settings` (Tenant Information)
Global institutional settings like site name, contact info, and meta tags.
```blade
<h1>Welcome to {{ $settings['site_name'] }}</h1>
```

### `$menus` (Navigation Data)
A collection of all menus created by the tenant.
```blade
@foreach($menus as $menu)
    <li>{{ $menu->name }}</li>
@endforeach
```

---

## 3. Helper Functions

ATS provides specialized helpers to resolve paths, styles, and media efficiently.

| Helper | Description |
| :--- | :--- |
| `scheme($key)` | Returns a string of CSS variables for a specific color scheme. |
| `media($path)` | Resolves a public URL for stored media (handles S3/Local/Signed). |
| `image($path)` | Returns an image URL with an automatic demo fallback if the path is empty. |
| `video($path)` | Returns a video URL with a demo fallback. |
| `render_logo()` | Returns the primary logo URL defined by the user. |
| `render_invert_logo()` | Returns the inverted logo URL (falls back to primary). |
| `tenant_name()` | Returns the active institution's name. |
| `section($array)` | Hydrates raw section data into a `Section` service object. |
| `block($array)` | Hydrates raw block data into a `Block` service object. |

---

## 4. Theme "Standard" CSS Classes

To ensure your theme respects the user's global settings, always use the following built-in classes:

### Typography
- `.arz-h1` to `.arz-h6`: Automatically applies the user's chosen font, size, and weight for headings.
- `.arz-paragraph`: Applies paragraph-specific typography.
- `.arz-body-text`: Applies secondary/small text typography.

### Layout & Spacing
- `.container`: Centers content and applies the `container_width` from settings.
- `.arz-content`: Applies global horizontal padding to keep content aligned.
- `.arz-core`: Base class for sections (adds relative positioning and background variables).

### UI Elements
- `.arz-btn-primary`: Renders a primary button using the global design system.
- `.arz-btn-secondary`: Renders a secondary button.
- `.arz-link`: Applies theme link colors and hover effects.
- `.arz-border`, `.arz-border-t`, `.arz-border-b`: Applies standard borders using theme-defined variables.

---

## 5. Building Sections (The Root Component)

Every section requires two files: `name.blade.php` and `name.json`.

### Recommended Blade Structure
```blade
{{-- 1. Root Element with attributes() is MANDATORY --}}
<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    
    {{-- 2. System-handled backgrounds (colors, images, overlays) --}}
    {!! $section->backgrounds() !!}

    {{-- 3. Content wrapper with global container/padding --}}
    <div class="section-content {{ $section->container }} arz-content">
        
        {{-- 4. Dynamic block rendering --}}
        {!! $section->blocks !!}
        
    </div>

</div>

{{-- 5. Scoped dynamic styling --}}
<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
    }
    .arz-{{ $section->id }} .section-content {
        {{ $section->padding }}
        {{ $section->flex }}
        {{ $section->height }}
    }
    {{-- Responsive Overrides --}}
    @media (max-width: 767px) {
        .arz-{{ $section->id }} { {{ $section->marginMobile }} }
        .arz-{{ $section->id }} .section-content {
            {{ $section->paddingMobile }}
            {{ $section->heightMobile }}
        }
    }
</style>
```

---

## 6. Building Blocks (The Content Units)

Blocks are the elements inside sections (e.g., Headings, Buttons, Cards).

### Block Data Access
In a block's Blade file, use the `$block` object:
```blade
<div {!! $block->attributes() !!} class="block-wrapper {{ $block->flexClass }}">
    <h2 class="arz-h2">{{ $block->title }}</h2>
    <p class="arz-paragraph">{{ $block->text }}</p>
    
    @if($block->button_link)
        <a href="{{ $block->button_link }}" class="arz-btn-primary">
            {{ $block->button_text }}
        </a>
    @endif
</div>
```

---

## 7. The Global Color System

ATS uses a CSS variable-based system for color schemes.

### Applying a Scheme
Every section is automatically wrapped in a class named `arz-{scheme_name}`. Inside your CSS, use these variables:

- `--arz-bg`: Section background
- `--arz-heading`: Heading color
- `--arz-paragraph`: Text color
- `--arz-link`: Link color
- `--arz-btn-bg`: Primary button background

**Example:**
```css
.my-custom-card {
    background: var(--arz-bg);
    color: var(--arz-heading);
    border: 1px solid var(--arz-border);
}
```

---

## 8. Best Practices for Developers

1. **Avoid Hardcoding:** Never hardcode colors or font sizes. Always use CSS variables or `$customizes`.
2. **Use `attributes()`:** Always put `{!! $section->attributes() !!}` on the root of your section. Without this, the admin builder cannot "pick" or edit the section.
3. **Respect `visibility`:** Use `class="{{ $section->visibility }}"` to honor the user's choice to hide sections on desktop or mobile.
4. **Recursive Blocks:** Use `{!! $section->blocks !!}` for standard block rendering. If you need to filter blocks, use `{!! $section->blocks->only('heading') !!}`.
5. **Scoped Styles:** Always wrap your section styles in `.arz-{{ $section->id }}` to prevent CSS leakage to other parts of the page.

---

## Summary
Building an Arzavo theme is about creating **modular, style-aware components**. By using the provided helpers and global classes, you ensure that your theme will automatically adapt to the user's global settings, providing a premium and consistent experience.
