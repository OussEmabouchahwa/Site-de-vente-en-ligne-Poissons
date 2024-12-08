# Contributing to ElMarché

## Welcome Contributors! 🎉

We're thrilled that you're interested in contributing to ElMarché, an open-source e-commerce marketplace platform.

## Code of Conduct

Please be respectful and considerate of others. Harassment, discrimination, and offensive behavior are not tolerated.

## How to Contribute

### 1. Reporting Issues

- Check existing issues before creating a new one
- Use the provided issue templates
- Provide detailed information:
  * Steps to reproduce
  * Expected behavior
  * Actual behavior
  * Environment details

### 2. Feature Requests

- Explain the feature in detail
- Provide use cases
- Discuss potential implementation approaches

### 3. Pull Request Process

1. Fork the repository
2. Create a feature branch
   ```bash
   git checkout -b feature/AmazingFeature
   ```
3. Commit your changes
   ```bash
   git commit -m 'Add some AmazingFeature'
   ```
4. Push to the branch
   ```bash
   git push origin feature/AmazingFeature
   ```
5. Open a Pull Request

### Coding Standards

- Follow Laravel and PSR-12 coding standards
- Write clean, readable code
- Add comments for complex logic
- Write tests for new features

### Development Setup

1. Clone the repository
2. Install dependencies
   ```bash
   composer install
   npm install
   ```
3. Copy `.env.example` to `.env`
4. Generate application key
   ```bash
   php artisan key:generate
   ```
5. Run migrations
   ```bash
   php artisan migrate
   ```

### Testing

- Run PHP tests
  ```bash
  php artisan test
  ```
- Run JavaScript tests
  ```bash
  npm run test
  ```

## Development Workflow

- Use feature branches
- Rebase before merging
- Squash commits for clean history

## Areas Needing Help

- Frontend improvements
- Performance optimization
- Additional payment gateways
- Internationalization
- Documentation

## Questions?

Open an issue or contact maintainers at contact@elmarche.com

## Thank You! 🚀

Your contributions make open-source amazing!
