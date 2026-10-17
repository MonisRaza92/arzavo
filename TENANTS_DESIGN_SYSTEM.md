# Arzavo UI Design Guidelines & System Rules

Ye document Arzavo platform ke sabhi pages ke liye standard visual rules define karta hai, taaki future me banaye gaye sabhi views aur layouts 100% consistent, clean aur modern lagein.

---

## 1. Color Tokens (`theme.css` Only)

Theme tokens dynamically dark aur light mode (aur ecosystem curated themes) ke according switch hote hain:

| Token Class | Use Case |
|---|---|
| `bg-secondary` | **Main Page Body Background** (`<body>` ya page container) |
| `bg-primary` | **Sidebar, Navbar, Blocks, Cards, Modals** |
| `bg-secondary` / `bg-subtle` | **Inner Sub-blocks, Nested Stat Cards, Inputs** |
| `bg-brand text-primary` | **Primary CTA Buttons, Active Nav Items, Fill Bars** |
| `text-theme-text` | Primary Headings aur High-contrast Content |
| `text-theme-text-muted` | Labels, Subtitles, Meta Information, Timestamps |

### Semantic Status Tokens
- **Success (Active / Verified):** `bg-success-bg text-success`
- **Warning (Unverified / Trial):** `bg-warning-bg text-warning`
- **Danger (Expired / Suspended / Delete):** `bg-danger-bg text-danger`
- **Info (Notice / Badge):** `bg-info-bg text-info`

---

## 2. Typography Rules

1. **NO `font-bold`:**
   - Kisi bhi element par `font-bold`, `font-extrabold`, ya `font-black` use **nahi** karna hai.
2. **Maximum Weight = `font-semibold`:**
   - Sirf main headings (`<h1>`, `<h2>`, `<h3>`) ya prominent labels ke liye `font-semibold` allow hai.
3. **Zyadatar Normal Font:**
   - Buttons, inputs, tables, descriptions, badges aur secondary content hamesha `font-normal` rahenge.
4. **No Font-Style Classes:**
   - `italic`, custom font classes (`font-mono` sparingly for IDs only), ya font-family overrides lagana mana hai.

---

## 3. Border Radius Hierarchy

Elements ke size ke hisaab se radius strictly define hai:

| Element Type | Radius Rule | Tailwind Class |
|---|---|---|
| **Bade Elements** (Cards, Containers, Sections, Modals, Tables) | Maximum `rounded-lg` (kabhi bhi `rounded-xl`, `rounded-2xl` nahi lagana) | `rounded-lg` / `rounded-md` |
| **Chhote Elements** (Buttons, Badges, Profiles, Avatars, Inputs, Pills, Search Bar, Dots) | Hamesha full round pill | `rounded-full` |

---

## 4. No Border & Shadow Hierarchy Rules

1. **Borders & Dividers Rule:**
   - Cards aur containers par external borders nahi lagani.
   - **Divider Exception:** Complex hierarchies (jaise Academic Streams, Classes, Batches, Subjects ya Table rows) ko clearly separate karne ke liye subtle horizontal dividers allow hain: `border-b border-subtle/50` ya `border-t border-subtle/40`.

2. **Alternating Background & Input Contrast Rule:**
   - Input fields aur dropdowns ka background hamesha parent container ke contrast me hoga:
     - Agar parent container `bg-primary` hai $\rightarrow$ to input / dropdown **`bg-secondary`** hoga.
     - Agar parent container `bg-secondary` hai $\rightarrow$ to input / dropdown **`bg-primary`** hoga.
   - Is rule se dropdowns aur inputs hamesha clean, prominent aur easily clickable dikhenge.

3. **Shadow Hierarchy (Flat Page vs Popup/Modal Exception):**
   - **General Page Layouts, Cards & Buttons:** Bilkul flat rehna chahiye — koi `shadow` use nahi karni.
   - **Popups & Modals (Shadow Exception Rule):** Jab bhi koi popup ya modal khule, floating depth dene ke liye size-based shadow use karni hai:
     - **Chhote Popups** (Dropdowns, 3-dots action menus, select options): Kam shadow — `shadow-md` ya `shadow-lg`.
     - **Medium Popups** (Drawers, flyouts, confirmation sheets): Moderate shadow — `shadow-xl`.
     - **Bade Popups & Modals** (Connect Domain, Password Reset, Delete Institute dialogs): Jitna bada popup utni zyada shadow — `shadow-2xl`.

