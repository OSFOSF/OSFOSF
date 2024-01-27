<?php
/**
 * Template for displaying content of single course.
 *
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       4.0.0
 *
 * @theme-since   2.4.0
 * @theme-version 2.7.3
 */

defined( 'ABSPATH' ) || exit;

get_header( 'course' );
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Maxcoach_Templates::render_sidebar( 'left' ); ?>

				<div id="page-main-content" class="page-main-content">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'template-parts/content-rich-snippet' ); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<h2 class="screen-reader-text"><?php echo esc_html( get_the_title() ); ?></h2>
							<?php learn_press_get_template( 'content-single-course' ); ?>
						</article>

						<?php
						if ( ! Maxcoach_LP_Course::instance()->is_single_lessons() && '1' === Maxcoach::setting( 'single_course_related_enable' ) ) :
							get_template_part( 'template-parts/course/content-related-courses' );
						endif;
						?>

					<?php endwhile; ?>

				</div>

				<?php Maxcoach_Templates::render_sidebar( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer( 'course' );
