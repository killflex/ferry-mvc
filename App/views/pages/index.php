<?php

use App\Entities\Post;

/**
 * @var string $title
 * @var Post[] $posts
 */

require partials('_header.php');

?>

<h1><?php isset($title) ? e($title) : 'Pages' ?></h1>

<ul>
    <?php foreach ($posts as $post): ?>
        <li><?php e($post->title) ?></li>
    <?php endforeach; ?>
</ul>

<?php require partials('_footer.php'); ?>