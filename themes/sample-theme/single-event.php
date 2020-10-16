<?php
get_header();
page_banner();
?>


<div class="container container--narrow page-section">
  <?php 
    while(have_posts()){
      the_post();
  ?>

    
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo site_url('/events');?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home</a> 
        <span class="metabox__main">
            <span><?php the_title();?></span>
        </span>
      </p>
    </div>

    <div class="container container--narrow page-section">
        <?php the_content(); ?>
    </div>
  <?php
    }
    $relatedPrograms = get_field('related_programs');
    if($relatedPrograms){
      echo "<h2 class='headline'>Related Program(s)</h2>";
      echo "<ul class='link-list min-list'>";
      foreach($relatedPrograms as $program){
  ?>
        <li><a href="<?php echo get_the_permalink($program);?>"><?php echo get_the_title($program);?></a></li>
  <?php
      }
      echo "</ul>";
    }
  ?>
</div>
    
<?php
get_footer();
?>