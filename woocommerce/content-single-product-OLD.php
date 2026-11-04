<?php
	defined('ABSPATH') || exit;

	use Boilerplate\Models\Product;
	use Boilerplate\Models\Post;

	global $product;

	$wc_product = wc_get_product(get_the_ID());
	if (! $wc_product) return;

	$my_product = new Product($wc_product->get_id());

	// Related products
	$related_ids = wc_get_related_products( $wc_product->get_id(), 4 );

	if (count($related_ids) < 4) {
		$needed = 4 - count($related_ids);

		$extra = get_posts([
			'post_type'      => 'product',
			'post__not_in'   => array_merge([$wc_product->get_id()], $related_ids),
			'posts_per_page' => $needed,
			'orderby'        => 'rand',
			'fields'         => 'ids',
		]);

		$related_ids = array_merge($related_ids, $extra);
	}

	$related_products = [];
	if (!empty($related_ids)) {
		$related_products = Product::getByIds($related_ids);
	}

	$featured_id = get_post_thumbnail_id($wc_product->get_id());
	$gallery_ids = $wc_product->get_gallery_image_ids();

	$images = [];
	if ($featured_id) $images[] = $featured_id;

	if ($gallery_ids) {
		foreach ($gallery_ids as $id) {
			if ($id != $featured_id) $images[] = $id;
		}
	}

	$has_images = !empty($images);
	$main_image_id  = $has_images ? $images[0] : null;
	$main_image_url = $main_image_id ? wp_get_attachment_image_url($main_image_id, 'large') : '';

	$post_ids = $my_product->getFeaturedInPostIds();
	$my_posts = [];

	if (!empty($post_ids)) {
		$my_posts = Post::getByIds($post_ids);
	}
?>

