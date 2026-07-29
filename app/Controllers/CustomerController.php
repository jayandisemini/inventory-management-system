<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\CustomerRepository;
use App\Core\CSRF;

class CustomerController extends Controller {
    private CustomerRepository $customerRepo;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->customerRepo = new CustomerRepository();
    }

    public function index(): void {
        $customers = $this->customerRepo->getAll();

        $this->render('customers/index', [
            'pageTitle' => 'Customer CRM Directory',
            'activeNav' => 'customers',
            'customers' => $customers
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);

        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'CSRF validation failed.'];
            $this->response->redirect('/customers');
            return;
        }

        $name = trim($_POST['customer_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $company = trim($_POST['company_name'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (empty($name)) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Customer name is required.'];
            $this->response->redirect('/customers');
            return;
        }

        $success = $this->customerRepo->create([
            'customer_name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company_name' => $company,
            'address' => $address
        ]);

        if ($success) {
            $_SESSION['flash_messages'][] = ['type' => 'success', 'value' => "Customer {$name} registered successfully."];
        } else {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Failed to register customer.'];
        }

        $this->response->redirect('/customers');
    }
}
