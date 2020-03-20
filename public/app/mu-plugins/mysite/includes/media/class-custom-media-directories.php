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
        add_filter( 'wp_get_attachment_url', [ $this, 'custom_uploads_url' ] );
        add_filter( 'upload_dir', [ $this, 'alter_post_type_directory' ], 999);
        add_filter( 'big_image_size_threshold', '__return_false' );
    }

    //turns relative attachment urls in absolute urls
    public function custom_uploads_url( $url ) {
        $url = str_replace( 'wp/../', '', $url );
        return $url;
    }

    private $custom_directories = [
        'audio-project',
        'creative-coding'
    ];

    public function alter_post_type_directory( $upload ){
        if( ! isset( $_REQUEST['post_id'] ) ){
            return;
        }

        $post_id = $_REQUEST['post_id'];
        $post_type = get_post_type( $post_id );

        // Check the post-type of the current post
        if( in_array( $post_type , $this->custom_directories ) ){
            $upload['subdir'] = '/' . $post_type;
            $upload['basedir'] = str_replace( 'wp/', '', wp_normalize_path( ABSPATH . 'media' . $upload['subdir'] ) );
            $upload['baseurl'] = home_url( 'media' . $upload['subdir'] );
            $upload['path'] = $upload['basedir'];
            $upload['url']  = $upload['baseurl'];
        }

        return $upload;
    }

}

new CustomMediaDirectories();
