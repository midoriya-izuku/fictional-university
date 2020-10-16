<?php
get_header();
page_banner(array('title' => 'Blog', 'subtitle' => 'Regular posts from the university'));
?>

<div class="container container--narrow page-section">
  <?php 
    $blog = new WP_Query(array(
      'type'                     => 'post',
      'post_status'              => 'publish',
      'posts_per_page'           => '10',
      'orderby'                  => 'date',
      'order'                    => 'DSC',
    ));
    while($blog->have_posts()){
      $blog->the_post();
  ?>

  <div class="post-item">
      <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>

      <div class="metabox">
        <span>Post by <?php the_author_posts_link();?> on <?php the_time('n.j.y');?> in <?php echo get_the_category_list(',');?></span>
      </div>

      <div class="generic-content">
        <p><?php the_excerpt();?></p>
        <div><a href="<?php the_permalink();?>" class="btn btn--blue">Continue Reading &raquo;</a></div>
      </div>
  </div>

  <?php
    }
    echo paginate_links();
    wp_reset_postdata();
  ?>
</div>
    
<?php
get_footer();
?>