<?php get_header(); 
    pageBanner(array(
    'title' => get_the_archive_title(),
    'subtitle' => get_the_archive_description()
    ));
?>

<div class="container container--narrow page-section">
<?php while(have_posts()):the_post(); ?>
<div class="post-item">
  <h2 class="headline headline--medium headline--post-title"><a href="<?php echo the_permalink(); ?>"> <?php the_title(); ?> </a></h2>

  <div class="metabox">
    <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?> in <?php echo get_the_category_list(', '); ?></p>
  </div>

  <div class="generic-content">
    <?php the_excerpt(); ?>
    <p><a class="btn btn--blue" href="<?php echo the_permalink(); ?>"> Continue read &raquo;</a></p>
  </div>
</div>
<?php endwhile; ?>
<!-- 显示页码，如果文章数量小于当前可显示数量，隐藏页码 -->
<?php echo paginate_links();  ?>
</div>

  <?php get_footer(); ?>