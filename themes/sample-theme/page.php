<?php
get_header();
page_banner();
?>

<?php while(have_posts()){
    the_post();
?>

  <div class="container container--narrow page-section">

    <?php
    $parent = wp_get_post_parent_id(get_the_ID());
    if($parent){ ?>

    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href=<?php echo get_permalink($parent);?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parent)?></a> <span class="metabox__main"><?php the_title();?></span></p>
    </div>
    <?php 
    } ?>

    <?php 
    $childOf = get_pages(array(
      'child_of' => get_the_ID()
    ));
    if($parent or $childOf) {
      ?>
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($parent);?>"><?php echo get_the_title($parent);?></a></h2>
      <ul class="min-list">
        <?php 
        if($parent){
          $childrenOf = $parent;
        }
        else{
          $childrenOf = get_the_ID();
        }
        wp_list_pages(array(
          'title_li' => NULL,
          'child_of' => $childrenOf
        ));
        ?>
      </ul>
    </div>
    <?php
      } ?>

    <div class="generic-content">
        <?php the_content();?>
    </div>

  </div>

<?php 
}
get_footer();
?>