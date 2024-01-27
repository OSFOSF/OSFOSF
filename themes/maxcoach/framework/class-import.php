<?php
defined( 'ABSPATH' ) || exit;

/**
 * Initial OneClick import for this theme
 */
if ( ! class_exists( 'Maxcoach_Import' ) ) {
	class Maxcoach_Import {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'insight_core_import_demos', array( $this, 'import_demos' ) );
			add_filter( 'insight_core_import_generate_thumb', '__return_false' );
			add_filter( 'insight_core_import_delete_exist_posts', '__return_true' );
		}

		public function import_demos() {
			$import_img_url = MAXCOACH_THEME_URI . '/assets/import';

			return array(
				'main'    => array(
					'screenshot' => MAXCOACH_THEME_URI . '/screenshot.jpg',
					'name'       => esc_html__( 'Main', 'maxcoach' ),
					'url'        => 'https://www.dropbox.com/s/odzdp19plny82b6/maxcoach-insightcore-main-2.7.0.zip?dl=1',
				),
				'rtl'     => array(
					'screenshot' => $import_img_url . '/rtl/screenshot.jpg',
					'name'       => esc_html__( 'RTL', 'maxcoach' ),
					'url'        => 'https://www.dropbox.com/s/gikzgpz5a6zk8io/maxcoach-insightcore-rtl-1.0.0.zip?dl=1',
				),
				'landing' => array(
					'screenshot' => $import_img_url . '/landing/screenshot.jpg',
					'name'       => esc_html__( 'Landing Page', 'maxcoach' ),
					'url'        => 'https://www.dropbox.com/s/llw42woutiuxqn3/maxcoach-insightcore-landing-1.0.0.zip?dl=1',
				),
			);
		}
	}

	Maxcoach_Import::instance()->initialize();
}
