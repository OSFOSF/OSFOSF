<?php
/**
 * Template for displaying own courses in courses tab of user profile page.
 * Edit by Nhamdv
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$profile       = learn_press_get_profile();
$user          = LP_Profile::instance()->get_user();
$filter_status = LP_Request::get_string( 'filter-status' );
$query         = $profile->query_courses( 'own', array( 'status' => $filter_status ) );
$counts        = $query['counts'];
$filters       = $profile->get_own_courses_filters( $filter_status );
?>

<div class="learn-press-subtab-content">
	<?php if ( $filters ) : ?>
		<ul class="learn-press-filters">
			<?php
			foreach ( $filters as $class => $link ) {
				$count = ! empty( $counts[ $class ] ) ? absint( $counts[ $class ] ) : false;

				if ( $count ) {
					?>
					<li class="<?php echo esc_attr( $class ); ?>">
						<?php printf( '%s <span class="count">%s</span>', $link, $count ); ?>
					</li>
					<?php
				}
			}
			?>
		</ul>
	<?php endif; ?>

	<?php if ( ! $query['total'] ) : ?>
		<?php learn_press_display_message( esc_html__( 'No courses!', 'maxcoach' ) ); ?>
	<?php else : ?>
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
			<div class="learn-press-courses profile-courses-list maxcoach-grid" id="learn-press-profile-created-courses">
				<div class="grid-sizer"></div>
				<?php
				global $post;

				foreach ( $query['items'] as $item ) {
					$course = learn_press_get_course( $item );
					$post   = get_post( $item );
					setup_postdata( $post );
					learn_press_get_template( 'content-course.php' );
				}

				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php $query->get_nav( '', true, $profile->get_current_url() ); ?>
	<?php endif; ?>
</div>
