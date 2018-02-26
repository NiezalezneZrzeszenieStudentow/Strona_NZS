<?php
/*
Template Name: content with sidebar
*/
	get_header();
?>
<header class="page-header">
            	<div class="header-back-archive">
                    <div class="box-write">
                        <h1 class="page-title"><?php the_title(); ?></h1>                       
                        <p>Jesteśmy niewielkim zespołem pasjonatów technologii internetowych, dobrego designu oraz przemyślanych strategii. Proces projektowy i przyjemna współpraca z klientami jest dla nas równie ważna co ostateczny projekt. Dlatego zaufały nam największe firmy w Polsce i na świecie.
                        </p>
                    </div>
                </div>		
</header>
<?php custom_breadcrumbs(); ?>
<div class="container box-all-margin">
	<div class="row">
    	<div class="col-md-8">
            <div class="box-single-all">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div>
		</div>
        
        <div class="col-md-4">
        	<div class="sidebar">
				<?php get_sidebar(); ?>
        	</div>
        </div>
    </div>
</div>
<?php if ( get_edit_post_link() ) : ?>
		<div class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edytuj <span class="screen-reader-text">%s</span>', 'zos' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</div><!-- .entry-footer -->
	<?php endif; ?>
<div class="fotter-top">
    <a href="#" title="Do góry!" id="scroll-to-top"><i class="far fa-thumbs-up"></i></a>
</div>
     
	
<?php get_footer(); ?>




