<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('inquiry/submit', 'Home::submitInquiry');
$routes->post('inquiry/send-email/(:num)', 'Home::sendEmailNotification/$1');
$routes->post('consult', 'Home::submitConsult');
$routes->get('works', 'Sub::works');
$routes->get('view', 'Sub::view');
$routes->get('about', 'Sub::about');
$routes->get('about/history', 'Sub::history');
$routes->get('contact', 'Sub::contact');
$routes->get('privacy', 'Home::privacy');
$routes->get('privacy-policy-content', 'Home::getPrivacyPolicy');
$routes->get('terms', 'Home::terms');
$routes->get('popup/view/(:num)', 'Home::popupView/$1');

// Policy 페이지 (동적 DB 조회)
// $routes->get('policy', 'PolicyFront::view/privacy');
// $routes->get('policy/(:segment)', 'PolicyFront::view/$1');

// Product Routes
$routes->get('product', 'Product::index');
$routes->get('product/view/(:num)', 'Product::view/$1');
$routes->post('product/goodsList', 'Product::goodsList');
$routes->get('product/uv-led', 'Product::uvLed');
$routes->get('product/uv-more', 'Product::uvMore');

// Admin Routes (No filter)
$routes->get('AdmMaster/login', 'AdmMaster\Auth::login');
$routes->post('AdmMaster/loginProcess', 'AdmMaster\Auth::loginProcess');
$routes->get('AdmMaster/logout', 'AdmMaster\Auth::logout');
$routes->get('AdmMaster', function() {
    return redirect()->to('AdmMaster/inquiry/1');
});

