<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LegacyRedirectFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = $request->getUri()->getPath();
        
        // Map legacy PHP paths to new clean routes
        $mapping = [
            'intro/greetings.php' => 'intro/greetings',
            'intro/history.php' => 'intro/history',
            'intro/vision.php' => 'intro/vision',
            'intro/organization.php' => 'intro/organization',
            'business/turn_key.php' => 'business/turn_key',
            'business/follow_up.php' => 'business/follow_up',
            'business/new_item.php' => 'business/new_item',
            'business/line_card.php' => 'business/line_card',
            'business/shipping.php' => 'business/shipping',
            'business/app.php' => 'business/app',
            'quality/quality.php' => 'quality',
            'quality/quality2.php' => 'quality/equipment',
            'quality/quality3.php' => 'quality/progress',
            'quality/quality4.php' => 'quality/inquiry',
            'recruit/recruit.php' => 'recruit',
            'news/inquisition.php' => 'news/inquisition',
            'contact/notice_list.php' => 'contact/notice_list',
            'contact/notice_view.php' => 'contact/notice_view',
            'contact/news.php' => 'contact/news',
            'contact/map.php' => 'contact/map',
            'sub/agreement.php' => 'sub/agreement',
            'sub/unauthorized_e-mail.php' => 'sub/unauthorized_email',
            'product/product.php' => 'product',
            'product/product_uv_led.php' => 'product/uv-led',
            'product/product_uv_more.php' => 'product/uv-more',
            'product/product_view.php' => 'product', // Redirect to main or handle specifically
        ];

        if (isset($mapping[$uri])) {
            return redirect()->to(base_url($mapping[$uri]), 301);
        }

        // Handle case where .php is added to a clean route
        if (preg_match('/\.php$/', $uri)) {
            $cleanUri = preg_replace('/\.php$/', '', $uri);
            // Some special cases like quality/quality.php -> quality
            $cleanUri = str_replace('quality/quality', 'quality', $cleanUri);
            return redirect()->to(base_url($cleanUri), 301);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
