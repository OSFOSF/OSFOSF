<?php
/**
 * Template for displaying course meta lessons within the loop.
 *
 * @author        ThemeMove
 * @theme-since   2.4.0
 * @theme-version 2.4.0
 */

defined( 'ABSPATH' ) || exit();

$course      = LP_Global::course();
$count_items = $course->count_items();

if ( empty( $count_items ) ) {
	return;
}

$count_items = intval( $count_items );
?>
<div class="course-lesson">
	<span class="meta-icon far fa-file-alt"></span>
	<span class="meta-value">
		<?php
		echo esc_html( sprintf( _n( '%s Lesson', '%s Lessons', $count_items, 'maxcoach' ), number_format_i18n( $count_items ) ) );
		?>
	</span>
</div>
