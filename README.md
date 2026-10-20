# Sigma Operations Platform

Laravel 12 operations platform for managing staff, attendance, customer visits, sales activity, reports, and role-based access.

## Roles

- **Admin** — staff, customers, attendance, reports, visits, holidays, and business records
- **HR** — staff and attendance management
- **Sales Head** — customers, salesmen, visits, and out-of-city visits
- **Salesman** — personal customers, attendance, visits, reports, and old-customer records
- **Staff** — dashboard, attendance, and attendance history

## Appearance

The interface has **two independent appearance controls**, giving four possible
combinations. They are separate on purpose, so you can change one without
affecting the other.

| Combination | Light/dark | Glassmorphism |
| --- | --- | --- |
| Classic light | light | off |
| Classic dark | dark | off |
| Glass light | light | on |
| Glass dark | dark | on |

### Control 1 — Light / dark mode

- Toggled with the **moon / sun** button, top-right of the app shell and of the
  login and welcome pages.
- Instant, client-side, no page reload.
- Persisted per browser in `localStorage` under `sigma-theme`.
- Falls back to the operating system preference on first visit.
- The initial class is applied by an inline script in `<head>` to avoid a flash
  of the wrong theme, then re-applied by `ThemeManager.init()` in
  `resources/js/app.js`.

### Control 2 — Glassmorphism on / off

- Toggled with the **layers** button, top-right of the app shell and of the
  login and welcome pages. Also available in **Profile → Glass interface**.
- The toggle shows a small accent dot when glass is active.
- **Persisted per user** in the `glass_mode` column on `users`, so it follows
  the account to a new device. The login and welcome pages have no session, so
  they read the mirrored `localStorage` key `sigma-glass`; the app shell seeds
  that key from the account default the first time it is used in a browser.

### Previewing without saving

Append `?glass=1` or `?glass=0` to any URL to force glass on or off for that
request. This is handy for comparing the two designs and for checking contrast
before switching a whole team over.

### How it is implemented

Both modes share one set of components. `.glass-ui` is a class on `<html>`;
when it is present the frosted styles apply, and when it is absent a
`html:not(.glass-ui)` block restores the standard opaque surfaces from the rest
of the application. The classic fallback is expressed entirely in CSS, so
templates do not need to branch on the setting.

| Concern | Where |
| --- | --- |
| Glass tokens (`--glass-*`, `--auth-*`, `--field-*`, `--tile-*`) | `resources/css/app.css` |
| Glass components (`.glass`, `.glass-field`, `.auth-feature`, …) | `resources/css/app.css` |
| Classic fallbacks (`html:not(.glass-ui) …`) | `resources/css/app.css` |
| Dashboard glass layer (`.glass-ui .card`, `.glass-ui .stat-card`, …) | `resources/css/app.css` |
| Workspace motion (`.glass-ui .btn`, hover lifts, bloom) | `resources/css/app.css` |
| Backdrop markup (`.glass-bg`) | `layouts/app.blade.php`, `layouts/guest.blade.php` |
| Topbar search (`.topbar-search*`, `.search-result-link`) | `resources/css/app.css`, `layouts/app.blade.php` |
| Floating panels (`.menu-panel`) | `resources/css/app.css`, `layouts/app.blade.php` |
| Filter-bar grid (`.filter-bar`, `.filter-bar__actions`) | `resources/css/app.css` |
| Filter-bar components | `resources/views/components/filter-*.blade.php` |
| Field icons (`.input-icon-wrap`) | `resources/css/app.css` |
| Theme manager | `resources/js/app.js` |
| Glass preference persistence | `ProfileController::toggleGlassMode()`, `PATCH /profile/glass-mode` |

### Motion inside the workspace

The sheen sweep and hover lift that were previously limited to the login and
welcome pages are now applied app-wide under `.glass-ui`:

