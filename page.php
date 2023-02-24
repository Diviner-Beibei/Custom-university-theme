<?php get_header(); ?>

<?php while ( have_posts() ) { 
  the_post(); 
  pageBanner();
  ?>

    <div class="container container--narrow page-section">
        <?php 
        $theParentId = wp_get_post_parent_id(get_the_ID());
        if($theParentId){  ?> 
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParentId); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParentId); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>

            <?php }
       ?> 
      

      <?php 
      $childPagesArray = get_pages(array(
        'child_of' => get_the_ID()
      ));
      if($theParentId or $childPagesArray):     
      ?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_permalink($theParentId); ?>"><?php echo get_the_title($theParentId) ?></a></h2>
        <ul class="min-list">
          
            <?php
                if($theParentId){
                    $childrenIdOf = $theParentId;
                }else{
                    $childrenIdOf = get_the_ID();
                }

                wp_list_pages(array(
                    'title_li' => NULL,
                    'child_of' => $childrenIdOf,
                    'sort_column'  => 'menu_order'
                ));
            ?>
        </ul>
      </div>
    <?php endif; ?>


      <div class="generic-content">
            <?php the_content(); 
            
            $skyColor = sanitize_text_field(get_query_var('skyColor'));
            $grassColor = sanitize_text_field(get_query_var('grassColor'));   

            if($skyColor == 'blue' && $grassColor == 'green') {
              echo '<p>The sky is blue and the grass is green.</p>';
            }

            ?>
            <!-- 隐私数据用post 希望可以分享的用get -->
            <form method="get">
              <input name="skyColor" placeholder="Sky color">
              <input name="grassColor" placeholder="Grass color">
              <button>Submit</button>
            </form>

      </div>
    </div>

    <?php } ?>

    <?php get_footer(); ?>