<?php
/**
 * Template for displaying general statistic in user profile overview.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $statistic ) ) {
	return;
}

$user = LP_Profile::instance()->get_user();
?>
<div class="dashboard-general-statistic profile-progress-status">

	<?php do_action( 'learn-press/before-profile-dashboard-general-statistic-row' ); ?>

	<div class="row">
		<?php do_action( 'learn-press/before-profile-dashboard-user-general-statistic' ); ?>

		<div class="col-md-4 col-sm-6">
			<div class="statistic-box green-box enrolled-courses">
				<div class="statistic-number"><?php echo esc_html( $statistic['enrolled_courses'] ); ?></div>
				<h6 class="statistic-text"><?php esc_html_e( 'Enrolled Courses', 'maxcoach' ); ?></h6>
			</div>
		</div>
		<div class="col-md-4 col-sm-6">
			<div class="statistic-box yellow-box active-courses">
				<div class="statistic-number"><?php echo esc_html( $statistic['active_courses']); ?></div>
				<h6 class="statistic-text"><?php esc_html_e( 'Active Courses', 'maxcoach' ); ?></h6>
			</div>
		</div>
		<div class="col-md-4 col-sm-6">
			<div class="statistic-box purple-box completed-courses">
				<div class="statistic-number"><?php echo esc_html( $statistic['completed_courses'] ); ?></div>
				<h6 class="statistic-text"><?php esc_html_e( 'Completed Courses', 'maxcoach' ); ?></h6>
			</div>
		</div>

		<?php do_action( 'learn-press/after-profile-dashboard-user-general-statistic' ); ?>

		<?php if ( $user->can_create_course() ) : ?>

			<?php do_action( 'learn-press/before-profile-dashboard-instructor-general-statistic' ); ?>

			<div class="col-md-4 col-sm-6">
				<div class="statistic-box pink-box total-courses">
					<div class="statistic-number"><?php echo esc_html( $statistic['total_courses'] ); ?></div>
					<h6 class="statistic-text"><?php esc_html_e( 'Total Courses', 'maxcoach' ); ?></h6>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="statistic-box orange-box total-students">
					<div class="statistic-number"><?php echo esc_html( $statistic['total_users']); ?></div>
					<h6 class="statistic-text"><?php esc_html_e( 'Total Students', 'maxcoach' ); ?></h6>
				</div>
			</div>

			<?php do_action( 'learn-press/after-profile-dashboard-instructor-general-statistic' ); ?>

		<?php endif; ?>
	</div>

	<?php do_action( 'learn-press/after-profile-dashboard-general-statistic-row' ); ?>

</div>
