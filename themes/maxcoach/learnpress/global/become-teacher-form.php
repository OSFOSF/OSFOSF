<?php
/**
 * Template for displaying the form let user fill out their information to become a teacher.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/global/become-teacher-form.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  4.1.5.1
 */

defined( 'ABSPATH' ) || exit();

$user = learn_press_get_current_user();
$wrapper_classes = 'become-teacher-form learn-press-form';

if ( $user && ! $user instanceof LP_User_Guest && ! learn_press_become_teacher_sent() ) {
	$wrapper_classes .= ' allow';
} else {
	$wrapper_classes .= ' block-fields';
}
?>

<div id="learn-press-become-teacher-form" class="<?php echo esc_attr( $wrapper_classes ); ?>">

	<h4 class="form-register-teacher-title"><?php esc_html_e( 'Register to become an Intructor', 'maxcoach' ); ?></h4>

	<form name="become-teacher-form" method="post" enctype="multipart/form-data" action="">
		<?php if ( ! empty( $description ) ) : ?>
			<p class="become-teacher-form__description"><?php echo wp_kses_post( $description ); ?></p>
		<?php endif ?>

		<ul class="become-teacher-fields form-fields">
			<?php do_action( 'learnpress/become-a-teacher/before-form' ); ?>

			<li class="form-field">
				<label for="bat_name"><?php esc_html_e( 'Name', 'maxcoach' ); ?></label>
				<input type="text" name="bat_name" required placeholder="<?php esc_attr_e( 'Your name', 'maxcoach' ); ?>" value="<?php echo isset( $_POST['bat_name'] ) ? wp_unslash( $_POST['bat_name'] ) : $user->get_display_name(); ?>">
			</li>
			<li class="form-field">
				<label for="bat_email"><?php esc_html_e( 'Email', 'maxcoach' ); ?></label>
				<input type="email" name="bat_email" required placeholder="<?php esc_attr_e( 'Your email address', 'maxcoach' ); ?>" value="<?php echo isset( $_POST['bat_email'] ) ? wp_unslash( $_POST['bat_email'] ) : $user->get_email(); ?>">
			</li>
			<li class="form-field">
				<label for="bat_phone"><?php esc_html_e( 'Phone', 'maxcoach' ); ?></label>
				<input type="text" name="bat_phone" placeholder="<?php esc_attr_e( 'Your phone number', 'maxcoach' ); ?>" value="<?php echo isset( $_POST['bat_phone'] ) ? wp_unslash( $_POST['bat_phone'] ) : ''; ?>">
			</li>
			<li class="form-field">
				<label for="bat_message"><?php esc_html_e( 'Message', 'maxcoach' ); ?></label>
				<textarea name="bat_message" placeholder="<?php esc_attr_e( 'Your message', 'maxcoach' ); ?>"><?php echo isset( $_POST['bat_message'] ) ? wp_unslash( $_POST['bat_message'] ) : ''; ?></textarea>
			</li>

			<?php do_action( 'learnpress/become-a-teacher/after-form' ); ?>

		</ul>

		<input type="hidden" name="request-become-a-teacher-nonce" value="<?php echo wp_create_nonce( 'request-become-a-teacher' ); ?>">

		<button type="submit" data-text="<?php echo ! empty( $submit_button_process_text ) ? esc_attr( $submit_button_process_text ) : esc_attr__( 'Submitting', 'maxcoach' ); ?>">
			<?php echo ! empty( $submit_button_text ) ? esc_html( $submit_button_text ) : esc_html__( 'Get the learning program', 'maxcoach' ); ?>
		</button>
	</form>
</div>
