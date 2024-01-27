<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Maxcoach_LP_Course' ) ) {
	class Maxcoach_LP_Course extends Maxcoach_Post_Type {

		protected static $instance = null;
		const POST_TYPE            = 'lp_course';
		const TAXONOMY_CATEGORY    = 'course_category';
		const TAXONOMY_TAGS        = 'course_tag';
		const POST_META_STUDENTS   = '_lp_students';
		const POST_META_PRICE      = '_lp_price';
		const POST_META_SALE_PRICE = '_lp_sale_price';

		/**
		 * custom post meta, used for ordering on archive pages.
		 */
		const CUSTOM_POST_META_SORT_PRICE = '_lp_sort_price';


		const MINIMUM_LEARNPRESS_VERSION = '4.1.0';

		const MINIMUM_TM_PAYMENT_VERSION = '1.2.0';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			if ( ! $this->is_activated() ) {
				return;
			}

			if ( version_compare( LEARNPRESS_VERSION, self::MINIMUM_LEARNPRESS_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_learnpress_version' ] );
			}

			if ( defined( 'TM_ADDON_PAYMENT_VER' ) && version_compare( TM_ADDON_PAYMENT_VER, self::MINIMUM_TM_PAYMENT_VERSION, '>' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_tm_payment_version' ] );
			}

			$maxcoach_db_version = get_option( 'maxcoach_db_version' );
			if ( empty( $maxcoach_db_version ) || version_compare( $maxcoach_db_version, '2.5.2', '<' ) ) {
				add_action( 'admin_notices', [ $this, 'update_db_252' ] );

				add_action( 'admin_init', [ $this, 'maxcoach_update_db' ] );
			}

			add_filter( 'learn-press/override-templates', '__return_true', 9999 );
			add_filter( 'learn-press/enable-cart', '__return_true', 9999 );

			add_filter( 'lp/template/archive-course/enable_lazyload', '__return_false' );
			/**
			 * Fix Course Membership Levels not render properly make save not working.
			 */
			add_action( 'lp_course_data_setting_tab_content', [ $this, 'fix_course_data_settings_not_saved' ], -1 );

			$this->change_single_components();

			$this->change_archive_components();

			$this->change_profile_components();

			/**
			 * Add more setting for password on register form.
			 */
			add_filter( 'learn-press/settings/profile', [ $this, 'add_user_profile_password_settings' ], 10, 1 );
			add_filter( 'learn-press/update-settings/redirect', [ $this, 'update_settings' ] );

			/**
			 * Changed validate with custom settings.
			 */
			add_filter( 'learnpress_registration_errors', [ $this, 'user_registration_validate' ], 10, 3 );

			add_filter( 'learn_press_course_price_html_free', [ $this, 'change_course_free_price' ], 99, 2 );
			add_filter( 'learn_press_course_origin_price_html', [ $this, 'add_wrapper_decimals_separator' ], 99, 1 );
			add_filter( 'learn_press_course_price_html', [ $this, 'add_wrapper_decimals_separator' ], 99, 1 );

			add_filter( 'maxcoach_sidebar_1', [ $this, 'remove_sidebar_for_sticky_layout' ] );

			add_action( 'maxcoach/title-bar-meta', [ $this, 'add_title_bar_meta_course_instructor' ] );
			add_action( 'maxcoach/title-bar-meta', [ $this, 'add_title_bar_meta_course_duration' ] );
			add_action( 'maxcoach/title-bar-meta', [ $this, 'add_title_bar_meta_course_lesson' ] );
			add_action( 'maxcoach/title-bar-meta', [ $this, 'add_title_bar_meta_course_student' ] );

			// Remove learn paid membership css.
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 99 );

			add_filter( 'learn-press/frontend-default-styles', [ $this, 'change_plugin_style' ], 9999 );
			add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ], 9999 );

			add_filter( 'lp/course/meta-box/fields/general', [ $this, 'add_fields_to_meta_box' ] );

			// Adds custom classes to the array of body classes.
			add_filter( 'body_class', [ $this, 'body_classes' ] );

			add_filter( 'pre_get_posts', [ $this, 'change_main_loop_courses_query' ] );

			add_action( 'wp_ajax_lp_course_infinite_load', [ $this, 'infinite_load' ] );
			add_action( 'wp_ajax_nopriv_lp_course_infinite_load', [ $this, 'infinite_load' ] );

			/**
			 * Add course meta price sort.
			 * Used priority 9999 to sure run after cmb2 plugin, make sure post meta saved.
			 */
			add_action( 'learnpress_save_lp_course_metabox', [ $this, 'save_course_sale_price' ], 9999 );

			/**
			 * Remove adv from leanpress.
			 */
			remove_action( 'admin_footer', 'learn_press_footer_advertisement', -10 );

			// Change button back to class style.
			remove_action( 'learn-press/after-checkout-form', 'learn_press_back_to_class_button' );
			remove_action( 'learn-press/after-empty-cart-message', 'learn_press_back_to_class_button' );
			add_action( 'learn-press/after-checkout-form', [ $this, 'back_to_class_button' ], 25 );
			add_action( 'learn-press/after-empty-cart-message', [ $this, 'back_to_class_button' ] );
		}

		public function admin_notice_minimum_learnpress_version() {
			maxcoach_notice_required_plugin_version( 'LearnPress', self::MINIMUM_LEARNPRESS_VERSION );
		}

		public function admin_notice_minimum_tm_payment_version() {
			maxcoach_notice_required_plugin_version( 'Thememove Payment Add-ons for LearnPress', self::MINIMUM_TM_PAYMENT_VERSION );
		}

		function fix_course_data_settings_not_saved() {
			global $thepostid, $post;

			$thepostid = empty( $thepostid ) ? $post->ID : $thepostid;
		}

		/**
		 *
		 * Plugin return empty instead of Free text.
		 *
		 * @param $text
		 * @param $course
		 *
		 * @return string
		 */
		public function change_course_free_price( $text, $course ) {
			return esc_html__( 'Free', 'maxcoach' );
		}

		public function back_to_class_button() {
			$courses_link = learn_press_get_page_link( 'courses' );
			if ( ! $courses_link ) {
				return;
			}

			Maxcoach_Templates::render_button( [
				'link'          => [
					'url' => esc_url( $courses_link ),
				],
				'text'          => esc_html__( 'Back to class', 'maxcoach' ),
				'icon'          => 'fal fa-long-arrow-left',
				'wrapper_class' => 'btn-back-to-class',
			] );
			?>
			<?php
		}

		/**
		 * @param WP_Error $errors
		 * @param string   $username
		 * @param string   $email
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function user_registration_validate( $errors, $username, $email ) {
			$password = isset( $_POST['reg_password'] ) ? wp_unslash( $_POST['reg_password'] ) : '';

			$password_capitalized  = LP()->settings->get( 'enable_password_capitalized_letter', 'yes' );
			$password_number       = LP()->settings->get( 'enable_password_number', 'yes' );
			$password_special_char = LP()->settings->get( 'enable_password_special_character', 'yes' );

			if ( 'yes' === $password_capitalized && ! preg_match( "#[A-Z]+#", $password ) ) {
				$errors->add( 'registration-error-missing-capitalized-char-password', esc_html__( 'Password must include at least one capitalized letter!', 'maxcoach' ) );
			}

			if ( 'yes' === $password_number && ! preg_match( "#[0-9]+#", $password ) ) {
				$errors->add( 'registration-error-missing-number-char-password', esc_html__( 'Password must include at least one number!', 'maxcoach' ) );
			}

			if ( 'yes' === $password_special_char && ! preg_match( '#[~!@\#$%^&*()]#', $password ) ) {
				$errors->add( 'registration-error-missing-special-char-password', esc_html__( 'Password must include at least one of these characters ~!@#$%^&*() !', 'maxcoach' ) );
			}

			return $errors;
		}

		public function add_user_profile_password_settings( $settings ) {
			$password_settings = array(
				array(
					'title' => esc_html__( 'Password Strength', 'maxcoach' ),
					'type'  => 'title',
					'desc'  => esc_html__( 'Controls the password strength on register form.', 'maxcoach' ),
					'id'    => 'maxcoach_profile_password',
				),
				array(
					'title'   => esc_html__( 'Password capitalized letter', 'maxcoach' ),
					'desc'    => esc_html__( 'Force user register with password at least one capitalized letter.', 'maxcoach' ),
					'id'      => 'enable_password_capitalized_letter',
					'default' => 'yes',
					'type'    => 'yes-no',
				),
				array(
					'title'   => esc_html__( 'Password number', 'maxcoach' ),
					'desc'    => esc_html__( 'Force user register with password at least one number.', 'maxcoach' ),
					'id'      => 'enable_password_number',
					'default' => 'yes',
					'type'    => 'yes-no',
				),
				array(
					'title'   => esc_html__( 'Password special character', 'maxcoach' ),
					'desc'    => esc_html__( 'Force user register with password at least one of these characters ~!@#$%^&*() ', 'maxcoach' ),
					'id'      => 'enable_password_special_character',
					'default' => 'yes',
					'type'    => 'yes-no',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'maxcoach_profile_password',
				),
			);

			$password_settings = apply_filters( 'learn-press/profile-settings-fields/password', $password_settings );

			$instructor_settings = array(
				array(
					'title' => esc_html__( 'Instructors', 'maxcoach' ),
					'type'  => 'title',
					'desc'  => esc_html__( 'Settings for instructors.', 'maxcoach' ),
					'id'    => 'maxcoach_profile_instructors',
				),
				array(
					'title'   => esc_html__( 'Create Events', 'maxcoach' ),
					'desc'    => esc_html__( 'Allows instructors create event posts.', 'maxcoach' ),
					'id'      => 'instructor_manage_event',
					'default' => '',
					'type'    => 'yes-no',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'maxcoach_profile_instructors',
				),
			);

			$instructor_settings = apply_filters( 'learn-press/profile-settings-fields/instructors', $instructor_settings );

			$settings = array_merge( $settings, $password_settings, $instructor_settings );

			return $settings;
		}

		public function update_settings( $url ) {
			$grant = isset( $_POST['learn_press_instructor_manage_event'] ) && 'yes' === $_POST['learn_press_instructor_manage_event'] ? true : false;

			$instructor = get_role( LP_TEACHER_ROLE );

			$instructor->add_cap( 'edit_tp_event', $grant );
			$instructor->add_cap( 'read_tp_event', $grant );
			$instructor->add_cap( 'delete_tp_event', $grant );

			$instructor->add_cap( 'edit_tp_events', $grant );
			$instructor->add_cap( 'edit_others_tp_events', $grant );
			$instructor->add_cap( 'publish_tp_events', $grant );
			$instructor->add_cap( 'read_private_tp_events', $grant );
			$instructor->add_cap( 'delete_tp_events', $grant );

			return $url;
		}

		public function enqueue_scripts() {
			wp_dequeue_style( 'learn-press-pmpro-style' );
		}

		public function change_profile_components() {
			/**
			 * Tab Dashboard.
			 * 3.x.x
			 */
			// Remove author bio.
			remove_action( 'learn-press/profile/dashboard-summary', 'learn_press_profile_dashboard_user_bio', 10 );

			remove_action(
				'learn-press/profile/dashboard-summary',
				LP()->template( 'profile' )->func( 'dashboard_featured_courses' ),
				20
			);
			remove_action(
				'learn-press/profile/dashboard-summary',
				LP()->template( 'profile' )->func( 'dashboard_latest_courses' ),
				30
			);

			add_action( 'learn-press/profile/dashboard-summary', [
				$this,
				'profile_dashboard_user_courses_progress',
			], 10 );

			LP()->template( 'profile' )->remove( 'learn-press/user-profile-account', '*' );
			add_action( 'learn-press/user-profile-account', LP()->template( 'profile' )->func( 'header' ), 20 );
		}

		public function profile_dashboard_user_courses_progress() {
			/**
			 * @var LP_Profile          $profile
			 * @var LP_User_Item_Course $user_course
			 */
			$profile = learn_press_get_profile();

			$courses_query = $profile->query_courses( 'purchased' );

			if ( $courses_query['items'] ) {
				?>
				<div class="profile-courses-progress">
					<h3 class="profile-courses-heading"><?php esc_html_e( 'Courses progress', 'maxcoach' ); ?></h3>

					<div class="maxcoach-progress style-01">
						<?php
						foreach ( $courses_query['items'] as $user_course ) {
							// Setup.
							$course         = learn_press_get_course( $user_course->get_id() );
							$percent_result = $user_course->get_percent_result();
							?>

							<div class="single-progress-bar">
								<h4 class="progress-title"><?php echo esc_html( $course->get_title() ); ?></h4>
								<div class="progress-bar-wrap">
									<div class="progress-bar" style="width: <?php echo esc_attr( $percent_result ); ?>">
										<div class="progress-value"><?php echo esc_html( $percent_result ); ?></div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>

				</div>
				<?php
			}
		}

		public function infinite_load() {
			$source     = isset( $_POST['source'] ) ? $_POST['source'] : '';
			$query_vars = $_POST['query_vars'];

			if ( 'custom_query' === $source ) {
				$query_vars = $this->build_extra_terms_query( $query_vars, $query_vars['extra_tax_query'] );
			}

			$maxcoach_query = new WP_Query( $query_vars );

			$settings = isset( $_POST['settings'] ) ? $_POST['settings'] : array();

			$response = array(
				'max_num_pages' => $maxcoach_query->max_num_pages,
				'found_posts'   => $maxcoach_query->found_posts,
				'count'         => $maxcoach_query->post_count,
			);

			ob_start();

			if ( $maxcoach_query->have_posts() ) :
				learn_press_setup_user();
				set_query_var( 'maxcoach_query', $maxcoach_query );
				set_query_var( 'settings', $settings );

				get_template_part( 'loop/widgets/course/style', $settings['layout'] );

				wp_reset_postdata();
			endif;

			$template = ob_get_contents();
			ob_clean();

			$template = preg_replace( '~>\s+<~', '><', $template );

			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}

		public function get_setting( $name ) {
			/**
			 * @var LP_Settings $settings
			 */
			$settings = LP()->settings;

			$setting = $settings->get( $name );

			return $setting;
		}

		/**
		 * Add html tag wrap decimal separator.
		 *
		 * @param string $origin_price
		 *
		 * @return mixed
		 */
		public function add_wrapper_decimals_separator( $origin_price ) {
			$number_decimal     = intval( $this->get_setting( 'number_of_decimals' ) );
			$decimals_separator = $this->get_setting( 'decimals_separator' );

			if ( $number_decimal > 0 && ! empty( $decimals_separator ) ) {
				$dec_position = strpos( $origin_price, $decimals_separator );
				$dec_part     = substr( $origin_price, $dec_position, $number_decimal + 1 );
				$dec_template = '<span class="decimals-separator">' . $dec_part . '</span>';
				$origin_price = str_replace( $dec_part, $dec_template, $origin_price );
			}

			return $origin_price;
		}

		/**
		 * @param int $post_ID
		 */
		public function save_course_sale_price( $post_ID ) {
			$sort_price = 0;
			$sale_price = get_post_meta( $post_ID, self::POST_META_SALE_PRICE, true );

			if ( '' !== $sale_price ) {
				$sort_price = $sale_price;
			} else {
				$price = get_post_meta( $post_ID, self::POST_META_PRICE, true );
				if ( '' !== $price ) {
					$sort_price = $price;
				}
			}

			update_post_meta( $post_ID, self::CUSTOM_POST_META_SORT_PRICE, $sort_price );
		}

		public function get_ordering_options() {
			return [
				'popularity' => esc_html__( 'Popularity', 'maxcoach' ),
				'date'       => esc_html__( 'Latest', 'maxcoach' ),
				'price'      => esc_html__( 'Price: low to high', 'maxcoach' ),
				'price-desc' => esc_html__( 'Price: high to low', 'maxcoach' ),
			];
		}

		public function get_ordering_selected_option() {
			$order_by = ! empty( $_GET['orderby'] ) ? $_GET['orderby'] : 'date';

			return $order_by;
		}

		/**
		 * @param WP_Query $query
		 */
		public function change_main_loop_courses_query( $query ) {
			if ( ! $query->is_main_query() || ! $this->is_archive() || is_admin() ) {
				return;
			}

			// Custom sort order.
			$orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : false;
			if ( ! empty( $orderby ) ) {
				switch ( $orderby ) {
					case 'popularity':
						$query->set( 'meta_key', self::POST_META_STUDENTS );
						$query->set( 'orderby', 'meta_value' );
						$query->set( 'order', 'DESC' );
						break;
					case 'date':
						$query->set( 'orderby', 'date' );
						break;
					case 'price':
						$query->set( 'meta_key', self::CUSTOM_POST_META_SORT_PRICE );
						$query->set( 'orderby', 'meta_value title' );
						$query->set( 'order', 'ASC' );
						break;
					case 'price-desc':
						$query->set( 'meta_key', self::CUSTOM_POST_META_SORT_PRICE );
						$query->set( 'orderby', 'meta_value title' );
						$query->set( 'order', 'DESC' );
						break;
				}
			}
		}

		public function change_archive_components() {
			/**
			 * @action learn-press/after-courses-loop-item
			 *
			 * @hooked
			 * @see    learn_press_courses_loop_item_begin_meta - 10
			 * @see    learn_press_courses_loop_item_price - 20
			 * @see    learn_press_courses_loop_item_title - 21
			 * @see    learn_press_courses_loop_item_instructor - 25
			 * @see    learn_press_courses_loop_item_end_meta - 30
			 * @see    learn_press_course_loop_item_buttons - 35
			 * @see    learn_press_course_loop_item_user_progress - 40
			 */

			/**
			 * Remove all hooks from Plugin.
			 */
			LP()->template( 'course' )->remove( 'learn-press/before-courses-loop-item', '*' );
			LP()->template( 'course' )->remove( 'learn-press/courses-loop-item-title', '*' );
			LP()->template( 'course' )->remove( 'learn-press/after-courses-loop-item', '*' );

			/**
			 * Re-add components
			 */
			add_action( 'learn-press/before-courses-loop-item', LP()->template( 'course' )->callback( 'loop/course/thumbnail.php' ), 20 );

			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->callback( 'loop/course/info-begin.php' ), 0 );
			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->func( 'courses_loop_item_price' ), 10 );
			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->callback( 'loop/course/title.php' ), 50 );
			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->callback( 'loop/course/meta-begin.php' ), 100 );
			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->callback( 'loop/course/meta-lessons.php' ), 110 );
			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->callback( 'loop/course/meta-students.php' ), 120 );
			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->callback( 'loop/course/meta-end.php' ), 200 );
			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->callback( 'loop/course/info-end.php' ), 1000 );

			LP()->template( 'course' )->remove( 'learn-press/before-courses-loop', 'courses_top_bar', 10 );
			add_filter( 'learn_press_pagination_args', [ $this, 'change_pagination_args' ] );
		}

		public function change_pagination_args( $args ) {
			$args['prev_text'] = Maxcoach_Templates::get_pagination_prev_text();
			$args['next_text'] = Maxcoach_Templates::get_pagination_next_text();

			return $args;
		}

		public function add_title_bar_meta_course_instructor() {
			/**
			 * @var LP_Course        $course
			 * @var LP_Abstract_User $instructor
			 */
			$course     = LP_Global::course();
			$instructor = $course->get_instructor();
			?>
			<div class="course-instructor post-author">
				<span class="meta-icon meta-image">
					<?php Maxcoach_Helper::e( $instructor->get_profile_picture( '', 32 ) ); ?>
				</span>
				<span class="meta-value"><?php echo ent2ncr( $instructor->get_display_name() ); ?></span>
			</div>
			<?php
		}

		public function add_title_bar_meta_course_duration() {
			$duration = $this->get_duration_translatable();

			if ( empty( $duration ) ) {
				return;
			}
			?>
			<div class="course-duration">
				<span class="meta-icon far fa-clock"></span>
				<span class="meta-value"><?php echo esc_html( $duration ); ?></span>
			</div>
			<?php
		}

		public function add_title_bar_meta_course_lesson() {
			$course      = LP_Global::course();
			$count_items = $course->count_items();

			if ( empty( $count_items ) ) {
				return;
			}

			$count_items = intval( $count_items );
			?>
			<div class="course-lesson">
				<span class="meta-icon far fa-file-alt"></span>
				<span class="meta-value">
					<?php
					printf( _n( '%s Lesson', '%s Lessons', $count_items, 'maxcoach' ), number_format_i18n( $count_items ) );
					?>
				</span>
			</div>
			<?php
		}

		public function add_title_bar_meta_course_student() {
			$course = LP_Global::course();
			if ( ! $course || ! $course->is_required_enroll() ) {
				return;
			}

			$count = intval( $course->count_students() );
			?>

			<div class="course-students">
				<span class="meta-icon far fa-user-alt"></span>
				<span class="meta-value">
					<?php printf( _n( '%s Student', '%s Students', $count, 'maxcoach' ), number_format_i18n( $count ) ); ?>
				</span>
			</div>
			<?php
		}

		public function body_classes( $classes ) {
			if ( is_singular( self::POST_TYPE ) ) {
				$layout = $this->get_single_layout();

				$classes[] = 'single-course-layout-' . $layout;
			}

			return $classes;
		}

		public function is_single_lessons() {
			global $wp_query;

			if ( $wp_query->is_single
			     && ! empty( $wp_query->query_vars['post_type'] ) && 'lp_course' === $wp_query->query_vars['post_type']
			     && ! empty( $wp_query->query_vars['item-type'] ) && in_array( $wp_query->query_vars['item-type'], [
					'lp_lesson',
					'lp_quiz',
				] )
			) {
				return true;
			}

			return false;
		}

		public function change_plugin_style( $handles ) {
			/**
			 * Remove font awesome to avoid duplicate enqueue.
			 */
			unset( $handles['font-awesome-5-all'] );

			/**
			 * Override frontend style.
			 */
			if ( isset( $handles['learnpress'] ) ) {
				if ( $handles['learnpress'] instanceof LP_Asset_Key ) { // Fix in LP 4.1.3
					$handles['learnpress'] = new LP_Asset_Key( MAXCOACH_THEME_URI . '/learnpress.css', [], [], 0 );
				} else {
					$handles['learnpress']['url'] = MAXCOACH_THEME_URI . '/learnpress.css';
					// Load on all pages.
					$handles['learnpress']['screens'] = '*';
				}
			}

			return $handles;
		}

		public function enqueue_frontend_scripts() {
			wp_dequeue_style( 'font-awesome-5-all' );
		}

		/**
		 * Change components for single pages.
		 */
		public function change_single_components() {
			LP()->template( 'course' )->remove( 'learn-press/course-content-summary', '*' );

			add_action( 'learn-press/course-content-summary',
				LP()->template( 'course' )->func( 'user_progress' ),
				10
			);

			add_action( 'learn-press/course-content-summary', LP()->template( 'course' )->func( 'course_extra_boxes' ), 40 );

			add_action( 'learn-press/course-content-summary',
				LP()->template( 'course' )->callback( 'single-course/tabs/tabs' ),
				60
			);

			// Add title.
			add_action( 'learn-press/before-single-course', 'learn_press_course_title' );

			add_action( 'learn-press/single-course-summary', [ $this, 'entry_course_thumbnail' ], 1 );

			/**
			 * For other layouts
			 */
			add_action( 'learn-press/after-single-course-summary', [ $this, 'single_course_buttons' ], 10 );

			/**
			 * For layout 01 Sticky Bar Features.
			 */
			add_action( 'learn-press/single-sticky-bar-features', [ $this, 'single_course_price' ], 5 );
			add_action( 'learn-press/single-sticky-bar-features', [ $this, 'single_course_meta_start_wrapper' ], 10 );
			add_action( 'learn-press/single-sticky-bar-features', [
				$this,
				'add_single_course_meta_instructor',
			], 20 );
			add_action( 'learn-press/single-sticky-bar-features', [ $this, 'add_single_course_meta_duration' ], 30 );
			add_action( 'learn-press/single-sticky-bar-features', [ $this, 'add_single_course_meta_lesson' ], 40 );
			/**
			 * @see learn_press_course_students()
			 */
			add_action( 'learn-press/single-sticky-bar-features', 'learn_press_course_students', 50 );
			add_action( 'learn-press/single-sticky-bar-features', [ $this, 'add_single_course_meta_language' ], 60 );
			add_action( 'learn-press/single-sticky-bar-features', [ $this, 'add_single_course_meta_time' ], 70 );
			add_action( 'learn-press/single-sticky-bar-features', [ $this, 'single_course_meta_end_wrapper' ], 80 );
			add_action( 'learn-press/single-sticky-bar-features', [ $this, 'single_course_buttons' ], 90 );
			add_action( 'learn-press/after-course-buttons', [ $this, 'entry_sharing' ] );

			// Fix Continue Lesson button show empty link
			add_filter( 'learn-press/current-course-item-permalink', [ $this, 'fix_continue_button_link' ], 10, 3 );
		}

		public function single_course_meta_start_wrapper() {
			echo '<div class="course-meta">';
		}

		public function single_course_meta_end_wrapper() {
			echo '</div>';
		}

		public function single_course_price() {
			learn_press_get_template( 'single-course/price.php' );
		}

		public function single_course_buttons() {
			LP()->template( 'course' )->course_buttons();
		}

		public function fix_continue_button_link( $link, $course_id, $id ) {
			//  Try fix empty link.
			if ( '' === $link ) {
				$user               = learn_press_get_current_user();
				$user_course        = $user->get_course_data( $course_id );
				$items              = $user_course->get_items();
				$continue_lesson_id = 0;

				if ( ! empty( $items ) ) {
					$loop_count = 0;

					foreach ( $items as $item_id => $item ) {
						$loop_count++;

						// Set continue lesson as first lesson if there is no completed lesson.
						if ( 1 === $loop_count ) {
							$continue_lesson_id = $item_id;
						}

						if ( $item->get_status() != 'completed' ) {
							$continue_lesson_id = $item_id;
							break;
						}
					}

					if ( $continue_lesson_id ) {
						$course = learn_press_get_course( $course_id );
						$link   = $course->get_item_link( $continue_lesson_id );

						return $link;
					}
				}
			}

			return $link;
		}

		public function add_fields_to_meta_box( $fields ) {
			$custom_fields = array(
				'maxcoach_course_language' => new LP_Meta_Box_Text_Field(
					esc_html__( 'Language', 'maxcoach' ),
					'',
					'',
					array()
				),
				'maxcoach_course_time'     => new LP_Meta_Box_Text_Field(
					esc_html__( 'Time', 'maxcoach' ),
					'',
					'',
					array()
				),
			);

			$fields = array_merge( $fields, $custom_fields );

			return $fields;
		}

		public function add_single_course_meta_language() {
			$course_language = get_post_meta( get_the_ID(), 'maxcoach_course_language', true );
			if ( empty( $course_language ) ) {
				return;
			}
			?>
			<div class="course-language">
				<span class="meta-label">
					<i class="meta-icon far fa-language"></i>
					<?php esc_html_e( 'Language', 'maxcoach' ); ?>
				</span>
				<span class="meta-value"><?php echo ent2ncr( $course_language ); ?></span>
			</div>
			<?php
		}

		public function add_single_course_meta_time() {
			$course_time = get_post_meta( get_the_ID(), 'maxcoach_course_time', true );
			if ( empty( $course_time ) ) {
				return;
			}
			?>
			<div class="course-time">
				<span class="meta-label">
					<i class="meta-icon far fa-calendar"></i>
					<?php esc_html_e( 'Deadline', 'maxcoach' ); ?>
				</span>
				<span class="meta-value"><?php echo ent2ncr( $course_time ); ?></span>
			</div>
			<?php
		}

		public function add_single_course_meta_instructor() {
			$course     = LP_Global::course();
			$instructor = $course->get_instructor_name();
			?>
			<div class="course-instructor">
				<span class="meta-label">
					<i class="meta-icon far fa-chalkboard-teacher"></i>
					<?php esc_html_e( 'Instructor', 'maxcoach' ); ?>
				</span>
				<span class="meta-value"><?php echo ent2ncr( $instructor ); ?></span>
			</div>
			<?php
		}

		public function add_single_course_meta_duration() {
			$duration = $this->get_duration_translatable();

			if ( empty( $duration ) ) {
				return;
			}
			?>
			<div class="course-duration">
				<span class="meta-label">
					<i class="meta-icon far fa-clock"></i>
					<?php esc_html_e( 'Duration', 'maxcoach' ); ?>
				</span>
				<span class="meta-value"><?php echo esc_html( $duration ); ?></span>
			</div>
			<?php
		}

		/**
		 * Fix duration text can't be translatable.
		 *
		 * @return string translatable duration.
		 */
		public function get_duration_translatable() {
			$course        = LP_Global::course();
			$duration_text = $course->get_data( 'duration' );

			$duration_text_translatable = $duration_text;

			$duration_arr = explode( ' ', $duration_text );
			if ( count( $duration_arr ) === 2 ) {
				$duration_number = intval( $duration_arr[0] );
				$duration_time   = $duration_arr[1];

				switch ( $duration_time ) {
					case 'week' :
						$duration_text_translatable = sprintf( _n( '%s week', '%s weeks', $duration_number, 'maxcoach' ), number_format_i18n( $duration_number ) );
						break;
					case 'day' :
						$duration_text_translatable = sprintf( _n( '%s day', '%s days', $duration_number, 'maxcoach' ), number_format_i18n( $duration_number ) );
						break;
					case 'hour' :
						$duration_text_translatable = sprintf( _n( '%s hour', '%s hours', $duration_number, 'maxcoach' ), number_format_i18n( $duration_number ) );
						break;
					case 'minute' :
						$duration_text_translatable = sprintf( _n( '%s minute', '%s minutes', $duration_number, 'maxcoach' ), number_format_i18n( $duration_number ) );
						break;
				}
			}

			return $duration_text_translatable;
		}

		public function add_single_course_meta_lesson() {
			$course      = LP_Global::course();
			$count_items = $course->count_items();

			if ( empty( $count_items ) ) {
				return;
			}
			?>
			<div class="course-lectures">
				<span class="meta-label">
					<i class="meta-icon far fa-file-alt"></i>
					<?php esc_html_e( 'Lectures', 'maxcoach' ); ?>
				</span>
				<span class="meta-value"><?php echo ent2ncr( $count_items ); ?></span>
			</div>
			<?php
		}

		public function entry_sharing() {
			if ( ! is_singular( self::POST_TYPE ) || ! class_exists( 'InsightCore' ) ) {
				return;
			}

			$social_sharing = Maxcoach::setting( 'social_sharing_item_enable' );
			if ( empty( $social_sharing ) ) {
				return;
			}

			$layout = $this->get_single_layout();
			?>
			<div class="entry-course-share">
				<div class="post-share style-02">
					<div class="share-media">

						<?php if ( '01' === $layout ) : ?>
							<div class="share-label">
								<?php esc_html_e( 'Share this course', 'maxcoach' ); ?>
							</div>
							<span class="share-icon far fa-share-alt"></span>

							<div class="share-list">
								<?php Maxcoach_Templates::get_sharing_list(); ?>
							</div>
						<?php else : ?>
							<?php Maxcoach_Templates::render_button( [
								'text'       => esc_html__( 'Share this course', 'maxcoach' ),
								'link'       => [
									'url' => 'javascript:void(0)',
								],
								'icon'       => 'far fa-share-alt',
								'icon_align' => 'right',
							] ); ?>

							<div class="share-list">
								<?php Maxcoach_Templates::get_sharing_list(); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Remove sidebar if single layout is layout '01 => Sticky Features Bar' style.
		 *
		 * @param $sidebar
		 *
		 * @return string
		 */
		public function remove_sidebar_for_sticky_layout( $sidebar ) {
			$layout = $this->get_single_layout();

			if ( is_singular( self::POST_TYPE ) && '01' === $layout ) {
				return 'none';
			}

			return $sidebar;
		}

		/**
		 * Get single course layout.
		 *
		 * @return string $layout
		 */
		public function get_single_layout() {
			$layout = Maxcoach_Helper::get_post_meta( 'single_course_layout', '' );

			if ( empty( $layout ) ) {
				$layout = Maxcoach::setting( 'single_course_layout' );
			}

			return $layout;
		}

		/**
		 * Check LearnPress plugin activated.
		 *
		 * @return boolean true if plugin activated
		 */
		public function is_activated() {
			if ( class_exists( 'LearnPress' ) ) {
				return true;
			}

			return false;
		}

		function get_categories( $args = array() ) {
			$defaults = array(
				'all' => true,
			);
			$args     = wp_parse_args( $args, $defaults );
			$terms    = get_terms( array(
				'taxonomy' => self::TAXONOMY_CATEGORY,
			) );
			$results  = array();

			if ( $args['all'] === true ) {
				$results['-1'] = esc_html__( 'All', 'maxcoach' );
			}

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$results[ $term->slug ] = $term->name;
				}
			}

			return $results;
		}

		function get_tags( $args = array() ) {
			$defaults = array(
				'all' => true,
			);
			$args     = wp_parse_args( $args, $defaults );
			$terms    = get_terms( array(
				'taxonomy' => self::TAXONOMY_TAGS,
			) );
			$results  = array();

			if ( $args['all'] === true ) {
				$results['-1'] = esc_html__( 'All', 'maxcoach' );
			}

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$results[ $term->slug ] = $term->name;
				}
			}

			return $results;
		}

		/**
		 * Check if current page is category or tag pages
		 */
		function is_taxonomy() {
			return is_tax( get_object_taxonomies( self::POST_TYPE ) );
		}

		/**
		 * Check if current page is tag pages
		 *
		 * @note
		 * For some reasons Learnpress didn't use archive template.
		 * So normal is tag will return wrong value.
		 * Instead of use constant from plugin.
		 *
		 */
		function is_tag() {
			//return is_tax( self::TAXONOMY_TAGS );
			return defined( 'LEARNPRESS_IS_TAG' ) && LEARNPRESS_IS_TAG;
		}

		/**
		 * Check if current page is category pages
		 */
		function is_category() {
			//return is_tax( self::TAXONOMY_CATEGORY );
			return defined( 'LEARNPRESS_IS_CATEGORY' ) && LEARNPRESS_IS_CATEGORY;
		}

		/**
		 * Check if current page is archive pages
		 */
		function is_archive() {
			return $this->is_taxonomy() || is_post_type_archive( self::POST_TYPE );
		}

		function has_tag() {
			if ( has_term( '', self::TAXONOMY_TAGS ) ) {
				return true;
			}

			return false;
		}

		function has_category() {
			if ( has_term( '', self::TAXONOMY_CATEGORY ) ) {
				return true;
			}

			return false;
		}

		public function the_loop_categories() {
			?>
			<div class="course-categories">
				<?php echo get_the_term_list( get_the_ID(), self::TAXONOMY_CATEGORY, '', ', ', '' ); ?>
			</div>
			<?php
		}

		public function the_loop_meta_categories() {
			?>
			<div class="meta-item course-categories">
				<span class="meta-icon far fa-folders"></span>
				<?php echo get_the_term_list( get_the_ID(), self::TAXONOMY_CATEGORY, '', ', ', '' ); ?>
			</div>
			<?php
		}

		public function the_loop_price() {
			$course          = LP_Global::course();
			$wrapper_classes = 'course-price';

			if ( $course->is_free() ) {
				$wrapper_classes .= ' course-free';
			}
			?>
			<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
				<?php if ( $price_html = $course->get_price_html() ) { ?>
					<?php
					if ( $course->get_origin_price() != $course->get_price() ) {
						$origin_price_html = $course->get_origin_price_html();
						echo '<span class="origin-price">' . $origin_price_html . '</span>';
					}
					?>

					<?php echo '<span class="price">' . $price_html . '</span>'; ?>
				<?php } ?>
			</div>
			<?php
		}

		/**
		 * Custom price template used for caption style 01.
		 * Edit free price with value + badge.
		 */
		public function the_loop_custom_price() {
			$course = LP_Global::course();

			$price      = $course->get_price();
			$price      = learn_press_format_price( $price, true );
			$price_html = apply_filters( 'learn_press_course_price_html', $price, $course );
			?>

			<?php if ( $course->is_free() ): ?>
				<div class="course-price-badge">
					<?php
					$free_price = apply_filters( 'learn_press_course_price_html_free', __( 'Free', 'maxcoach' ), $course );
					echo esc_html( $free_price );
					?>
				</div>
			<?php endif; ?>

			<div class="course-price">
				<?php if ( $price_html ) { ?>
					<?php
					if ( $course->get_origin_price() != $course->get_price() ) {
						$origin_price_html = $course->get_origin_price_html();
						echo '<span class="origin-price">' . $origin_price_html . '</span>';
					}
					?>

					<?php echo '<span class="price">' . $price_html . '</span>'; ?>
				<?php } ?>
			</div>
			<?php
		}

		public function the_loop_duration() {
			$duration = $this->get_duration_translatable();

			if ( empty( $duration ) ) {
				return;
			}
			?>
			<div class="meta-item course-duration">
				<span class="meta-icon far fa-clock"></span>
				<span class="meta-value"><?php echo esc_html( $duration ); ?></span>
			</div>
			<?php
		}

		public function the_loop_lessons() {
			$course      = LP_Global::course();
			$count_items = $course->count_items();

			if ( empty( $count_items ) ) {
				return;
			}

			$count_items = intval( $count_items );
			?>
			<div class="meta-item course-lesson">
				<span class="meta-icon far fa-file-alt"></span>
				<span class="meta-value">
					<?php printf( _n( '%s Lesson', '%s Lessons', $count_items, 'maxcoach' ), number_format_i18n( $count_items ) ); ?>
				</span>
			</div>
			<?php
		}

		public function the_loop_students() {
			$course = LP_Global::course();
			if ( ! $course || ! $course->is_required_enroll() ) {
				return;
			}

			$count = intval( $course->count_students() );
			?>

			<div class="meta-item course-students">
				<span class="meta-icon far fa-user-alt"></span>
				<span class="meta-value">
					<?php printf( _n( '%s Student', '%s Students', $count, 'maxcoach' ), number_format_i18n( $count ) ); ?>
				</span>
			</div>
			<?php
		}

		public function the_loop_instructor() {
			/**
			 * @var LP_Course        $course
			 * @var LP_Abstract_User $instructor
			 */
			$course     = LP_Global::course();
			$instructor = $course->get_instructor();
			?>
			<div class="course-instructor">
				<span class="instructor-avatar">
					<?php Maxcoach_Helper::e( $instructor->get_profile_picture( '', 32 ) ); ?>
				</span>
				<span class="instructor-name"><?php echo ent2ncr( $instructor->get_display_name() ); ?></span>
			</div>
			<?php
		}

		public function the_loop_buttons() {
			if ( function_exists( 'learn_press_course_loop_item_buttons' ) ) {
				learn_press_course_loop_item_buttons();
			}
		}

		/**
		 * Get url account page
		 */
		public function get_login_page_url( $redirect_url = '' ) {
			$page = get_page_by_path( 'account' );
			if ( $page ) {
				return ! empty( $redirect_url ) ? add_query_arg( 'redirect_to', urlencode( $redirect_url ), get_permalink( $page[0] ) ) : get_permalink( $page->ID );
			}

			return wp_login_url();
		}

		/**
		 * Get membership level price
		 *
		 * @param mixed $level Membership level.
		 */
		public function get_membership_level_price( $level ) {
			if ( pmpro_isLevelFree( $level ) ): ?>
				<?php esc_html_e( 'Free', 'maxcoach' ); ?>
			<?php else: ?>
				<?php
				global $pmpro_currency, $pmpro_currency_symbol, $pmpro_currencies;

				$price = $level->initial_payment;
				//start with the price formatted with two decimals
				$formatted = number_format( (double) $price, 0 );

				//settings stored in array?
				if ( ! empty( $pmpro_currencies[ $pmpro_currency ] ) && is_array( $pmpro_currencies[ $pmpro_currency ] ) ) {
					//format number do decimals, with decimal_separator and thousands_separator
					$formatted = number_format( $price,
						( isset( $pmpro_currencies[ $pmpro_currency ]['decimals'] ) ? (int) $pmpro_currencies[ $pmpro_currency ]['decimals'] : 2 ),
						( isset( $pmpro_currencies[ $pmpro_currency ]['decimal_separator'] ) ? $pmpro_currencies[ $pmpro_currency ]['decimal_separator'] : '.' ),
						( isset( $pmpro_currencies[ $pmpro_currency ]['thousands_separator'] ) ? $pmpro_currencies[ $pmpro_currency ]['thousands_separator'] : ',' )
					);

					//which side is the symbol on?
					if ( ! empty( $pmpro_currencies[ $pmpro_currency ]['position'] ) && $pmpro_currencies[ $pmpro_currency ]['position'] == 'left' ) {
						$formatted = $pmpro_currency_symbol . $formatted;
					} else {
						$formatted = $formatted . $pmpro_currency_symbol;
					}
				} else {
					$formatted = $pmpro_currency_symbol . $formatted;
				}    //default to symbol on the left

				//filter
				$cost_text = apply_filters( 'pmpro_format_price', $formatted, $price, $pmpro_currency, $pmpro_currency_symbol );

				echo ent2ncr( $cost_text ); ?>
			<?php endif;
		}

		public function get_course_image( $course_id, $size = '480x298' ) {
			if ( ! $course = learn_press_get_course( $course_id ) ) {
				return '';
			}

			$image = '';

			// Used thumbnail size setting.
			$image_size = LP()->settings->get( 'course_thumbnail_dimensions' );

			if ( ! empty( $image_size ) && ( ! empty( $image_size['width'] ) || ! empty( $image_size['height'] ) ) ) {
				$image_size_width = $image_size_height = 9999;

				if ( ! empty( $image_size['width'] ) ) {
					$image_size_width = $image_size['width'];
				}

				if ( ! empty( $image_size['height'] ) ) {
					$image_size_height = $image_size['height'];
				}

				$size = "{$image_size_width}x{$image_size_height}";
			}

			if ( has_post_thumbnail( $course_id ) ) {
				$image = Maxcoach_Image::get_the_post_thumbnail( [
					'post_id' => $course_id,
					'size'    => $size,
				] );
			} elseif ( ( $parent_id = wp_get_post_parent_id( $course_id ) ) && has_post_thumbnail( $parent_id ) ) {
				$image = Maxcoach_Image::get_the_post_thumbnail( [
					'post_id' => $parent_id,
					'size'    => $size,
				] );
			}

			return $image;
		}

		public function entry_course_thumbnail() {
			$thumbnail_enable = Maxcoach::setting( 'single_course_featured_image_enable' );

			if ( '1' !== $thumbnail_enable || ! has_post_thumbnail() ) {
				return;
			}
			?>
			<div class="entry-course-thumbnail">
				<?php
				Maxcoach_Image::the_post_thumbnail( [
					'size' => '770x450',
				] );
				?>
			</div>
			<?php
		}

		public function get_the_categories() {
			$id    = get_the_ID();
			$terms = get_the_terms( $id, self::TAXONOMY_CATEGORY );

			return $terms;
		}

		public function get_the_tags() {
			$id    = get_the_ID();
			$terms = get_the_terms( $id, self::TAXONOMY_TAGS );

			return $terms;
		}

		public function get_related_courses( $args ) {
			$defaults = array(
				'post_id'      => '',
				'number_posts' => 3,
			);
			$args     = wp_parse_args( $args, $defaults );

			if ( $args['number_posts'] <= 0 || $args['post_id'] === '' ) {
				return false;
			}

			$related_by = Maxcoach::setting( 'course_related_by' );

			if ( empty( $related_by ) ) {
				return false;
			}

			$query_args = array(
				'post_type'      => self::POST_TYPE,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => $args['number_posts'],
				'post__not_in'   => array( $args['post_id'] ),
				'no_found_rows'  => true,
			);

			if ( in_array( 'category', $related_by, true ) ) {
				$terms = $this->get_the_categories();

				if ( $terms && ! is_wp_error( $terms ) ) {

					$term_ids = array();

					foreach ( $terms as $category ) {
						if ( $category->parent === 0 ) {
							$term_ids[] = $category->term_id;
						} else {
							$term_ids[] = $category->parent;
							$term_ids[] = $category->term_id;
						}
					}

					// Remove duplicate values from the array.
					$unique_term_ids = array_unique( $term_ids );

					if ( empty( $query_args['tax_query'] ) ) {
						$query_args['tax_query'] = [];
					}

					$query_args['tax_query'][] = array(
						'taxonomy'         => self::TAXONOMY_CATEGORY,
						'terms'            => $unique_term_ids,
						'include_children' => false,
					);
				}
			}

			if ( in_array( 'tags', $related_by, true ) ) {
				$terms = $this->get_the_tags();

				if ( $terms && ! is_wp_error( $terms ) ) {
					$term_ids = array();

					foreach ( $terms as $tag ) {
						if ( $tag->parent === 0 ) {
							$term_ids[] = $tag->term_id;
						} else {
							$term_ids[] = $tag->parent;
							$term_ids[] = $tag->term_id;
						}
					}

					// Remove duplicate values from the array.
					$unique_term_ids = array_unique( $term_ids );

					if ( empty( $query_args['tax_query'] ) ) {
						$query_args['tax_query'] = [];
					}

					$query_args['tax_query'][] = array(
						'taxonomy'         => self::TAXONOMY_TAGS,
						'terms'            => $unique_term_ids,
						'include_children' => false,
					);
				}
			}

			if ( isset( $query_args['tax_query'] ) && count( $query_args['tax_query'] ) > 1 ) {
				$query_args['tax_query']['relation'] = 'OR';
			}

			$query = new WP_Query( $query_args );

			wp_reset_postdata();

			return $query;
		}

		public function update_db_252() {
			$message = sprintf(
				'<strong>%1$s</strong><br/><a href="%2$s" class="button">Go to Update</a>',
				__( 'Maxcoach update - We need to update your database to the latest version.', 'maxcoach' ),
				wp_nonce_url( admin_url( 'themes.php' ), 'theme_update_database', 'maxcoach_nonce' )
			);

			printf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message );
		}

		public function maxcoach_update_db() {
			$maxcoach_db_version = get_option( 'maxcoach_db_version' );
			if ( empty( $maxcoach_db_version ) || version_compare( $maxcoach_db_version, '2.5.2', '<' ) ) {
				if ( isset( $_GET['maxcoach_nonce'] ) && wp_verify_nonce( $_GET['maxcoach_nonce'], 'theme_update_database' ) ) {
					$the_query = new WP_Query( [
						'posts_per_page' => -1,
						'post_type'      => self::POST_TYPE,
					] );

					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();

							$course_id = get_the_ID();

							$this->save_course_sale_price( $course_id );
						}

						wp_reset_postdata();
					}

					update_option( 'maxcoach_db_version', MAXCOACH_THEME_VERSION );
				}
			}
		}
	}

	Maxcoach_LP_Course::instance()->initialize();
}
