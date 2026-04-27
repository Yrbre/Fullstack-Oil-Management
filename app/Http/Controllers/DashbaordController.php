<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;


class DashbaordController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
}
