<?php

namespace App\Http\Controllers;

use App\Services\CertificateService;

class DashboardController extends Controller
{
    public function __construct(protected CertificateService $service) {}

    public function index()
    {
        $stats = $this->service->getOverviewStats();

        return view('dashboard.index', compact('stats'));
    }
}
