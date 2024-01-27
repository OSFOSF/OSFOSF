<?php
/**
 * Template for displaying main user profile page.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( ! isset( $profile ) ) {
	return;
}
?>
<?php do_action( 'learnpress/template/pages/profile/before-content' ); ?>

<?php if ( $profile->is_public() || $profile->get_user()->is_guest() ) {
	?>

	<?php
	/**
	 * Add css class .row for wrap login form + register form
	 */
	$extra_class = '';
	if ( $profile->get_user()->is_guest() ) {
		$extra_class = 'row';
	}
	?>
	<div id="learn-press-user-profile"<?php $profile->main_class( true, $extra_class ); ?>>
		<?php
		do_action( 'learn-press/before-user-profile', $profile );

		do_action( 'learn-press/user-profile', $profile );

		do_action( 'learn-press/after-user-profile', $profile );
		?>
	</div>

<?php } else {
	esc_html_e( 'This user does not public their profile.', 'maxcoach' );
}
