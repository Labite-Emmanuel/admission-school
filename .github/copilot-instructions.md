# admission-scholarapp Development Guide for AI Agents

## Project Overview
This is a **Laravel 11 admission scholarship application** with a multi-step enrollment workflow (5 sequential steps). The app manages student admissions, academic years, classes, and parent/guardian information. Frontend uses Blade templates with Vite/Vue integration; backend uses Laravel with SQLite (dev) and MySQL (production).

## Architecture & Key Data Flows

### Multi-Step Admission Workflow (Core Feature)
The admission process consists of 5 steps, tracked by `AdmissionStep` table and enforced in `get_Redirection()`:
1. **Step 1** → StudentDetails (student personal info)
2. **Step 2** → medical table (health records)  
3. **Step 3** → child_pickup table (pickup arrangements)
4. **Step 4** → parents (father, mother, guardian relationships)
5. **Step 5** → Finalization (review + submit)

**Controller pattern**: Each step has a `PostInfo_StepN()` method in [Admission.php](app/Http/Controllers/Admission.php). Data is saved incrementally; flow validation uses LEFT JOINs to check parent relationships. Store step state in session via `localstorage_data` JSON.

### Key Models & Relationships
- **User** (PK: `id_us`) - Tracks role-based access (admin/manager/teacher/student), academic year, registration status
- **StudentDetail** - Links to User, Classe, Parents (via code_father/code_mother/code_guardian)
- **AdmissionStep** - Progress tracking (code_stud, step, term, academicyear)
- **Classe** - Academic class linked to academicyear
- **AcademicYear** - Active academic period (etat=1 marks current)

Non-eloquent models: Use raw DB queries extensively (`DB::table()`, joins). Models mostly define table names + fillable fields.

### Authentication Patterns
- [AuthController.php](app/Http/Controllers/AuthController.php) handles login/register/logout
- Password hashing: Dual support—`Hash::check()` for new accounts, base64 fallback for legacy data
- Role-based redirects: admin/manager/teacher/student have different dashboard routes (not yet fully implemented)
- Session-based auth with `Auth::check()`
- Logout route: `POST /logout` with CSRF protection

## Development Workflows

### Build & Run
```bash
# PHP + Laravel backend
php artisan serve               # Default: http://localhost:8000
php artisan migrate             # Apply pending migrations
php artisan tinker              # Interactive shell

# Frontend (Vite + Vue/JS)
npm run dev                     # Watch mode for assets
npm run build                   # Production build (output: public/build/)
```

### Testing
```bash
php vendor/bin/phpunit          # Run all tests
```

### Database
- **SQLite** (dev, default): `database/database.sqlite`
- **MySQL** (prod): Configure via `.env` (DB_CONNECTION=mysql)
- Migrations live in `database/migrations/` with date-based naming (2026_02_05_*)

### Cache & Config Commands
- `php artisan cache:clear`, `config:clear` — triggered via `/clear-cache` route (debug utility)

## Code Patterns & Conventions

### Naming & Structure
- **Controllers**: CamelCase, single responsibility per controller (e.g., Admission, AuthController)
- **Models**: PascalCase, custom primary keys common (e.g., User uses `id_us` instead of `id`)
- **Tables**: snake_case with underscores (StudentDetails, academicyear, admission_step)
- **Routes**: kebab-case (`/home-admission`, `/post_info_step1`) — note inconsistency with underscores