| Effect | Selector |
| --- | --- |
| Diagonal sheen sweep on hover | `.glass-ui .btn::after` |
| Button hover lift | `.glass-ui .btn:hover` |
| Card / stat-card lift | `.glass-ui .card-hover:hover`, `.glass-ui .stat-card:hover` |
| Sidebar link nudge | `.glass-ui .side-link:hover` |
| Magenta bloom through frosted panels | `.glass-ui .card::before` |
| Field icon colour + scale on focus | `.input-icon-wrap:focus-within` |

Every one of these is disabled under `prefers-reduced-motion: reduce`.

### The animated backdrop

Glass mode renders a full-bleed blurred gradient mesh:

- `.auth-mesh` — five layered radial gradients, animating `background-position`
- `.auth-blob--1…5` — large colour blobs, each drifting and independently
  cycling `hue-rotate`, with offset `--auth-blob-hue-delay` values so the
  colours never pulse in lockstep
- `.auth-vignette` — keeps contrast behind the frosted card
- `.auth-grain` — SVG turbulence to remove gradient banding

All of it stops under `prefers-reduced-motion: reduce`.

Glass depends on `backdrop-filter`. On browsers without support the panels fall
back to a flat translucent tint and stay readable.

## Current UI

The application uses a unified professional design system across authenticated, public, authentication, profile, attendance, and error views.

- Company logo: `public/images/logo.webp`
- Brand color: `#eb268f`, used sparingly for primary actions and active states
- Light and dark themes with saved preference
- Optional glassmorphism interface, switchable per user
- Responsive full-width authenticated workspace
- Collapsible desktop sidebar with persistent state
- Responsive mobile navigation drawer
- Subtle animated ambient glows, rings, and depth across the UI
- Theme-aware cards, tables, forms, alerts, statistics, and navigation
- Live activity indicators and subtle dashboard animations
- One shared filter-bar layout on every list page
- Reduced-motion support through `prefers-reduced-motion`

The Admin, HR, and Sales Head dashboards share one structure: a `page-head` with
title and date badge, a three-column tinted `stat-card` grid with `stat-bar`
progress meters, and paired live-activity cards. HR and Sales Head were restyled
onto the Admin layout; the underlying queries and the data they surface are
unchanged.

## Global Search

Search is exposed through `GET /global-search` and is filtered on the server according to the authenticated user's role.

| Role | Search options | Accessible results |
| --- | --- | --- |
| Admin | All, Customers, Staff | Staff, customers, old customers |
| HR | Staff | Staff only |
| Sales Head | Customers | Customers only |
| Salesman | Customers, Visits | Own customers, old customers, own visits |
| Other staff | Workspace pages | No restricted record search |

Single-option roles do not display a redundant search selector. The backend still enforces role permissions if a scope is manually requested.

## Key Features

- Role-aware dashboard redirects and navigation
- Global search with selectable record scopes
- 12-hour live clock with AM/PM
- Staff attendance clock-in and clock-out
- QR and GPS-assisted attendance
- Late-arrival reasons and dashboard summaries
- Employee and attendance administration
- Customer and old-customer records
- Visit creation, completion, cancellation, route history, and pitstops
- Out-of-city visit tracking
- Admin and Sales Head reports
- Holiday and non-working-day handling
- Excel and PDF export support
- Theme-aware Chart.js visualizations
- Tom Select and HTML5 QR scanner integrations
- Independent light/dark and glassmorphism preferences, persisted per user
- Shared filter-bar layout on all 20 list and report pages
- Glass motion language (button sheen, card lift, panel bloom) across the workspace

## Design System

The main design tokens and reusable components are defined in:

- `resources/css/app.css`
- `tailwind.config.js`
- `resources/js/app.js`

Important tokens include:

```text
--bg
--surface
--surface-2
--surface-3
--border
--border-strong
--text
--text-soft
--text-muted
--accent
--accent-strong
--accent-soft
--shadow
--shadow-lg
--pop-shadow
--pop-glass
```

Reusable classes include:

```text
card, card-hover, card-flat, soft-panel
btn, btn-primary, btn-outline, btn-ghost, btn-soft, btn-danger, btn-success
input, select, textarea, label, icon-btn
page-head, page-title, stat-card, tab
tbl-wrap, tbl, badge, alert, avatar
side-link, live-pill, ambient-canvas, ambient-orb
filter-bar, filter-bar__actions, input-icon-wrap
menu-panel, topbar-search, topbar-search__field, topbar-search__scope, search-result-link
```

