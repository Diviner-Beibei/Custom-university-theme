<?php get_header(); ?>

<?php  while(have_posts()): the_post();pageBanner(); ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i>All Programs</a> 
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content">          
            <?php the_field('main_body_content'); ?>
        </div>
        <?php 
          //查询事件
          $relatedrofessor = new WP_Query(array(
                'posts_per_page' => -1,          //全部列出
                'post_type' => 'professor',         //文章类型 
                'orderby' => 'title',  //根据指定的额外键排序
                'order' => 'ASC',               //指定排序顺序
                'meta_query' => array(          //指定排除信息（该条将过期的活动信息排除）
                    array(
                        'key' => 'related_programs',        //自定义字段的名称
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            )); 
            if($relatedrofessor->have_posts()){
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">'.get_the_title(). ' Professor</h2>';
                echo '<ul class="professor-cards">';
                while($relatedrofessor->have_posts()):$relatedrofessor->the_post();
        ?>
            <li class="professor-card__list-item">
                <a class="professor-card" href="<?php permalink_link();  ?>">
                    <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" >
                    <span class="professor-card__name"><?php the_title(); ?></span> 
                </a>
            </li> 
        <?php 
                endwhile; 
                echo '</ul>';
                wp_reset_postdata(); 
        };    
        ?>
        <?php 
          $today = date('Ymd');
          //查询事件
          $homepageEvent = new WP_Query(array(
                'posts_per_page' => -1,          //每页2条
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
                    ),
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            )); 
            if($homepageEvent->have_posts()){
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Upcoming '.get_the_title(). ' Events</h2>';
                while($homepageEvent->have_posts()){
                    $homepageEvent->the_post();
                    get_template_part( 'template-parts/content', 'event' );
                }           
            } 
            wp_reset_postdata(); 
            $relatedCampuses = get_field('related_campus');
            if($relatedCampuses){
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">'.get_the_title().' is Avcilable At These Campuses:</h2>';
                echo '<ul class="min-list link-list">';
                foreach($relatedCampuses as $campus){
                    ?>
                    <li>
                        <a href="<?php echo get_the_permalink($campus);  ?>">
                            <?php echo get_the_title($campus); ?>
                        </a>
                    </li> 
                    <?php    
                }
                echo '</ul>';
            }
        ?>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>