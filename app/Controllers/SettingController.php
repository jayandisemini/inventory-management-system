<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\SettingRepository;

class SettingController extends Controller {
    private SettingRepository $settingRepository;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        AuthMiddleware::authorize(['Admin']);
        $this->settingRepository = new SettingRepository();
    }

    public function index(): void {
        $settings = $this->settingRepository->getSettings();

        $this->render('settings/index', [
            'pageTitle' => 'System & Company Settings',
            'activeNav' => 'users',
            'settings' => $settings
        ]);
    }

    public function update(): void {
        $this->validateCSRF();
        $body = $this->request->getBody();

        $companyName = trim($body['company_name'] ?? '');
        if (empty($companyName)) {
            $this->session->setFlash('error', 'Company name is required.');
            $this->response->redirect('/settings');
        }

        $this->settingRepository->update([
            'company_name' => $companyName,
            'tax_id' => trim($body['tax_id'] ?? ''),
            'currency_symbol' => trim($body['currency_symbol'] ?? 'Rs.'),
            'default_min_stock' => (int)($body['default_min_stock'] ?? 5),
            'company_address' => trim($body['company_address'] ?? '')
        ]);

        $this->session->setFlash('success', 'System settings updated successfully!');
        $this->response->redirect('/settings');
    }
}
