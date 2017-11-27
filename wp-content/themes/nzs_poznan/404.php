<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<section class="body-404">
	<div class="row">
    	<div class="col-md-6">
            <h1>Błąd 404</h1>
            <h2>Niestety strona, którą chcesz odwiedzić, nie istnieje<br>
                A może...wpisałaś/eś zły adres</h2>
                
            <p>Spróbuj wyszukać czegoś innego <br>lub zapraszamy na 
            <span>
            <a href="<?php echo esc_url(home_url('/')); ?>">STRONĘ GŁÓWNĄ</a>
            <span></p> 
            <?php get_search_form(); ?>
        </div>
        <div class="col-md-6">
        
        </div>
    </div>
    



</section>

					

<?php get_footer(); ?>
