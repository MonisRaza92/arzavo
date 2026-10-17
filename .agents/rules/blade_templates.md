# Blade Template Guidelines & Rules

## 1. No Manual Flash Alert Blocks
- **DO NOT** manually insert `@if(session('success'))` or `@if(session('error'))` blocks in individual page Blade views.
- The master admin layout (`resources/views/layouts/admin.blade.php`) already includes `<x-alert />` globally. Adding manual alert blocks causes duplicated messages and clutters page templates.

## 2. Layout & Design System Compliance
- Always refer to `TENANTS_DESIGN_SYSTEM.md` for styling, tokens, and component structures.
- Use `rounded-lg` for cards/modals, `rounded-full` for buttons/badges/inputs.
- Strict typography: Never use `font-bold` (maximum weight is `font-semibold`).
- Alternating container/input backgrounds (`bg-primary` card -> `bg-secondary` input).