Glassmorphism classes (auth pages, and the dashboard under `.glass-ui`):

```text
glass, glass-field, glass-field__icon, glass-field__toggle
glass-btn, glass-chip, glass-toggle-btn, glass-dot, glass-bg
auth-stage, auth-mesh, auth-blob, auth-vignette, auth-grain
auth-card, auth-card-line, auth-feature, auth-mini, auth-notice, auth-alert
```

Use these existing classes and CSS variables instead of introducing page-specific color systems or heavy shadows.

## Filter Bars

Every list page uses the same filter layout. Build it from the three shared
components instead of hand-writing the grid:

| Component | Purpose |
| --- | --- |
| `<x-filter-bar :action="route('…')">` | The `card filter-bar` grid, `method="GET"` |
| `<x-filter-field icon="users" label="Staff" class="lg:col-span-2">` | Icon-prefixed `.input-icon-wrap` cell |
| `<x-filter-actions :reset="route('…')">` | Filter / Reset buttons, right aligned; extra buttons go in the slot |

```blade
<x-filter-bar :action="route('admin.customers.index')">
    <x-filter-field icon="map-pin">
        <select name="city_id" class="select">…</select>
    </x-filter-field>

    <x-filter-field icon="search" class="lg:col-span-2">
        <input type="text" name="search" class="input">
    </x-filter-field>

    <x-filter-actions :reset="route('admin.customers.index')" />
</x-filter-bar>
```

`x-filter-field` passes any extra attribute (`id`, `class`, …) to its wrapper, so
fields that JS toggles (`id="customFromWrap"`) keep working.

## Floating Panels

`.menu-panel` is the shared surface for anything that floats above the page —
the user menu and the global-search results. It is opaque in classic mode and a
high-opacity frosted panel under `.glass-ui`, so it never becomes unreadable
against the animated mesh.

## Application Structure

```text
app/Http/Controllers/       Role and feature controllers
resources/views/            Blade templates
resources/views/layouts/    Authenticated, guest, and legacy navigation layouts
resources/views/components/ Shared Blade components (filter-bar, filter-field, filter-actions)
resources/css/app.css       Global design system
resources/js/app.js         Alpine components, icons, theme, clock, and search
public/images/logo.webp     Company logo
routes/web.php              Application routes
```

## Development

### Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- A configured Laravel database connection

### Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```

On Windows, copy `.env.example` to `.env` manually if `cp` is unavailable.

### Local Development

```bash
php artisan serve
npm run dev
```

Laragon users can access the application at:

```text
http://salesmantrackingsystem.test
```

### Verification Commands

```bash
npm run build
php artisan view:cache
php artisan route:list
php -l app/Http/Controllers/GlobalSearchController.php
vendor/bin/pint --test app/Http/Controllers/GlobalSearchController.php
```

The frontend project does not define separate lint or TypeScript scripts. There
is no test suite in this repository, so the checks above plus a manual pass over
each role are the only verification available.

## Notes

- `resources/views/layouts/app.blade.php` is the active authenticated application shell.
- `resources/views/layouts/guest.blade.php` is the active authentication shell.
- `resources/views/layouts/navigation.blade.php` is legacy and is not used by active routes.
- `resources/views/admin/attendance/pdf.blade.php` is a standalone print/PDF template.
- Some legacy routes reference controller methods that are not currently implemented; these are separate from the completed UI redesign.
- Filter query parameter names are unchanged from the pre-redesign markup, so
  bookmarked and emailed filter URLs keep working. Do not rename a `name`
  attribute on a filter control without also updating the controller.
- Reset on the staff list preserves the active / left / all tab, so it does not
  silently drop the user back to the default filter.
- The `glass_mode` column requires `php artisan migrate` on any environment that has
  not been migrated yet. The layout reads it defensively via
  `auth()->user()?->glass_mode`, so a missing column will not fatal, but the
  per-user preference will not persist until the migration has run.
