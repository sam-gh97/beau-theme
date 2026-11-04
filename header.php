<!DOCTYPE html>
<html <?php language_attributes() ?>>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<link rel="stylesheet" href="https://use.typekit.net/wba0qre.css">
		
		<title><?php wp_title(''); ?></title>

		<link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/favicon-96x96.png">
		<link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/favicon.svg">
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/favicon.ico">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/apple-touch-icon.png">
		<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicons/site.webmanifest">
		
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<script>
		window.isLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
	</script>


	<header class="header fixed top-0 left-0 right-0 z-[80] bg-white flex items-center justify-center border-b-[1px] border-neutral-200 h-[44px] md:h-[60px] lg:h-auto">
	<div class="px-5 py-2 border-r-[1px] flex items-center justify-center absolute left-0 top-0 bottom-0 border-neutral-200 max-w-[100px] sm:max-w-none">
		<?php if(get_field('site_logo', 'options')): ?>
				<img src="<?= get_field('site_logo', 'options')['url']; ?>" alt="<?= get_field('site_logo', 'options')['alt']; ?>" class="object-contain">
				<a href="<?= home_url(); ?>" class="absolute inset-0 w-full h-full"></a>
		<?php endif; ?>
	</div>
		
	<div class="px-5 hidden lg:flex items-center justify-center">
		<?php Boilerplate\Services\Menus::displayMainMenu(); ?>
	</div>

	<div class="px-5 py-2 border-l-[1px] flex items-center justify-center absolute right-0 top-0 bottom-0 border-neutral-200">

		<?php if (!get_field('soft_launch_mode', 'options')) : ?>
			<?php if (is_user_logged_in()) : ?>
				<a href="<?= home_url('/my-account/wishlist/'); ?>" class="wishlist-link flex items-center pr-4 gap-2" title="View Wishlist">
					<svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg" class="wishlist-icon">
						<path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="currentColor" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="round" fill="none" class="wishlist-path"/>
					</svg>
				</a>
			<?php else : ?>
				<button class="wishlist-link flex items-center cursor-pointer pr-4 gap-2" title="View Wishlist" id="wishlist-login-trigger">
					<svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg" class="wishlist-icon">
						<path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="currentColor" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="round" fill="none" class="wishlist-path"/>
					</svg>
				</button>
			<?php endif; ?>
		<?php endif; ?>
		
		<button class="hamburger-menu lg:hidden flex flex-col justify-center items-center ml-4 w-5 h-5 relative" aria-label="Toggle menu">
			<span class="hamburger-line"></span>
			<span class="hamburger-line"></span>
		</button>

		<?php if (is_user_logged_in()) : ?>
			<?php if (!get_field('soft_launch_mode', 'options')) : ?>
				<a href="<?= wp_logout_url(home_url()); ?>" class="lg:px-4 px-2 uppercase body-2 !font-futura-pt font-medium hidden lg:block">Log out</a>
			<?php endif; ?>
		<?php else : ?>
			<a href="<?= home_url('/sign-up/'); ?>" class="lg:px-4 px-2 uppercase body-2 !font-futura-pt font-medium hidden lg:block">Sign up</a>
			<?php if (!get_field('soft_launch_mode', 'options')) : ?>
				<a href="<?= home_url('/log-in/'); ?>" class="lg:px-4 px-2 uppercase body-2 !font-futura-pt font-medium hidden lg:block">Log in</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	</header>

	<div class="mobile-menu-overlay fixed inset-0 bg-black opacity-70 z-[60] hidden" id="mobile-menu-overlay"></div>
	
	<div class="mobile-menu-container fixed top-0 right-0 bottom-0 w-[calc(100%-100px)] sm:w-[calc(100%-130px)] md:w-[300px] bg-white z-[70] transform translate-x-full transition-transform duration-300 overflow-y-auto border-l border-neutral-200" id="mobile-menu-container">
		<div class="min-h-full flex flex-col pt-[60px] md:pt-[76px]">
			<div class="px-6 pb-5">
				<?php Boilerplate\Services\Menus::displayMobileMenu(); ?>
			</div>
			<div class="mt-auto">
				<div class="flex items-center justify-center gap-4 px-6 py-5">
					<a href="<?= home_url('/sign-up/'); ?>" class="text-sm font-futura-pt uppercase font-medium">Sign up</a>
					<a href="<?= home_url('/log-in'); ?>" class="text-sm font-futura-pt uppercase font-medium">Log in</a>
				</div>
			</div>
		</div>
	</div>

		<?php if ( have_rows('banners', 'options') && is_front_page() ) : ?>
		
	<div class="banner-slider relative swiper" data-swiper="banner">
		<div class="swiper-wrapper">
			<?php 
			while( have_rows('banners', 'options') ) : the_row(); 
				$background_colour = get_sub_field('background_colour');
				$text_colour = get_sub_field('text_colour');
				
				$link_class = ($text_colour === 'white') ? 'ui-link ui-link--white' : 'ui-link';
				
				$text_color_class = ($text_colour === 'white') ? 'text-white' : 'text-black';
				
				$bg_class = $background_colour ? '' : 'bg-alt-red';
				$bg_style = $background_colour ? 'background-color: ' . esc_attr($background_colour) . ';' : '';
				
				$link = get_sub_field('button');
			?>
				
				<div class="swiper-slide <?= $text_color_class; ?> <?= $bg_class; ?>" <?= $bg_style ? 'style="' . $bg_style . '"' : ''; ?>>
					<div class="flex flex-col md:flex-row items-center justify-center pb-2 gap-3 md:gap-7">
						<h4 class="heading-2-sm"><?= get_sub_field('title'); ?></h4>
						<p class="body-2"><?= get_sub_field('short_content'); ?></p>
						<?php if ($link && !empty($link['url'])): ?>
							<a href="<?= esc_url($link['url']); ?>" target="<?= esc_attr($link['target'] ?? '_self'); ?>" class="<?= $link_class; ?>">
								<?= esc_html(!empty($link['title']) ? $link['title'] : 'Learn more'); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			
			<?php endwhile; ?>
		</div>
		<div class="banner-pagination swiper-pagination"></div>
	</div>
		
		<?php endif; ?>
		