<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\Controller;
use App\Models\PostModel;

class PagesController extends Controller
{

    private PostModel $postModel;

    public function __construct()
    {
        $this->postModel = $this->model('PostModel');
    }

    public function index()
    {
        $posts = $this->postModel->getPosts();

        echo $this->view('pages/index', [
            'title' => 'Pages',
            'posts' => $posts,
        ]);
    }

    public function about()
    {
        echo $this->view('pages/about', [
            'title' => 'About Us',
        ]);
    }
}
