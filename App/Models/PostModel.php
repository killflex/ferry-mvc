<?php

declare(strict_types=1);

namespace App\Models;

use App\Libraries\Database;
use App\Entities\Post;

class PostModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getPosts(): array
    {
        $this->db->query('SELECT * FROM posts');
        $rows = $this->db->resultSet();

        return array_map(
            fn(object $row) => new Post(
                (int) $row->id,
                (string) $row->title,
            ),
            $rows
        );
    }
}
