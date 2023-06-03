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

    public function get_rest_data( $object, $field_name, $request ){
        $post_id = $object[ 'id' ];
        switch ($field_name) {
            case 'key':
                return $post_id;
                break;
            case 'featuredImage':
                return get_the_post_thumbnail_url( $post_id, 'full' );
                break;
            case 'featuredImages':
                $index = 0;
                $images[$index] = get_the_post_thumbnail_url( $post_id, 'full' );

                for ($i=2; $i < 9; $i++) { 
                    $image_id = get_post_meta( $post_id, 'kdmfi_featured-image-' . $i, true);
                    if( $image_id && $url = wp_get_attachment_url( $image_id ) ) {
                        $index++;
                        $images[$index] = $url;
                    }
                }
                return $images;
                break;
            case 'reactComponent':
                $filename = get_post_meta( $post_id, '_wp_page_template', true );
                return $filename ? $this->get_page_template_name( $filename ) : '';
                break;
            case 'animationLink':
                return get_post_meta( $post_id, '_animation_url', true );
                break;
        }
    }

    public function get_page_template_name( $filename ){
        $templates = \wp_get_theme()->get_page_templates(); 
        return isset( $templates[ $filename ] ) ? $templates[ $filename ] : '';
    }

}
