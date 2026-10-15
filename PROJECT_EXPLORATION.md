# Project Exploration Guide

This document is a practical map of the project for someone opening the codebase for the first time.

## 1. What the project is

This is a Laravel 12 application for a company support portal. It currently combines:

- Support ticket creation, search, replies, attachments, status changes, and realtime chat.
- A training center with trainings, modules, uploaded media, and timestamped chapters.
- User and company management.
- Breeze-based authentication, email verification, password reset, and profile management.
- English and Arabic interface support.

The application uses server-rendered Blade views, Eloquent models, Laravel middleware, Alpine.js, Tailwind CSS, Vite, and Laravel Echo/Reverb for realtime updates.

## 2. Start here

When exploring the project, read files in this order:

1. `routes/web.php` — the main feature map and middleware boundaries.
2. `app/Models/` — the domain objects and their relationships.
3. `app/Http/Controllers/` — request handling and feature workflows.
4. `resources/views/layouts/` — shared application and navigation layout.
5. `resources/views/ticket/` and `resources/views/training/` — the two largest feature areas.
6. `resources/js/` — browser interactions, ticket search, chat, loading states, and Echo setup.
7. `database/migrations/` — the database schema and its evolution.
8. `app/Http/Middleware/` and `app/Http/Requests/` — access rules and validation.

The existing `README.md` is a route guide and contains useful background, but some of its “missing” items may no longer match the current code. Confirm behavior against the source files before relying on it.

## 3. Top-level structure

| Path | Purpose |
| --- | --- |
| `app/Http/Controllers/` | Feature and authentication controllers |
| `app/Http/Middleware/` | Active-user and role checks, locale selection |
| `app/Http/Requests/` | Reusable request validation |
| `app/Models/` | Eloquent models and relationships |
| `app/Events/` | Broadcast events, including ticket messages |
| `bootstrap/` | Laravel application bootstrapping and providers |
| `config/` | Framework, database, queue, broadcast, mail, and Reverb configuration |
| `database/migrations/` | Schema definitions |
| `database/factories/` | Test data factories |
| `database/seeders/` | Database seed data |
| `resources/views/` | Blade templates and UI components |
| `resources/js/` | Vite JavaScript entrypoints and feature scripts |
| `resources/css/` | Tailwind and application styling |
| `routes/` | Web, auth, console, and broadcast routes |
| `tests/` | Feature and unit tests |
| `public/` | Web entrypoint and public assets |
| `storage/` | Logs, generated framework files, and uploaded public files |
| `ticket-system.html` | Standalone/static UI reference or prototype; not the primary Laravel view layer |

## 4. Request and UI flow

```text
Browser request
    -> routes/web.php
    -> auth / active / role middleware
    -> controller
    -> request validation + Eloquent models
    -> Blade view or JSON response
    -> resources/js feature behavior
    -> optional broadcast event through Echo/Reverb
```

Most authenticated feature routes are grouped behind `auth` and `active` in `routes/web.php`. Role-specific behavior is currently enforced through middleware and controller checks using `User::type`.

## 5. Main domain model

```text
Company
  └── has many Users

User
  ├── has many Tickets
  ├── has many TicketReplies through authored replies
  └── belongs to Company

Ticket
  ├── belongs to User
  ├── has many Media attachments
  ├── has many TicketReplies
  └── has many TicketViews

Training
  ├── has many Modules
  └── has many TrainingMedia records

Module
  ├── belongs to Training
  ├── has one TrainingMedia record
  └── has many Chapters
```

Important role constants live in `app/Models/User.php`:

- `user`
- `admin`
- `super_admin`

Ticket statuses are defined in `app/Models/Ticket.php`:

- `pending`
- `in_progress`
- `completed`

## 6. Feature map

### Tickets

Main files:

- `app/Http/Controllers/TicketController.php`
- `app/Http/Controllers/ReplyController.php`
- `app/Models/Ticket.php`
- `app/Models/TicketReply.php`
- `resources/views/ticket/dashborad.blade.php`
- `resources/views/ticket/reply.blade.php`
- `resources/js/ticket-dashboard.js`
- `resources/js/ticket-chat.js`

Key behavior:

- Admins can see all tickets; regular users see their own tickets.
- Users create tickets with optional attachments.
- Admins move tickets into progress when opening them and can complete them.
- Replies are stored as `TicketReply` records.
- Replies can be returned as JSON and are broadcast through `SendMessage` for realtime updates.
- Ticket search supports text, date, and status filters.

Relevant routes include `ticket.index`, `ticket.create`, `ticket.store`, `ticket.reply`, `ticket.search`, `ticket.update`, and `reply.store`.

### Training center

Main files:

- `app/Http/Controllers/TrainingController.php`
- `app/Http/Controllers/ModuleController.php`
- `app/Http/Controllers/ChpaterController.php`
- `app/Models/Training.php`
- `app/Models/Module.php`
- `app/Models/Chapter.php`
- `app/Models/TrainingMedia.php`
- `resources/views/training/`

