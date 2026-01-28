# Dead Simple Inventory Manager

A lightweight inventory management system built with Laravel 11 and PHP 8.4.

## Features

- **Product Management**: Track name, code, price, and descriptions.
- **Stock Tracking**: Monitor stock levels with minimum stock warnings.
- **Supplier Management**: Keep track of your suppliers and their contact details.
- **Category Organization**: Group products into categories.
- **Storage Locations**: (In progress) Manage where your inventory is stored.
- **API First**: Powered by Laravel Passport for secure API access.

## Requirements

- **PHP 8.4+**
- **Composer**
- **MySQL / PostgreSQL / SQLite**

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/malows/dead-simple-inventory-manager.git
   cd dead-simple-inventory-manager
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Setup environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations:
   ```bash
   php artisan migrate
   ```

5. Install Passport:
   ```bash
   php artisan passport:install
   ```

## Development

- **Tests**: Run `php artisan test`
- **Static Analysis**: Run `./vendor/bin/phpstan analyse`
- **Code Style**: Run `composer lint`

## License

The project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
