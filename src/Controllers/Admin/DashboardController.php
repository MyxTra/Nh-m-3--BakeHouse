<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\ContactRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\StatsRepository;

final class DashboardController extends BaseController
{
    public function index(Request $request): Response
    {
        $stats = new StatsRepository();
        $contacts = new ContactRepository();
        $reviews = new ReviewRepository();
        $products = new ProductRepository();

        return $this->render('admin.dashboard', [
            'title' => 'Tổng quan',
            'summary' => $stats->summary(),
            'contactCounts' => $contacts->statusCounts(),
            'reviewCounts' => $reviews->viewCounts(),
            'productCounts' => $products->viewCounts(),
            'monthlyChart' => $stats->monthlySeries(12),
            'dailyChart' => $stats->dailySeries(30),
            'topSelling' => $stats->topSelling(5),
            'mostReviewed' => $stats->mostReviewed(5),
            'recentOrders' => $stats->recentOrders(5),
            'recentActivity' => $stats->recentActivity(8),
        ], 'layouts.admin');
    }
}