Key behavior:

- Trainings can be searched and listed.
- Regular users only see active trainings.
- Trainings contain modules; modules can have uploaded video/media.
- Chapters store a title and a timestamp string such as `12:30`.
- Module and chapter creation uses JSON/fetch requests from the Blade UI.

### Users and companies

Main files:

- `app/Http/Controllers/UsersController.php`
- `app/Http/Controllers/CompanyController.php`
- `app/Models/User.php`
- `app/Models/Company.php`
- `resources/views/user/`
- `resources/views/companies/`

The current routes support listing, creating, editing, and updating users, plus toggling user status. Companies support listing, creation, editing, updating, and showing associated users.

### Authentication and profile

Authentication routes are in `routes/auth.php` and use Laravel Breeze controllers under `app/Http/Controllers/Auth/`. Profile operations are handled by `ProfileController` and the views under `resources/views/profile/`.

## 7. Access control

The main access checks are:

- `auth`: the user must be signed in.
- `active`: disabled users are prevented from continuing to use the application.
- `isUser`: restricts routes intended for regular users.
- `isAdmin`: restricts routes intended for administrators.
- `isSuperAdmin`: available for super-admin checks.

Role checks are also present inside controllers, especially ticket and training workflows. When changing permissions, inspect both the route middleware and the controller logic.

## 8. Frontend behavior

`resources/js/app.js` is the main Vite entrypoint. Other scripts provide focused behavior:

- `create-ticket-form.js` — ticket form interactions and attachment handling.
- `ticket-dashboard.js` — local filtering, sorting, menus, and modal state.
- `ticket-chat.js` — reply submission, rendering, notifications, and realtime Echo listeners.
- `echo.js` — Laravel Echo/Reverb setup.
- `global-loading.js` — loading overlay behavior.

The main styling entrypoint is `resources/css/app.css`. It includes Tailwind directives, RTL support for Arabic, theme variables, and custom application styles.

## 9. Database and file storage

The database schema is defined in `database/migrations/`. The most important tables are:

- `users`
- `companies`
- `tickets`
- `ticket_replies`
- `ticket_views`
- `media`
- `trainings`
- `modules`
- `chapters`
- `training_media`

Uploaded files are generally stored on the `public` disk under paths such as `tickets`, `media`, or `training`. If local uploads are not visible, check that the public storage link has been created:

```bash
php artisan storage:link
```

## 10. Local setup and useful commands

Requirements are PHP 8.2+, Composer, Node.js/npm, and a configured database.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

For frontend development:

```bash
npm run dev
```

For the full development process defined by Composer:

```bash
composer run dev
```

Build frontend assets for production:

```bash
npm run build
```

Run tests:

```bash
composer test
```

Useful inspection commands:

```bash
php artisan route:list
php artisan migrate:status
php artisan about
```

## 11. Recommended exploration tasks

### Trace ticket creation

Start at `routes/web.php`, follow `TicketController::store`, then inspect the `Ticket` and `Media` models, the `tickets` and `media` migrations, and finally `resources/views/ticket/create.blade.php`.

### Trace a realtime reply

Start at `resources/views/ticket/reply.blade.php`, follow the `ticket-chat.js` fetch call to `ReplyController::store`, then inspect `app/Events/SendMessage.php`, `routes/channels.php`, and `resources/js/echo.js`.

### Trace training media

Start at `TrainingController::store`, then follow `TrainingMedia`, `ModuleController::store`, the training migrations, and the JavaScript/fetch code in `resources/views/training/show.blade.php`.

### Trace account disabling

Start at the user status route in `routes/web.php`, follow `UsersController::toggleStatus`, then inspect `EnsureUserIsActive` to see how the status affects later requests.

## 12. Current areas to verify before extending

- `TicketControllerOld.php` contains an older ticket implementation and can be confused with the active `TicketController.php`.
- `ChpaterController.php` is misspelled; preserve the existing route binding or rename it carefully across all references.
- Some controllers manually assign IDs using record counts. This can create collisions after deletions or concurrent writes; prefer database-generated IDs for future changes.
- `CompanyController` validates phone numbers as integers. Phone numbers are usually safer as strings so leading zeroes and long values are preserved.
- Role and ownership checks are split between middleware and controllers, so new endpoints should apply both authentication and ownership rules explicitly.
- The test suite has authentication/profile coverage, but feature-specific ticket, training, user, and company coverage should be expanded as behavior changes.

## 13. Suggested next improvements

1. Add feature tests for ticket ownership, admin visibility, replies, status changes, and attachments.
2. Add feature tests for training visibility and module/chapter creation.
3. Replace manual ID assignment with database-generated IDs.
4. Convert company and user phone fields to string validation/storage where appropriate.
5. Consolidate or remove the legacy `TicketControllerOld.php` after confirming it is unused.
6. Centralize authorization in policies or clearly defined middleware.
7. Add a consistent API/JSON response strategy for fetch-based UI actions.

