<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package nzs_poznan
 */

?>

	</div><!-- #content -->



<footer>

<div class="footer-container">
	<div class="container box-all">
    	<div class="row">
            <div class="col-md-4 container-one">
                <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/_img/logo.png"></a>
                <p>Proin quis velit eleifend, consequat neque in, porta lectus. Suspendisse laoreet sed ipsum at commodo. Fusce euismod bibendum sollicitudin. Nullam neque mauris, ullamcorper vel dolor at, sollicitudin vulputate felis. Praesent nec mattis velit, non aliquam mauris. Sed blandit tristique tellus, vitae rutrum mi suscipit non. Pellentesque in scelerisque mauris, nec sagittis odio. Curabitur efficitur massa ut arcu ullamcorper, eu luctus risus imperdiet. </p>
            </div>
            <div class="col-md-4 container-two">
            	<h5>Ostatnie posty</h5>
               <?php query_posts('posts_per_page=2'); ?>
               <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
               <div class="box-post-footer">
                <div class="row">
                	<div class="col-md-4">
                    	<?php the_post_thumbnail( array(150, 150, 'aligncenter') ); ?>
                    </div>
                    <div class="col-md-8">
                    	<h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                        <p><?php echo the_excerpt_max_charlength(140); ?></p>
                    </div>
                 </div>
                </div>
                 <?php endwhile; endif; ?>
                
              
                
                
            </div>
            <div class="col-md-4 container-three">
            	<h5>Kontakt</h5>
                <div class="phone">
                	<div class="contact-left"><i class="fa fa-phone" aria-hidden="true"></i></div>
                    <div class="contact-right">
                    <p>Numer telefonu</p> 
                    <p>600-985-344</p>
                    </div>
                </div>
                <div class="place">
                	<div class="contact-left"><i class="fa fa-globe" aria-hidden="true"></i></div>
                    <div class="contact-right">
                    <p>Adres biura</p> 
                    <p>Dom Studencki 1, PUT Space</p>
                    <p>ul. Jana Pawła II 28</p>
                    <p>61-139, Poznań </p>
                    </div>
                </div>
                <div class="email">
                	<div class="contact-left"><i class="fas fa-at"></i></div>
                    <div class="contact-right">
                    	<p>E-mail</p>
                        <p>nzs@put.poznan.pl</p>
                    </div>
                </div>
                <div class="connect">
                	<h5>Dołącz do nas</h5>
                	<ul>
                    	<li><a class="facebook circle" href="facebook.pl"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a class="twitter circle" href="twitter.com"><i class="fab fa-twitter"></i></a></li>
                        <li><a class="instagram circle" href="instagram.com"><i class="fab fa-instagram"></i></a></li>
                        <li><a class="youtube circle" href="youtube.pl"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                   </div>
            </div>
    	</div>
	</div>
</div>
   
<p>&copy; <?php echo date("Y"); ?> All Rights Reserved. <a href="index.php">Niezależne Zrzeszenie studentów.</a> Wykonanie: <a href="mailto:karolznojkiewcz@outlook.com?subject=Kontakt">Karol Znojkiewicz</a></p>
      </footer>
	
    
    
    
    
    
    
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
