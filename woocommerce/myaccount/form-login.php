<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');
$is_register_page = is_page_template('template-register.php');
?>

<?php if (!$is_register_page) : ?>
<div class="woocommerce-form-login-wrap">

    <form method="post" class="woocommerce-form woocommerce-form-login login">
        <?php do_action('woocommerce_login_form_start'); ?>

        <p class="form-row">
            <label for="username"><?php esc_html_e('Email', 'woocommerce'); ?></label>
            <input type="text" name="username" id="username" autocomplete="username" placeholder="Enter email address">
        </p>

        <p class="form-row">
            <label for="password"><?php esc_html_e('Password', 'woocommerce'); ?></label>
            <input type="password" name="password" id="password" autocomplete="current-password" placeholder="Enter password">
        </p>

        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>

        <p class="form-row">
            <button type="submit" class="button" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">
                <?php esc_html_e('Log in', 'woocommerce'); ?>
            </button>
        </p>

        <input type="hidden" name="rememberme" value="forever">

        <p class="lost_password">
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>">
                <?php esc_html_e('Lost your password?', 'woocommerce'); ?>
            </a>
        </p>

        <?php do_action('woocommerce_login_form_end'); ?>
    </form>

</div>
<?php endif; ?>

<?php if ($is_register_page && get_option('woocommerce_enable_myaccount_registration') === 'yes') : ?>
<div class="woocommerce-form-register-wrap">

    <form method="post" class="woocommerce-form woocommerce-form-register register">
        <?php do_action('woocommerce_register_form_start'); ?>

        <?php if (get_option('woocommerce_registration_generate_username') === 'no') : ?>
            <p class="form-row">
                <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?></label>
                <input type="text" name="username" id="reg_username" autocomplete="username" placeholder="Enter username">
            </p>
        <?php endif; ?>

        <p class="form-row">
            <label for="reg_email"><?php esc_html_e('Email', 'woocommerce'); ?></label>
            <input type="email" name="email" id="reg_email" autocomplete="email" required placeholder="Enter email address">
        </p>

        <p class="form-row">
            <label for="reg_first_name">First name</label>
            <input type="text" name="first_name" id="reg_first_name" autocomplete="given-name" required placeholder="Enter first name">
        </p>

        <p class="form-row">
            <label for="reg_last_name">Last name</label>
            <input type="text" name="last_name" id="reg_last_name" autocomplete="family-name" required placeholder="Enter last name">
        </p>


        <?php do_action('woocommerce_register_form'); ?>

        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>

        <p class="form-row">
            <button type="submit" class="button" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>">
                <?php esc_html_e('Register', 'woocommerce'); ?>
            </button>
        </p>

        <?php do_action('woocommerce_register_form_end'); ?>
    </form>

</div>
<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>
