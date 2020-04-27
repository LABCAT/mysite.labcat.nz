<?php
/**

 *
 * @trait     PostTypes\PostTypeTrait
 * @Version: 1.0.0
 * @package   LABCAT/PostTypes
 * @category  Trait
 * @author    LABCAT
*/

namespace LABCAT\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

trait PostTypeTrait {

    public function init(){
         add_action( 'rest_api_init', [ $this, 'add_addtional_rest_fields' ], 99 );
    }

    public static function add_addtional_rest_fields(){
        $rest_fields = [
            'key',
            'featuredImage',
            'reactComponent'
        ];

        foreach( $rest_fields as $rest_field ){
            register_rest_field(
                self::$post_type_slug,
                $rest_field,
                [
                    'get_callback' => [ $this, 'get_rest_data' ]
                ]
            );
        }
    }

    public function get_rest_data( $object, $field_name, $request ){
        $post_id = $object[ 'id' ];
        switch ($field_name) {
            case 'key':
                return $post_id;
                break;
            case 'featuredImage':
                return get_the_post_thumbnail_url( $post_id, 'full' );
                break;
            case 'reactComponent':
                $filename = get_post_meta( $post_id, '_wp_page_template', true );
                return $filename ? $this->get_page_template_name( $filename ) : '';
                break;
        }
    }

    public function get_page_template_name( $filename ){
        $templates = \wp_get_theme()->get_page_templates(); 
        return isset( $templates[ $filename ] ) ? $templates[ $filename ] : '';
    }

}