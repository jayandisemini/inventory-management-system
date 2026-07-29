<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SupplierRepository;
use App\Services\ProductService;

class ProductController extends Controller {
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private SupplierRepository $supplierRepository;
    private ProductService $productService;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->supplierRepository = new SupplierRepository();
        $this->productService = new ProductService();
    }

    public function index(): void {
        $filters = $this->request->getBody();
        $products = $this->productRepository->search($filters);
        $categories = $this->categoryRepository->getAll();
        $suppliers = $this->supplierRepository->getAll();

        if ($this->request->isAjax()) {
            $this->response->json(['success' => true, 'products' => $products]);
        }

        $this->render('products/index', [
            'pageTitle' => 'Product Inventory Catalog',
            'activeNav' => 'products',
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'filters' => $filters
        ]);
    }

    public function create(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $categories = $this->categoryRepository->getAll();
        $suppliers = $this->supplierRepository->getAll();

        $this->render('products/create', [
            'pageTitle' => 'Add New Inventory Product',
            'activeNav' => 'products',
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $postData = $this->request->getBody();
        $file = $this->request->getFile('image');

        $result = $this->productService->validateAndSave($postData, $file);

        if (!$result['success']) {
            $this->session->setFlash('error', implode('<br>', $result['errors']));
            $this->response->redirect('/products/create');
        }

        $this->session->setFlash('success', 'Product has been created successfully!');
        $this->response->redirect('/products');
    }

    public function edit(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $product = $this->productRepository->findById($id);

        if (!$product) {
            $this->session->setFlash('error', 'Requested product could not be found.');
            $this->response->redirect('/products');
        }

        $categories = $this->categoryRepository->getAll();
        $suppliers = $this->supplierRepository->getAll();

        $this->render('products/edit', [
            'pageTitle' => "Edit Product - {$product->product_name}",
            'activeNav' => 'products',
            'product' => $product,
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function update(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $postData = $this->request->getBody();
        $id = (int)($postData['id'] ?? 0);
        $file = $this->request->getFile('image');

        $result = $this->productService->validateAndSave($postData, $file, $id);

        if (!$result['success']) {
            $this->session->setFlash('error', implode('<br>', $result['errors']));
            $this->response->redirect("/products/edit?id={$id}");
        }

        $this->session->setFlash('success', 'Product details updated successfully!');
        $this->response->redirect('/products');
    }

    public function show(): void {
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $product = $this->productRepository->findById($id);

        if ($this->request->isAjax()) {
            if (!$product) {
                $this->response->json(['success' => false, 'message' => 'Product not found'], 404);
            }
            $this->response->json(['success' => true, 'product' => $product]);
        }

        if (!$product) {
            $this->session->setFlash('error', 'Requested product could not be found.');
            $this->response->redirect('/products');
        }

        $this->render('products/show', [
            'pageTitle' => "Product Details - {$product->product_name}",
            'activeNav' => 'products',
            'product' => $product
        ]);
    }

    public function delete(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $id = (int)($this->request->getBody()['id'] ?? 0);
        if ($id <= 0) {
            $this->session->setFlash('error', 'Invalid product ID.');
            $this->response->redirect('/products');
        }

        $this->productRepository->delete($id);
        $this->session->setFlash('success', 'Product permanently deleted from system catalog.');
        $this->response->redirect('/products');
    }

    public function barcodeLabels(): void {
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $product = null;

        if ($id > 0) {
            $product = $this->productRepository->findById($id);
        }

        $products = $product ? [$product] : $this->productRepository->getAll();

        $this->renderAuthView('products/barcode', [
            'pageTitle' => 'Printable Barcode & QR Label Sheet',
            'products' => $products
        ]);
    }
}
