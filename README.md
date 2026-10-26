# Ferry MVC

Lightweight PHP MVC framework built with simplicity and performance in mind.

## Features

- **Simple MVC Architecture** - Clean separation of concerns
- **PSR-4 Autoloading** - Composer-based class loading
- **Flexible Routing** - URL-based controller/method routing
- **View Rendering** - Clean template system with data passing
- **Database Support** - Ready for database integration
- **Helper Functions** - Global utility functions
- **Apache Ready** - `.htaccess` configurations included

## Requirements

- PHP 8.0 or higher
- Apache with `mod_rewrite` enabled
- Composer

## Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/ferry-mvc.git
cd ferry-mvc
```

2. Install dependencies:

```bash
composer install
```

3. Configure Apache virtual host or place in web root.

4. Update `App/Config/config.php` with your settings:

```php
define('URLROOT', 'http://localhost/ferry-mvc');
```

5. Visit your application:

```
http://localhost/ferry-mvc
```

## Project Structure

```
ferry-mvc/
├── App/
│   ├── Config/          # Configuration files
│   ├── Controllers/     # Application controllers
│   ├── Libraries/       # Core framework classes
│   ├── Models/          # Data models
│   ├── views/           # View templates
│   ├── bootstrap.php    # Bootstrap file
│   └── functions.php    # Helper functions
├── public/
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript files
│   ├── .htaccess       # Apache rewrite rules
│   └── index.php       # Entry point
├── vendor/             # Composer dependencies
├── .htaccess           # Root Apache config
├── composer.json       # Composer configuration
└── README.md          # This file
```

## Usage

### Creating a Controller

```php
<?php

namespace App\Controllers;

use App\Libraries\Controller;

class UsersController extends Controller
{
    public function index(): void
    {
        echo $this->view('users/index', [
            'title' => 'Users List',
        ]);
    }

    public function show(string $id): void
    {
        echo $this->view('users/show', [
            'title' => 'User Details',
            'userId' => $id,
        ]);
    }
}
```

### Creating a View

`App/views/users/index.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
</body>
</html>
```

### Creating a Model

```php
<?php

namespace App\Models;

class UserModel
{
    public function getUsers(): array
    {
        // Database logic here
        return [];
    }
}
```

Use in controller:

```php
$userModel = $this->model('user');
$users = $userModel->getUsers();
```

### Routing

URLs follow this pattern:

```
/{controller}/{method}/{params}
```

Examples:

- `/` → `PagesController::index()`
- `/pages/about` → `PagesController::about()`
- `/users/show/123` → `UsersController::show('123')`

### Helper Functions

`base_url()` - Generate application URLs:

```php
echo base_url('css/style.css');
// Output: /ferry-mvc/css/style.css
```

## Configuration

Edit `App/Config/config.php`:

```php
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost/ferry-mvc');
```

## Contributing

Contributions welcome. Fork the repository and submit pull requests.

## License

MIT License. See `LICENSE` file for details.

## Author

Ferry Hasan

## Roadmap

- [ ] Database abstraction layer
- [ ] Request/Response objects
- [ ] Middleware support
- [ ] Session management
- [ ] CSRF protection
- [ ] Validation library
- [ ] CLI commands
- [ ] Template engine integration
- [ ] Dependency injection container

## Support

Open an issue on GitHub for bugs or feature requests.
