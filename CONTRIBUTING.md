# Contributing to SAPOJAM UPP Jampea

Thank you for considering contributing to SAPOJAM UPP Jampea! This document provides guidelines for contributing to the project.

---

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How to Contribute](#how-to-contribute)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Feature Requests](#feature-requests)

---

## 🤝 Code of Conduct

### Our Pledge

We are committed to providing a welcoming and inspiring community for all. Please be respectful and constructive in all interactions.

### Our Standards

**Positive behavior includes**:
- Using welcoming and inclusive language
- Being respectful of differing viewpoints
- Gracefully accepting constructive criticism
- Focusing on what is best for the community

**Unacceptable behavior includes**:
- Harassment or discriminatory language
- Trolling or insulting comments
- Public or private harassment
- Publishing others' private information without permission

---

## 🚀 How to Contribute

### Types of Contributions

We welcome the following types of contributions:

1. **Bug Fixes**: Fix issues or bugs in the codebase
2. **New Features**: Implement new functionality
3. **Documentation**: Improve or add documentation
4. **Code Refactoring**: Improve code quality without changing functionality
5. **Performance Improvements**: Optimize existing code
6. **Tests**: Add or improve test coverage

---

## 💻 Development Setup

### Prerequisites

- PHP 8.3+
- PostgreSQL 16
- Composer 2.7+
- Node.js 20+
- Git

### Fork & Clone

```bash
# Fork the repository on GitHub/GitLab

# Clone your fork
git clone https://github.com/YOUR_USERNAME/sapojam-upp-jampea.git
cd sapojam-upp-jampea

# Add upstream remote
git remote add upstream https://github.com/uppjampea/sapojam-upp-jampea.git
```

### Setup Development Environment

```bash
# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Build assets
npm run dev

# Start development server
php artisan serve
```

### Keep Your Fork Updated

```bash
# Fetch upstream changes
git fetch upstream

# Merge upstream main into your local main
git checkout main
git merge upstream/main

# Push to your fork
git push origin main
```

---

## 📝 Coding Standards

### PHP Code Style

We follow **PSR-12** coding standard. Use Laravel Pint for automatic formatting:

```bash
# Format all files
./vendor/bin/pint

# Format specific file
./vendor/bin/pint app/Http/Controllers/KunjunganController.php

# Dry run (check without changing)
./vendor/bin/pint --test
```

**Key Rules**:
- 4 spaces for indentation (NO tabs)
- Line length max 120 characters
- CamelCase for class names
- camelCase for method names
- snake_case for variable names
- Type hints for parameters and return types

### Blade Templates

```blade
{{-- Good: Proper spacing and indentation --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>
    </div>
@endsection

{{-- Bad: No spacing, poor indentation --}}
@extends('layouts.app')
@section('content')
<div class="container"><h1>{{ $title }}</h1></div>
@endsection
```

### JavaScript (Alpine.js)

```javascript
// Good: Clear, readable structure
<div x-data="{
    items: [],
    addItem() {
        this.items.push({ name: '', value: '' });
    }
}">
    <!-- Content -->
</div>

// Bad: Unreadable inline code
<div x-data="{items:[],addItem(){this.items.push({name:'',value:''})}}">
```

### CSS (Tailwind)

```html
<!-- Good: Logical grouping, consistent spacing -->
<div class="bg-white rounded-lg shadow-md p-6 mb-4">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Title</h2>
</div>

<!-- Bad: Random order, no spacing pattern -->
<div class="mb-4 bg-white p-6 shadow-md rounded-lg">
    <h2 class="mb-2 text-gray-800 font-semibold text-xl">Title</h2>
</div>
```

---

## 🔀 Pull Request Process

### 1. Create a Feature Branch

```bash
# Create and checkout a new branch
git checkout -b feature/your-feature-name

# Or for bug fixes
git checkout -b fix/bug-description
```

**Branch Naming Convention**:
- `feature/` - New features (e.g., `feature/laporan-pelra`)
- `fix/` - Bug fixes (e.g., `fix/autocomplete-error`)
- `docs/` - Documentation (e.g., `docs/update-api-guide`)
- `refactor/` - Code refactoring (e.g., `refactor/kunjungan-controller`)
- `test/` - Adding tests (e.g., `test/kunjungan-validation`)

### 2. Make Your Changes

```bash
# Make your code changes
# ...

# Add files
git add .

# Commit with descriptive message
git commit -m "feat: add PELRA report generation"
```

**Commit Message Format** (Conventional Commits):
```
<type>: <description>

[optional body]

[optional footer]
```

**Types**:
- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation only
- `style:` - Code style (formatting, missing semicolons, etc.)
- `refactor:` - Code refactoring
- `test:` - Adding or updating tests
- `chore:` - Maintenance tasks

**Examples**:
```
feat: add dashboard statistics chart
fix: resolve autocomplete bug for kapal search
docs: update API documentation for pelabuhan endpoint
refactor: optimize kunjungan query with eager loading
test: add unit tests for KunjunganModel
```

### 3. Run Tests & Checks

```bash
# Format code
./vendor/bin/pint

# Run tests
php artisan test

# Check for errors
php artisan route:list
php artisan config:clear
```

### 4. Push to Your Fork

```bash
git push origin feature/your-feature-name
```

### 5. Create Pull Request

1. Go to GitHub/GitLab
2. Click "New Pull Request"
3. Select your branch
4. Fill in the PR template:

```markdown
## Description
Brief description of what this PR does.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update
- [ ] Code refactoring

## Changes Made
- List of changes
- Another change

## Testing
Describe how to test your changes.

## Screenshots (if applicable)
Add screenshots for UI changes.

## Checklist
- [ ] Code follows PSR-12 standards
- [ ] Tests added/updated and passing
- [ ] Documentation updated
- [ ] No console errors/warnings
- [ ] Database migrations tested (if applicable)
```

5. Submit the PR

### 6. Code Review Process

- Maintainers will review your PR
- Address any feedback or requested changes
- Once approved, your PR will be merged

---

## 🐛 Reporting Bugs

### Before Reporting

1. Check existing issues to avoid duplicates
2. Update to latest version and test again
3. Gather relevant information

### Bug Report Template

```markdown
**Describe the bug**
A clear and concise description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

**Expected behavior**
A clear description of what you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment:**
- OS: [e.g., Windows 11, Ubuntu 22.04]
- PHP Version: [e.g., 8.3.0]
- PostgreSQL Version: [e.g., 16.0]
- Browser: [e.g., Chrome 120]

**Additional context**
Add any other context about the problem.
```

---

## 💡 Feature Requests

### Before Requesting

1. Check if feature already exists or is planned
2. Search existing feature requests
3. Consider if it fits the project scope

### Feature Request Template

```markdown
**Is your feature request related to a problem?**
A clear description of the problem.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
Alternative solutions or features you've considered.

**Additional context**
Any other context, mockups, or examples.
```

---

## ✅ Pull Request Checklist

Before submitting a PR, ensure you have:

- [ ] Created a feature branch from `main`
- [ ] Written descriptive commit messages
- [ ] Followed coding standards (PSR-12)
- [ ] Added/updated tests for new features
- [ ] Run `./vendor/bin/pint` for code formatting
- [ ] Run `php artisan test` and all tests pass
- [ ] Updated documentation if needed
- [ ] Tested the changes locally
- [ ] Database migrations tested (if applicable)
- [ ] No console errors or warnings
- [ ] Added comments for complex logic
- [ ] Removed debug code (dd(), var_dump(), console.log())

---

## 🧪 Testing Guidelines

### Writing Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_example_feature()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get('/example');
        
        $response->assertStatus(200);
    }
}
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=KunjunganTest

# Run with coverage
php artisan test --coverage
```

---

## 📞 Getting Help

If you need help:

- **Email**: dev@uppjampea.id
- **Documentation**: See [DEVELOPMENT.md](DEVELOPMENT.md)
- **Issues**: Create a GitHub issue for bugs

---

## 📄 License

By contributing to SAPOJAM UPP Jampea, you agree that your contributions will be licensed under the project's proprietary license.

---

## 🙏 Thank You!

Thank you for contributing to SAPOJAM UPP Jampea! Your contributions help improve this project for everyone.

---

**Happy Contributing! 🚀**
