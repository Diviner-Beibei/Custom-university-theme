<?php 
//在about页面测试
function universityQueryVars($vars) {
    $vars[] = 'skyColor';
    $vars[] = 'grassColor';

    return $vars;
}

add_filter('query_vars', 'universityQueryVars');

require get_theme_file_path( '/inc/like-route.php' );
require get_theme_file_path( '/inc/search-route.php' );

//在返回数据中添加属性
function university_custom_rest() {
    register_rest_field( 'post', 'authorName', array(
        'get_callback' => function() {return get_the_author();}
    ));

    register_rest_field( 'note', 'userNoteCount', array(
        'get_callback' => function() {return count_user_posts( get_current_user_id(), 'note' );}
    ));
}

add_action( 'rest_api_init', 'university_custom_rest' );

function pageBanner($args = NULL){

    if(!$args['title']){
        $args['title'] = get_the_title();
    }

    if(!$args['page_banner_subtitle']){
        $args['page_banner_subtitle'] = get_field('page_banner_subtitle');
    }

    if(!$args['photo']){
        $pageBannerImage = get_field('page_banner_background_image');
        if(isset($pageBannerImage['sizes']['pageBanner'])){
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        }else{
            $args['photo'] = get_theme_file_uri( '/images/ocean.jpg' );
        }     
    }

    ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle']; ?></p>
        </div>
      </div>
    </div>
    <?php
}

function university_files(){
    wp_enqueue_script( 'googleMap', '//api.map.baidu.com/api?v=1.0&&type=webgl&ak=c9UcZh6B2PuldN839GATWdUDhYZSveAx', NULL,'1.0',true);   //加载百度地图js文件，需再最后加上apikey
    //wp_enqueue_script( 'googleMap', '//maps.googleapis.com/maps/api/js?key=', NULL,'1.0',true);   加载谷歌地图js文件，需再最后加上apikey
    wp_enqueue_script( 'main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'),'1.0',true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('build/index.css'));
    
    wp_localize_script( 'main-university-js', 'universityData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce( 'wp_rest' )
    ) );
}

add_action('wp_enqueue_scripts','university_files');


function university_features(){
    //注册菜单显示位置
    // register_nav_menu( 'headerMenuLocation', 'Header Menu Location');
    // register_nav_menu( 'footerLocationOne', 'Footer Location One');
    // register_nav_menu( 'footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');                 //添加标题，还有很多其他字段
    add_theme_support('post-thumbnails');           //文章缩略图
    add_image_size( 'professorLandscape', 400, 260, true);      //添加使用图片尺寸
    add_image_size( 'professorPortrait', 480, 650, true);
    add_image_size( 'pageBanner', 1500, 350, true);
    add_image_size( 'slideBackground', 1500, 350, true);
}
add_action('after_setup_theme','university_features');

//调整查询语句，满足条件调整相应的查询语句
function university_adjust_queries($query){
    if ( ! is_admin() && is_post_type_archive( 'campus' ) && is_main_query() ) {
        $query->set( 'posts_per_page', -1);   
    }

    if ( ! is_admin() && is_post_type_archive( 'program' ) && is_main_query() ) {
        $query->set( 'orderby', 'title' );//按标题字母排序
        $query->set( 'order', 'ASC');   
        $query->set( 'posts_per_page', -1);   
    }

    if ( ! is_admin() && is_post_type_archive( 'event' ) && is_main_query() ) {
        $today = date('Ymd');
         $query->set( 'meta_key', 'event_date' );
         $query->set( 'orderby', 'meta_value_num' );
         $query->set( 'order', 'ASC');   
        $query->set('meta_query', array(          //指定排除信息（该条将过期的活动信息排除）
            array(
                'key' => 'event_date',  //指定键
                'compare' => '>=',      //指定比较方式
                'value' => $today,      //指定和键进行比较的值
                'type' => 'numeric'     //指定比较类型
            )
        ));
    }
}

add_action('pre_get_posts','university_adjust_queries');

function universityMapKey($api){
    $api['key'] = '';//google map key
    return $api;
}

add_filter( 'acf/fields/google_map/api', 'universityMapKey' );

//Redirect subscriber accounts out of admin and onto homepage

add_action( 'admin_init', 'redirectSubsToFrontend' );

function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect( site_url( '/' ) );
        exit;
    }
}

add_action( 'wp_loaded', 'noSubsAdminBar' );

function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar( false );
    }
}

//Customize Login Screen
add_filter( 'login_headerurl', 'ourHeaderUrl' );

function ourHeaderUrl() {
    return esc_url( site_url( '/' ) );
}


add_action( 'login_enqueue_scripts', 'ourLoginCSS' );

function ourLoginCSS() {
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('build/index.css'));
}

add_filter( 'login_headertitle', 'ourLoginTitle' );

function ourLoginTitle() {
    return get_bloginfo('name' );
}

//Force note posts to be private

add_filter( 'wp_insert_post_data', 'makeNotePrivate', 10, 2);

//未创建的笔记没有ID，已创建的含有ID
function makeNotePrivate($data, $postarr) {
    
    if($data['post_type'] == 'note') {
        if(count_user_posts( get_current_user_id(), 'note' ) > 4 && !$postarr['ID']) {
            die("You have reached your note limit.");
        }

        $data['post_content'] = sanitize_textarea_field( $data['post_content'] );
        $data['post_title'] = sanitize_textarea_field( $data['post_title'] );
    }

    if($data['post_type'] == 'note' && $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }

    return $data;
}

?>