<?php get_header(); ?>

<?php while ( have_posts() ) { 
  the_post(); 
  pageBanner();
  ?>

    <div class="container container--narrow page-section">

      <div class="generic-content">
            <?php get_search_form(); ?>
      </div>
    </div>

    <?php } ?>

    <?php get_footer(); ?>