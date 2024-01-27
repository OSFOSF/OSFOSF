<?php
/**
 * Template for displaying course meta students within the loop.
 *
 * @author        ThemeMove
 * @theme-since   2.4.0
 * @theme-version 2.4.0
 */

defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
if ( ! $course || ! $course->is_required_enroll() ) {
	return;
}

$count = intval( $course->count_students() );
?>
<div class="course-students">
	<span class="meta-icon far fa-user-alt"></span>
	<span class="meta-value">
		<?php echo esc_html( sprintf( _n( '%s Student', '%s Students', $count, 'maxcoach' ), number_format_i18n( $count ) ) ); ?>
	</span>
</div>