<section class="pt-8 mb-8">
	<div class="grid grid-cols-5 gap-7 items-center">
		<?php if ($has_images) : ?>
		<div class="col-span-5 md:col-span-3 flex gap-5">
			<div class="w-[50px] sm:w-24 aspect-square relative">
				<div class="product-thumbs-swiper swiper h-full" data-swiper="product-thumbs">
					<div class="swiper-wrapper">
						<?php foreach ($images as $img_id) : ?>
							<?php $thumb = wp_get_attachment_image_url($img_id, 'thumbnail'); ?>
							<div class="swiper-slide">
								<div class="border-neutral-200 border-1 cursor-pointer">
									<img
										src="<?= esc_url($thumb); ?>"
										class="w-full aspect-square object-cover"
										alt=""
									>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="flex-1 product-gallery-swiper swiper overflow-hidden border-neutral-200 border-1" data-swiper="product-gallery">
				<div class="swiper-wrapper">
					<?php foreach ($images as $img_id) : ?>
						<?php $full = wp_get_attachment_image_url($img_id, 'large'); ?>
						<div class="swiper-slide">
							<div class="">
								<img
									src="<?= esc_url($full); ?>"
									class="w-full aspect-square object-cover"
									alt="<?= esc_attr($product->get_name()); ?>"
								>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="col-span-5 <?= $has_images ? 'md:col-span-2' : ''; ?>">
			<div class="flex flex-col gap-3">
				<?php if ($my_product->getBrand()): ?>
					<div class="body-2 text-neutral-500">
						<span><?= esc_html($my_product->getBrand()); ?></span>
					</div>
				<?php endif; ?>

				<h1 class="display-2 !font-normal mb-4"><?php the_title(); ?></h1>

				<?php if ($product->get_sku()) : ?>
					<div class="body-2">
						<span>SKU: <?= esc_html($product->get_sku()); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<div class="flex flex-col items-start gap-6 mt-6">
				<?php if ($product->get_price_html() && is_user_logged_in()) : ?>
					<div class="body-1">
						<?= $product->get_price_html(); ?>
					</div>
				<?php endif; ?>

			<div class="flex justify-between items-center gap-5 flex-wrap">
				<label class="flex items-center gap-2 body-2 cursor-pointer">
					<input type="checkbox" name="compare[]" value="<?= $my_product->getId(); ?>" class="compare-checkbox w-[20px] h-[20px] rounded-none cursor-pointer accent-black">
					<span>Compare</span>
				</label>
			<?php if (is_user_logged_in()) : ?>
				<button 
					class="wishlist-btn flex items-center gap-2 body-2 cursor-pointer hover:opacity-70 transition-opacity"
					data-product-id="<?= $my_product->getId(); ?>"
					data-product-title="<?= esc_attr($my_product->getTitle()); ?>"
					data-product-url="<?= esc_url($my_product->getPermalink()); ?>"
					data-product-image="<?= esc_url($my_product->getImageUrl('large')); ?>"
					aria-label="Add to wishlist"
				>
					<svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="black" stroke-miterlimit="10" stroke-linecap="square"/>
					</svg>
					<p>Add to wishlist</p>
				</button>
			<?php else : ?>
				<button 
					class="wishlist-login-trigger flex items-center gap-2 body-2 cursor-pointer hover:opacity-70 transition-opacity"
					aria-label="Add to wishlist"
				>
					<svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="black" stroke-miterlimit="10" stroke-linecap="square"/>
					</svg>
					<p>Add to wishlist</p>
				</button>
			<?php endif; ?>
			</div>

				<?php if (!is_user_logged_in()) : ?>
					<a href="<?= home_url('/log-in/'); ?>" class="ui-btn !inline-flex items-center gap-2" target="_blank" rel="noopener">
						<p>Sign in or register to view price</p>
					</a>
				<?php endif; ?>

			<?php
			if ($product instanceof WC_Product_External) :
				$url = $product->get_product_url();
				$text = $product->get_button_text() ?: 'Purchase Now';
				
				if (is_user_logged_in()) :
			?>
				<a href="<?= esc_url($url); ?>" class="ui-btn !inline-flex items-center gap-2" target="_blank" rel="noopener">
					<p><?= esc_html($text); ?></p>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M2.5 13.5L13.5 2.5M13.5 2.5H4.5M13.5 2.5V11.5" stroke="white" stroke-width="1.5" stroke-linecap="square"/>
					</svg>
				</a>
			<?php 
				endif;
			endif;
			?>
			</div>
		</div>
	</div>
</section>

<?php if (get_the_content()): ?>
	<section class="pb-8">
		<div class="flex gap-10">
			<!-- Tab Navigation -->
			<div class="flex-shrink-0">
				<div class="flex flex-col min-w-[260px]">
					<div class="flex items-center justify-between gap-4 py-2 border-t border-b border-black text-left">
						<span class="body-2">Description</span>

						<div class="flex items-center justify-center w-[20px] h-[20px]">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.4375 18L13.4375 10L5.4375 2" stroke="black" stroke-linecap="square"/>
							</svg>
						</div>
					</div>
				</div>
			</div>

			<!-- Tab Content -->
			<div class="flex-1">
				<div>
					<h3 class="heading-3 mb-3">Description</h3>
					<div class="body-content">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if (!empty($related_products)) : ?>
	<section class="py-12">
		<h2 class="heading-2 text-center mb-6">Related products</h2>

		<div class="lg:hidden">
			<div class="swiper overflow-visible" data-swiper="products">
				<div class="swiper-wrapper">
					<?php foreach ($related_products as $item) : ?>
						<div class="swiper-slide">
							<?php
							get_template_part(
								'partials/loop/product-card',
								null,
								['product' => $item]
							);
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<div class="hidden lg:grid grid-cols-4 gap-5">
			<?php foreach ($related_products as $item) : ?>
				<?php
				get_template_part(
					'partials/loop/product-card',
					null,
					['product' => $item]
				);
				?>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<?php if (!empty($my_posts)) : ?>
	<section class="py-12">
		<h2 class="heading-2 text-center mb-6">Featured in these posts</h2>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
			<?php foreach ($my_posts as $post) : ?>
				<div>
					<?php include partial('loop/post-card.php'); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

