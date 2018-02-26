<?php
/*
Template Name: Full width
*/
get_header(); 
?>


<div class="bannerContainer">
	<div class="desktop-banner" style="background-image:url(http://zos.cba.pl/wp-content/uploads/2018/02/baseline_banner_img.png.jpg);">
        <div class="desktop-banner-container"></div>
            <div class="content-container-inner">
            	<div class="container">
                	<h1 class="titleBanner"><?php the_title(); ?></h1>
                </div>
            </div>
    </div>
</div>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>


<?php get_footer(); ?>
