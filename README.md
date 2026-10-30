# Ferry MVC

Lightweight PHP MVC framework built with simplicity and performance in mind.

## Features

- **Simple MVC Architecture** - Clean separation of concerns
- **PSR-4 Autoloading** - Composer-based class loading
- **Flexible Routing** - URL-based controller/method routing
- **PDO Database Layer** - Prepared statements with parameter binding
- **Typed Entities** - Type-safe data objects with readonly properties
- **View Rendering** - Template system with partials support
- **Helper Functions** - XSS protection, URL generation, partials loader
- **Apache Ready** - `.htaccess` configurations included

## Requirements

- PHP 8.1 or higher
- Apache with `mod_rewrite` enabled
- Composer
- MySQL or compatible database

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/killflex/ferry-mvc.git
cd ferry-mvc
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Database

Update `App/Config/config.php` with your database credentials:

```php
<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ferry_mvc');

// Application URL
define('URLROOT', 'http://localhost/ferry-mvc');
define('SITENAME', 'FerryMVC');
```

### 4. Create Database

Create database and sample table:

```sql
CREATE DATABASE ferry_mvc;

USE ferry_mvc;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO posts (title, body) VALUES
('Welcome to Ferry MVC', 'This is your first post!'),
('Getting Started', 'Learn how to build with Ferry MVC framework.');
```

### 5. Configure Apache

**Option A: Use existing web root**

Place project in `htdocs` or `www` folder:

```
htdocs/ferry-mvc/
```

Access: `http://localhost/ferry-mvc`

**Option B: Virtual Host (Recommended)**

Add to Apache `httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName ferrymvc.local
    DocumentRoot "D:/path/to/ferry-mvc/public"

    <Directory "D:/path/to/ferry-mvc/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `hosts` file:

```
127.0.0.1 ferrymvc.local
```

Update `URLROOT` in `config.php`:

```php
define('URLROOT', 'http://ferrymvc.local');
```

### 6. Verify Installation

Restart Apache and visit:

```
http://localhost/ferry-mvc
```

or

```
http://ferrymvc.local
```

You should see posts list from database.

## Project Structure

```
ferry-mvc/
├── App/
│   ├── Config/          # Configuration files
│   │   └── config.php   # Database and app configuration
│   ├── Controllers/     # Application controllers
│   │   └── PagesController.php
│   ├── Entities/        # Typed data objects
│   │   └── Post.php
│   ├── Libraries/       # Core framework classes
│   │   ├── Controller.php  # Base controller
│   │   ├── Core.php        # Router
│   │   └── Database.php    # PDO wrapper
│   ├── Models/          # Data models
│   │   └── PostModel.php
│   ├── views/           # View templates
│   │   ├── pages/       # Page views
│   │   └── partials/    # Reusable partials
│   ├── bootstrap.php    # Bootstrap file
│   └── functions.php    # Helper functions
├── public/              # Web-accessible directory
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript files
│   ├── .htaccess       # Apache rewrite rules
│   └── index.php       # Entry point
├── vendor/             # Composer dependencies
├── .htaccess           # Root Apache config
├── composer.json       # Composer configuration
└── README.md          # Documentation
```

## Usage

### Creating an Entity

`App/Entities/User.php`:

```php
<?php

declare(strict_types=1);

namespace App\Entities;

class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
    ) {}
}
```

### Creating a Model

`App/Models/UserModel.php`:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Libraries\Database;
use App\Entities\User;

class UserModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUsers(): array
    {
        $this->db->query('SELECT * FROM users');
        $rows = $this->db->resultSet();

        return array_map(
            fn(object $row) => new User(
                (int) $row->id,
                (string) $row->name,
                (string) $row->email,
            ),
            $rows
        );
    }

    public function getUserById(int $id): ?User
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        return $row ? new User(
            (int) $row->id,
            (string) $row->name,
            (string) $row->email,
        ) : null;
    }
}
```

### Creating a Controller

`App/Controllers/UsersController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\Controller;
use App\Models\UserModel;

class UsersController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('user');
    }

    public function index(): void
    {
        $users = $this->userModel->getUsers();

        echo $this->view('users/index', [
            'title' => 'Users List',
            'users' => $users,
        ]);
    }

    public function show(string $id): void
    {
        $user = $this->userModel->getUserById((int) $id);

        if (!$user) {
            echo 'User not found';
            return;
        }

        echo $this->view('users/show', [
            'title' => 'User Details',
            'user' => $user,
        ]);
    }
}
```

