<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\NotificationRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReviewRepository;

final class ReviewController extends BaseController
{
    public function __construct(
        private readonly ReviewRepository $reviews = new ReviewRepository(),
        private readonly NotificationRepository $notifications = new NotificationRepository(),
        private readonly ProductRepository $products = new ProductRepository()
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim((string) $request->getQuery('q', ''));
        $view = (string) $request->getQuery('reply', '');
        if (!in_array($view, ['none', 'replied'], true)) {
            $view = '';
        }
        $queryView = $view === 'none' ? 'unanswered' : $view;
        $rating = (int) $request->getQuery('rating', 0);
        $paginator = Paginator::fromRequest($request, $this->reviews->adminCount($search, $queryView, $rating), 10);

        return $this->render('admin.reviews.index', [
            'title' => 'Đánh giá',
            'rows' => $this->reviews->adminList($search, $queryView, $rating, $paginator->getLimit(), $paginator->getOffset()),
            'paginator' => $paginator,
            'counts' => $this->reviews->viewCounts(),
            'search' => $search,
            'view' => $view,
            'rating' => $rating,
        ], 'layouts.admin');
    }

    public function reply(Request $request, string $id): Response
    {
        $review = $this->reviews->find((int) $id);
        if ($review === null) {
            return $this->redirectBack('/admin/reviews');
        }

        $reply = trim((string) $request->getInput('admin_reply'));
        $this->reviews->reply((int) $id, $reply !== '' ? $reply : null);
        if ($reply !== '') {
            $product = $this->products->findById((int) $review['product_id']);
            $this->notifications->create(
                (int) $review['user_id'],
                'review_replied',
                'Đánh giá của bạn đã được phản hồi',
                mb_strimwidth($reply, 0, 140, '…'),
                $product !== null ? '/products/' . $product['slug'] : null
            );
        }
        $this->flash('success', $reply !== '' ? 'Đã lưu phản hồi.' : 'Đã xóa phản hồi.');

        return $this->redirectBack('/admin/reviews');
    }

    public function destroy(Request $request, string $id): Response
    {
        $this->reviews->deleteById((int) $id);
        $this->flash('success', 'Đã xóa đánh giá.');

        return $this->redirectBack('/admin/reviews');
    }

    public function toggleHidden(Request $request, string $id): Response
    {
        $this->reviews->toggleHidden((int) $id);
        $this->flash('success', 'Đã cập nhật hiển thị đánh giá.');

        return $this->redirectBack('/admin/reviews');
    }
}
