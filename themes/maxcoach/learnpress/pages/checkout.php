<?php
/**
 * Template for displaying content of page for processing checkout feature.
 *
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       4.0.1
 *
 * @theme-since   2.4.0
 * @theme-version 2.4.0
 */

defined( 'ABSPATH' ) || exit();

get_header();
?>
	<div class="page-content">
		<div class="container">
			<div class="row">
				<div class="page-main-content">
					<?php
					do_action( 'learnpress/template/pages/checkout/before-content' );
					?>

					<div id="learn-press-checkout" class="lp-content-wrap">
						<?php
						/**
						 * LP Hook
						 *
						 * @since 4.0.0
						 */
						do_action( 'learn-press/before-checkout-page' );

						// Shortcode for displaying checkout form
						echo do_shortcode( '[learn_press_checkout]' );

						/**
						 * LP Hook
						 *
						 * @since 4.0.0
						 */
						do_action( 'learn-press/after-checkout-page' );
						?>
					</div>

					<?php
					do_action( 'learnpress/template/pages/checkout/after-content' );
					?>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
