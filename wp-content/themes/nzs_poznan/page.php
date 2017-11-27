<?php get_header(); ?>

<header class="page-header">
            	<div class="header-back-archive">
                <?php  ?>
                    <div class="box-write">
                        <?php
							the_title( '<h1 class="page-title">', '</h1>' );
						?>
                        <p>Jesteśmy niewielkim zespołem pasjonatów technologii internetowych, dobrego designu oraz przemyślanych strategii. Proces projektowy i przyjemna współpraca z klientami jest dla nas równie ważna co ostateczny projekt. Dlatego zaufały nam największe firmy w Polsce i na świecie.
                        </p>
                    </div>
                </div>	
            
				
</header><!-- .page-header -->

		<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php custom_breadcrumbs(); ?>
		<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->



<?php get_footer(); ?>
