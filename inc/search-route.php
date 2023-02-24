<?php
    //http://localhost/wordpress/wp-json/wp/v2/professor
    //wp是命名空间  professor是根目录
    //v2 是命名空间的一部分，相当于版本号，一旦有人使用你的api，并且需要改变时，可以改成 v3或者v4等等

    add_action( 'rest_api_init', 'universityRegisterSearch' );

    function universityRegisterSearch() {
        register_rest_route( 'university/v1', 'search', array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'universitySearchResults' 
        ) );
    }

    function universitySearchResults($data) {
        $mainQuery = new WP_Query(array(
            'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
            's' => sanitize_text_field( $data['term'] )
        ));

        $results = array(
            'generalInfo' => array(),
            'professors' => array(),
            'programs' => array(),
            'events' => array(),
            'campuses' => array()
        );

       while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if(get_post_type() == 'post' || get_post_type() == 'page') {
            array_push($results['generalInfo'],array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }
        
        if(get_post_type() == 'professor') {
            array_push($results['professors'],array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0,'professorLandscape')//0 代表获取当前帖子，professorLandscape 代表图片大小
            ));
        }

        if(get_post_type() == 'program') {
            $relatedCampuses = get_field('related_campus');

            if($relatedCampuses) {
                foreach($relatedCampuses as $campus) {
                    array_push($results['campuses'],array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }

            array_push($results['programs'],array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_ID()
            ));
        }

        if(get_post_type() == 'campus') {
            array_push($results['campuses'],array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if(get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()){
                $description = get_the_excerpt();
            }else{
                $description = wp_trim_words(get_the_content(),15); 
            }  

            array_push($results['events'],array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ));
        }
       }

       if($results['programs']) {
            //拼接查询
            $programMetaQuery = array('relation' => 'OR');
            //例如：也许有多个生物课 ，海洋生物， 热带生物。。。 我们想要查询每个生物课的教授， 但无法预测有多少门生物课， 用遍历数组方式拼接
            foreach($results['programs'] as $item) {
                array_push($programMetaQuery,array(
                    'key' => 'related_programs',        //自定义字段的名称
                    'compare' => 'LIKE',
                    'value' => '"' . $item['id'] . '"'
                ));
            }
        // print_r($programMetaQuery);
            $programRelationshipQuery = new WP_Query(array(
                'post_type' => array('professor', 'event'),         //文章类型 
                'meta_query' => $programMetaQuery   //如果meta_query对应的值为空，相当于只查询post_type， 会返回所有教授信息
            )); 

            while($programRelationshipQuery->have_posts()) {
                $programRelationshipQuery->the_post();

                if(get_post_type() == 'professor') {
                    array_push($results['professors'],array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'image' => get_the_post_thumbnail_url(0,'professorLandscape')//0 代表获取当前帖子，professorLandscape 代表图片大小
                    ));
                }

                if(get_post_type() == 'event') {
                    $eventDate = new DateTime(get_field('event_date'));
                    $description = null;
                    if(has_excerpt()){
                        $description = get_the_excerpt();
                    }else{
                        $description = wp_trim_words(get_the_content(),15); 
                    }  

                    array_push($results['events'],array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'month' => $eventDate->format('M'),
                        'day' => $eventDate->format('d'),
                        'description' => $description
                    ));
                }
            }

            $results['professors'] = array_values(array_unique($results['professors'],SORT_REGULAR));
            $results['events'] = array_values(array_unique($results['events'],SORT_REGULAR));
        }
       

        return $results;
    }

?>