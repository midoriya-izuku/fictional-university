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
        <a class="metabox__blog-home-link" href="<?php echo site_url('/programs');?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> 
        <span class="metabox__main">
            <span><?php the_title();?></span>
        </span>
      </p>
    </div>

    <div class="container container--narrow page-section">
        <?php the_content(); ?>
    </div>
    <?php 
          $relatedProfessors = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'professor',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
              array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"'. get_the_ID() . '"',
              )
            )
          ));
          if($relatedProfessors->have_posts()){
            echo "<h2 class='headline'>Professor(s)</h2>";
            echo "<br>";
            echo "<ul class='professor-cards'>";
          while($relatedProfessors->have_posts()){
            $relatedProfessors->the_post();
        ?> 
        <li class="professor-card__list-item">
          <a href="<?php the_permalink();?>" class="professor-card">
            <img src="<?php the_post_thumbnail_url('professorLandscape');?>" class="professor-card__image">
            <span class="professor-card__name"><?php the_title();?></span>
          </a>
        </li>
        
      <?php
        }
        echo "</ul>";
      }
        wp_reset_postdata();
      ?>
    <?php
      }
    ?>
    <hr> 
    <?php 
      $today = date('Ymd');
      $relatedEvents = new WP_Query(array(
        'posts_per_page' => '2',
        'post_type' => 'event',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
          array(
            'key' => 'event_date',
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric'
          ),
          array(
            'key' => 'related_programs',
            'compare' => 'LIKE',
            'value' => '"'. get_the_ID() . '"',
          )
        )
      ));
      if($relatedEvents->have_posts()){
        echo "<h2 class='headline'>Upcoming ". get_the_title(get_the_ID()) ." Events</h2>";
        echo "<br>";
        while($relatedEvents->have_posts()){
          $relatedEvents->the_post();
          get_template_part('templates/content-event');
        }
      }
      wp_reset_postdata();
    ?>
</div>
<?php
get_footer();
?>