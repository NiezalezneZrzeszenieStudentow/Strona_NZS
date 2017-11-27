<?php get_header(); ?>
<div class="container">
	<div class="row">
        <div class="col-md-8">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
            <?php
                    // Start the loop.
                    while ( have_posts() ) : the_post(); ?>
                    <div class="img-blog">
        				<img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/_img/img_post1.jpg">
        			</div>
                    	<h4><a href="<?php the_permalink(); ?>"><?php single_post_title(); ?></a></h4>
                <?php endwhile; ?>
                
                <?php the_content(); ?>
            </article>
        </div>
        <div class="col-md-4">
            <?php get_sidebar(); ?>
        </div>
    </div>	
</div>


<?php
get_footer();
