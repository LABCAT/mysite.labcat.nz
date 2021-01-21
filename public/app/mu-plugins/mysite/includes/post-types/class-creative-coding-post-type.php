<?php
/**
 * @class     PostTypes\CreativeCoding
 * @Version: 0.0.1
 * @package   LABCAT\PostTypes
 * @category  Class
 * @author    LABCAT
 */
namespace LABCAT\PostTypes;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CreativeCoding Class.
 */
class CreativeCoding {

    use PostTypeTrait;

    public static $post_type_slug = 'creative-coding';

    public static $post_type_slug_plural = 'creative-coding';


    public function __construct()
    {
        add_action( 'init', [ $this , 'register_post_type' ], 5 );
        add_action( 'init', [ $this , 'init' ], 5 );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'add_addtional_rest_fields'], 99);
    }

    public function add_addtional_rest_fields()
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

    public function register_post_type() {
        if ( post_type_exists( self::$post_type_slug ) ) {
            return;
        }


        $labels = [
            'name'                  => __( 'Creative Coding Projects', self::$post_type_slug ),
            'singular_name'         => _x(
                                            'Creative Coding Projects',
                                            'Creative Coding Projects post type singular name',
                                            self::$post_type_slug
                                        ),
            'add_new'               => __( 'Add Creative Coding Project', self::$post_type_slug ),
            'add_new_item'          => __( 'Add New Creative Coding Project', self::$post_type_slug ),
            'edit'                  => __( 'Edit', self::$post_type_slug ),
            'edit_item'             => __( 'Edit Creative Coding Project', self::$post_type_slug ),
            'new_item'              => __( 'New Creative Coding Project', self::$post_type_slug ),
            'view'                  => __( 'View Creative Coding Project', self::$post_type_slug ),
            'view_item'             => __( 'View Creative Coding Project', self::$post_type_slug ),
            'search_items'          => __( 'Search Creative Coding Project', self::$post_type_slug ),
            'not_found'             => __( 'No Creative Coding Projects found', self::$post_type_slug ),
            'not_found_in_trash'    => __( 'No Creative Coding Projects found in trash', self::$post_type_slug ),
            'parent'                => __( 'Parent Creative Coding Project', self::$post_type_slug ),
            'menu_name'             => _x( 'Creative Coding Projects', 'Admin menu name', self::$post_type_slug ),
            'filter_items_list'     => __( 'Filter Creative Coding Projects', self::$post_type_slug ),
            'items_list_navigation' => __( 'Creative Coding Projects navigation', self::$post_type_slug ),
            'items_list'            => __( 'Creative Coding Projects list', self::$post_type_slug ),
        ];

        $args =  [
            'labels'              => $labels,
            'description'         => __( 'This is where Creative Coding Projects are stored.', self::$post_type_slug ),
            'capability_type'     => 'page',
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'supports'            => [
                'editor',
                'title',
                'author',
                'page-attributes',
                'excerpt',
                'thumbnail'
            ],
            'show_in_rest'        => true,
            'has_archive'         => true
        ];

        register_post_type(
            self::$post_type_slug,
            $args
        );
    }

}

new CreativeCoding();
