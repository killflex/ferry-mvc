<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\Controller;

class PagesController extends Controller
{

    public function index()
    {
        echo $this->view('pages/index', [
            'title' => 'Welcome',
        ]);
    }

    public function about()
    {
        echo $this->view('pages/about', [
            'title' => 'About Us',
        ]);
    }
}
