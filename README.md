# Smart Inventory Management System (SIMS) 📦🚀

An enterprise-level, modern Smart Inventory Management System (SIMS) built with PHP 8, MySQL 8+, Bootstrap 5, Chart.js, DataTables, AJAX, and object-oriented MVC Architecture.

---

## 🌟 Key Features

- **Enterprise Analytics Dashboard**: Dynamic KPI summary cards (Total Products, Categories, Suppliers, Total Inventory Valuation, Low Stock & Out of Stock counts) paired with Chart.js visualisations (Monthly Stock Movements, Category Distribution, Top Moving Items).
- **Authentication & Role-Based Access Control (RBAC)**: Secure session-based auth with pre-configured roles:
  - 👑 **Admin**: Full system access (User accounts management, catalog, inventory, audit logs, reports).
  - 📦 **Inventory Manager**: Catalog CRUD (Products, Categories, Suppliers), Stock Control (In/Out/Adjust), Reports.
  - 👁️ **Staff**: Catalog browsing, real-time AJAX stock search, and stock request operations.
- **Product Management**: Full CRUD, automatic SKU code generation, barcode tracking, cost vs selling prices, image upload validation, min stock level alerts, DataTables integration.
- **Supplier & Category Directories**: Vendor profile management, supplied item tracking, category grouping.
- **Stock Control & Audit Logging**:
  - **Stock In (Receiving)**: Restock inventory with reference notes and supplier purchase order IDs.
  - **Stock Out (Dispatch)**: Log item dispatches with non-negative stock enforcement.
  - **Stock Adjustments**: Reconcile physical warehouse stock counts with audit variance logging.
  - **Immutable Audit Trail**: Full history table tracking Movement ID, Product, Type, User operator, Date, and Reference Notes.
- **Automated Low Stock & Out of Stock Alerts**: Real-time header dropdown notifications and warning badges.
- **Business Intelligence Reporting**: Printable HTML views & PDF download support for Inventory Valuation, Low Stock, Stock Movement Logs, and Supplier Directories.
- **Security & Integrity**: CSRF token validation on all POST actions, PDO prepared statements protecting against SQL injection, HTML entity output sanitization, file upload type/size checks.

---

## 🛠️ Technology Stack

| Layer | Technologies |
| :--- | :--- |
| **Frontend UI** | HTML5, CSS3, Bootstrap 5.3, Font Awesome 6, Inter Google Font |
| **Interactivity** | Vanilla JavaScript (ES6+), AJAX Live Search, DataTables 1.13, Chart.js 4 |
| **Backend Engine** | PHP 8.0+ (OOP, MVC, Repositories, Services, Front Controller Router) |
| **Database** | MySQL 8.0+ / MariaDB 10.4+ (InnoDB Engine, Foreign Key Constraints, Indexes) |

---

## 🏗️ Architecture & Project Structure

```
SIMS/
├── app/
│   ├── Controllers/       # HTTP Request Handlers
│   ├── Core/              # Router, Database Singleton, Request/Response, Auth Middleware, Session, CSRF
│   ├── Models/            # Entity Data Models
│   ├── Repositories/      # Database Access Abstraction (PDO Prepared Statements)
│   ├── Services/          # Business Logic, Stock Rules & Automated Notifications
│   └── Views/             # Modular HTML Views (Layouts, Auth, Dashboard, Products, Suppliers, etc.)
├── config/                # App & Database Config
├── database/              # MySQL ERD Schema & Pre-seeded Sample Data
├── public/                # Document Root
│   ├── assets/            # Stylesheets, JavaScript, Icons
│   └── uploads/           # Product Uploaded Images
├── README.md              # Project Documentation
└── DEPLOYMENT.md          # Setup & Deployment Guide
```

---

## 🔑 Demo Account Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **System Admin** | `admin@sims.com` | `admin123` |
| **Inventory Manager** | `manager@sims.com` | `manager123` |
| **Staff Member** | `staff@sims.com` | `staff123` |

---

## 💻 Quick Start & Running Locally

1. **Import Database**:
   Import `database/schema.sql` and `database/seed.sql` into MySQL/MariaDB.
2. **Start PHP Server**:
   ```bash
   php -S localhost:8000 -t public
   ```
3. Open `http://localhost:8000` in your web browser.
