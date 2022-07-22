<?php

$router->group(['prefix' => 'api'], function () use ($router) {
    // Authentication
    $router->post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
    $router->post('register', ['as' => 'register', 'uses' => 'AuthController@register']);
});

// Admin
$router->group(['prefix' => 'api/admin', 'namespace' => 'Admin'], function () use ($router) {

    // Options
    $router->get('generals/options/{name}', ['as' => 'generals.options.index', 'uses' => 'GeneralOptionController@index']);
    $router->put('generals/options/{name}', ['as' => 'generals.options.update', 'uses' => 'GeneralOptionController@update']);

    // Menus
    $router->post('appearances/menus/reorder', ['as' => 'apperances.menus.reorder', 'uses' => 'AppearanceMenuController@reorder']);
    $router->get('appearances/menus/{menu_type}', ['as' => 'apperances.menus.index', 'uses' => 'AppearanceMenuController@index']);
    $router->post('appearances/menus/{menu_type}', ['as' => 'apperances.menus.store', 'uses' => 'AppearanceMenuController@store']);
    $router->put('appearances/menus/{menu_type}/{id}', ['as' => 'apperances.menus.update', 'uses' => 'AppearanceMenuController@update']);
    $router->delete('appearances/menus/{id}', ['as' => 'apperances.menus.destroy', 'uses' => 'AppearanceMenuController@destroy']);

    // Footer
    $router->get('appearances/footer', ['as' => 'appearances.footer.index', 'uses' => 'AppearancesFooterController@index']);
    $router->post('appearances/footer', ['as' => 'appearances.footer.store', 'uses' => 'AppearancesFooterController@store']);

    // Users
    $router->get('generals/users', ['as' => 'generals.users.index', 'uses' => 'GeneralUserController@index']);
    $router->get('generals/users/{id}', ['as' => 'generals.users.show', 'uses' => 'GeneralUserController@show']);
    $router->post('generals/users', ['as' => 'generals.users.store', 'uses' => 'GeneralUserController@store']);
    $router->put('generals/users/{id}', ['as' => 'generals.users.update', 'uses' => 'GeneralUserController@update']);
    $router->put('generals/users/{id}/toggle-active', ['as' => 'generals.users.toggleactive', 'uses' => 'GeneralUserController@toggleActive']);
    $router->delete('generals/users/{id}', ['as' => 'generals.users.destroy', 'uses' => 'GeneralUserController@destroy']);
    $router->put('change-password', ['as' => 'change_password', 'uses' => 'GeneralUserController@change_password']);

    // Blogs
    $router->get('blogs/posts', ['as' => 'blogs.posts.index', 'uses' => 'BlogPostController@index']);
    $router->get('blogs/posts/search', ['as' => 'blogs.posts.search', 'uses' => 'BlogPostController@search']);
    $router->post('blogs/posts', ['as' => 'blogs.posts.store', 'uses' => 'BlogPostController@store']);
    $router->get('blogs/posts/{id}', ['as' => 'blogs.posts.show', 'uses' => 'BlogPostController@show']);
    $router->put('blogs/posts/{id}', ['as' => 'blogs.posts.update', 'uses' => 'BlogPostController@update']);
    $router->put('blogs/posts/{id}/toggle-active', ['as' => 'blogs.posts.toggle-active', 'uses' => 'BlogPostController@toggleActive']);
    $router->delete('blogs/posts/{id}', ['as' => 'blogs.posts.destroy', 'uses' => 'BlogPostController@destroy']);
});
