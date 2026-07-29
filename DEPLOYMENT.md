# SIMS - Production & Local Deployment Guide 🚀

Follow this step-by-step guide to deploy the Smart Inventory Management System (SIMS) on local development environments (XAMPP, WAMP, PHP CLI) or Linux web hosting servers (cPanel, Nginx, Apache).

---

## 1. System Requirements

- **PHP**: Version 8.0 or higher (with `pdo_mysql`, `mbstring`, `fileinfo` extensions enabled).
- **Database**: MySQL 8.0+ or MariaDB 10.4+.
- **Web Server**: Apache 2.4+ (with `mod_rewrite` enabled) or Nginx, or PHP Built-in CLI Server.

---

## 2. Database Setup

### Step 2.1: Create Database & Import Schema
Run the following commands using MySQL CLI or phpMyAdmin:

```bash
# Using MySQL CLI
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sims_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p sims_db < database/schema.sql
mysql -u root -p sims_db < database/seed.sql
```

---

## 3. Application Configuration

Open `config/database.php` and configure your database credentials:

```php
return [
    'host' => '127.0.0.1',
    'port' => '3306',
    'dbname' => 'sims_db',
    'username' => 'your_db_user',
    'password' => 'your_db_password',
    'charset' => 'utf8mb4',
];
```

Ensure the `public/uploads/products` directory exists and has write permissions (`0755` or `0777`).

---

## 4. Running the Application

### Option A: Using PHP Built-in Server (Recommended for Development)
Run from project root directory:

```bash
C:\xampp\php\php.exe -S localhost:8000 -t public
```
Navigate to `http://localhost:8000` in your web browser.

### Option B: Apache / XAMPP / WAMP Setup
Place the project inside `htdocs` or `www` directory, or set your VirtualHost DocumentRoot to pointing to `/path/to/SIMS/public`.

Sample Apache VirtualHost:
```apache
<VirtualHost *:80>
    ServerName sims.local
    DocumentRoot "C:/xampp/htdocs/SIMS/public"
    <Directory "C:/xampp/htdocs/SIMS/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

---

## 5. Security & Verification Checklist

- [x] All user passwords are encrypted using `PASSWORD_DEFAULT` (bcrypt).
- [x] PDO prepared statements used for 100% of database queries.
- [x] Anti-CSRF token verification enabled on all state-changing POST forms.
- [x] Image file uploads verified for MIME type (`JPG`, `PNG`, `WEBP`) and file size limits (2MB).
- [x] Non-negative stock constraints enforced in database and service logic.
