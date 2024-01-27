<?php
/**
 * Template for displaying content of archive courses page.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">
				<div class="page-main-content">
					<?php
					/**
					 * @since 3.0.0
					 */
					do_action( 'learn-press/archive-description' );
					?>
					<?php
					if ( have_posts() ) : ?>
						<?php
						/**
						 * @since 3.0.0
						 */
						do_action( 'learn-press/before-courses-loop' );
						?>
						<div class="archive-row-actions course-actions row row-sm-center">
							<div class="archive-result-count course-result-count col-md-6">
								<?php
								$result_count = $wp_query->found_posts;
								printf( _n( 'We found %s course available for you', 'We found %s courses available for you', $result_count, 'maxcoach' ), '<span class="count">' . $result_count . '</span>' );
								?>
							</div>
							<div class="col-md-6">
								<form id="archive-form-filtering" class="archive-form-filtering course-form-filtering"
								      method="get">
									<?php
									$options         = Maxcoach_LP_Course::instance()->get_ordering_options();
									$selected        = Maxcoach_LP_Course::instance()->get_ordering_selected_option();
									$select_settings = [
										'fieldLabel' => esc_html__( 'Sort by:', 'maxcoach' ),
									];
									?>
									<select class="maxcoach-nice-select orderby" name="orderby"
									        data-select="<?php echo esc_attr( wp_json_encode( $select_settings ) ); ?>">
										<?php foreach ( $options as $value => $text ) : ?>
											<option
												value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected, $value ); ?> >
												<?php echo esc_html( $text ); ?>
											</option>
										<?php endforeach; ?>
									</select>
									<input type="hidden" name="paged" value="1">
								</form>
							</div>
						</div>
						<?php

						LP()->template( 'course' )->begin_courses_loop();

						while ( have_posts() ) :
							the_post();

							learn_press_get_template_part( 'content', 'course' );

						endwhile;

						LP()->template( 'course' )->end_courses_loop();

						/**
						 * @since 3.0.0
						 */
						do_action( 'learn-press/after-courses-loop' );

						wp_reset_postdata();

					else:
						learn_press_display_message( esc_html__( 'No course found.', 'maxcoach' ), 'error' );
					endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
