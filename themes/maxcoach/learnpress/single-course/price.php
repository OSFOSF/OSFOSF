<?php
/**
 * Template for displaying price of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/price.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.1
 */

defined( 'ABSPATH' ) || exit();

$user   = LP_Global::user();
$course = LP_Global::course();

$price = $course->get_course_price_html();

if ( ! $price ) {
	return;
}
?>
<div class="course-price">
	<span class="meta-label">
		<i class="meta-icon far fa-money-bill-wave"></i>
		<?php esc_html_e( 'Price', 'maxcoach' ); ?>
	</span>

	<span class="meta-value">
		<?php if ( $course->has_sale_price() ) { ?>
			<?php echo '<span class="origin-price">' . $course->get_origin_price_html() . '</span>'; ?>
		<?php } ?>

		<?php echo '<span class="price">' . $price . '</span>'; ?>
	</span>
</div>

