<?php
/**
 * Template for displaying content of course without header and footer
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}

$layout = Maxcoach_LP_Course::instance()->get_single_layout();
?>
<div id="lp-single-course" class="lp-single-course">
	<?php do_action( 'learn-press/before-single-course' ); ?>

	<?php if ( '01' === $layout ) : ?>
		<div id="learn-press-course" class="course-summary row tm-sticky-parent">
			<div class="col-lg-8">
				<div class="tm-sticky-column">
					<?php
					/**
					 * @since 3.0.0
					 *
					 * @see   learn_press_single_course_summary()
					 */
					do_action( 'learn-press/single-course-summary' );
					?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="entry-course-info tm-sticky-column">
					<?php
					/**
					 * Custom hook by Maxcoach.
					 *
					 * @author  Maxcoach
					 * @package Maxcoach
					 * @since   1.0.0
					 */
					do_action( 'learn-press/single-sticky-bar-features' );
					?>
				</div>
			</div>
		</div>
	<?php else: ?>
		<div id="learn-press-course" class="course-summary">
			<?php
			/**
			 * @since 3.0.0
			 *
			 * @see   learn_press_single_course_summary()
			 */
			do_action( 'learn-press/single-course-summary' );
			?>

			<?php
			/**
			 * Custom hook by Maxcoach.
			 *
			 * @author  Maxcoach
			 * @package Maxcoach
			 * @since   1.0.0
			 */
			do_action( 'learn-press/after-single-course-summary' );
			?>
		</div>
	<?php endif; ?>
	<?php
	do_action( 'learn-press/after-single-course' );
	?>
</div>
