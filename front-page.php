<?php get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/library-hero.jpg') ?>)"></div>
    <div class="page-banner__content container t-center c-white">
      <h1 class="headline headline--large">Welcome!</h1>
      <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
      <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
      <a href="<?php echo get_post_type_archive_link('program'); ?>" class="btn btn--large btn--blue">Find Your Major</a>
    </div>
</div>

    <div class="full-width-split group">
      <div class="full-width-split__one">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
          <?php 
          $today = date('Ymd');
          //查询事件
          $homepageEvent = new WP_Query(array(
                'posts_per_page' => 2,          //每页2条
                'post_type' => 'event',         //文章类型 
                'meta_key' => 'event_date',     // 指定额外键
                'orderby' => 'meta_value_num',  //根据指定的额外键排序
                'order' => 'ASC',               //指定排序顺序
                'meta_query' => array(          //指定排除信息（该条将过期的活动信息排除）
                    array(
                        'key' => 'event_date',  //指定键
                        'compare' => '>=',      //指定比较方式
                        'value' => $today,      //指定和键进行比较的值
                        'type' => 'numeric'     //指定比较类型
                    )
                )
            )); 
            while($homepageEvent->have_posts()){
                $homepageEvent->the_post();
                get_template_part( 'template-parts/content', 'event' );
            } 
            wp_reset_postdata(); 
            ?>
          <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a></p>
        </div>
      </div>
      <div class="full-width-split__two">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
            <?php $homepagePost = new WP_Query(array(
                'posts_per_page' => 2
            )); 
            while($homepagePost->have_posts()):$homepagePost->the_post();
            ?>
          <div class="event-summary">
            <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
              <span class="event-summary__month"><?php the_time('M'); ?></span>
              <span class="event-summary__day"><?php the_time('d'); ?></span>
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <p><?php 
              if(has_excerpt()){
                echo get_the_excerpt();
              }else{
                echo wp_trim_words(get_the_content(),15); 
              }
              ?><a href="<?php the_permalink(); ?>" class="nu gray"> Read more</a></p>
            </div>
          </div>
            <?php endwhile; wp_reset_postdata(); ?>

          <p class="t-center no-margin"><a href="<?php echo site_url('/blog'); ?>" class="btn btn--yellow">View All Blog Posts</a></p>
        </div>
      </div>
    </div>

    <div class="hero-slider">
      <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
        <?php 
          $frontPageSlide = new WP_Query(array(
            'post_type' => 'slide'
          ));
          
          while($frontPageSlide->have_posts()) { 
            $frontPageSlide->the_post();
        ?>      

          <div class="hero-slider__slide" style="background-image: url(<?php echo get_field('front_page_image_background')['sizes']['slideBackground']; ?>)">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center"><?php echo get_the_title(); ?></h2>
                <p class="t-center"><?php echo get_field('front_page_sub_title'); ?></p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>

          <?php }  
          wp_reset_postdata(); 
          ?>

        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
      </div>
    </div>

<?php get_footer(); ?>