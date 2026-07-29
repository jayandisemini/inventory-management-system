<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller {
    private CategoryRepository $categoryRepository;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->categoryRepository = new CategoryRepository();
    }

    public function index(): void {
        $categories = $this->categoryRepository->getAll();

        $this->render('categories/index', [
            'pageTitle' => 'Category Management',
            'activeNav' => 'categories',
            'categories' => $categories
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $body = $this->request->getBody();
        $name = trim($body['category_name'] ?? '');
        $description = trim($body['description'] ?? '');

        if (empty($name)) {
            $this->session->setFlash('error', 'Category Name is required.');
            $this->response->redirect('/categories');
        }

        $this->categoryRepository->create([
            'category_name' => $name,
            'description' => $description
        ]);

        $this->session->setFlash('success', "Category '{$name}' created successfully!");
        $this->response->redirect('/categories');
    }

    public function update(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $body = $this->request->getBody();
        $id = (int)($body['id'] ?? 0);
        $name = trim($body['category_name'] ?? '');
        $description = trim($body['description'] ?? '');

        if ($id <= 0 || empty($name)) {
            $this->session->setFlash('error', 'Category ID and Name are required.');
            $this->response->redirect('/categories');
        }

        $this->categoryRepository->update($id, [
            'category_name' => $name,
            'description' => $description
        ]);

        $this->session->setFlash('success', "Category updated successfully!");
        $this->response->redirect('/categories');
    }

    public function delete(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $id = (int)($this->request->getBody()['id'] ?? 0);
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            $this->session->setFlash('error', 'Category not found.');
            $this->response->redirect('/categories');
        }

        if ($category->product_count > 0) {
            $this->session->setFlash('error', "Cannot delete category '{$category->category_name}' because it contains {$category->product_count} active product(s).");
            $this->response->redirect('/categories');
        }

        $this->categoryRepository->delete($id);
        $this->session->setFlash('success', 'Category deleted successfully!');
        $this->response->redirect('/categories');
    }
}
