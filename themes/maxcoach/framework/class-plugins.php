<?php
defined( 'ABSPATH' ) || exit;

/**
 * Plugin installation and activation for WordPress themes
 */
if ( ! class_exists( 'Maxcoach_Register_Plugins' ) ) {
	class Maxcoach_Register_Plugins {

		protected static $instance = null;

		const GOOGLE_DRIVER_API = 'AIzaSyBQsxIg32Eg17Ic0tmRvv1tBZYrT9exCwk';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_filter( 'insight_core_tgm_plugins', array( $this, 'register_required_plugins' ) );
		}

		public function register_required_plugins( $plugins ) {
			/*
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */
			$new_plugins = array(
				array(
					'name'     => esc_html__( 'Insight Core', 'maxcoach' ),
					'slug'     => 'insight-core',
					'source'   => 'https://www.dropbox.com/s/u4o8qjg3pzk26xz/insight-core-2.6.4.zip?dl=1',
					'version'  => '2.6.4',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Elementor', 'maxcoach' ),
					'slug'     => 'elementor',
					'required' => true,
				),
				array(
					'name'        => 'ThemeMove Addons For Elementor',
					'description' => 'Additional functions for Elementor',
					'slug'        => 'tm-addons-for-elementor',
					'logo'        => 'insight',
					'source'      => 'https://www.dropbox.com/s/mabcomq7s1lgkje/tm-addons-for-elementor-1.3.0.zip?dl=1',
					'version'     => '1.3.0',
				),
				array(
					'name'    => esc_html__( 'Revolution Slider', 'maxcoach' ),
					'slug'    => 'revslider',
					'source'  => 'https://www.dropbox.com/s/zfakncfqtjoh19a/revslider-6.6.12.zip?dl=1',
					'version' => '6.6.12',
				),
				array(
					'name' => esc_html__( 'LearnPress – WordPress LMS Plugin', 'maxcoach' ),
					'slug' => 'learnpress',
				),
				array(
					'name' => esc_html__( 'LearnPress – Course Review', 'maxcoach' ),
					'slug' => 'learnpress-course-review',
				),
				array(
					'name'    => esc_html__( 'ThemeMove Payment Add-ons for LearnPress', 'maxcoach' ),
					'slug'    => 'thememove-payment',
					'source'  => 'https://www.dropbox.com/s/0lkgm1x08m76uv6/thememove-payment-addon-for-learnpress-1.2.0.zip?dl=1',
					'version' => '1.2.0',
				),
				array(
					'name' => esc_html__( 'Paid Memberships Pro', 'maxcoach' ),
					'slug' => 'paid-memberships-pro',
				),
				array(
					'name'    => esc_html__( 'LearnPress - Paid Membership Pro Integration', 'maxcoach' ),
					'slug'    => 'learnpress-paid-membership-pro',
					'source'  => 'https://www.dropbox.com/s/vb8ylua8egmnplr/learnpress-paid-membership-pro-4.0.1.1.zip?dl=1',
					'version' => '4.0.1.1',
				),
				array(
					'name' => esc_html__( 'WP Events Manager', 'maxcoach' ),
					'slug' => 'wp-events-manager',
				),
				array(
					'name' => esc_html__( 'Video Conferencing with Zoom', 'maxcoach' ),
					'slug' => 'video-conferencing-with-zoom-api',
				),
				array(
					'name'     => esc_html__( 'Taxonomy Thumbnail', 'maxcoach' ),
					'slug'     => 'sf-taxonomy-thumbnail',
					'required' => true,
				),
				array(
					'name' => esc_html__( 'Contact Form 7', 'maxcoach' ),
					'slug' => 'contact-form-7',
				),
				array(
					'name' => esc_html__( 'MailChimp for WordPress', 'maxcoach' ),
					'slug' => 'mailchimp-for-wp',
				),
				array(
					'name' => esc_html__( 'WooCommerce', 'maxcoach' ),
					'slug' => 'woocommerce',
				),
				array(
					'name' => esc_html__( 'WPC Smart Compare for WooCommerce', 'maxcoach' ),
					'slug' => 'woo-smart-compare',
				),
				array(
					'name' => esc_html__( 'WPC Smart Wishlist for WooCommerce', 'maxcoach' ),
					'slug' => 'woo-smart-wishlist',
				),
				array(
					'name' => esc_html__( 'WP-PostViews', 'maxcoach' ),
					'slug' => 'wp-postviews',
				),
				array(
					'name'    => esc_html__( 'Instagram Feed', 'maxcoach' ),
					'slug'    => 'elfsight-instagram-feed-cc',
					'source'  => 'https://www.dropbox.com/s/o55sjvh8fs2nmoq/elfsight-instagram-feed-cc-4.0.3.zip?dl=1',
					'version' => '4.0.3',
				),
			);

			$plugins = array_merge( $plugins, $new_plugins );

			return $plugins;
		}
	}

	Maxcoach_Register_Plugins::instance()->initialize();
}
