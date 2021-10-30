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
        add_filter( 'wp_get_attachment_url', [ $this, 'custom_media_url' ], 999, 2);
        add_filter( 'pre_option_upload_path', [ $this, 'absolute_upload_path' ] );
        add_filter( 'upload_dir', [ $this, 'alter_post_type_directory' ], 999);
        add_filter( 'big_image_size_threshold', '__return_false' );
    }

    //turns relative attachment urls in absolute urls
    public function custom_media_url( $url, $post_id ) {
        $post = get_post( $post_id );
        if( $post->post_type === 'attachment' ){
            return str_replace('http', 'https', $post->guid);
        }
        return $url;
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
        if( ! isset( $_REQUEST['post_id'] ) || ! defined( 'ABSOLUTE_UPLOADS' ) ){
            return $upload;
        }

        $post_id = $_REQUEST['post_id'];
        $post_type = get_post_type( $post_id );


        // Check the post-type of the current post
        if( in_array( $post_type , $this->custom_directories ) ){
            
            
            $upload['subdir'] = '/' . $post_type;
            $upload['basedir'] = ABSOLUTE_UPLOADS . $upload['subdir'];
            $upload['baseurl'] = $upload['baseurl'] . $upload['subdir'];
            $upload['path'] = $upload['basedir'];
            $upload['url']  = $upload['baseurl'];
            if ( ! wp_mkdir_p( $upload['basedir'] )) {
                trigger_error( 'This file cannot be uploaded because your uploads directory is not currently writable', E_USER_ERROR );
            }
        }

       

        return $upload;
    }

}

new CustomMediaDirectories();
