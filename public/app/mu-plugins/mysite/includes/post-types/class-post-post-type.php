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

    public function init()
    {
        add_action('rest_api_init', [$this, 'add_addtional_rest_fields'], 99);
    }

    public static function add_addtional_rest_fields()
    {
        $rest_fields = [
            'key',
            'featuredImage',
            'reactComponent'
        ];

        foreach ($rest_fields as $rest_field) {
            register_rest_field(
                self::$post_type_slug,
                $rest_field,
                [
                    'get_callback' => [$this, 'get_rest_data']
                ]
            );
        }
    }
}

new Post();
