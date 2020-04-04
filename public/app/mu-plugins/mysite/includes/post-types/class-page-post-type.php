<?php
/**
 * @class     PostTypes\Page
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
 * Page Class.
 */
class Page {

    use PostTypeTrait;

    public static $post_type_slug = 'page';

    public function __construct()
    {
        add_action( 'init', [ $this , 'init' ], 5 );
    }

}

new Page();
