<?php
/**
 * Template for displaying purchased courses in courses tab of user profile page.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$profile       = learn_press_get_profile();
$filter_status = LP_Request::get_string( 'filter-status' );
$query         = $profile->query_courses( 'purchased', array( 'status' => $filter_status ) );
$counts        = $query['counts'];
$filters       = $profile->get_purchased_courses_filters( $filter_status );
?>

<div class="learn-press-subtab-content">

	<?php if ( $filters ) : ?>
		<ul class="learn-press-filters">
			<?php
			foreach ( $filters as $class => $link ) {
				$count = ! empty( $counts[ $class ] ) ? $counts[ $class ] : false;

				if ( $count !== false ) {
					?>

					<li class="<?php echo esc_attr( $class ); ?>">
						<?php echo sprintf( '%s <span class="count">%s</span>', $link, $count ); ?>
					</li>
					<?php
				}
			}
			?>
		</ul>
	<?php endif; ?>

	<?php if ( $query['items'] ) : ?>
		<?php
		$wrapper_classes = [
			'maxcoach-main-post',
			'maxcoach-grid-wrapper',
			'maxcoach-course',
			'maxcoach-animation-zoom-in',
		];

		$grid_options = [
			'type'          => 'grid',
			'columns'       => 3,
			'columnsTablet' => 2,
			'columnsMobile' => 1,
			'gutter'        => 30,
		];
		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
		     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
		>
			<div class="learn-press-courses profile-courses-list maxcoach-grid">
				<div class="grid-sizer"></div>
				<?php
				global $post;

				foreach ( $query['items'] as $item ) {
					$course = learn_press_get_course( $item->get_id() );
					$post   = get_post( $item->get_id() );
					setup_postdata( $post );
					learn_press_get_template( 'content-course.php' );
				}

				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php $query->get_nav( '', true, $profile->get_current_url() ); ?>
	<?php else : ?>
		<?php learn_press_display_message( esc_html__( 'No courses!', 'learnpress' ) ); ?>
	<?php endif; ?>
</div>
