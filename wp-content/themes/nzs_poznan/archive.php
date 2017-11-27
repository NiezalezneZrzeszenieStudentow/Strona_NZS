<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package nzs_poznan
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header">
            	<div class="header-back-archive">
                    <div class="box-write">
                        <?php
							the_archive_title( '<h1 class="page-title">', '</h1>' );
						?>
                        <p>Jesteśmy niewielkim zespołem pasjonatów technologii internetowych, dobrego designu oraz przemyślanych strategii. Proces projektowy i przyjemna współpraca z klientami jest dla nas równie ważna co ostateczny projekt. Dlatego zaufały nam największe firmy w Polsce i na świecie.</p>
                    </div>
                </div>	
            
				
			</header><!-- .page-header -->
         <div class="container-fluid search-border">
         	<div class="pos-center">
                <section class="top-search">
                    <div class="search">
                    
                    <?php $search= getQueryParams('wyszukaj'); ?>
                        <form class="search" method="get" action="<?php getCurrentPageUrl(); ?>">
                            <label for="search">Znajdź wpis:</label>
                            <fieldset>
                                <input name="search" id="search" value="<?php echo $search ?>" type="text">
                                <input value="" type="submit">
                            </fieldset>
                        </form>
                    </div>
                </section>	
         	</div>
         </div>
         <div class="pos-center">
         	<div class="row">
                <div class="col-md-8">
                	<?php if(isset($search)):?>
                        <div class="finder">
                            <p>Wyniki wyszukiwania:</p>
                        </div>
                    <?php endif; ?>
                    <?php
					$query_params = getQueryParams();
					if(isset($query_params['search'])){
						$query_params['post_title_like'] = $query_params['search'];
						unset($query_params['search']);	
					}
					$loop = new WP_Query($query_params);
					
					?>
                    
                    <?php if($loop->have_posts()): ?>
                    
                    <?php while($loop->have_posts()): $loop->the_post(); ?>
                    
                    <?php if(get_post_format($post->ID) == 'gallery'): ?>
                    <div class="article-box-arcive">
                    	<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="img-post">
                                    	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                        <h4><a href="<?php the_permalink(); ?>"<?php the_title();?>></a></h4>
                                       <span><?php the_author(); ?></span>
                                       <p><?php echo get_the_excerpt(); ?></p>
                                </div>
                                </div>
                    	</article>
                    </div>
                           
                    <?php else: ?>
                    <div class="article-box-arcive">
                        <div class="row">
                            <div class="col-md-12">
                                                <h4><a href="<?php the_permalink(); ?>"<?php echo the_title();?>></a></h4>
                                               <span><?php the_author(); ?></span>
                                               <p><?php echo get_the_excerpt(); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                     
                    
                    
                    
                    <?php endwhile; ?>
                    
                    <?php else: ?>
                    	<h4>Nie ma żadnych postów</h4>
                    
                    <?php  endif; ?>
                    
                    
                </div>
                <div class="col-md-4">
                    <?php get_sidebar(); ?>
                </div>
            </div>
         </div>
          

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();


			endwhile;

			the_posts_navigation();

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
