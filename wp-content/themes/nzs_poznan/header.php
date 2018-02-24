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
<header>
   <nav class="navbar fixed-top navbar-light background-top">

        <div class="container-fluid">
          <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>

			 <h1 class="m-0">
              <a class="navbar-brand img-fluid" href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/_img/logo.png">
              </a>
          	</h1> 
          	

        <ul class="ul1 d-none d-md-block">
            <li>
                <a href="https://www.facebook.com/NZS.PP/" rel="nofollow">
                    <i class="fa fa-facebook" aria-hidden="true"></i>
                </a>
            </li>
            <li>
                <a href="https://twitter.com/nzs_pp?lang=pl" rel="nofollow">
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                </a>
            </li>
            <li>
                <a href="https://www.instagram.com/nzs_pp/" rel="nofollow">
                    <i class="fa fa-instagram" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
<?php
  if(is_user_logged_in()){
?>
<div class="dropdown">
<img class="img-fluid d-none d-sm-inline" src="<?php echo get_template_directory_uri(); ?>/_img/osoby/avatar.png">
           
            
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Moje konto
          </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <a href="<?php echo home_url('/wp-admin/'); ?>">
                    <button class="dropdown-item" type="button">Edycja profilu</button>
                </a>
                    <button class="dropdown-item" type="button">Ustawienia</button>
                <a href="<?php echo wp_logout_url(); ?>"> 
                  <button class="dropdown-item" type="button">Wyloguj się</button>
                </a>
            </div>
    </div>
                
                
                
        <?php
            }else{
            echo "<a href=".home_url('/wp-login.php').">
                        <button type='button' class='btn btn-outline-secondary my-2 my-sm-0' data-container='body' data-toggle='popover' data-placement='bottom' data-content=''>Zaloguj się</button>
                    </a>";
            }
        ?>

        </div>
               <?php
                wp_nav_menu( array(
                    'menu'              => 'Menu Główne',
                    'theme_location'    => 'primary',
                    'depth'             => 2,
                    'container'         => 'div',
                    'container_class'   => 'collapse navbar-collapse',
                    'container_id'      => 'navbarNav',
                    'menu_class'        => 'navbar-nav mr-auto',
                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                    'walker'            => new WP_Bootstrap_Navwalker())
                );
            ?>   
                    
    </nav>

</header>


