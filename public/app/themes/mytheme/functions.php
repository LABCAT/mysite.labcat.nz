<?php

add_theme_support( 'post-thumbnails' );
add_theme_support( 'align-wide' );

add_filter( 'kdmfi_featured_images', 
    function( $featured_images ) {
        for ($i=0; $i < 9; $i++) { 
            $image_num = $i + 2;
            $args = [
                'id' => 'featured-image-' . $image_num,
                'desc' => 'Your description here.',
                'label_name' => 'Featured Image ' . $image_num,
                'label_set' => 'Set featured image ' . $image_num,
                'label_remove' => 'Remove featured image ' . $image_num,
                'label_use' => 'Set featured image ' . $image_num,
                'post_type' => [ 'page', 'building-blocks' ],
            ];
            $featured_images[$i] = $args;
        }
       
        $featured_images[] = $args;

        return $featured_images;
    }
);