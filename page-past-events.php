<?php get_header(); 
pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events.'
  ));
?>

<div class="container container--narrow page-section">
<?php 
    $today = date('Ymd');
   //查询事件
   $pastEvents = new WP_Query(array(
    'paged' => get_query_var('paged',1),    //获取页码信息（自定义分页查询使用的参数）
    'post_type' => 'event',         //文章类型 
    'meta_key' => 'event_date',     // 指定额外键
    'orderby' => 'meta_value_num',  //根据指定的额外键排序
    'order' => 'ASC',               //指定排序顺序
    'meta_query' => array(          //指定排除信息（该条将过期的活动信息排除）
        array(
            'key' => 'event_date',  //指定键
            'compare' => '<',      //指定比较方式
            'value' => $today,      //指定和键进行比较的值
            'type' => 'numeric'     //指定比较类型
        )
    )
)); 
while($pastEvents->have_posts()){
    $pastEvents->the_post(); 
    get_template_part( 'template-parts/content', 'event' );
}
    wp_reset_postdata();
?>
<!-- 显示页码，如果文章数量小于当前可显示数量，隐藏页码 -->
<!-- 自定义查询， 如果不输入参数paginate_links只会调用默认查询 -->
<?php echo paginate_links(array(
    'total' => $pastEvents->max_num_pages
));  ?>
</div>

  <?php get_footer(); ?>