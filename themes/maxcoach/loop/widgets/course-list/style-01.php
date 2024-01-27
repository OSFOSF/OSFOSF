<?php
if ( ! isset( $settings ) ) {
	$settings = array();
}
$loop_count        = 0;
$left_box_template = $right_box_template = '';
?>
<?php while ( $maxcoach_query->have_posts() ) : $maxcoach_query->the_post(); ?>
	<?php if ( $loop_count === 0 ) : ?>
		<?php ob_start(); ?>
		<div <?php post_class(); ?>>
			<div class="course-wrapper maxcoach-box">
				<div class="course-thumbnail-wrapper maxcoach-image">
					<a href="<?php the_permalink(); ?>" class="course-permalink link-secret">
						<div class="course-thumbnail">
							<?php if ( has_post_thumbnail() ) { ?>
								<?php $size = Maxcoach_Image::elementor_parse_image_size( $settings, '570x345' ); ?>
								<?php Maxcoach_Image::the_post_thumbnail( array( 'size' => $size ) ); ?>
							<?php } else { ?>
								<?php Maxcoach_Templates::image_placeholder( 570, 345 ); ?>
							<?php } ?>
							<div class="course-overlay-bg"></div>
						</div>
					</a>
				</div>
				<div class="course-info">
					<?php if ( 'yes' === $settings['show_caption_instructor'] ): ?>
						<?php Maxcoach_LP_Course::instance()->the_loop_instructor(); ?>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_caption_date'] ) : ?>
						<div class="course-date"><?php echo get_the_date(); ?></div>
					<?php endif; ?>

					<h3 class="course-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>

					<div class="course-price-box">
						<?php Maxcoach_LP_Course::instance()->the_loop_price(); ?>

						<?php if ( ! empty( $settings['show_caption_meta'] ) ) : ?>
							<?php $meta = $settings['show_caption_meta']; ?>
							<div class="course-meta">
								<?php if ( in_array( 'lessons', $meta, true ) ): ?>
									<?php Maxcoach_LP_Course::instance()->the_loop_lessons(); ?>
								<?php endif; ?>

								<?php if ( in_array( 'students', $meta, true ) ): ?>
									<?php Maxcoach_LP_Course::instance()->the_loop_students(); ?>
								<?php endif; ?>

								<?php if ( in_array( 'duration', $meta, true ) ): ?>
									<?php Maxcoach_LP_Course::instance()->the_loop_duration(); ?>
								<?php endif; ?>

								<?php if ( in_array( 'category', $meta, true ) ): ?>
									<?php Maxcoach_LP_Course::instance()->the_loop_meta_categories(); ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="course-excerpt">
						<?php Maxcoach_Templates::excerpt( array(
							'limit' => 50,
							'type'  => 'word',
						) ); ?>
					</div>

					<?php if ( 'yes' === $settings['show_caption_buttons'] && function_exists( 'learn_press_get_template' ) ) : ?>
						<div class="course-buttons">
							<?php Maxcoach_LP_Course::instance()->the_loop_buttons(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php $left_box_template .= ob_get_clean(); ?>
	<?php else: ?>
		<?php ob_start(); ?>
		<div <?php post_class(); ?>>
			<div class="course-wrapper maxcoach-box">
				<div class="course-thumbnail-wrapper maxcoach-image">
					<a href="<?php the_permalink(); ?>" class="course-permalink link-secret">
						<div class="course-thumbnail">
							<?php if ( has_post_thumbnail() ) { ?>
								<?php $size = Maxcoach_Image::elementor_parse_image_size( $settings, '160x100' ); ?>
								<?php Maxcoach_Image::the_post_thumbnail( array( 'size' => $size ) ); ?>
							<?php } else { ?>
								<?php Maxcoach_Templates::image_placeholder( 160, 100 ); ?>
							<?php } ?>
							<div class="course-overlay-bg"></div>
						</div>
					</a>
				</div>
				<div class="course-info">
					<?php if ( 'yes' === $settings['show_caption_instructor'] ): ?>
						<?php Maxcoach_LP_Course::instance()->the_loop_instructor(); ?>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_caption_date'] ) : ?>
						<div class="course-date"><?php echo get_the_date(); ?></div>
					<?php endif; ?>

					<h3 class="course-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>

					<div class="course-price-box">
						<?php Maxcoach_LP_Course::instance()->the_loop_price(); ?>

						<?php if ( ! empty( $settings['show_caption_meta'] ) ) : ?>
							<?php $meta = $settings['show_caption_meta']; ?>
							<div class="course-meta">
								<?php if ( in_array( 'lessons', $meta, true ) ): ?>
									<?php Maxcoach_LP_Course::instance()->the_loop_lessons(); ?>
								<?php endif; ?>

								<?php if ( in_array( 'students', $meta, true ) ): ?>
									<?php Maxcoach_LP_Course::instance()->the_loop_students(); ?>
								<?php endif; ?>

								<?php if ( in_array( 'duration', $meta, true ) ): ?>
									<?php Maxcoach_LP_Course::instance()->the_loop_duration(); ?>
								<?php endif; ?>

								<?php if ( in_array( 'category', $meta, true ) ): ?>
									<?php Maxcoach_LP_Course::instance()->the_loop_meta_categories(); ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( 'yes' === $settings['show_caption_buttons'] && function_exists( 'learn_press_get_template' ) ) : ?>
						<div class="course-buttons">
							<?php Maxcoach_LP_Course::instance()->the_loop_buttons(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php $right_box_template .= ob_get_clean(); ?>
	<?php endif; ?>
	<?php $loop_count++; ?>
<?php endwhile; ?>
<div class="row">
	<div class="col-md-6">
		<div class="featured-course">
			<?php echo $left_box_template; ?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="normal-courses">
			<?php echo $right_box_template; ?>
		</div>
	</div>
</div>
