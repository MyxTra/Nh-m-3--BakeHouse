<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\ErrorHandler;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReviewRepository;
use App\Services\ReviewService;

final class ProductController extends BaseController
{
    private const PER_PAGE = 9;
    private const REVIEWS_PER_PAGE = 5;

    public function index(Request $request): Response
    {
        $products = new ProductRepository();
        $sliderMax = (int) (ceil(max($products->maxPrice(), 10000) / 10000) * 10000);

        $sort = (string) $request->getQuery('sort', 'newest');
        if (!in_array($sort, ProductRepository::sortKeys(), true)) {
            $sort = 'newest';
        }

        $minPrice = max(0, (int) $request->getQuery('min_price', 0));
        $maxPrice = max(0, (int) $request->getQuery('max_price', 0));

        if ($minPrice > $sliderMax) {
            $minPrice = $sliderMax;
        }
        if ($maxPrice > $sliderMax) {
            $maxPrice = 0;
        }
        if ($minPrice > 0 && $maxPrice > 0 && $minPrice > $maxPrice) {
            [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
        }

        $filters = [
            'q' => trim((string) $request->getQuery('q', '')),
            'category' => trim((string) $request->getQuery('category', '')),
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
        ];

        $paginator = Paginator::fromRequest($request, $products->countFiltered($filters), self::PER_PAGE);

        return $this->render('client.products.index', [
            'title' => 'Sản phẩm',
            'products' => $products->search($filters, $sort, $paginator->getLimit(), $paginator->getOffset()),
            'categories' => (new CategoryRepository())->activeWithCounts(),
            'paginator' => $paginator,
            'filters' => $filters,
            'sort' => $sort,
            'sliderMax' => $sliderMax,
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $products = new ProductRepository();
        $product = $products->findBySlug($slug);
        if ($product === null) {
            return ErrorHandler::render(404, $request);
        }

        $reviews = new ReviewRepository();
        $paginator = Paginator::fromRequest($request, $reviews->countVisible((int) $product['id']), self::REVIEWS_PER_PAGE);
        $userId = Auth::id();

        return $this->render('client.products.show', [
            'title' => $product['name'],
            'product' => $product,
            'images' => $products->images((int) $product['id']),
            'related' => $products->related((int) $product['id'], (int) $product['category_id'], 4),
            'reviews' => $reviews->listVisible((int) $product['id'], $paginator->getLimit(), $paginator->getOffset()),
            'paginator' => $paginator,
            'myReview' => $userId === null ? null : $reviews->findByUserAndProduct($userId, (int) $product['id']),
            'canReview' => $userId !== null && (new ReviewService())->canReview($userId, (int) $product['id']),
        ]);
    }
}