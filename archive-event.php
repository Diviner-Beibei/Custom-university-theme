<?php get_header(); 
    pageBanner(array(
        'title' => 'All Events',
        'subtitle' => 'See what is going on in our world.'
    ));
?>

<div class="container container--narrow page-section">
<?php while(have_posts()){
        the_post(); 
        get_template_part( 'template-parts/content', 'event' );
    }
    wp_reset_postdata(); 
 ?>
<!-- 显示页码，如果文章数量小于当前可显示数量，隐藏页码 -->
<?php echo paginate_links();  ?>
<hr class="section-break">
<p>Looking for a recap of past events?<a href="<?php echo site_url('/past-events') ?>">Check out our past events archive</a>.</p>
</div>

  <?php get_footer(); ?>