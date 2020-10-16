<?php
get_header();
page_banner();
?>

<div class="container container--narrow page-section">
    <?php 
        while(have_posts()){
        the_post();
    ?>
    <div class="container container--narrow page-section">
        <div class="row group">
            <div class="one-third">
                <?php the_post_thumbnail('professorPotrait'); ?>
            </div>

            <div class="two-thirds">
                <?php the_content(); ?> 
            </div>
        </div>
    </div>
    <?php
        }
        $relatedPrograms = get_field('related_programs');
        if($relatedPrograms){
        echo "<h2 class='headline'>Subject(s) Taught</h2>";
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