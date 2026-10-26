<?php

declare(strict_types=1);

function dd(mixed $value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}

function e(mixed $value)
{
    echo htmlspecialchars((string) $value);
}

function base_url(string $path = ''): string
{
    return '/ferry-mvc/' . ltrim($path, '/');
}
