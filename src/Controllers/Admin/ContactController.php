<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\ErrorHandler;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\ContactRepository;
use App\Repositories\NotificationRepository;
use DateTimeImmutable;

/** Liên hệ khách gửi từ trang /contact: xem, lọc theo trạng thái/khoảng ngày và phản hồi. */
final class ContactController extends BaseController
{
    private const STATUSES = ['new', 'replied'];

    public function __construct(
        private readonly ContactRepository $contacts = new ContactRepository(),
        private readonly NotificationRepository $notifications = new NotificationRepository()
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim((string) $request->getQuery('q', ''));
        $status = (string) $request->getQuery('status', '');
        if (!in_array($status, self::STATUSES, true)) {
            $status = '';
        }

        $fromDate = $this->normalizeDate((string) $request->getQuery('from_date', ''));
        $toDate = $this->normalizeDate((string) $request->getQuery('to_date', ''));
        if ($fromDate !== '' && $toDate !== '' && $fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $total = $this->contacts->adminCount($search, $status, $fromDate, $toDate);
        $paginator = Paginator::fromRequest($request, $total, 10);

        return $this->render('admin.contacts.index', [
            'title' => 'Liên hệ',
            'rows' => $this->contacts->adminList($search, $status, $fromDate, $toDate, $paginator->getLimit(), $paginator->getOffset()),
            'paginator' => $paginator,
            'counts' => $this->contacts->statusCounts(),
            'search' => $search,
            'status' => $status,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ], 'layouts.admin');
    }

    public function show(Request $request, string $id): Response
    {
        $contact = $this->contacts->find((int) $id);
        if ($contact === null) {
            return ErrorHandler::render(404, $request);
        }

        return $this->render('admin.contacts.show', [
            'title' => 'Chi tiết liên hệ',
            'contact' => $contact,
        ], 'layouts.admin');
    }

    public function reply(Request $request, string $id): Response
    {
        $contact = $this->contacts->find((int) $id);
        if ($contact === null) {
            return ErrorHandler::render(404, $request);
        }

        $reply = trim((string) $request->getInput('admin_reply'));
        $back = '/admin/contacts/' . (int) $id;
        if ($reply === '') {
            return $this->redirectWithErrors($request, $back, ['admin_reply' => 'Vui lòng nhập nội dung phản hồi.']);
        }
        if (mb_strlen($reply) > 2000) {
            return $this->redirectWithErrors($request, $back, ['admin_reply' => 'Phản hồi tối đa 2000 ký tự.']);
        }

        $this->contacts->reply((int) $id, $reply);
        if ($contact['user_id'] !== null) {
            $this->notifications->create(
                (int) $contact['user_id'],
                'contact_replied',
                'Liên hệ của bạn đã được phản hồi',
                mb_strimwidth($reply, 0, 140, '…'),
                '/contacts'
            );
        }
        $this->flash('success', 'Đã gửi phản hồi. Khách sẽ thấy trong mục "Liên hệ của tôi".');

        return $this->redirect($back);
    }

    private function normalizeDate(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        $dt = DateTimeImmutable::createFromFormat('Y-m-d', $value);
        if ($dt === false || $dt->format('Y-m-d') !== $value) {
            return '';
        }

        return $value;
    }
}