# ACME Donations

Internal donation platform for ACME Corp employees to create, manage, and contribute to fundraising campaigns.

## Quick Start

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=SettingsSeeder
php artisan db:seed --class=TestDataSeeder
npm run build
php artisan serve
```

## Test Account

- **Email**: admin@acme-donations.life
- **Password**: password

## Main URLs

- Homepage: http://acme-donations.test/
- Admin Dashboard: http://acme-donations.test/admin/dashboard
- Admin Settings: http://acme-donations.test/admin/settings

## Stack

- Laravel 12 + Vue 3 + Inertia.js
- SQLite (configurable for PostgreSQL/MySQL/MariaDB)
- Tailwind CSS
- Pest for testing
- PHPStan level 8

## Features

- Browse and create campaigns with search/filter
- Donate to campaigns with progress tracking
- Donation confirmation messages
- View personal donation history
- Admin dashboard with statistics
- Admin settings for application parameters
- Dark mode support

## Architecture Choices

### Backend

- **Laravel 12**: Latest LTS version with modern PHP 8.2+ features
- **Inertia.js**: Provides SPA experience without API complexity, sharing authentication state seamlessly
- **SQLite default**: Zero-configuration for development, easily switchable to production databases
- **Service pattern**: Controllers remain thin, business logic is encapsulated

### Frontend

- **Vue 3 Composition API**: Better TypeScript support and code organization
- **Tailwind CSS**: Rapid UI development with consistent design system
- **Reusable components**: InputError, PrimaryButton, etc. for consistency

### Security

- CSRF protection via Inertia
- Authorization via Policies (CampaignPolicy)
- Admin middleware for restricted routes
- Input validation on all forms

## Hypotheses & Assumptions

1. **Single currency**: All donations are in USD; multi-currency support can be added later
2. **Immediate donations**: No recurring donation schedules in MVP
3. **Internal users**: All users are employees with verified emails (no public registration in production)
4. **Campaign ownership**: Users can only edit their own campaigns (admins can edit all)
5. **Simple payment**: Payment gateway integration is deferred; current flow simulates successful payments
6. **No refunds**: Donation refund workflow is out of scope for MVP

## Technical Constraints & Solutions

### Constraint: Payment system undecided
**Solution**: Abstracted payment flow with placeholder `payment_method` field. The `DonationController::store()` can be extended to call payment gateway APIs (Stripe, PayPal) when ready.

### Constraint: Large organization (20K+ employees)
**Solutions**:
- Pagination on campaign lists
- Database indexes on frequently queried columns
- Eager loading to prevent N+1 queries
- Ready for horizontal scaling (stateless sessions)

### Constraint: Easy to run
**Solutions**:
- SQLite as default database
- Docker support via docker-compose
- Comprehensive seeder for test data
- Clear documentation

### Constraint: Enterprise security requirements
**Solutions**:
- PHPStan level 8 for static analysis
- Pest tests for critical flows
- OWASP-compliant input handling
- Role-based access control (is_admin)

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=DonationTest

# Static analysis
./vendor/bin/phpstan analyse
```

## Docker

```bash
docker-compose up --build
```

## Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | / | Homepage with featured campaigns |
| GET | /campaigns | All campaigns with search/filter |
| GET | /campaigns/{id} | Campaign details + donation form |
| POST | /campaigns | Create campaign |
| PUT | /campaigns/{id} | Update campaign |
| POST | /campaigns/{id}/donate | Make donation |
| GET | /donations | My donation history |
| GET | /admin/dashboard | Admin statistics |
| GET | /admin/settings | Application parameters |

## Future Enhancements

- Payment gateway integration (Stripe/PayPal)
- Email notifications for donations
- Campaign categories/tags
- Recurring donations
- Export reports (CSV/PDF)
- Campaign approval workflow
- Donation receipts/certificates

## License

MIT
