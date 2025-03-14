# CLAUDE.md - Laravel GMAO Project Guide

## Build, Lint, Test Commands
- Start Development: `composer dev` (server, queue, logs, vite)
- Build Frontend: `npm run build`
- Development Build: `npm run dev`
- Run All Tests: `php artisan test`
- Run Single Test: `php artisan test --filter TestName`
- Run Specific Test Class: `php artisan test tests/Feature/ExampleTest.php`
- PHP Linting: `./vendor/bin/pint`
- Database Migrations: `php artisan migrate`

## Code Style Guidelines
- Follow Laravel conventions: PSR-12 + Laravel adaptations
- Use typed properties (`string $name`) and return types (`function getName(): string`)
- Namespaces: Always use proper PSR-4 namespacing
- Models: Use fillable arrays, type hints in docblocks for relations
- Controllers: Minimal logic, delegate to services where appropriate
- Error Handling: Use Laravel's exception handling and validation
- Blade Templates: Use components, avoid logic in views
- Follow existing code's style for consistency (tabs/spaces)
- Prefix Naming: Controllers for plurals (UsersController), Models for singular (User)