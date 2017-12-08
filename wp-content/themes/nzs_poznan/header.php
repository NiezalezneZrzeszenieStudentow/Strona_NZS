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
	<nav class="navbar fixed-top">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-bars" aria-hidden="true"></i>

      </button>

      <div class="collapse navbar-collapse" id="navbarsExample01">
        <ul class="navbar-nav mr-auto header-center"> 
			  <?php
                wp_nav_menu( array(
                    'menu'              => 'Menu Główne',
                    'theme_location'    => 'primary',
                    'depth'             => 2,
                    'container'         => 'div',
                    'container_class'   => 'collapse navbar-collapse',
                    'container_id'      => 'navbarsExampleDefault',
                    'menu_class'        => 'nav navbar-nav',
                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                    'walker'            => new WP_Bootstrap_Navwalker())
                );
            ?>       
    	</ul>       
        <form class="form-inline my-2 my-md-0">
          <input class="form-control" type="text" placeholder="Search" aria-label="Search">
        </form>
      </div>
          <h1>
              <a href="<?php //echo esc_url(home_url('/')); ?>"><img src="<?php //echo get_template_directory_uri(); ?>/_img/logo.png">
              </a>
          </h1>
      </div>  
            <ul>
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
      

		
			<div class="box-header-add">       
<?php
	if(is_user_logged_in()){
?>
       
        	
                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/_img/osoby/avatar.png">
           
            
            <div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        <button type='button' class='btn btn-secondary' data-container='body' data-toggle='popover' data-placement='bottom' data-content=''>Zaloguj się</button>
                    </a>";
            }
        ?>
        
        </div>
   </nav>
</header>


