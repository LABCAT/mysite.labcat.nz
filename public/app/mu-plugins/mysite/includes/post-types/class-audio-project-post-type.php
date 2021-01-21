<?php
/**
 * @class     PostTypes\AudioProject
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
 * AudioProject Class.
 */
class AudioProject {

    use PostTypeTrait;

    public static $post_type_slug = 'audio-project';

    public static $post_type_slug_plural = 'audio-projects';


    public function __construct()
    {
        add_action( 'init', [ $this , 'register_post_type' ], 5 );
        add_action( 'init', [ $this , 'init' ], 5 );
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

    public function register_post_type() {
        if ( post_type_exists( self::$post_type_slug ) ) {
            return;
        }


        $labels = [
            'name'                  => __( 'Audio Projects', self::$post_type_slug ),
            'singular_name'         => _x(
                                            'Audio Projects',
                                            'Audio Projects post type singular name',
                                            self::$post_type_slug
                                        ),
            'add_new'               => __( 'Add Audio Project', self::$post_type_slug ),
            'add_new_item'          => __( 'Add New Audio Project', self::$post_type_slug ),
            'edit'                  => __( 'Edit', self::$post_type_slug ),
            'edit_item'             => __( 'Edit Audio Project', self::$post_type_slug ),
            'new_item'              => __( 'New Audio Project', self::$post_type_slug ),
            'view'                  => __( 'View Audio Project', self::$post_type_slug ),
            'view_item'             => __( 'View Audio Project', self::$post_type_slug ),
            'search_items'          => __( 'Search Audio Project', self::$post_type_slug ),
            'not_found'             => __( 'No Audio Projects found', self::$post_type_slug ),
            'not_found_in_trash'    => __( 'No Audio Projects found in trash', self::$post_type_slug ),
            'parent'                => __( 'Parent Audio Project', self::$post_type_slug ),
            'menu_name'             => _x( 'Audio Projects', 'Admin menu name', self::$post_type_slug ),
            'filter_items_list'     => __( 'Filter Audio Projects', self::$post_type_slug ),
            'items_list_navigation' => __( 'Audio Projects navigation', self::$post_type_slug ),
            'items_list'            => __( 'Audio Projects list', self::$post_type_slug ),
        ];

        $args =  [
            'labels'              => $labels,
            'description'         => __( 'This is where Audio Projects are stored.', self::$post_type_slug ),
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
            'rest_base'           => self::$post_type_slug_plural,
            'has_archive'         => self::$post_type_slug_plural,
            'rewrite'             => [
                'slug' => 'audio-project',
                'with_front' => false
            ],
        ];

        register_post_type(
            self::$post_type_slug,
            $args
        );
    }

}

new AudioProject();
