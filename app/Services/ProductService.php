<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;

class ProductService {
    private ProductRepository $productRepository;
    private NotificationService $notificationService;

    public function __construct() {
        $this->productRepository = new ProductRepository();
        $this->notificationService = new NotificationService();
    }

    public function validateAndSave(array $data, ?array $file = null, ?int $id = null): array {
        $errors = [];

        if (empty($data['product_name'])) {
            $errors[] = 'Product Name is required.';
        }

        if (empty($data['sku'])) {
            $errors[] = 'SKU Code is required.';
        } elseif ($this->productRepository->findBySku($data['sku'], $id)) {
            $errors[] = 'The SKU Code "' . htmlspecialchars($data['sku']) . '" is already assigned to another product.';
        }

        if (!empty($data['barcode']) && $this->productRepository->findByBarcode($data['barcode'], $id)) {
            $errors[] = 'The Barcode "' . htmlspecialchars($data['barcode']) . '" is already assigned to another product.';
        }

        if (empty($data['category_id']) || (int)$data['category_id'] <= 0) {
            $errors[] = 'Please select a valid Product Category.';
        }

        if (empty($data['supplier_id']) || (int)$data['supplier_id'] <= 0) {
            $errors[] = 'Please select a valid Product Supplier.';
        }

        if (!isset($data['unit_price']) || (float)$data['unit_price'] < 0) {
            $errors[] = 'Unit Cost Price cannot be negative.';
        }

        if (!isset($data['selling_price']) || (float)$data['selling_price'] < 0) {
            $errors[] = 'Selling Price cannot be negative.';
        }

        if (!isset($data['quantity']) || (int)$data['quantity'] < 0) {
            $errors[] = 'Initial Quantity cannot be negative.';
        }

        if (!isset($data['min_stock_level']) || (int)$data['min_stock_level'] < 0) {
            $errors[] = 'Minimum Stock Level cannot be negative.';
        }

        // Image upload handling
        $imageFileName = null;
        if ($id) {
            $existingProduct = $this->productRepository->findById($id);
            $imageFileName = $existingProduct ? $existingProduct->image : null;
        }

        if ($file && !empty($file['name']) && $file['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 2 * 1024 * 1024; // 2MB

            if (!in_array($file['type'], $allowedTypes)) {
                $errors[] = 'Invalid image format. Allowed formats: JPG, PNG, WEBP.';
            } elseif ($file['size'] > $maxSize) {
                $errors[] = 'Uploaded image file exceeds the 2MB size limit.';
            } else {
                if (!is_dir(UPLOAD_PATH)) {
                    mkdir(UPLOAD_PATH, 0755, true);
                }
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $imageFileName = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
                $destination = UPLOAD_PATH . '/' . $imageFileName;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    $errors[] = 'Failed to save uploaded product image to disk.';
                }
            }
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $productData = [
            'product_name' => $data['product_name'],
            'sku' => strtoupper(trim($data['sku'])),
            'barcode' => !empty($data['barcode']) ? trim($data['barcode']) : null,
            'description' => $data['description'] ?? null,
            'category_id' => (int)$data['category_id'],
            'supplier_id' => (int)$data['supplier_id'],
            'unit_price' => (float)$data['unit_price'],
            'selling_price' => (float)$data['selling_price'],
            'quantity' => (int)$data['quantity'],
            'min_stock_level' => (int)$data['min_stock_level'],
            'image' => $imageFileName
        ];

        if ($id) {
            $this->productRepository->update($id, $productData);
            $productId = $id;
        } else {
            $productId = $this->productRepository->create($productData);
        }

        // Trigger stock status checks
        $qty = (int)$data['quantity'];
        $min = (int)$data['min_stock_level'];
        if ($qty <= 0) {
            $this->notificationService->notifyOutOfStock($data['product_name']);
        } elseif ($qty <= $min) {
            $this->notificationService->notifyLowStock($data['product_name'], $qty, $min);
        }

        return ['success' => true, 'product_id' => $productId];
    }
}
