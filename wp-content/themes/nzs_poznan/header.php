<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<title><?php
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
				
			?></title>
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
  
<body <?php body_class(); ?>>
	<header>
    	<div class="top-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        	<i class="fa fa-phone" aria-hidden="true"></i><a href="tel:&#055;&#050;&#056;&#051;&#056;&#050;&#055;&#054;&#056;">&#055;&#050;&#056;-&#051;&#056;&#050;-&#055;&#054;&#056;</a>
                            <i class="fa fa-globe" aria-hidden="true"></i>Dom Studencki 1, PUT Space, ul. Jana Pawła II 28
                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
<a href="mailto:&#110;&#122;&#115;&#064;&#112;&#117;&#116;&#046;&#112;&#111;&#122;&#110;&#097;&#110;&#046;&#112;&#108;">&#110;&#122;&#115;&#064;&#112;&#117;&#116;&#046;&#112;&#111;&#122;&#110;&#097;&#110;&#046;&#112;&#108;</a>
                    </div>
                    <div class="col-md-2">
                    		<ul class="header-ul">
                            	<li>A</li>
                                <li>A<sup>+</sup>
                            </ul>
                    		<a href=""><i class="fa fa-adjust" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-1">
                    	<ul class="header-ul">
						  <li><a href="https://www.facebook.com/NZS.PP/" rel="nofollow"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li><a href="https://twitter.com/nzs_pp?lang=pl" rel="nofollow"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="https://www.instagram.com/nzs_pp/" rel="nofollow"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> 
    <nav class="navbar navbar-expand-md navbar-dark background-header">
    	<h1><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/_img/logo.png"></a></h1>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      
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
        <div class="box-header-add">
        
       
<?php
	if(is_user_logged_in()){
?>
       
        	<div class="header-avatar">
                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/_img/osoby/avatar.png">
            </div>
                <div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Moje konto
  				</button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenu2">
<a href="<?php echo home_url('/wp-admin/'); ?>">
                    <button class="dropdown-item" type="button">Edycja profilu</button>
</a>
                    <button class="dropdown-item" type="button">Ustawienia</button>
                   <a href="<?php echo wp_logout_url(); ?>"> <button class="dropdown-item" type="button">Wyloguj się</button></a>
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