### Creating a View

`App/views/users/index.php`:

```php
<?php

/**
 * @var string $title
 * @var \App\Entities\User[] $users
 */

require partials('_header.php');

?>

<h1><?= e($title) ?></h1>

<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <a href="<?= urlroot("users/show/{$user->id}") ?>">
                <?= e($user->name) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php require partials('_footer.php'); ?>
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

### Database Operations

The `Database` class provides PDO wrapper methods:

```php
// SELECT multiple rows
$this->db->query('SELECT * FROM posts WHERE status = :status');
$this->db->bind(':status', 'published');
$posts = $this->db->resultSet();

// SELECT single row
$this->db->query('SELECT * FROM posts WHERE id = :id');
$this->db->bind(':id', $id);
$post = $this->db->single();

// INSERT
$this->db->query('INSERT INTO posts (title, body) VALUES (:title, :body)');
$this->db->bind(':title', $title);
$this->db->bind(':body', $body);
$this->db->execute();

// UPDATE
$this->db->query('UPDATE posts SET title = :title WHERE id = :id');
$this->db->bind(':title', $newTitle);
$this->db->bind(':id', $id);
$this->db->execute();

// DELETE
$this->db->query('DELETE FROM posts WHERE id = :id');
$this->db->bind(':id', $id);
$this->db->execute();

// Row count
$count = $this->db->rowCount();
```

### Helper Functions

Available global functions:

```php
// Generate application URLs
echo urlroot('css/style.css');
// Output: /ferry-mvc/css/style.css

// Escape HTML output (XSS protection)
echo e($userInput);
// Output: HTML-safe string

// Load view partials
require partials('_header.php');
// Loads: App/views/partials/_header.php

// Get application root path
echo approot('App/Config/config.php');
// Output: /full/path/to/App/Config/config.php

// Get site name
echo sitename();
// Output: FerryMVC
```

## Configuration

### Database Configuration

Edit `App/Config/config.php`:

```php
<?php

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ferry_mvc');

// Application settings
define('URLROOT', 'http://localhost/ferry-mvc');
define('SITENAME', 'FerryMVC');
```

### Update Database Class

The `Database` class reads these constants automatically. Update credentials in `config.php` only.

### Apache Configuration

Ensure `mod_rewrite` is enabled:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Ensure `AllowOverride All` for your directory:

```apache
<Directory "D:/laragon/www">
    AllowOverride All
    Require all granted
</Directory>
```

## Best Practices

### Type Hints in Views

Add PHPDoc to avoid IDE warnings:

```php
<?php
/**
 * @var string $title
 * @var \App\Entities\Post[] $posts
 */
?>
```

### XSS Protection

Always escape output:

```php
<?= e($userInput) ?>
```

Not:

```php
<?= $userInput ?>
```

### Prepared Statements

Always use parameter binding:

```php
$this->db->query('SELECT * FROM users WHERE email = :email');
$this->db->bind(':email', $email);
```

Never:

```php
$this->db->query("SELECT * FROM users WHERE email = '$email'");
```

## Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'feat: add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Style

- Use PHP 8.1+ features
- Follow PSR-4 autoloading
- Add type hints for all method parameters and return types
- Use `declare(strict_types=1)` in all files
- Write descriptive commit messages

## License

MIT License. See `LICENSE` file for details.

## Author

Ferry Hasan

## Roadmap

- [x] MVC architecture with PSR-4 autoloading
- [x] PDO database layer with prepared statements
- [x] Typed entities with readonly properties
- [x] View rendering with partials
- [x] Helper functions (URL generation, XSS protection)
- [ ] Request/Response objects
- [ ] Middleware support
- [ ] Session management
- [ ] CSRF protection
- [ ] Form validation library
- [ ] CLI commands
- [ ] Dependency injection container
- [ ] Migration system
- [ ] Template engine integration
- [ ] Authentication system
- [ ] API support with JSON responses

## Support

- **Issues**: [GitHub Issues](https://github.com/killflex/ferry-mvc/issues)
- **Documentation**: This README

## Changelog

### v1.0.0 (2026-10-30)

- Initial release
- MVC architecture
- PDO database layer
- Typed entities
- View partials
- Helper functions
