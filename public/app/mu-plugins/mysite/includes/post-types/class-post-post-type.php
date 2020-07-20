<?php

/**
 * @class     PostTypes\Post
 * @Version: 0.0.1
 * @package   LABCAT\PostTypes
 * @category  Class
 * @author    LABCAT
 */

namespace LABCAT\PostTypes;


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Post Class.
 */
class Post
{

    use PostTypeTrait;

    public static $post_type_slug = 'post';

    public function __construct()
    {
        add_action('init', [$this, 'init'], 5);
    }
}

new Post();
