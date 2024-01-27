<?php
defined( 'ABSPATH' ) || exit;

// Do nothing if not an admin page.
if ( ! is_admin() ) {
	return;
}

/**
 * Hook & filter that run only on admin pages.
 */
if ( ! class_exists( 'Maxcoach_Admin' ) ) {
	class Maxcoach_Admin {

		protected static $instance = null;

		/**
		 * Minimum Insight Core version required to run the theme.
		 *
		 * @var string
		 */
		const MINIMUM_INSIGHT_CORE_VERSION = '2.1.1';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_action( 'after_switch_theme', array( $this, 'count_switch_time' ), 1 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_editor' ) );

			if ( class_exists( 'InsightCore' ) ) {
				if ( ! defined( 'INSIGHT_CORE_VERSION' ) || ( defined( 'INSIGHT_CORE_VERSION' ) && version_compare( INSIGHT_CORE_VERSION, self::MINIMUM_INSIGHT_CORE_VERSION, '<' ) ) ) {
					add_action( 'admin_notices', [ $this, 'admin_notice_minimum_insight_core_version' ] );
				}
			}
		}

		public function admin_notice_minimum_insight_core_version() {
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
			/* translators: 1: Plugin name 2: Insight Core 3: Required Insight Core version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'maxcoach' ),
				'<strong>' . MAXCOACH_THEME_NAME . '</strong>',
				'<strong>' . esc_html__( 'Insight Core', 'maxcoach' ) . '</strong>',
				self::MINIMUM_INSIGHT_CORE_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		public function gutenberg_editor() {
			/**
			 * Enqueue fonts for gutenberg editor.
			 */
			wp_enqueue_style( 'font-gilroy', MAXCOACH_THEME_URI . '/assets/fonts/gilroy/font-gilroy.css', null, null );
		}

		public function count_switch_time() {
			$count = get_option( 'maxcoach_switch_theme_count' );

			if ( $count ) {
				$count++;
			} else {
				$count = 1;
			}

			update_option( 'maxcoach_switch_theme_count', $count );
		}

		/**
		 * Enqueue scrips & styles.
		 *
		 * @access public
		 */
		function enqueue_scripts() {
			$this->enqueue_fonts_for_rev_slider_page();

			wp_enqueue_style( 'maxcoach-admin', MAXCOACH_THEME_URI . '/assets/admin/css/style.min.css' );
		}

		/**
		 * Enqueue fonts for Rev Slider edit page.
		 */
		function enqueue_fonts_for_rev_slider_page() {
			$screen = get_current_screen();

			if ( 'toplevel_page_revslider' !== $screen->base ) {
				return;
			}

			$typo_fields = array(
				'typography_body',
				'typography_heading',
				'button_typography',
			);

			if ( ! is_array( $typo_fields ) || empty( $typo_fields ) ) {
				return;
			}

			foreach ( $typo_fields as $field ) {
				$value = Maxcoach::setting( $field );

				if ( is_array( $value ) && isset( $value['font-family'] ) && $value['font-family'] !== '' ) {
					switch ( $value['font-family'] ) {
						case 'TTCommons':
							wp_enqueue_style( 'ttcommons-font', MAXCOACH_THEME_URI . '/assets/fonts/TTCommons/TTCommons.css', null, null );
							break;
						default:
							do_action( 'maxcoach_enqueue_custom_font', $value['font-family'] ); // hook to custom do enqueue fonts
							break;
					}
				}
			}
		}
	}

	Maxcoach_Admin::instance()->initialize();
}
