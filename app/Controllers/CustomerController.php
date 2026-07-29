<?php

namespace App\Controllers;

use App\Repositories\CustomerRepository;
use App\Core\CSRF;

class CustomerController extends BaseController {
    private CustomerRepository $customerRepo;

    public function __construct() {
        $this->customerRepo = new CustomerRepository();
    }

    public function index(): void {
        $this->requireLogin();

        $customers = $this->customerRepo->getAll();

        $this->render('customers/index', [
            'activeNav' => 'customers',
            'customers' => $customers
        ]);
    }

    public function store(): void {
        $this->requireLogin();
        $this->requireRole(['Admin', 'Inventory Manager']);
        CSRF::validateToken();

        $name = trim($_POST['customer_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $company = trim($_POST['company_name'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (empty($name)) {
            $this->flash('error', 'Customer name is required.');
            $this->redirect('/customers');
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
            $this->flash('success', "Customer {$name} registered successfully.");
        } else {
            $this->flash('error', 'Failed to register customer.');
        }

        $this->redirect('/customers');
    }
}
