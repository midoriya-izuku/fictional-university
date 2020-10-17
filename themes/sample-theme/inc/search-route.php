<?php 

function registerUniversityRoute() {
    register_rest_route( "university/v1", "search", array(
        "methods" => "GET",
        "callback" => "universitySearchResults"
    ));
}

function universitySearchResults($data){
    $searchQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'campus', 'program', 'event'),
        's' => sanitize_text_field($data['term'])
    ));

    $searchResults = array(
        'generalInfo' => array(),
        'professors' => array(),
        'campuses' => array(),
        'programs' => array(),
        'events' => array()
    );
    while($searchQuery->have_posts()){
        $searchQuery->the_post();
        if(get_post_type() == 'post' OR get_post_type() == 'page'){
            array_push($searchResults['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }

        if(get_post_type() == 'professor'){
            array_push($searchResults['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ));
        }

        if(get_post_type() == 'campus'){
            array_push($searchResults['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if(get_post_type() == 'program'){
            array_push($searchResults['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
        
        if(get_post_type() == 'event'){
            $eventDate = new DateTime(get_field('event_date'));
            $description = wp_trim_words( get_the_content(), 18);
            array_push($searchResults['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ));
        }
        
    }
    return $searchResults;
}
add_action("rest_api_init", "registerUniversityRoute");