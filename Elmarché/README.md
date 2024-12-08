# ElMarché - E-commerce Marketplace Platform

## Overview
ElMarché is a modern, feature-rich e-commerce marketplace platform built with Laravel, designed to connect buyers and sellers seamlessly.

## Features
- User Authentication and Authorization
- Product Management
  * Create, Read, Update, Delete (CRUD) products
  * Image upload and management
  * Stock tracking
- Order Management
  * Place orders
  * Track order status
- Role-based Access Control
  * Seller and Buyer profiles
- Responsive Design with Tailwind CSS

## Technology Stack
- Backend: Laravel 9+
- Frontend: Blade, Tailwind CSS, Alpine.js
- Database: MySQL
- Authentication: Laravel Breeze

## Prerequisites
- PHP 8.0+
- Composer
- Node.js
- MySQL

## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/elmarche.git
cd elmarche
```

2. Install PHP dependencies
```bash
composer install
```

3. Install JavaScript dependencies
```bash
npm install
npm run dev
```

4. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

5. Set up database
```bash
php artisan migrate
php artisan db:seed
```

6. Start the development server
```bash
php artisan serve
```

## User Roles
- **Buyer**: Can browse products, place orders
- **Seller**: Can manage products, track orders

## Contributing
1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License
This project is open-sourced software licensed under the MIT license.

## Contact
- Project Maintainer: [Your Name]
- Email: contact@elmarche.com
