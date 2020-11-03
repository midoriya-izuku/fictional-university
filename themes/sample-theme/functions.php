<?php

require get_theme_file_path('/inc/search-route.php');

function page_banner($args = NULL) {
    if(!$args['title']){
        $args['title'] = get_the_title();
    }

    if(!$args['subtitle']){
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!$args['photo']) {
        if(get_field('page_banner_background_image')){
            $pageBanner = get_field('page_banner_background_image');
            $args['photo'] = $pageBanner['sizes']['pageBanner'];
        }
        else {
            $args['photo'] = get_theme_file_uri('images/ocean.jpg');
        }
    }
?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'];?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'];?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle'];?></p>
      </div>
    </div>  
</div>
<?php
}
function load_scripts_and_styles(){
    wp_enqueue_script('bundled-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('custom-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('universal_style_sheet', get_stylesheet_uri());
    wp_localize_script('bundled-js', 'universityData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ) );
}

function features_setup(){
    register_nav_menu( 'footerMenuLocation1', 'Footer Menu Location One');
    register_nav_menu( 'footerMenuLocation2', 'Footer Menu Location Two');
    add_theme_support( 'title-tag' );
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, false);
    add_image_size('professorPotrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

function custom_query($query){
    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order','ASC');
        $query->set('posts_per_page',-1);
    }

    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()){
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order','ASC');
        $query->set('meta_query', array(
            array(
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
            )
            ));
    }

}

function university_custom_rest() {
    register_rest_field( 'post', 'authorName', array(
        'get_callback' => function() { return get_the_author();}
    ));

    register_rest_field( 'note', 'userNotesCount', array(
        'get_callback' => function() { return count_user_posts( get_current_user_id(), 'note');}
    ));
}

function redirectSubs(){
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber'){
        wp_redirect(site_url('/'));
        exit;
    }
}

function hideAdminBar(){
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber'){
        show_admin_bar(false);
    }
}

function customHeaderUrl(){
    return esc_url(site_url('/'));
}

function customHeaderTitleUrl(){
    return get_bloginfo('name');
}

function customLoginCSS(){
    wp_enqueue_style('custom-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('universal_style_sheet', get_stylesheet_uri());
}

function changeNoteStatus($data, $postarr){
    if($data['post_type'] == 'note'){
        if(count_user_posts( get_current_user_id(), 'note') > 5 AND !$postarr['ID']){
            die("Note limit reached");
        }
        $data['post_title'] = sanitize_text_field($data['post_title']);
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        
    }

    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){
        $data['post_status'] = 'private';
    }
    return $data;
}
//load scripts and styles
add_action('wp_enqueue_scripts','load_scripts_and_styles');

//set up all the features of the site
add_action('after_setup_theme', 'features_setup');

//custom query for posts to 
add_action('pre_get_posts', 'custom_query');

//search api for the site
add_action('rest_api_init', 'university_custom_rest');

//redirect subs to front end page
add_action('admin_init', 'redirectSubs');

//hide admin bar for subs
add_action('wp_loaded', 'hideAdminBar');

//custom css for login screen
add_action('login_enqueue_scripts', 'customLoginCSS');

//change login screen header url
add_filter('login_headerurl', 'customHeaderUrl');

//change login screen title
add_filter('login_headertitle', 'customHeaderTitleUrl');

//set note status to private
add_filter('wp_insert_post_data', 'changeNoteStatus', 10, 2);
?>