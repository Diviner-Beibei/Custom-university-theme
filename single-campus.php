<?php get_header(); ?>

<?php  while(have_posts()): the_post();pageBanner(); ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    All Campuses
                </a> 
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content">          
            <?php the_content(); ?>
        </div>
        <div class="container container--narrow page-section">
            <div class="acf-map">    
                <?php  
                    //$mapLocation = get_field('map_location');
                ?>
        
                <!-- 谷歌地图 -->
                <!-- <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
                <h3 ><?php the_title(); ?> </h3>
                <?php echo $mapLocation['address']; ?>
                </div> -->
                <!-- 百度地图 -->
                <div id="map-container" style="width: 100%; height: 400px; "></div>
            </div>
        </div>


        <?php 
          //查询事件
          $relatedPrograms = new WP_Query(array(
                'posts_per_page' => -1,          //全部列出
                'post_type' => 'program',         //文章类型 
                'orderby' => 'title',  //根据指定的额外键排序
                'order' => 'ASC',               //指定排序顺序
                'meta_query' => array(          //指定排除信息（该条将过期的活动信息排除）
                    array(
                        'key' => 'related_campus',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            )); 
            if($relatedPrograms->have_posts()){
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Programs Available At this Campus</h2>';
                echo '<ul class="min-list link-list">';
                while($relatedPrograms->have_posts()):$relatedPrograms->the_post();
        ?>
            <li>
                <a href="<?php permalink_link();  ?>">
                    <?php the_title(); ?>
                </a>
            </li> 
        <?php 
                endwhile; 
                echo '</ul>';
                wp_reset_postdata(); 
            };    
        ?>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>
<script>
    //百度地图
    var map = new BMapGL.Map('map-container'); // 创建Map实例
    var point = new BMapGL.Point(116.404, 39.925);
    map.centerAndZoom(point, 15); // 初始化地图,设置中心点坐标和地图级别
    // map.enableScrollWheelZoom(true); // 开启鼠标滚轮缩放
    // 创建点标记
    var marker = new BMapGL.Marker(point);
    map.addOverlay(marker);
   // var mtitle ="<?php echo get_the_title();?>" ; //赋值
    var mtitle ='<?php the_title(); ?>' ; //赋值
    // 创建信息窗口
    var opts = {
        width: 200,
        height: 50,
        title:  mtitle
    };
    var infoWindow = new BMapGL.InfoWindow('There is the campus', opts);
    // 点标记添加点击事件
    marker.addEventListener('click', function () {
    map.openInfoWindow(infoWindow, point); // 开启信息窗口
});
</script>