<?php

declare(strict_types=1);

function dd(mixed $value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}

function e(string $value)
{
    echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function urlroot(string $path = ''): string
{
    return '/ferry-mvc/' . ltrim($path, '/');
}

function approot(string $path = ''): string
{
    return dirname(__DIR__, 2) . '/' . ltrim($path, '/');
}

function partials(string $path = ''): string
{
    return dirname(dirname(__FILE__)) . '/App/views/partials/' . ltrim($path, '/');
}

function sitename(): string
{
    return 'FerryMVC';
}
