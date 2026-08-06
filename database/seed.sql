-- Smart Inventory Management System (SIMS) Seed Data
USE `sims_db`;

-- Insert Roles
INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Inventory Manager'),
(3, 'Staff')
ON DUPLICATE KEY UPDATE `role_name` = VALUES(`role_name`);

-- Insert Initial Users (Passwords: admin123, manager123, staff123)
INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role_id`, `created_at`) VALUES
(1, 'System Administrator', 'admin@sims.com', '$2y$10$yEFR9aeyIcoMvUA79Hf/Ou5MWcOK5jgLhYs50FbJwXWMYxjH3xBN2', 1, NOW()),
(2, 'Eleanor Vance (Manager)', 'manager@sims.com', '$2y$10$DweWT1Gj7DWbbFogCC1jyevaqG81kYiGvSPrBZ1c9O6.o1vlg1Vea', 2, NOW()),
(3, 'Robert Staff (Warehouse)', 'staff@sims.com', '$2y$10$fW1wuG.Ctgbre0gDG2jDfeBsggQnC/gXZs1YODSF9VvLnj3vwxtE2', 3, NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Insert Categories
INSERT INTO `categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Electronics & Gadgets', 'Computers, laptops, monitors, peripheries, and high-tech equipment.', NOW()),
(2, 'Office Furniture', 'Ergonomic chairs, standing desks, conference tables, and storage cabinets.', NOW()),
(3, 'Stationery & Supplies', 'Paper, pens, toner cartridges, binders, and everyday office accessories.', NOW()),
(4, 'Networking & Servers', 'Routers, Ethernet switches, server racks, CAT6 patch cables, and access points.', NOW()),
(5, 'Safety & Maintenance', 'First aid kits, fire extinguishers, cleaning supplies, and PPE equipment.', NOW())
ON DUPLICATE KEY UPDATE `category_name` = VALUES(`category_name`);

