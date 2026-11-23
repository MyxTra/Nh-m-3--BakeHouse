<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\NotificationRepository;

final class NotificationController extends BaseController
{
    public function __construct(private readonly NotificationRepository $notifications = new NotificationRepository())
    {
    }

    public function index(Request $request): Response
    {
        $userId = (int) Auth::id();
        $paginator = Paginator::fromRequest($request, $this->notifications->countForUser($userId), 15);
        $this->notifications->markAllRead($userId);

        return $this->render('client.notifications.index', [
            'title' => 'Thông báo',
            'rows' => $this->notifications->listForUser($userId, $paginator->getLimit(), $paginator->getOffset()),
            'paginator' => $paginator,
        ]);
    }

    public function markRead(Request $request): Response
    {
        $this->notifications->markAllRead((int) Auth::id());

        return $this->json(['ok' => true]);
    }
}
