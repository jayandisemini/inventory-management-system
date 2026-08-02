# NEXUS ERP - Smart Inventory Management System (SIMS Pro) 📦🚀

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Database](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Frontend](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](#license)

**NEXUS ERP / SIMS Pro** is an enterprise-grade, modern Smart Inventory Management System engineered with PHP 8, MySQL 8+, Bootstrap 5 dark theme, Chart.js analytics, DataTables, AJAX live search, and object-oriented MVC Architecture.

---

## 🌟 Key Features & Functional Modules

### 1. ⚡ Admin Executive Command Center
- **Executive Quick Actions Bar**: 1-click shortcuts for `Stock In`, `Stock Out`, `Add Product`, `Create PO`, `Manage Users`, and `View Reports`.
- **Live Trend Telemetry**: Real-time KPI stat cards with trend percentage comparison (`+14.2% vs last mo`), catalog health indicators (`Health: 100%`), and cost vs retail profit valuation.
- **Dual Visual Analytics**: Interactive Chart.js visualizations for **Monthly Stock Activity Telemetry** (Inflow vs Outflow) and **Category Stock Distribution** (Donut chart).
- **System Governance & Moderation**: 
  - **Pending Staff Requisitions Queue**: Admin queue with 1-click `Approve` and `Reject` buttons.
  - **Live Audit Stream Telemetry**: Real-time immutable audit trail displaying recent stock movements, operator usernames, timestamps, and movement badges.

---

### 2. 🔐 Role-Based Access Control (RBAC) & Security
- **Multi-Role Security Engine**: Session-based auth with pre-configured role permissions:
  - 👑 **Admin**: Full system authority (Users, Catalog, Warehouses, Stock Operations, Audits, Financial Reports, System Settings).
  - 📦 **Inventory Manager**: Catalog CRUD, Reorder Engine, Stock Operations, Suppliers, Purchase Orders, Reports.
  - 👁️ **Staff Member**: Catalog browsing, real-time AJAX stock search, and stock requisition submissions.
- **Security & Integrity Enforcement**:
  - Anti-CSRF token verification on 100% of POST forms.
  - PDO Prepared Statements protecting against SQL Injection attacks.
  - HTML entity output sanitization avoiding XSS vulnerabilities.
  - Strict MIME-type and 2MB file size checks on product image uploads.

---

### 3. 📦 Catalog & Warehouse Location Management
- **Product Management**: SKU auto-generator, barcode label rendering, cost price vs selling price tracking, category assignment, supplier linking, minimum stock alert limits.
- **Supplier & Category Directories**: Vendor profile management, supplied items tracking, and category grouping.
- **Multi-Warehouse Locations**: Warehouse profiles, storage capacities, and inter-warehouse stock transfer tracking.

---

### 4. 🔄 Stock Operations & Audit Trails
- **Stock In (Receiving)**: Inward inventory adjustments with supplier purchase order IDs, reference notes, and automated stock incrementing.
- **Stock Out (Dispatch)**: Outward item dispatches enforced with non-negative inventory validation rules.
- **Stock Adjustments**: Reconcile physical warehouse stock with system counts and audit variance logging.
- **Inter-Warehouse Transfers**: Move stock seamlessly between different warehouse locations.
- **Stock-Take Audits**: Conduct physical audit counts, log variances, and automatically reconcile inventory balances.

---

### 5. 🏷️ Advanced ERP Modules
- **Batch & Expiry Date Tracking**: Assign batch numbers and expiration dates to products with automated "Expiring Soon" and "Expired" alert badges.
- **Bill of Materials (BOM) & Assemblies**: Manage component recipes and execute finished product assembly work orders with automated raw material deductions.
- **Purchase Orders (PO) Procurement Engine**: Generate supplier purchase orders, track PO status (`Sent`, `Received`, `Cancelled`), print PO documents, and automatically update inventory upon receipt.
- **Customer Sales Orders & CRM**: Maintain customer profiles, process sales orders, auto-deduct stock upon sale, and generate printable receipts.
- **Staff Requisitions & Approvals**: Staff members can submit stock requests; Admins/Managers approve or reject requests with automatic stock allocation.

---

### 6. 📊 Reports & CSV Data Exporters
- **Printable HTML Views & PDF Reports**: Inventory Valuation, Low Stock Summary, Stock Movement Audit Logs, and Supplier Directories.
- **1-Click CSV Exporters**:
  - `GET /reports/export-inventory-csv`
  - `GET /reports/export-movements-csv`
  - `GET /reports/export-sales-csv`
  - `GET /reports/export-batch-expiry-csv`
  - `GET /reports/export-procurement-csv`

---

### 7. 🔌 RESTful API v1
Provides structured JSON endpoints for third-party integrations, mobile apps, or external ERP connectors:

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/v1/stock-summary` | `GET` | Overall inventory metrics, valuation, and stock health |
| `/api/v1/products` | `GET` | List all catalog products with search & category filters |
| `/api/v1/products/show?id={id}` | `GET` | Detailed product specifications and stock levels |
| `/api/v1/movements` | `GET` | Audit trail movement logs |
| `/api/v1/stock-adjust` | `POST` | Execute programmatic stock adjustments |

---

## 🛠️ Technology Stack

| Layer | Technologies |
| :--- | :--- |
| **Frontend UI** | HTML5, CSS3, Bootstrap 5.3 Dark Theme, Font Awesome 6, Inter Font |
| **Interactivity** | Vanilla JavaScript (ES6+), AJAX Live Search, DataTables 1.13, Chart.js 4 |
| **Backend Engine** | PHP 8.0+ (OOP, MVC Architecture, Repositories, Services, Front Controller Router) |
| **Database** | MySQL 8.0+ / MariaDB 10.4+ (InnoDB Engine, Foreign Key Constraints, Indexes) |

---

## 🏗️ Project Architecture

```
NEXUS-ERP/
├── app/
│   ├── Controllers/       # HTTP Request Handlers (Dashboard, Product, Inventory, PO, Sales, etc.)
│   ├── Core/              # Router, Database Singleton, Request/Response, Auth Middleware, Session, CSRF
│   ├── Models/            # Entity Data Models (Product, Movement, StockRequest, User, etc.)
│   ├── Repositories/      # Database Access Layer (PDO Prepared Statements)
│   ├── Services/          # Business Logic, Stock Rules & Automated Notifications
│   └── Views/             # Modular HTML Views (Layouts, Auth, Dashboard, Products, Reports, etc.)
├── config/                # App & Database Configuration
├── database/              # MySQL Database Schema (`schema.sql`) & Sample Data (`seed.sql`)
├── public/                # Document Root
│   ├── assets/            # Stylesheets, JavaScript, Icons
│   └── uploads/           # Uploaded Product Images
├── README.md              # Project Documentation
└── DEPLOYMENT.md          # Setup & Production Deployment Guide
```

---

## 🔑 Demo Account Credentials

| Role | Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| 👑 **System Admin** | `admin@sims.com` | `admin123` | Full System Authority |
| 📦 **Inventory Manager** | `manager@sims.com` | `manager123` | Operations, POs & Stock Control |
| 👁️ **Staff Member** | `staff@sims.com` | `staff123` | Terminal Search & Requisitions |

---

## 💻 Quick Start & Running Locally

### Step 1: Clone Repository
```bash
git clone https://github.com/jayandisemini/inventory-management-system.git
cd inventory-management-system
```

### Step 2: Import Database
Import the SQL files into your local MySQL / MariaDB server (e.g. via phpMyAdmin or MySQL CLI):
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS sims_db;"
mysql -u root sims_db < database/schema.sql
mysql -u root sims_db < database/seed.sql
```

### Step 3: Configure Database Credentials
Edit `config/database.php` if your local MySQL settings differ:
```php
return [
    'host' => '127.0.0.1',
    'port' => '3306',
    'dbname' => 'sims_db',
    'username' => 'root',
    'password' => '', // Your MySQL password
];
```

### Step 4: Start PHP Server
If PHP is in your system PATH:
```bash
php -S localhost:8000 -t public
```
Or if using XAMPP on Windows:
```powershell
C:\xampp\php\php.exe -S localhost:8000 -t public
```

### Step 5: Access Application
Open **[http://localhost:8000](http://localhost:8000)** in your web browser and sign in! 🚀

---

## 📄 License
This project is open-source software licensed under the **MIT License**.
