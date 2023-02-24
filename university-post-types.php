<?php 

// 注册自定义帖子类型
function university_post_types(){

    //Event Post Type
    register_post_type('event',array(
        'capability_type' => 'event',   //capability_type的默认值是post,所以默认的对于该文章类型的各种权限跟post是一样的
        'map_meta_cap' => true,         //将一个能力映射到给定用户所需的原始能力,以满足被检查的能力。
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,  //参数设置为true，固定链接才有效。
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar'
    ));

    //Program Post Type
    register_post_type('program',array(
        'show_in_rest' => true,
        'supports' => array('title'),
        'rewrite' => array('slug' => 'programs'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards'
    ));

       //Professor Post Type
       register_post_type('professor',array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'public' => true,
        'labels' => array(
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more'
    ));

    //Note Post Type
    register_post_type('note',array(
        'capability_type' => 'note',   //capability_type的默认值是post,所以默认的对于该文章类型的各种权限跟post是一样的
        'map_meta_cap' => true,         //将一个能力映射到给定用户所需的原始能力,以满足被检查的能力。
        'show_in_rest' => true,     //所有的REST API 都适用于当前文章类型
        'supports' => array('title', 'editor'), 
        'public' => false,  //设置为false时，后台也会不显示
        'show_ui' => true,  //在这里设置为 true，后台显示
        'labels' => array(
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note'
        ),
        'menu_icon' => 'dashicons-welcome-write-blog'
    ));

    //Like Post Type
    register_post_type('like',array(
        'supports' => array('title'), 
        'public' => false,  //设置为false时，后台也会不显示
        'show_ui' => true,  //在这里设置为 true，后台显示
        'labels' => array(
            'name' => 'Likes',
            'add_new_item' => 'Add New Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like'
        ),
        'menu_icon' => 'dashicons-heart'
    ));

       //slide Post Type
    register_post_type('slide',array(
        'show_in_rest' => true,
        'supports' => array('title'), 
        'public' => true,  //设置为false时，后台也会不显示
        'labels' => array(
            'name' => 'Slides',
            'add_new_item' => 'Add New Slide',
            'edit_item' => 'Edit Slide',
            'all_items' => 'All Slides',
            'singular_name' => 'Slide'
        ),
        'menu_icon' => 'dashicons-heart'
    ));
}
}

add_action('init','university_post_types');

?>
