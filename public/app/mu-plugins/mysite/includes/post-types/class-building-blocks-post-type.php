<?php
/**
 * @class     PostTypes\BuildingBlocks
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
 * BuildingBlocks Class.
 */
class BuildingBlocks {

    use PostTypeTrait;

    public static $post_type_slug = 'building-blocks';

    public static $post_type_slug_plural = 'building-blocks';

    public static $saved_meta_boxes  = false;


    public function __construct()
    {
        add_action( 'init', [ $this , 'register_post_type' ], 5 );
        add_action( 'init', [ $this , 'init' ], 5 );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'add_addtional_rest_fields'], 99);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2);
    }

    public function add_addtional_rest_fields()
    {
        $rest_fields = [
            'key',
            'featuredImage',
            'featuredImages',
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

    public function disable_gutenberg($current_status, $post_type)
    {
        if ($post_type === self::$post_type_slug) {
            return false;
        }
        return $current_status;
    }

    public function register_post_type() {
        if ( post_type_exists( self::$post_type_slug ) ) {
            return;
        }


        $labels = [
            'name'                  => __( 'Building Blocks', self::$post_type_slug ),
            'singular_name'         => _x(
                                            'Building Blocks',
                                            'Building Blocks post type singular name',
                                            self::$post_type_slug
                                        ),
            'add_new'               => __( 'Add Building Blocks', self::$post_type_slug ),
            'add_new_item'          => __( 'Add New Building Blocks', self::$post_type_slug ),
            'edit'                  => __( 'Edit', self::$post_type_slug ),
            'edit_item'             => __( 'Edit Building Blocks', self::$post_type_slug ),
            'new_item'              => __( 'New Building Blocks', self::$post_type_slug ),
            'view'                  => __( 'View Building Blocks', self::$post_type_slug ),
            'view_item'             => __( 'View Building Blocks', self::$post_type_slug ),
            'search_items'          => __( 'Search Building Blocks', self::$post_type_slug ),
            'not_found'             => __( 'No Building Blocks found', self::$post_type_slug ),
            'not_found_in_trash'    => __( 'No Building Blocks found in trash', self::$post_type_slug ),
            'parent'                => __( 'Parent Building Blocks', self::$post_type_slug ),
            'menu_name'             => _x( 'Building Blocks', 'Admin menu name', self::$post_type_slug ),
            'filter_items_list'     => __( 'Filter Building Blocks', self::$post_type_slug ),
            'items_list_navigation' => __( 'Building Blocks navigation', self::$post_type_slug ),
            'items_list'            => __( 'Building Blocks list', self::$post_type_slug ),
        ];

        $args =  [
            'labels'              => $labels,
            'description'         => __( 'This is where Building Blocks are stored.', self::$post_type_slug ),
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
                'slug' => self::$post_type_slug,
                'with_front' => false
            ],
        ];

        register_post_type(
            self::$post_type_slug,
            $args
        );
    }

}

new BuildingBlocks();