-- Insert Suppliers
INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_person`, `phone`, `email`, `address`, `created_at`) VALUES
(1, 'TechDistributors Inc.', 'Sarah Jenkins', '+1 (555) 234-5678', 'orders@techdist.com', '100 Silicon Way, San Jose, CA 95134', NOW()),
(2, 'Global Office Solutions', 'Marcus Brody', '+1 (555) 876-5432', 'sales@globalofficesolutions.com', '45 Enterprise Blvd, Chicago, IL 60607', NOW()),
(3, 'Apex Server Hardware Ltd.', 'David Kim', '+1 (555) 432-1098', 'support@apexserver.io', '800 Innovation Pkwy, Austin, TX 78701', NOW()),
(4, 'SafetyFirst Industrial', 'Rachel Green', '+1 (555) 345-6789', 'info@safetyfirstind.com', '12 Logistics Hwy, Memphis, TN 38118', NOW())
ON DUPLICATE KEY UPDATE `supplier_name` = VALUES(`supplier_name`);

-- Insert Sample Products
INSERT INTO `products` (`product_id`, `product_name`, `sku`, `barcode`, `description`, `category_id`, `supplier_id`, `unit_price`, `selling_price`, `quantity`, `min_stock_level`, `image`, `created_at`) VALUES
(1, 'Dell UltraSharp 27" 4K Monitor', 'ELE-MON-001', '884116382901', '27-inch 4K UHD IPS monitor with USB-C hub functionality.', 1, 1, 350.00, 489.99, 18, 5, NULL, NOW()),
(2, 'Logitech MX Master 3S Wireless Mouse', 'ELE-MOU-002', '097855172630', 'Ergonomic performance wireless mouse with silent clicks and 8K DPI.', 1, 1, 65.00, 99.99, 45, 10, NULL, NOW()),
(3, 'Ergonomic Mesh Executive Chair', 'FUR-CHR-001', '742689100123', 'High-back mesh ergonomic office chair with adjustable lumbar support.', 2, 2, 140.00, 249.99, 12, 4, NULL, NOW()),
(4, 'Motorized Adjustable Standing Desk (60x30)', 'FUR-DSK-002', '742689100456', 'Dual-motor height adjustable sit-stand desk frame with bamboo top.', 2, 2, 280.00, 499.00, 3, 5, NULL, NOW()),
(5, 'Cisco Catalyst 24-Port Gigabit Switch', 'NET-SWT-001', '619659102938', 'Managed 24-port Gigabit Ethernet switch with PoE+ support.', 4, 3, 420.00, 699.99, 7, 3, NULL, NOW()),
(6, 'HP LaserJet Pro M404dn Printer', 'STA-PRN-001', '193905492019', 'Monochrome laser printer with automatic duplex printing.', 3, 2, 180.00, 279.99, 2, 5, NULL, NOW()),
(7, 'Cat6 Ethernet Patch Cable 10ft (Pack of 10)', 'NET-CAB-002', '619659203947', 'Snagless RJ45 molded Ethernet cables 550MHz Gigabit speed.', 4, 3, 12.00, 24.99, 65, 15, NULL, NOW()),
(8, 'Commercial Heavy Duty First Aid Kit', 'SAF-FAK-001', '810012345678', 'ANSI 2021 Class B compliant 200-piece industrial medical kit.', 5, 4, 35.00, 64.99, 25, 8, NULL, NOW()),
(9, 'Apple MacBook Pro 16 M3 Pro 36GB', 'ELE-LAP-003', '194253019283', '16-inch Liquid Retina XDR, Apple M3 Pro chip, 36GB Unified Memory, 512GB SSD.', 1, 1, 2100.00, 2499.00, 0, 2, NULL, NOW()),
(10, 'Multipurpose Copy Paper 20lb A4 (Case of 10)', 'STA-PAP-002', '037000129485', '92 bright 20lb bond standard multipurpose white paper case.', 3, 2, 28.00, 44.99, 80, 20, NULL, NOW())
ON DUPLICATE KEY UPDATE `product_name` = VALUES(`product_name`);

-- Insert Initial Stock Movement Logs
INSERT INTO `stock_movements` (`movement_id`, `product_id`, `movement_type`, `quantity`, `reference_note`, `user_id`, `created_at`) VALUES
(1, 1, 'Stock In', 20, 'Initial procurement batch #PO-9001', 1, DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 1, 'Stock Out', 2, 'Assigned to Engineering Dept', 2, DATE_SUB(NOW(), INTERVAL 8 DAY)),
(3, 4, 'Stock In', 5, 'Procurement order #PO-9004', 1, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(4, 4, 'Stock Out', 2, 'Fulfilled remote employee request', 2, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(5, 6, 'Stock In', 5, 'Supplier delivery #PO-9006', 1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(6, 6, 'Stock Out', 3, 'Installed in Sales & HR departments', 2, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(7, 9, 'Stock Out', 5, 'All units reserved for Executive team rollout', 1, DATE_SUB(NOW(), INTERVAL 1 DAY))
ON DUPLICATE KEY UPDATE `reference_note` = VALUES(`reference_note`);

-- Insert Initial System Notifications
INSERT INTO `notifications` (`notification_id`, `user_id`, `type`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'warning', 'Low stock alert: HP LaserJet Pro M404dn Printer (Qty: 2, Min: 5)', 0, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 1, 'danger', 'Out of stock alert: Apple MacBook Pro 16 M3 Pro 36GB (Qty: 0, Min: 2)', 0, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 2, 'info', 'New stock movement recorded by Admin for Dell UltraSharp 27" 4K Monitor', 1, DATE_SUB(NOW(), INTERVAL 3 DAY))
ON DUPLICATE KEY UPDATE `message` = VALUES(`message`);

-- Insert Settings
INSERT INTO `settings` (`id`, `company_name`, `tax_id`, `currency_symbol`, `default_min_stock`, `company_address`) VALUES
(1, 'Smart Inventory Systems', 'TAX-889920', 'Rs.', 5, 'Colombo, Sri Lanka')
ON DUPLICATE KEY UPDATE `currency_symbol` = 'Rs.';

-- Insert Sample Product Batches & Expiry Data
INSERT INTO `product_batches` (`batch_id`, `product_id`, `batch_number`, `quantity`, `mfd_date`, `expiry_date`, `status`, `created_at`) VALUES
(1, 1, 'BATCH-2025-09A', 15, '2025-01-10', '2027-01-10', 'Active', NOW()),
(2, 6, 'BATCH-2026-MED', 20, '2025-06-01', DATE_ADD(CURRENT_DATE(), INTERVAL 18 DAY), 'Expiring Soon', NOW()),
(3, 3, 'BATCH-2024-OFF', 50, '2024-03-01', '2026-05-15', 'Expired', NOW()),
(4, 2, 'BATCH-2026-ELE', 35, '2026-01-15', '2028-01-15', 'Active', NOW()),
(5, 7, 'BATCH-2025-NET', 60, '2025-02-01', '2027-02-01', 'Active', NOW())
ON DUPLICATE KEY UPDATE `batch_number` = VALUES(`batch_number`);
