# HireFlow

A multi-tenant Applicant Tracking System (ATS) built with Laravel 11, Livewire 3, and a full production-style stack — the same category of product as Greenhouse, Workable, or Lever.

Every company that hires needs software like this. HireFlow lets multiple companies share a single platform while keeping their data completely isolated, and covers the full hiring lifecycle: job postings, candidate applications, resume parsing, a drag-and-drop hiring pipeline, structured interview scorecards, self-service interview booking, real-time notifications, a public REST API, webhooks, full-text search, and a platform-level admin panel.


## Tech Stack

| Layer | Technology |
|---|---|
| Backend framework | Laravel 11 (PHP 8.3) |
| Reactive UI | Livewire 3 |
| Styling | Tailwind CSS + Alpine.js |
| Admin panel | Filament 4 |
| Database | MySQL 8 |
| Cache / Queues | Redis |
| Object storage | MinIO (S3-compatible) |
| Full-text search | Meilisearch (via Laravel Scout) |
| Real-time / WebSockets | Laravel Reverb + Echo |
| API authentication | Laravel Sanctum |
| Multi-tenancy | Spatie Laravel Multitenancy |
| Roles & permissions | Spatie Laravel Permission |
| Activity logging | Spatie Laravel Activitylog |
| Resume parsing | smalot/pdfparser |
| Local dev environment | Laravel Sail (Docker) |

## Core Features

### Multi-tenant architecture
Each company (tenant) gets its own subdomain (`company-name.hireflow.test`) and completely isolated data, backed by a single shared database with tenant-scoped queries enforced at the model layer.

### Company onboarding
A three-step Livewire wizard (company details → admin account → plan selection) that provisions a new tenant, default hiring pipeline stages, and an admin account via a queued background job — so the workspace is ready without blocking the browser.

### Team management
Company admins invite teammates by email and role. Invitations use cryptographically signed, time-limited URLs (72-hour expiry) rather than plain links.

### Job postings & public careers page
Full CRUD for job postings with draft/published/closed/archived states. Each company gets a public, unauthenticated careers page listing only its published roles.

### Candidate applications & resume parsing
Candidates apply through the public careers page with a resume upload (stored in S3-compatible object storage). A background job extracts text from PDF resumes and automatically detects email, phone, LinkedIn URL, and a set of known technical skills.

### Drag-and-drop hiring pipeline
A Kanban-style board per job posting, built with Livewire and SortableJS. Moving a candidate card between stages persists instantly, logs an audit trail entry, and triggers an automated, queued candidate email.

### Scorecards & consensus view
Hiring team members submit structured interview feedback (per-criterion ratings plus a hire/reject/undecided decision). A consensus view aggregates every reviewer's scores side by side with averaged ratings per criterion.

### Interview self-scheduling
Recruiters publish open interview time slots via a signed, shareable link. Candidates pick their own slot with no back-and-forth emails; double-booking is handled gracefully.

### Real-time notifications
Laravel Reverb powers live, in-app notifications — for example, when a new application arrives or a teammate submits a scorecard, other logged-in users see it instantly with no page refresh.

### REST API
A token-authenticated API (Sanctum) lets external tools create job postings, submit applications, and read pipeline status, with per-tenant rate limiting and JSON responses shaped by explicit API Resource classes.

### Webhooks
Companies can register webhook endpoints for events like `application.stage_changed`. Payloads are HMAC-signed so receivers can verify authenticity, and delivery automatically retries on failure.

### Full-text search
Meilisearch-backed candidate search across name, email, and detected skills, with live-filtering results as you type.

### Platform admin panel
A Filament-based dashboard, separate from the tenant-facing app, for the platform owner to view and manage every tenant, with role-gated access restricted to a `super_admin` role.

## Architecture Notes

**Multi-tenancy:** implemented as single-database, shared-schema multi-tenancy — every tenant-scoped table carries a `tenant_id` column, and Spatie's `DomainTenantFinder` identifies the active tenant from the request's subdomain. Central, non-tenant routes (`hireflow.test`) handle company registration and cross-tenant actions like invitation acceptance.

**Background jobs:** anything that doesn't need to block the user's request — tenant provisioning, invitation emails, resume parsing, stage-transition emails, webhook delivery — runs through Laravel's queue system (`database` driver in development). Company onboarding specifically uses `dispatchSync()` rather than `dispatch()`, since a brand-new user should see their workspace exist immediately rather than wait on a queue worker.

