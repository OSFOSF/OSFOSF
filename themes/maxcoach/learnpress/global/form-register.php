<?php
/**
 * Template for displaying global login form.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/global/form-register.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.8
 */

defined( 'ABSPATH' ) || exit();
?>

<div class="learn-press-form-wrap learn-press-form-register-wrap col-md-6 col-md-push-1">

	<?php do_action( 'learn-press/before-form-register' ); ?>

	<div class="learn-press-form-register learn-press-form">

		<h3><?php echo esc_html_x( 'Register', 'register-heading', 'maxcoach' ); ?></h3>

		<form name="learn-press-register" method="post" action="">
			<ul class="form-fields">
				<?php do_action( 'learn-press/before-form-register-fields' ); ?>

				<li class="form-field">
					<label for="reg_email"><?php esc_html_e( 'Email address', 'maxcoach' ); ?>&nbsp;<span class="required">*</span></label>
					<input id="reg_email" name="reg_email" type="email"
					       placeholder="<?php esc_attr_e( 'Email', 'maxcoach' ); ?>" autocomplete="email"
					       value="<?php echo ( ! empty( $_POST['reg_email'] ) ) ? esc_attr( wp_unslash( $_POST['reg_email'] ) ) : ''; ?>">
				</li>
				<li class="form-field">
					<label for="reg_username"><?php esc_html_e( 'Username', 'maxcoach' ); ?>&nbsp;<span class="required">*</span></label>
					<input id="reg_username" name="reg_username" type="text"
					       placeholder="<?php esc_attr_e( 'Username', 'maxcoach' ); ?>" autocomplete="username"
					       value="<?php echo ( ! empty( $_POST['reg_username'] ) ) ? esc_attr( wp_unslash( $_POST['reg_username'] ) ) : ''; ?>">
				</li>
				<li class="form-field">
					<label for="reg_password"><?php esc_html_e( 'Password', 'maxcoach' ); ?>&nbsp;<span class="required">*</span></label>
					<input id="reg_password" name="reg_password" type="password"
					       placeholder="<?php esc_attr_e( 'Password', 'maxcoach' ); ?>" autocomplete="new-password">
					<p class="form-input-description">
						<?php
						$password_capitalized  = LP()->settings->get( 'enable_password_capitalized_letter', 'yes' );
						$password_number       = LP()->settings->get( 'enable_password_number', 'yes' );
						$password_special_char = LP()->settings->get( 'enable_password_special_character', 'yes' );

						$password_description = __( 'The password must be at least 6 characters long,', 'maxcoach' );

						if ( 'yes' === $password_capitalized ) {
							$password_description .= __( ' contain upper and lower case letters,', 'maxcoach' );
						}

						if ( 'yes' === $password_number ) {
							$password_description .= __( ' contain numbers,', 'maxcoach' );
						}

						if ( 'yes' === $password_special_char ) {
							$password_description .= __( ' contain symbols like ! " ? $ % ^ & ),', 'maxcoach' );
						}

						$password_description = rtrim( $password_description, ',' );
						$password_description .= '.';

						echo $password_description;
						?>
					</p>
				</li>
				<li class="form-field">
					<label for="reg_password2"><?php esc_html_e( 'Confirm Password', 'maxcoach' ); ?>&nbsp;<span class="required">*</span></label>
					<input id="reg_password2" name="reg_password2" type="password"
					       placeholder="<?php esc_attr_e( 'Password', 'maxcoach' ); ?>" autocomplete="off">
				</li>

				<?php do_action( 'learn-press/after-form-register-fields' ); ?>
			</ul>

			<?php do_action( 'register_form' ); ?>

			<p>
				<?php wp_nonce_field( 'learn-press-register', 'learn-press-register-nonce' ); ?>
				<button type="submit"><?php esc_html_e( 'Register', 'maxcoach' ); ?></button>
			</p>

		</form>

	</div>

	<?php do_action( 'learn-press/after-form-register' ); ?>

</div>
