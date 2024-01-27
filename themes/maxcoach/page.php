<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Maxcoach
 * @since   1.0.0
 * @version 2.3.0
 */
$header_footer = '';

if ( Maxcoach_LP_Course::instance()->is_single_lessons() ) {
	$header_footer = 'blank';
}

get_header( $header_footer );

/**
 * For some reasons Learnpress use template page.php instead of single.php or single-lp_course.php to show single course.
 * LP 4.0.0 used single-course.php file.
 */
$post_type = get_post_type();

switch ( $post_type ) {
	case Maxcoach_LP_Course::POST_TYPE:
		get_template_part( 'template-parts/content-single', 'course' );
		break;
	default:
		get_template_part( 'template-parts/content-single', 'page' );
		break;
}

get_footer( $header_footer );