**N+1 query prevention:** the pipeline board eager-loads every relationship it needs (`candidate`, `currentStage`) in a small, fixed number of queries regardless of how many candidates are displayed, rather than triggering a query per card.

**Signed URLs over authentication:** team invitations and interview bookings use Laravel's signed URL feature instead of requiring the recipient to have an account — appropriate for actions taken by people (new teammates, job candidates) who aren't yet, or may never be, authenticated users of the system.

## Local Development Setup

### Prerequisites
- Docker Desktop
- WSL2 (Windows) or a native Linux/Mac shell
- PHP 8.3, Composer, Node.js 20+ (installed inside your WSL2/Linux environment, not required on the host)

### Installation

```bash
git clone <your-repo-url>
cd hireflow

composer install
cp .env.example .env
php artisan key:generate

npm install
```

Configure your `.env` file with local database, Redis, and MinIO credentials (see `.env.example` for the required keys — `DB_*`, `AWS_*` for S3-compatible storage, `REVERB_*` for WebSockets, `MEILISEARCH_HOST`).

Start the Docker environment:

```bash
./vendor/bin/sail up -d
```

Run migrations and seed demo data:

```bash
sail artisan migrate --seed
```

This creates three demo companies (NovaTech, Systems Limited, Arbisoft), each with an admin, recruiter, and hiring manager account (password: `password` for all seeded users), plus sample job postings and candidates.

### Running the app

You'll need four processes running concurrently during development:

```bash
npm run dev                          # Vite dev server
sail artisan queue:work              # background job worker
sail artisan reverb:start --debug    # WebSocket server
```

Add each tenant subdomain to your hosts file (Windows: `C:\Windows\System32\drivers\etc\hosts`, macOS/Linux: `/etc/hosts`):

```
127.0.0.1 hireflow.test
127.0.0.1 novatech.hireflow.test
127.0.0.1 systems-limited.hireflow.test
127.0.0.1 arbisoft.hireflow.test
```

Visit `http://hireflow.test/register-company` to create a new company, or log in directly at `http://novatech.hireflow.test/login` with a seeded account.

### Platform admin access

A platform owner account is required for the Filament admin panel at `http://hireflow.test/admin`:

```bash
sail artisan tinker --execute="
\$user = App\Models\User::create([
    'name' => 'Platform Owner',
    'email' => 'owner@hireflow.test',
    'password' => bcrypt('password'),
    'tenant_id' => null,
]);
\$user->assignRole('super_admin');
"
```

## API Reference

Authenticate with a Sanctum bearer token, generated from a logged-in account at `/api-tokens`.

```
GET  /api/jobs                          List job postings for the authenticated token's company
POST /api/jobs                          Create a job posting
GET  /api/jobs/{id}                     Retrieve a single job posting
POST /api/applications                  Submit a candidate application
GET  /api/jobs/{jobPostingId}/pipeline  List applications for a job posting
```

Example:

```bash
curl -H "Authorization: Bearer <token>" -H "Accept: application/json" \
  http://novatech.hireflow.test/api/jobs
```

## Project Structure

```
app/
├── Events/              Broadcast events (real-time notifications)
├── Filament/            Platform admin panel resources
├── Http/
│   ├── Controllers/     Standard + API controllers
│   ├── Controllers/Api/ Token-authenticated API endpoints
│   ├── Middleware/      Tenant identification, API tenant resolution
│   └── Resources/       API response shaping
├── Jobs/                Queued background jobs
├── Livewire/            Reactive UI components
├── Mail/                Mailable classes
├── Models/              Eloquent models
└── Policies/            Authorization policies

database/
├── migrations/          Full schema history
└── seeders/              Demo data (tenants, roles, permissions)

routes/
├── web.php               Central-domain and tenant-domain routes
├── api.php                Token-authenticated API routes
└── channels.php           Broadcast channel authorization
```

## What This Project Demonstrates

- Single-database multi-tenant architecture with domain-based tenant resolution
- Background job orchestration, including the distinction between synchronous and queued dispatch
- Real-time features with Laravel Reverb and private broadcast channels
- File uploads to S3-compatible object storage, including presigned URL generation
- Signed URLs as an authentication-free security mechanism for external-facing actions
- A token-authenticated REST API with explicit response shaping and per-tenant rate limiting
- HMAC-signed webhook delivery with automatic retry
- Role-based authorization across three levels: gate/policy checks, Livewire component guards, and a separate admin-panel access contract
- Full-text search integration via Laravel Scout and Meilisearch

