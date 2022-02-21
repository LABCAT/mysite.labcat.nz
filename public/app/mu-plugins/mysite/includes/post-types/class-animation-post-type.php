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
class Animation {

    use PostTypeTrait;

    public static $post_type_slug = 'animation';

    public static $post_type_slug_plural = 'animations';

    public static $saved_meta_boxes  = false;


    public function __construct()
    {
        add_action( 'init', [ $this , 'register_post_type' ], 5 );
        add_action( 'init', [ $this , 'init' ], 5 );
    }

    public function init()
    {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 30 );
        add_action( 'save_post', [ $this, 'save_meta_boxes' ], 1, 2 );

        add_action('rest_api_init', [$this, 'add_addtional_rest_fields'], 99);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2);
    }

    /**
     * Add SS Meta boxes.
     */
    public function add_meta_boxes() {
        add_meta_box( 
            'animation-url', 
            'Animation URL', 
            [$this, 'animation_url_metabox'], 
            self::$post_type_slug, 
            'normal', 
            'high'
        );
    }

    public static function animation_url_metabox( $post ) {
        global $post;

        $animation_url = get_post_meta($post->ID, '_animation_url', TRUE);

        wp_nonce_field( 'animation_save_data', 'animation_meta_nonce' );

        ?>
        <div class="panel-wrap">
            <p class="form-field">
                <label for="_animation_url" class="bold-label">Animation URL:</label>
                <input name="_animation_url" type="text" value="<?php echo $animation_url; ?>"/>
            </p>
        </div>
        <?php
    }

    /**
     * Check if we're saving, the trigger an action based on the post type.
     *
     * @param  int $post_id
     * @param  object $post
     */
    public function save_meta_boxes( $post_id, $post ) {
        // $post_id and $post are required
        if ( empty( $post_id ) || empty( $post ) || self::$saved_meta_boxes ) {
            return;
        }

        // Dont' save meta boxes for revisions or autosaves
        if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
            return;
        }

        // Check the nonce
        if ( empty( $_POST['animation_meta_nonce'] ) || ! wp_verify_nonce( $_POST['animation_meta_nonce'], 'animation_save_data' ) ) {
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events
        if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
            return;
        }

        // Check user has permission to edit
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        self::$saved_meta_boxes = true;

        if ( isset( $_POST['_animation_url'] ) ) {
            update_post_meta( $post_id, '_animation_url',  $_POST['_animation_url'] );
        }
    }

    public function add_addtional_rest_fields()
    {
        $rest_fields = [
            'key',
            'featuredImage',
            'reactComponent',
            'animationLink'
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
            'name'                  => __( 'Animations', self::$post_type_slug ),
            'singular_name'         => _x(
                                            'Animation',
                                            'Animationspost type singular name',
                                            self::$post_type_slug
                                        ),
            'add_new'               => __( 'Add Animation', self::$post_type_slug ),
            'add_new_item'          => __( 'Add New Animation', self::$post_type_slug ),
            'edit'                  => __( 'Edit', self::$post_type_slug ),
            'edit_item'             => __( 'Edit Animation', self::$post_type_slug ),
            'new_item'              => __( 'New Animation', self::$post_type_slug ),
            'view'                  => __( 'View Animation', self::$post_type_slug ),
            'view_item'             => __( 'View Animation', self::$post_type_slug ),
            'search_items'          => __( 'Search Animation', self::$post_type_slug ),
            'not_found'             => __( 'No Animations found', self::$post_type_slug ),
            'not_found_in_trash'    => __( 'No Animations found in trash', self::$post_type_slug ),
            'parent'                => __( 'Parent Animation', self::$post_type_slug ),
            'menu_name'             => _x( 'Animations', 'Admin menu name', self::$post_type_slug ),
            'filter_items_list'     => __( 'Filter Animations', self::$post_type_slug ),
            'items_list_navigation' => __( 'Animations navigation', self::$post_type_slug ),
            'items_list'            => __( 'Animations list', self::$post_type_slug ),
        ];

        $args =  [
            'labels'              => $labels,
            'description'         => __( 'This is where Animations are stored.', self::$post_type_slug ),
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

new Animation();
