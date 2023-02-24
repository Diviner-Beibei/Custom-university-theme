<?php get_header(); 
    pageBanner(array(
        'title' => 'All Programs.',
        'subtitle' => 'There is something for everyone. Have a look around.'
    ));
?>

<div class="container container--narrow page-section">
<ui class="link-list min-list">    
<?php while(have_posts()):the_post(); ?>
    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php endwhile; ?>
<!-- 显示页码，如果文章数量小于当前可显示数量，隐藏页码 --> 
<?php echo paginate_links();  ?>
</ui>

</div>

  <?php get_footer(); ?>