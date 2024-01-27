<?php

namespace Maxcoach\Woo;

defined( 'ABSPATH' ) || exit;

class Compare {
	protected static $instance = null;

	const MINIMUM_PLUGIN_VERSION = '4.0.2';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( ! $this->is_activate() ) {
			return;
		}

		// Check old version installed.
		if ( defined( 'WOOSCP_VERSION' ) // Constant in old version
		     || ( defined( 'WOOSC_VERSION' ) && version_compare( WOOSC_VERSION, self::MINIMUM_PLUGIN_VERSION, '<' ) )
		) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_plugin_version' ] );
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

		add_filter( 'woosc_button_position_archive', '__return_false' );
		add_filter( 'woosc_button_position_single', '__return_false' );

		// Add compare & wishlist button again.
		add_action( 'woocommerce_after_add_to_cart_button', [
			\Maxcoach_Woo::instance(),
			'get_compare_button_template',
		], 40 );

		// Change compare button color on popup.
		add_filter( 'woosc_bar_btn_color_default', [ $this, 'change_compare_button_color' ] );
	}

	public function is_activate() {
		if ( class_exists( 'WPCleverWoosc' ) ) {
			return true;
		}

		return false;
	}

	public function admin_notice_minimum_plugin_version() {
		maxcoach_notice_required_plugin_version( 'Smart Compare for WooCommerce', self::MINIMUM_PLUGIN_VERSION );
	}

	public function frontend_scripts() {
		// Remove styles from plugin.
		wp_dequeue_style( 'hint' );
		wp_dequeue_style( 'woosc-frontend' );
		wp_dequeue_style( 'perfect-scrollbar-wpc' );
	}

	public function change_compare_button_color() {
		$primary_color = \Maxcoach::setting( 'primary_color' );

		return $primary_color;
	}
}

Compare::instance()->initialize();
