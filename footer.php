			<?php
				use Boilerplate\Services\Menus;
			?>

			<footer class="bg-black pt-8 pb-6">
				<div class="container">

					<div class="flex flex-col xl:flex-row gap-8 xl:gap-[120px] pb-8">

						<div class="max-w-full xl:max-w-[400px]">
							<?php if(get_field('footer_logo', 'options')): ?>
								<a href="<?= home_url(); ?>">
									<img src="<?= get_field('footer_logo', 'options')['url']; ?>" alt="<?= get_field('footer_logo', 'options')['alt']; ?>" class="object-contain">
								</a>
							<?php endif; ?>

							<?php if(get_field('footer_content', 'options')): ?>
								<div class="body-1 mt-6 text-white">
									<?= get_field('footer_content', 'options'); ?>
								</div>
							<?php endif; ?>

							<?php if(get_field('footer_newsletter_form', 'options')): ?>
								<div class="mt-6">
									<?= do_shortcode('[contact-form-7 id="' . get_field('footer_newsletter_form', 'options') . '" title="Newsletter Footer"]'); ?>
								</div>
							<?php endif; ?>
						</div>

						<div class="flex justify-between flex-1 gap-5">
							<div class="flex flex-col gap-7">
								<div>
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Discover</h3>
									<?php Menus::displayFooterMenu('footer_discover'); ?>
								</div>

								<div>
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Learn</h3>
									<?php Menus::displayFooterMenu('footer_learn'); ?>
								</div>

								<div>
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Haus of more</h3>
									<?php Menus::displayFooterMenu('footer_haus'); ?>
								</div>

								<div class="sm:hidden">
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Customer Support</h3>
									<?php Menus::displayFooterMenu('footer_discover'); ?>
								</div>
							</div>

							<div class="flex flex-col gap-7">
								<div>
									<h3 class="s-3 uppercase text-neutral-400 mb-2">About</h3>
									<?php Menus::displayFooterMenu('footer_about'); ?>
								</div>

								<div>
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Useful Links</h3>
									<?php Menus::displayFooterMenu('footer_useful_links'); ?>
								</div>

								<div>
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Connect with us</h3>
									<?php Menus::displayFooterMenu('footer_connect'); ?>
								</div>

								<div class="sm:hidden">
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Legal / T&Cs</h3>
									<?php Menus::displayFooterMenu('footer_legal'); ?>
								</div>
							</div>

							<div class="hidden sm:flex flex-col gap-7">
								<div>
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Customer Support</h3>
									<?php Menus::displayFooterMenu('footer_discover'); ?>
								</div>

								<div>
									<h3 class="s-3 uppercase text-neutral-400 mb-2">Legal / T&Cs</h3>
									<?php Menus::displayFooterMenu('footer_legal'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="flex justify-between border-t border-neutral-200 pt-5">
						<p class="body-3 text-white">&copy; <?= date('Y'); ?> <?= get_bloginfo('name'); ?> <?php date('Y'); ?></p>
						
						<p class="body-3 text-white">Website by: <a href="https://www.voodoochilli.com" title="Hereford web design" target="_blank">Voodoochilli</a></p>
					</div>

				</div>
			</footer>

		<!-- Wishlist Login Modal -->
		<div id="wishlist-login-modal" class="wishlist-modal fixed inset-0 z-[100] hidden items-center justify-center bg-black bg-opacity-20">
			<div class="wishlist-modal-content bg-white p-8 md:p-10 max-w-md mx-4 relative">
				<button class="wishlist-modal-close absolute top-4 right-4 w-8 h-8 flex items-center justify-center hover:opacity-70 transition-opacity" aria-label="Close modal">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 1L15 15M15 1L1 15" stroke="black" stroke-width="1.5" stroke-linecap="square"/>
					</svg>
				</button>

				<div class="text-center">
					<svg width="44" height="38" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-5">
						<path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="square" fill="none"/>
					</svg>
					
					<h2 class="heading-2 mb-4"><?= get_field('wishlist_popup_title', 'options'); ?></h2>
					<p class="mb-6"><?= get_field('wishlist_popup_content', 'options'); ?></p>
					
				<div class="flex flex-col gap-3">
					<a href="<?= home_url('/sign-up/'); ?>" class="ui-btn text-center">Sign up</a>
					<?php if (!get_field('soft_launch_mode', 'options')) : ?>
						<a href="<?= home_url('/log-in/'); ?>" class="ui-btn-outline text-center">Log in</a>
					<?php endif; ?>
				</div>
				</div>
			</div>
		</div>

		<script>
		document.addEventListener('DOMContentLoaded', function() {
			const modal = document.getElementById('wishlist-login-modal');
			const triggers = document.querySelectorAll('.wishlist-login-trigger, #wishlist-login-trigger');
			const closeBtn = document.querySelector('.wishlist-modal-close');

			function openModal() {
				if (modal) {
					modal.classList.remove('hidden');
					modal.classList.add('flex');
					document.body.style.overflow = 'hidden';
				}
			}

			function closeModal() {
				if (modal) {
					modal.classList.add('hidden');
					modal.classList.remove('flex');
					document.body.style.overflow = '';
				}
			}

			triggers.forEach(function(trigger) {
				trigger.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					openModal();
				});
			});

			if (closeBtn) {
				closeBtn.addEventListener('click', closeModal);
			}

			if (modal) {
				modal.addEventListener('click', function(e) {
					if (e.target === modal) {
						closeModal();
					}
				});
			}
		});
		</script>

		<?php wp_footer(); ?>
	</body>
</html>
