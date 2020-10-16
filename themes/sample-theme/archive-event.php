<?php
get_header();
page_banner(array('title' => 'All Events', 'subtitle' => 'See what is going on'));
?>

<div class="container container--narrow page-section">
  <?php 
    while(have_posts()){
      the_post();
      get_template_part('templates/content-event');          
    }
    echo paginate_links();
  ?>

    <p>Looking for past events? Check out the past events <a href="<?php echo site_url('/past-events');?>">here</a></p>
</div>
    
<?php
get_footer();
?>