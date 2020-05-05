<?php
/**
 * @class     Media\CustomMediaDirectories
 * @Version: 0.0.1
 * @package   LABCAT\Media
 * @category  Class
 * @author    LABCAT
 */
namespace LABCAT\Media;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CustomMediaDirectories Class.
 */
class CustomMediaDirectories {


    public function __construct()
    {   
        add_filter( 'pre_option_upload_path', [ $this, 'absolute_upload_path' ] );
        add_filter( 'upload_dir', [ $this, 'alter_post_type_directory' ], 999);
        add_filter( 'big_image_size_threshold', '__return_false' );
    }

    public function absolute_upload_path( $upload_path ) {
        if( defined( 'ABSOLUTE_UPLOADS' ) ){
            return ABSOLUTE_UPLOADS;
        }
        return $upload_path;   
    }

    private $custom_directories = [
        'audio-project',
        'creative-coding'
    ];

    public function alter_post_type_directory( $upload ){
        if( ! isset( $_REQUEST['post_id'] ) ){
            return $upload;
        }

        $post_id = $_REQUEST['post_id'];
        $post_type = get_post_type( $post_id );

        // Check the post-type of the current post
        if( in_array( $post_type , $this->custom_directories ) ){

            $upload['subdir'] = '/' . $post_type;
            $upload['basedir'] = wp_normalize_path( ABSPATH . 'media' . $upload['subdir'] );
            $upload['baseurl'] = home_url( 'media' . $upload['subdir'] );
            $upload['path'] = $upload['basedir'];
            $upload['url']  = $upload['baseurl'];

        }

        return $upload;
    }

}

new CustomMediaDirectories();
