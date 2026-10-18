<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend
$routes->post('comments/store', 'CommentController::store', ['as' => 'comment.store']);
$routes->get('/', 'BlogController::index');
$routes->get('post/(:segment)', 'BlogController::readPost/$1', ['as' => 'read-post']);
$routes->get('page/(:segment)', 'PageController::show/$1', ['as' => 'page-show']);
$routes->get('category/(:segment)', 'BlogController::categoryPost/$1', ['as' => 'category-post']);
$routes->get('tag/(:segment)', 'BlogController::tagPost/$1', ['as' => 'tag-post']);
$routes->get('search', 'BlogController::searchPost', ['as' => 'search-post']);
$routes->get('contact-us', 'BlogController::contactUs', ['as' => 'contact-us']);
$routes->post('contact-us', 'BlogController::contactUsForm', ['as' => 'contact-us-form']);
$routes->get('about', 'AdminController::about', ['as' => 'about']);


$routes->group('admin', static function ($routes) {
    $routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('profile', 'AdminController::profile', ['as' => 'admin.profile']);
        $routes->post('update-personal-details', 'AdminController::updatePersonalDetails', ['as' => 'update-personal-details']);
        $routes->post('update-profile-picture', 'AdminController::updateProfilePicture', ['as' => 'update-profile-picture']);
        $routes->post('change-password', 'AdminController::changePassword', ['as' => 'change-password']);
        $routes->get('settings', 'AdminController::settings', ['as' => 'settings']);
        $routes->post('update-general-settings', 'AdminController::updateGeneralSettings', ['as' => 'update-general-settings']);
        $routes->post('update-blog-logo', 'AdminController::updateBlogLogo', ['as' => 'update-blog-logo']);
        $routes->post('update-blog-favicon', 'AdminController::updateBlogFavicon', ['as' => 'update-blog-favicon']);
        $routes->post('update-social-media', 'AdminController::updateSocialMedia', ['as' => 'update-social-media']);

        $routes->get('author', 'AdminController::authorInfo', ['as' => 'admin.author']);
        $routes->post('update-author', 'AdminController::updateAuthor', ['as' => 'admin.update-author']);
        $routes->post('update-author-photo', 'AdminController::updateAuthorPhoto', ['as' => 'admin.update-author-photo']);
        $routes->post('delete-author-photo', 'AdminController::deleteAuthorPhoto', ['as' => 'admin.delete-author-photo']);

        $routes->get('categories', 'AdminController::categories', ['as' => 'categories']);
        $routes->post('add-categories', 'AdminController::addCategories', ['as' => 'add-categories']);
        $routes->get('get-categories', 'AdminController::getCategories', ['as' => 'get-categories']);
        $routes->get('get-category', 'AdminController::getCategory', ['as' => 'get-category']);
        $routes->post('update-categories', 'AdminController::updateCategories', ['as' => 'update-categories']);
        $routes->get('delete-category', 'AdminController::deleteCategory', ['as' => 'delete-category']);
        $routes->get('reorder-categories', 'AdminController::reorderCategories', ['as' => 'reorder-categories']);
        $routes->get('get-parent-categories', 'AdminController::getParentCategories', ['as' => 'get-parent-categories']);
        $routes->post('add-subcategory', 'AdminController::addSubCategory', ['as' => 'add-subcategory']);
        $routes->get('get-subcategories', 'AdminController::getSubCategories', ['as' => 'get-subcategories']);
        $routes->get('get-subcategory', 'AdminController::getSubCategory', ['as' => 'get-subcategory']);
        $routes->post('update-subcategory', 'AdminController::updateSubCategory', ['as' => 'update-subcategory']);
        $routes->get('reorder-subcategories', 'AdminController::reorderSubCategories', ['as' => 'reorder-subcategories']);
        $routes->get('delete-subcategory', 'AdminController::deleteSubCategory', ['as' => 'delete-subcategory']);

        $routes->group('posts', static function ($routes) {
            $routes->get('new-post', 'AdminController::addPost', ['as' => 'new-post']);
            $routes->post('create-post', 'AdminController::createPost', ['as' => 'create-post']);
            $routes->get('/', 'AdminController::allPosts', ['as' => 'all-posts']);
            $routes->get('get-posts', 'AdminController::getPosts', ['as' => 'get-posts']);
            $routes->get('edit-post/(:segment)', 'AdminController::editPost/$1', ['as' => 'edit-post']);
            $routes->post('update-post', 'AdminController::updatePost', ['as' => 'update-post']);
            $routes->get('delete-post', 'AdminController::deletePost', ['as' => 'delete-post']);
        });

        // AdSense
        $routes->get('adsense', 'AdminController::adsense', ['as' => 'admin.adsense']);
        $routes->post('adsense/update', 'AdminController::updateAdsense', ['as' => 'admin.adsense.update']);

        // Static Pages
        $routes->get('pages', 'AdminController::pages', ['as' => 'admin.pages']);
        $routes->get('pages/add', 'AdminController::addPage', ['as' => 'admin.pages.add']);
        $routes->post('pages/create', 'AdminController::createPage', ['as' => 'admin.pages.create']);
        $routes->get('pages/edit/(:num)', 'AdminController::editPage/$1', ['as' => 'admin.pages.edit']);
        $routes->post('pages/update/(:num)', 'AdminController::updatePage/$1', ['as' => 'admin.pages.update']);
        $routes->get('pages/delete/(:num)', 'AdminController::deletePage/$1', ['as' => 'admin.pages.delete']);
        $routes->post('pages/toggle-footer/(:num)', 'AdminController::togglePageFooter/$1', ['as' => 'admin.pages.toggle-footer']);

        // Comments moderation
        $routes->get('comments', 'CommentController::index', ['as' => 'admin.comments']);
        $routes->get('comments/approve/(:num)', 'CommentController::approve/$1', ['as' => 'admin.comments.approve']);
        $routes->get('comments/spam/(:num)', 'CommentController::spam/$1', ['as' => 'admin.comments.spam']);
        $routes->get('comments/trash/(:num)', 'CommentController::trash/$1', ['as' => 'admin.comments.trash']);
        $routes->get('comments/destroy/(:num)', 'CommentController::destroy/$1', ['as' => 'admin.comments.destroy']);
        $routes->post('comments/reply', 'CommentController::reply', ['as' => 'admin.comments.reply']);
        $routes->post('comments/bulk', 'CommentController::bulk', ['as' => 'admin.comments.bulk']);
    });

    $routes->group('', ['filter' => 'cifilter:guest'], static function ($routes) {
        $routes->get('login', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('login', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
        $routes->get('forgot-password', 'AuthController::forgotForm', ['as' => 'admin.forgot.form']);
        $routes->post('send-password-reset-link', 'AuthController::sendPasswordResetLink', ['as' => 'send-password-reset-link']);
        $routes->get('password/reset/(:segment)', 'AuthController::resetPassword/$1', ['as' => 'admin.reset-password']);
        $routes->post('reset-password-handler/(:segment)', 'AuthController::resetPasswordHandler/$1', ['as' => 'reset-password-handler']);
    });
});