// Admin Routes (Filtered)
$routes->group('AdmMaster', ['namespace' => 'App\Controllers\AdmMaster', 'filter' => 'adminAuth'], function($routes) {
    $routes->get('/', function() {
        return redirect()->to('AdmMaster/inquiry/1');
    });
    $routes->get('dashboard', function() {
        return redirect()->to('AdmMaster/inquiry/1');
    });
    
    // Banners
    $routes->get('banners', 'Banners::index');
    $routes->get('banners/form', 'Banners::form');
    $routes->get('banners/form/(:num)', 'Banners::form/$1');
    $routes->post('banners/save', 'Banners::save');
    $routes->get('banners/delete/(:num)', 'Banners::delete/$1');
    $routes->post('banners/bulkDelete', 'Banners::bulkDelete');
    $routes->post('banners/updateStatus', 'Banners::updateStatus');
    $routes->post('banners/updateOrder', 'Banners::updateOrder');

    // LineCard
    $routes->get('line_card', 'LineCard::index');
    $routes->get('line_card/form', 'LineCard::form');
    $routes->get('line_card/form/(:num)', 'LineCard::form/$1');
    $routes->post('line_card/save', 'LineCard::save');

    // Bbs
    $routes->get('bbs/policy', 'Policy::form');
    $routes->post('bbs/policy/save', 'Policy::save');
    $routes->get('bbs/(:segment)', 'Bbs::list/$1');
    $routes->get('bbs/(:segment)/form', 'Bbs::form/$1');
    $routes->get('bbs/(:segment)/new', 'Bbs::form/$1');
    $routes->get('bbs/(:segment)/form/(:num)', 'Bbs::form/$1/$2');
    $routes->get('bbs/(:segment)/edit/(:num)', 'Bbs::form/$1/$2');
    $routes->post('bbs/(:segment)/save', 'Bbs::save/$1');
    $routes->get('bbs/(:segment)/delete/(:num)', 'Bbs::delete/$1/$2');
    $routes->post('bbs/(:segment)/bulkDelete', 'Bbs::bulkDelete/$1');
    $routes->post('bbs/(:segment)/updateOrder', 'Bbs::updateOrder/$1');

    // Inquiry
    $routes->get('inquiry', 'Inquiry::index/1');
    $routes->get('inquiry/(:num)', 'Inquiry::index/$1');
    $routes->get('inquiry/(:num)/excel', 'Inquiry::excel/$1');
    $routes->get('inquiry/(:num)/view/(:num)', 'Inquiry::view/$1/$2');
    $routes->get('inquiry/(:num)/delete/(:num)', 'Inquiry::delete/$1/$2');
    $routes->post('inquiry/(:num)/bulkDelete', 'Inquiry::bulkDelete/$1');

    // Agency
    $routes->get('agency', 'Agency::index');
    $routes->get('agency/form', 'Agency::form');
    $routes->get('agency/form/(:num)', 'Agency::form/$1');
    $routes->post('agency/save', 'Agency::save');
    $routes->get('agency/delete/(:num)', 'Agency::delete/$1');
    $routes->post('agency/bulkDelete', 'Agency::bulkDelete');

    // Popups
    $routes->get('popups', 'Popups::index');
    $routes->get('popups/form', 'Popups::form');
    $routes->get('popups/form/(:num)', 'Popups::form/$1');
    $routes->post('popups/save', 'Popups::save');
    $routes->get('popups/delete/(:num)', 'Popups::delete/$1');
    $routes->post('popups/bulkDelete', 'Popups::bulkDelete');
    $routes->post('popups/updateStatus', 'Popups::updateStatus');
    $routes->post('popups/uploadSummernoteImage', 'Popups::uploadSummernoteImage');

    // Category
    $routes->get('category', 'Category::index');
    $routes->get('category/form', 'Category::form');
    $routes->get('category/form/(:num)', 'Category::form/$1');
    $routes->post('category/save', 'Category::save');
    $routes->get('category/delete/(:num)', 'Category::delete/$1');
    $routes->post('category/updateOrder', 'Category::updateOrder');

    // Policy
    $routes->get('policy', 'Policy::index');
    $routes->get('policy/form', 'Policy::form');
    $routes->get('policy/form/(:num)', 'Policy::form/$1');
    $routes->post('policy/save', 'Policy::save');
    $routes->get('policy/delete/(:num)', 'Policy::delete/$1');
    $routes->post('policy/bulkDelete', 'Policy::bulkDelete');
    $routes->post('policy/uploadSummernoteImage', 'Policy::uploadSummernoteImage');

    // Profile
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');

    // Stats Management
    $routes->get('stats', 'Stats::index');

    // Goods
    $routes->get('goods', 'Goods::index');
    $routes->get('goods/form', 'Goods::form');
    $routes->get('goods/form/(:num)', 'Goods::form/$1');
    $routes->post('goods/save', 'Goods::save');
    $routes->get('goods/get_code', 'Goods::get_code');
    $routes->get('goods/delete/(:num)', 'Goods::delete/$1');

    // Estimate Inquiry Management
    $routes->get('estimate_inquiry', 'EstimateInquiry::index');
    $routes->get('estimate_inquiry/view/(:num)', 'EstimateInquiry::view/$1');
    $routes->post('estimate_inquiry/save', 'EstimateInquiry::save');
    $routes->post('estimate_inquiry/bulkDelete', 'EstimateInquiry::bulkDelete');
    $routes->get('estimate_inquiry/delete/(:num)', 'EstimateInquiry::delete/$1');

    // Works Management
    $routes->get('works', 'Works::index');
    $routes->get('works/form', 'Works::form');
    $routes->get('works/form/(:num)', 'Works::form/$1');
    $routes->post('works/save', 'Works::save');
    $routes->post('works/bulkDelete', 'Works::bulkDelete');
    $routes->get('works/delete/(:num)', 'Works::delete/$1');
    $routes->post('works/deleteGalleryImage', 'Works::deleteGalleryImage');
    $routes->post('works/updateOrder', 'Works::updateOrder');
});

// Front contact submit route
$routes->post('contact/submit', 'Sub::submit');

// Frontend Works Routes
$routes->get('works/view/(:any)', 'Sub::view/$1');


