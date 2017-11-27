<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package nzs_poznan
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
   <div class="container">
   	<div class="row">
    	<div class="img-blog">
        	<img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/_img/img_post1.jpg">
        </div>
        <div class="box-wrapper-blog">
            <div class="title-blog">
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            </div>
            <div class="data-blog">
                <i class="fa fa-user" aria-hidden="true"></i><?php the_author(); ?>
                <i class="fa fa-clock-o" aria-hidden="true"></i><?php the_date(); ?>
            </div>
    	
        <div class="entry-content"> 
                <?php echo the_excerpt_max_charlength(580); ?>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
