<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$requestUri = $_SERVER['REQUEST_URI'];
$uri = urldecode(
    parse_url($requestUri, PHP_URL_PATH)
);

// Project templates often generate "/public/..." asset URLs.
// When running via `php artisan serve`, the public directory is already the web root.
// Normalize "/public/*" requests so local dev serves the expected files.
if ($uri === '/public' || str_starts_with($uri, '/public/')) {
    $normalizedUri = $uri === '/public' ? '/' : substr($uri, 7);
    $queryString = parse_url($requestUri, PHP_URL_QUERY);
    $_SERVER['REQUEST_URI'] = $normalizedUri.($queryString ? '?'.$queryString : '');
    $uri = $normalizedUri;

    $normalizedFile = __DIR__.'/public'.$uri;
    if ($uri !== '/' && is_file($normalizedFile)) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
        ];
        $extension = strtolower(pathinfo($normalizedFile, PATHINFO_EXTENSION));
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: '.$mimeTypes[$extension]);
        }
        readfile($normalizedFile);
        return true;
    }
}

// Normalize legacy storage URLs: "/storage/app/public/*" -> "/storage/*"
if (str_starts_with($uri, '/storage/app/public/')) {
    $normalizedStorageUri = '/storage/'.substr($uri, strlen('/storage/app/public/'));
    $queryString = parse_url($requestUri, PHP_URL_QUERY);
    $_SERVER['REQUEST_URI'] = $normalizedStorageUri.($queryString ? '?'.$queryString : '');
    $uri = $normalizedStorageUri;

    $storageFile = __DIR__.'/storage/app/public/'.substr($uri, strlen('/storage/'));
    if (is_file($storageFile)) {
        $mimeTypes = [
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
        ];
        $extension = strtolower(pathinfo($storageFile, PATHINFO_EXTENSION));
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: '.$mimeTypes[$extension]);
        }
        readfile($storageFile);
        return true;
    }
}

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
