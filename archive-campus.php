<?php get_header(); 
    pageBanner(array(
        'title' => 'Our Campuses',
        'subtitle' => 'We have several conveniently located campuses.'
    ));
?>

<div class="container container--narrow page-section">
    <div class="acf-map">    
    <?php while(have_posts()){
        the_post(); 
        //$mapLocation = get_field('map_location');
        ?>
        <!-- 谷歌地图 -->
        <!-- <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
            <h3 ><a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a> </h3>
            <?php echo $mapLocation['address']; ?>
        </div> -->
        <!-- 百度地图 -->
        <div id="map-container" style="width: 100%; height: 400px;">
        </div>
        <?php } ?>
    </div>
</div>

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
    var mtitle ='<a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>' ; //赋值
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