4. **Tenant / Institute Icon:**
   - Tenant ka primary avatar/icon badge hamesha `bg-brand text-primary font-semibold rounded-full` hona chahiye.

---

---

## 5. Standard Component Cheat Sheet

### Button (Primary CTA - py-3)
```html
<button class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
    Action Button
</button>
```

### Button (Secondary / Ghost - py-3)
```html
<button class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
    Secondary Action
</button>
```

### Input Field (py-3)
```html
<input type="text"
       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none"
       placeholder="Enter value...">
```

### Content Card / Block
```html
<div class="rounded-lg bg-primary p-5">
    <h3 class="text-sm font-semibold text-theme-text mb-2">Block Title</h3>
    <div class="p-3 rounded-lg bg-secondary text-xs text-theme-text font-normal">
        Inner Stat or Content
    </div>
</div>
```

### Status Badge
```html
<span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-success-bg text-success">
    Active
</span>
```

---

## 6. Search & Content Guidance Patterns
- **Single Working Search:** Search input page par kabhi duplicate nahi hona chahiye. Top navbar me jo global search bar hai, wahi live Alpine reactive filtering drive karta hai (`x-model="search"`).
- **Search Feedback Strip:** Redundant filter tabs ki jagah intuitive section header use kiya jata hai jo dynamic search feedback provide karta hai (jaise: `Showing results for "query" · 2 matches found [Clear]`).
- **Guiding Micro-Copy & Metrics:** Sabhi metric cards aur metadata blocks me icons ke saath 1-line contextual explanation di jaati hai (jaise "Multi-tenant instances", "White-label DNS active", "Isolated Database schema") taaki users ko har parameter ka purpose samajh aaye.
- **Resource & Operations Guidance:** Page ke bottom me best practices cards include kiye jaate hain jo Single Sign-On, custom DNS setup aur database security ko explain karte hain.

---

## 7. Standard Page Header Banner Rules (Curriculum Architecture Standard)

Sabhi modules aur admin views ke top par hamesha standardized header banner hona chahiye, taaki platform ka har page 100% consistent dikhe:

### Structure & Layout Rules
1. **Container:** `rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4` (FLAT, NO BORDERS, NO SHADOW).
2. **Left Branding Area (`flex items-start gap-3.5`):**
   - **Icon Badge:** `w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5`
   - **Page Heading:** `text-base font-semibold text-theme-text leading-tight` (Kabhi bhi oversized `text-xl`/`text-2xl` use nahi karna; `text-base font-semibold` se clean aur balanced compact hierarchy milti hai).
   - **Subtitle / Description:** `text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl`
3. **Right Action Area (`flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0`):**
   - Action buttons (Primary `bg-brand text-primary`, Secondary `bg-secondary text-theme-text hover:bg-subtle`), count pills, ya navigation trails (`rounded-full`, `font-normal`, `text-xs`).

### Standard Header Component Cheat Sheet
```html
<div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <h1 class="text-base font-semibold text-theme-text leading-tight">
                Module / Page Title
            </h1>
            <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                Short contextual description explaining what users can manage and configure in this view.
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
        <a href="#" class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
            <span>Secondary Action</span>
        </a>
        <button type="button" class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
            <i class="fa-solid fa-plus text-[11px]"></i>
            <span>Primary CTA</span>
        </button>
    </div>
</div>
```

---

## 8. Breadcrumb Rule (Dashboard Only)
- **Global Layout (`layouts/admin.blade.php`) me Breadcrumbs / Welcome Back banner nahi hoga.**
- Breadcrumb strip (`Home > Admin > ...`), live clock, aur "Welcome Back" greeting card strictly sirf **Dashboard** (`admin.dashboard.index`) view ke top par rahega.
- Baaki sabhi inner module pages (Curriculum, Users, Students, Finance, Library, Courses, Blogs etc.) directly apne standardized **Page Header Banner** se shuru honge.

---

## 9. Global Flash Message Rule (`<x-alert />`)
- **Kisi bhi page Blade view me manually `@if(session('success'))` ya `@if(session('error'))` alert blocks nahi lagane hain.**
- Master layout (`resources/views/layouts/admin.blade.php`) me already `<x-alert />` globally included hai. Page views me manual flash message blocks lagana strictly prohibited hai.

