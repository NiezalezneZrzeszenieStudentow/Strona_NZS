<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<title>
			<?php
				//echo bloginfo('name');
				//echo wp_title();
				
				if(is_archive()) {
					echo ucfirst(trim(wp_title('', false))) . ' - ';
				} else
				
				if(!(is_404()) && (is_single()) || (is_page())) {
					$title = wp_title('', false);
					if(!empty($title)) {
						echo $title . ' - ';
					}
				} else
				
				if(is_404()) {
					echo 'Nie znaleziono strony';
				}
				
				if(is_home()) {
					bloginfo('name');
					echo ' - ';
					bloginfo('description');
				} else {
					echo bloginfo('name');
				}
				
				global $paged;
				if($paged > 1) {
					echo ' - strona ' . $paged;
				}
				
			?>           
</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta name="author" content="Karol Znojkiewicz">
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" >
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/ionicons/css/ionicons.min.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/font-awesome/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Lato:400,700|Roboto:400,700&amp;subset=latin-ext" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Kaushan+Script&amp;subset=latin-ext" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr(get_option('nzs_gmap_api_key')); ?>&amp;sensor=true"></script>
<?php if(is_search()):?>
<meta name="robots" content="noindex, nofollow" />
<?php endif; ?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.10&appId=2249145018643131";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: 'pl'}
</script>

</head>
  
<body <?php body_class(); ?>

    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
           <?php
                   // wp_nav_menu( array(
                       // 'menu'              => 'Menu Główne',
                       // 'theme_location'    => 'primary',
                       // 'depth'             => 2,
                       // 'container'         => 'div',
                       // 'container_class'   => 'collapse navbar-collapse',
                       // 'container_id'      => 'navbarNav',
                        //'menu_class'        => 'navbar-nav mr-auto',
                       // 'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                       // 'walker'            => new WP_Bootstrap_Navwalker())
                   // ); 
           ?>   
      </div>
    </nav>



