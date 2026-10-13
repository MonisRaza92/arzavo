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

1. **NO Borders:**
   - Cards, buttons, tables, containers aur inputs par koi `border`, `border-t`, `border-b`, `border-dashed` nahi lagani.
   - Separation sirf background colors ke subtle contrast se aati hai (jaise `bg-secondary` main background par `bg-primary` cards, aur card ke andar `bg-secondary` inner blocks).

2. **Shadow Hierarchy (Flat Page vs Popup/Modal Exception):**
   - **General Page Layouts, Cards & Buttons:** Bilkul flat rehna chahiye — koi `shadow` use nahi karni.
   - **Popups & Modals (Shadow Exception Rule):** Jab bhi koi popup ya modal khule, floating depth dene ke liye size-based shadow use karni hai:
     - **Chhote Popups** (Dropdowns, 3-dots action menus, select options): Kam shadow — `shadow-md` ya `shadow-lg`.
     - **Medium Popups** (Drawers, flyouts, confirmation sheets): Moderate shadow — `shadow-xl`.
     - **Bade Popups & Modals** (Connect Domain, Password Reset, Delete Institute dialogs): Jitna bada popup utni zyada shadow — `shadow-2xl`.

3. **Tenant / Institute Icon:**
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

