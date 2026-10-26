<?php

/* 
Base Controller
Loads the models and views 
*/

declare(strict_types=1);

namespace App\Libraries;

use RuntimeException;

class Controller
{
    // load model
    protected function model(string $model): object
    {
        $modelName = ucfirst(strtolower($model)) . 'Model';
        $modelClass = 'App\\Models\\' . $modelName;

        if (!class_exists($modelClass)) {
            throw new RuntimeException('Model not found: ' . $modelClass);
        }

        // instatiate model
        return new $modelClass();
    }

    // load view
    public function view(string $view, array $data = []): string
    {
        $viewPath = dirname(__DIR__) . '/views/' . trim($view, '/') . '.php';

        if (!is_file($viewPath)) {
            throw new RuntimeException('View not found: ' . $viewPath);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;

        return ob_get_clean();
    }
}
