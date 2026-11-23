<?php

// [SOAL 3] Entry point Vercel Serverless untuk Laravel
$tmpStorage = '/tmp/storage';

// Ensure storage subdirectories exist in /tmp
if (! is_dir($tmpStorage)) {
    @mkdir($tmpStorage, 0755, true);
    @mkdir($tmpStorage.'/app', 0755, true);
    @mkdir($tmpStorage.'/app/public', 0755, true);
    @mkdir($tmpStorage.'/framework', 0755, true);
    @mkdir($tmpStorage.'/framework/cache', 0755, true);
    @mkdir($tmpStorage.'/framework/cache/data', 0755, true);
    @mkdir($tmpStorage.'/framework/sessions', 0755, true);
    @mkdir($tmpStorage.'/framework/views', 0755, true);
    @mkdir($tmpStorage.'/logs', 0755, true);
}

// Instruct Laravel to use /tmp/storage
$_ENV['LARAVEL_STORAGE_PATH'] = $tmpStorage;
$_SERVER['LARAVEL_STORAGE_PATH'] = $tmpStorage;
putenv("LARAVEL_STORAGE_PATH={$tmpStorage}");

// Ensure compiled views go to /tmp
$_ENV['VIEW_COMPILED_PATH'] = $tmpStorage.'/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $tmpStorage.'/framework/views';
putenv("VIEW_COMPILED_PATH={$tmpStorage}/framework/views");

// Put bootstrap cache files into /tmp
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes-v7.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes-v7.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');

// Forward request to Laravel public/index.php
require __DIR__.'/../public/index.php';
