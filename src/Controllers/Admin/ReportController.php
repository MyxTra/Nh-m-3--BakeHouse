<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\OrderRepository;
use App\Repositories\StatsRepository;

/** Sales report: search + date range over delivered orders only, with printable KPI summary. */
final class ReportController extends BaseController
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->getQuery('q', ''));
        $from = $this->validDate((string) $request->getQuery('from', ''));
        $to = $this->validDate((string) $request->getQuery('to', ''));
        $stats = new StatsRepository();

        return $this->render('admin.reports.index', [
            'title' => 'In báo cáo',
            'search' => $search,
            'from' => $from,
            'to' => $to,
            'summary' => $stats->reportSummary($search, $from, $to),
            'rows' => (new OrderRepository())->reportList($search, $from, $to),
        ], 'layouts.admin');
    }

    private function validDate(string $value): ?string
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1 ? $value : null;
    }
}