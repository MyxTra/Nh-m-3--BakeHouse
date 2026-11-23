<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

/** Banner trình chiếu ở đầu trang chủ. */
final class BannerController extends AbstractResourceController
{
    protected function config(): array
    {
        return [
            'title' => 'Banner',
            'route' => 'banners',
            'table' => 'banners',
            'search' => ['title'],
            'columns' => [
                ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text'],
                ['key' => 'image_path', 'label' => 'Ảnh', 'type' => 'image'],
                ['key' => 'link_url', 'label' => 'Liên kết', 'type' => 'text'],
                ['key' => 'is_active', 'label' => 'Hiển thị', 'type' => 'bool'],
            ],
            'fields' => [
                ['name' => 'title', 'label' => 'Tiêu đề', 'type' => 'text', 'rules' => 'required|max:190'],
                ['name' => 'image_path', 'label' => 'Ảnh banner (khuyến nghị 1920×600)', 'type' => 'image', 'required_on_create' => true],
                ['name' => 'link_url', 'label' => 'Liên kết nút "Khám phá ngay" (để trống nếu không cần nút)', 'type' => 'text', 'rules' => 'max:255', 'format' => 'url', 'placeholder' => '/products'],
                ['name' => 'is_active', 'label' => 'Hiển thị trên trang chủ', 'type' => 'checkbox'],
            ],
        ];
    }
}