### Database Queries
- Heavy reliance on **raw `DB::table()` queries** instead of Eloquent relationships
- LEFT JOINs used for optional relationships (e.g., checking parent existence)
- Queries return objects; access via `$result->column_name`
- Example pattern from [Admission.php](app/Http/Controllers/Admission.php#L122):
  ```php
  $stepAdmi4 = DB::table("StudentDetails")
    ->leftJoin('parents as father', 'father.code_parent', '=', 'StudentDetails.code_father')
    ->leftJoin('parents as mother', 'mother.code_parent', '=', 'StudentDetails.code_mother')
    ->leftJoin('parents as guardian', 'guardian.code_parent', '=', 'StudentDetails.code_guardian')
    ->where('StudentDetails.code_student', $codeStud)
    ->first();
  ```

### Form Handling
- POST endpoints validate with `Validator::make()` + custom messages
- File uploads use `FacadesStorage::disk('local')->put()` (reference File model)
- JSON responses for AJAX: `response()->json(['status' => '...', 'message' => '...'])`

### Views (Blade Templates)
- Base layout: [layout/](resources/views/admissions/layouts/)
  - [header.blade.php](resources/views/admissions/layouts/header.blade.php) — Main wrapper with navbar, sidebar, responsive design
  - [navbar.blade.php](resources/views/admissions/layouts/navbar.blade.php) — Included in header
  - [siderbar.blade.php](resources/views/admissions/layouts/siderbar.blade.php) — Included in header
- Admission views: [admissions/](resources/views/admissions/) with step-specific partials in `steps/`
- Pass data via controller: `view('template')->with($data)` or `->with(['key' => $value])`
- Frontend uses Vite for CSS/JS: `@vite(['resources/js/app.js', 'resources/css/app.css'])`

## UI/UX Design System

### Layout Architecture (Refactored Feb 2026)
- **Sidebar** (260px fixed, dark theme `#0f172a`): Navigation menu with icons, sticky position
- **Navbar** (sticky top): User avatar + name + role + logout button
- **Main content**: Flexbox layout with responsive breakpoints
- **Background**: Subtle gradient with watermark logo (opacity 0.08)

### Color Palette (CSS Variables)
```css
--primary-color: #1e40af (blue)
--secondary-color: #64748b (gray)
--success-color: #10b981 (green)
--danger-color: #ef4444 (red)
--warning-color: #f59e0b (amber)
--dark-bg: #0f172a (dark)
--light-bg: #f8fafc (light)
```

### Component Styling
- **Cards**: No border, rounded (10px), shadow on hover with lift effect
- **Tables**: Responsive, hover state, clean headers with uppercase labels
- **Buttons**: Rounded (6px), font-weight 600, hover transforms with shadows
- **Form inputs**: Border #e2e8f0, focus ring 3px blue outline, rounded 6px
- **Badges**: Various colors, padding 6px 12px, font-weight 600

### Responsive Behavior
- **Desktop** (> 768px): Sidebar visible + main content + navbar
- **Mobile** (≤ 768px): Sidebar hidden (toggle via `toggleSidebar()`), navbar simplified, user-details hidden

### User Display in Navbar
- Avatar: Circular badge with user's first letter, 40px, primary color background
- Name: First name + username displayed in user-details
- Role: User role (capitalized) displayed below name in gray
- Logout: Red button with icon, POST to `/logout` with CSRF token

## Critical Integration Points

### Session Management
- Uses Laravel's session (config in [config/session.php](config/session.php))
- Store step state in localstorage_data: `json_encode(['current_step' => 'stepN', 'code_student' => $code])`
- Redirect helper: `redirect('route')->with(['key' => $value])`
- Auth check: `Auth::check()` and `Auth::user()` available in all views

### Notifications
- [ValidationRequeteNotification.php](app/Notifications/ValidationRequeteNotification.php) defined but not integrated
- Mail config: [config/mail.php](config/mail.php)

### File Storage
- [File model](app/Models/File.php) manages uploads
- Disk config: [config/filesystems.php](config/filesystems.php)

### Third-Party Dependencies
- **Laravel Vite Plugin**: Asset bundling for frontend
- **Bootstrap 5.3**: CSS framework (CDN)
- **Font Awesome 6.4**: Icons library (CDN)
- **DataTables.net-dt**: For table rendering in views (datatables.net-dt in package.json)
- **Axios**: HTTP client for AJAX calls
- **FakerPHP**: Seeding test data

## Common Pitfalls & Quirks

1. **Mixed naming conventions**: Tables use snake_case and camelCase inconsistently (e.g., `StudentDetails` vs `academicyear`)
2. **No eager loading**: N+1 query risk; always check for unnecessary repeated queries in loops
3. **Legacy password encoding**: Some users have base64 passwords; auth checks both
4. **Raw SQL over Eloquent**: Relations not defined in models; harder to refactor but faster to prototype
5. **Hardcoded table names**: Use `DB::table('table_name')` not models for queries; harder to discover without reading controller
6. **Role-based redirects incomplete**: Dashboard routes exist in redirect logic but not all routes implemented
7. **Mobile sidebar**: Must be manually toggled on mobile; click outside to close via JS event listener

## Custom CSS Classes

Added to `resources/css/app.css` for consistency:
- `.empty-state`, `.empty-state-icon`, `.empty-state-text` — For empty data states
- `.status-active`, `.status-pending`, `.status-success` — Status indicators
- `.hover-shadow`, `.hover-lift` — Interactive effects
- `.divider` — Separator lines
- Alert colors: `.alert-success`, `.alert-danger`, `.alert-warning`, `.alert-info`

## Getting Help
- Read [CHANGELOG.md](CHANGELOG.md) for recent updates
- Check recent migrations in `database/migrations/` for schema changes
- Admission workflow logic is centralized in [Admission.php](app/Http/Controllers/Admission.php#L162-L220)
- Test edge cases around step transitions (validation in `get_Redirection()`)
- UI/UX refactored Feb 5, 2026: Check header.blade.php for design system implementation

