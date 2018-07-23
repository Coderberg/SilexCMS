<?php

// Register routes

$app->get('/', 'App\Controllers\ArticleController::index')
    ->bind('homepage');

$app->get('/articles', 'App\Controllers\ArticleController::index')
        ->bind('articles');

$app->get('/articles/page/{page}', 'App\Controllers\ArticleController::index')
    ->assert('page', '[0-9]+');

$app->get('/articles/{id}.html', 'App\Controllers\ArticleController::article')
    ->assert('id', '[0-9]+')
    ->bind('articles.single');

// Auth
$app->get('/login', 'App\Controllers\Auth\LoginController::login');

// Admin dashboard
$app->get('/admin', 'App\Controllers\Admin\DashboardController::index')
    ->bind('admin');

// Admin articles
$app->get('/admin/articles', 'App\Controllers\Admin\ArticleController::index')
        ->bind('admin.articles');

$app->get('/admin/articles/page/{page}', 'App\Controllers\Admin\ArticleController::index')
    ->assert('page', '[0-9]+');

$app->match('/admin/articles/create', 'App\Controllers\Admin\ArticleController::create')
        ->bind('admin.create_articles');

$app->match('/admin/articles/update/{id}', 'App\Controllers\Admin\ArticleController::update')
    ->method('GET|POST')
    ->assert('id', '[0-9]+')
    ->bind('admin.articles_update');

$app->get('/admin/articles/delete/{id}', 'App\Controllers\Admin\ArticleController::delete')
    ->assert('id', '[0-9]+')
    ->bind('admin.articles_delete');
