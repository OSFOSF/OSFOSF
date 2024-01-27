<?php
/**
 * Template for the profile page
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/profile.php
 *
 * Page: "Profile"
 *
 * @version 2.6.1
 *
 * @var string $mode
 * @var int    $form_id
 * @var array  $args
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="um <?php echo esc_attr( $this->get_class( $mode ) ); ?> um-<?php echo esc_attr( $form_id ); ?> um-role-<?php echo esc_attr( um_user( 'role' ) ); ?> ">

	<div class="um-form" data-mode="<?php echo esc_attr( $mode ) ?>">

		<?php
		/**
		 * UM hook
		 *
		 * @type action
		 * @title um_profile_before_header
		 * @description Some actions before profile form header
		 * @input_vars
		 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
		 * @change_log
		 * ["Since: 2.0"]
		 * @usage add_action( 'um_profile_before_header', 'function_name', 10, 1 );
		 * @example
		 * <?php
		 * add_action( 'um_profile_before_header', 'my_profile_before_header', 10, 1 );
		 * function my_profile_before_header( $args ) {
		 *     // your code here
		 * }
		 * ?>
		 */
		do_action( 'um_profile_before_header', $args );

		if ( um_is_on_edit_profile() ) { ?>
			<form method="post" action="">
		<?php }

		/**
		 * UM hook
		 *
		 * @type action
		 * @title um_profile_header_cover_area
		 * @description Profile header cover area
		 * @input_vars
		 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
		 * @change_log
		 * ["Since: 2.0"]
		 * @usage add_action( 'um_profile_header_cover_area', 'function_name', 10, 1 );
		 * @example
		 * <?php
		 * add_action( 'um_profile_header_cover_area', 'my_profile_header_cover_area', 10, 1 );
		 * function my_profile_header_cover_area( $args ) {
		 *     // your code here
		 * }
		 * ?>
		 */
		// do_action( 'um_profile_header_cover_area', $args );

		/**
		 * UM hook
		 *
		 * @type action
		 * @title um_profile_header
		 * @description Profile header area
		 * @input_vars
		 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
		 * @change_log
		 * ["Since: 2.0"]
		 * @usage add_action( 'um_profile_header', 'function_name', 10, 1 );
		 * @example
		 * <?php
		 * add_action( 'um_profile_header', 'my_profile_header', 10, 1 );
		 * function my_profile_header( $args ) {
		 *     // your code here
		 * }
		 * ?>
		 */
		do_action( 'um_profile_header', $args );
		
		 
		$current_user_id = get_current_user_id();
		um_fetch_user($current_user_id);
		$current_username = um_user('user_login');

		// Format current_username
		$current_username = str_replace('.', '-', $current_username);
		$current_username = str_replace('@', '', $current_username);
		$current_username = str_replace(' ', '+', $current_username);

		// Get the profile user username
		$profile_id = um_profile_id();
		um_fetch_user($profile_id);
		$profile_username = um_user('user_login');

		// Format profile_username
		$profile_username = str_replace('.', '-', $profile_username);
		$profile_username = str_replace('@', '', $profile_username);
		$profile_username = str_replace(' ', '+', $profile_username);
		 
		$userid = um_profile_id();
		um_fetch_user($profile_id);
		$user_website_url = um_user('user_url');

		if (!is_user_logged_in()) {
			$login_link = um_get_core_page('login');
			$contactBtn = '<a id="contact" href="'.$login_link.'" data-userid='.$userid.' class="elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in" role="button"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Send Email</span></span></a>';
//dhammarato contact .send email
			$composeBtn = '<a id="compose" href="'.$login_link.'" class="elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in" role="button"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Message</span></span></a>';
		} else {
			$compose_url = '';
			$composeBtn = '';
			
			
			 if ($current_username != $profile_username) {
			 // They are different, so let's construct the URL
			 $compose_url = "https://opensanghafoundation.org/newsite/members/{$current_username}/messages/compose/?r={$profile_username}";
				$composeBtn = '<a id="compose-btn" href="'.$compose_url.'" class="elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in" role="button"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Message</span></span></a>';
			
		 }

// dhammarato start code to fix button // contact button to send email or something  >Contact< and send email
		 $user_email       = um_user('user_email');
		 $testemail = "soft.in";
		$goto = "/newsite/test/Email-bad-2/?r=$profile_id"; 
		$goem = "window.open('/newsite/test/email-get/?r=$profile_id','popup', 'width=500,height=700,left=' + (screen.width - 500) / 2 + ',top=' + (screen.height - 600) / 4)";
		   if (strpos($user_email, $testemail) !== false) { // If yes, use header to redirect to the URL
			$contactBtn = "<a id=\"contact-btn\" href=\"#\" onclick=\"window.location.href='$goto'\" data-userid=\"$userid\" class=\"elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in\" role=\"button\"><span class=\"elementor-button-content-wrapper\"><span class=\"elementor-button-text\">No Email</span></span></a>";
			} else {
			$contactBtn = "<a id=\"contact-btn\" href=\"#\" onclick=\"".$goem."\" data-userid=\"".$userid."\" class=\"elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in\" role=\"button\"><span class=\"elementor-button-content-wrapper\"><span class=\"elementor-button-text\">Send Email</span></span></a>";
			}
	



//		$contactBtn = '<a id="contact-btn" href="#" onclick="osfEnquiry('.$userid.')" data-userid='.$userid.' class="elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in" role="button"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Send Email</span></span></a>';

		
		}

		echo '<div class="osf-user-contact">'.$contactBtn.$composeBtn.'<a href="'.$user_website_url.'" class="elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in" role="button"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Website</span></span></a></div>';

				
					
		/**
		 * UM hook
		 *
		 * @type filter
		 * @title um_profile_navbar_classes
		 * @description Additional classes for profile navbar
		 * @input_vars
		 * [{"var":"$classes","type":"string","desc":"UM Posts Tab query"}]
		 * @change_log
		 * ["Since: 2.0"]
		 * @usage
		 * <?php add_filter( 'um_profile_navbar_classes', 'function_name', 10, 1 ); ?>
		 * @example
		 * <?php
		 * add_filter( 'um_profile_navbar_classes', 'my_profile_navbar_classes', 10, 1 );
		 * function my_profile_navbar_classes( $classes ) {
		 *     // your code here
		 *     return $classes;
		 * }
		 * ?>
		 */
		$classes = apply_filters( 'um_profile_navbar_classes', '' ); ?>

		<div class="um-profile-navbar <?php echo esc_attr( $classes ); ?>">
			<?php
			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_profile_navbar
			 * @description Profile navigation bar
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_profile_navbar', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_profile_navbar', 'my_profile_navbar', 10, 1 );
			 * function my_profile_navbar( $args ) {
			 *     // your code here
			 * }
			 * ?>
			 */
			do_action( 'um_profile_navbar', $args ); ?>
			<div class="um-clear"></div>
		</div>

		<?php
		/**
		 * UM hook
		 *
		 * @type action
		 * @title um_profile_menu
		 * @description Profile menu
		 * @input_vars
		 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
		 * @change_log
		 * ["Since: 2.0"]
		 * @usage add_action( 'um_profile_menu', 'function_name', 10, 1 );
		 * @example
		 * <?php
		 * add_action( 'um_profile_menu', 'my_profile_navbar', 10, 1 );
		 * function my_profile_navbar( $args ) {
		 *     // your code here
		 * }
		 * ?>
		 */
		do_action( 'um_profile_menu', $args );
        if(get_current_user_id() == $userid){
		if ( um_is_on_edit_profile() || UM()->user()->preview ) {

			$nav = 'main';
			$subnav = UM()->profile()->active_subnav();
			$subnav = ! empty( $subnav ) ? $subnav : 'default'; ?>

			<div class="<?php echo esc_attr( $nav . ' ' . $nav . '-' . $subnav ); ?>">

				<?php
				/**
				 * UM hook
				 *
				 * @type action
				 * @title um_profile_content_{$nav}
				 * @description Custom hook to display tabbed content
				 * @input_vars
				 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
				 * @change_log
				 * ["Since: 2.0"]
				 * @usage add_action( 'um_profile_content_{$nav}', 'function_name', 10, 1 );
				 * @example
				 * <?php
				 * add_action( 'um_profile_content_{$nav}', 'my_profile_content', 10, 1 );
				 * function my_profile_content( $args ) {
				 *     // your code here
				 * }
				 * ?>
				 */
				do_action("um_profile_content_{$nav}", $args);

				/**
				 * UM hook
				 *
				 * @type action
				 * @title um_profile_content_{$nav}_{$subnav}
				 * @description Custom hook to display tabbed content
				 * @input_vars
				 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
				 * @change_log
				 * ["Since: 2.0"]
				 * @usage add_action( 'um_profile_content_{$nav}_{$subnav}', 'function_name', 10, 1 );
				 * @example
				 * <?php
				 * add_action( 'um_profile_content_{$nav}_{$subnav}', 'my_profile_content', 10, 1 );
				 * function my_profile_content( $args ) {
				 *     // your code here
				 * }
				 * ?>
				 */
				do_action( "um_profile_content_{$nav}_{$subnav}", $args ); ?>

				<div class="clear"></div>
			</div>

			<?php if ( ! UM()->user()->preview ) { ?>

			</form>

			<?php }
		} else {
			$menu_enabled = UM()->options()->get( 'profile_menu' );
			$tabs = UM()->profile()->tabs_active();

			$nav = UM()->profile()->active_tab();
			$subnav = UM()->profile()->active_subnav();
			$subnav = ! empty( $subnav ) ? $subnav : 'default';

			if ( $menu_enabled || ! empty( $tabs[ $nav ]['hidden'] ) ) { ?>

				<div class="<?php echo esc_attr( $nav . ' ' . $nav . '-' . $subnav ); ?>">

					<?php
					// Custom hook to display tabbed content
					/**
					 * UM hook
					 *
					 * @type action
					 * @title um_profile_content_{$nav}
					 * @description Custom hook to display tabbed content
					 * @input_vars
					 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
					 * @change_log
					 * ["Since: 2.0"]
					 * @usage add_action( 'um_profile_content_{$nav}', 'function_name', 10, 1 );
					 * @example
					 * <?php
					 * add_action( 'um_profile_content_{$nav}', 'my_profile_content', 10, 1 );
					 * function my_profile_content( $args ) {
					 *     // your code here
					 * }
					 * ?>
					 */
					do_action("um_profile_content_{$nav}", $args);

					/**
					 * UM hook
					 *
					 * @type action
					 * @title um_profile_content_{$nav}_{$subnav}
					 * @description Custom hook to display tabbed content
					 * @input_vars
					 * [{"var":"$args","type":"array","desc":"Profile form shortcode arguments"}]
					 * @change_log
					 * ["Since: 2.0"]
					 * @usage add_action( 'um_profile_content_{$nav}_{$subnav}', 'function_name', 10, 1 );
					 * @example
					 * <?php
					 * add_action( 'um_profile_content_{$nav}_{$subnav}', 'my_profile_content', 10, 1 );
					 * function my_profile_content( $args ) {
					 *     // your code here
					 * }
					 * ?>
					 */
					do_action( "um_profile_content_{$nav}_{$subnav}", $args ); ?>

	
					<div class="clear"></div>
				</div>

			<?php }
		}
        } else {
//echo '===============PPPPPPPPP====3333333333333333==========';
// dfhammarato new code goes here	
echo do_shortcode('	[xyz-ics snippet="User-Profile-1"]');
echo do_shortcode('	[xyz-ics snippet="User-Profile-Bud"] ');
echo do_shortcode('	[xyz-ics snippet="User-Profile-Social"]');
echo do_shortcode('	[xyz-ics snippet="User-Profile-Photos"]');
echo do_shortcode('	[xyz-ics snippet="User-Profile-Gmap"]');

// dhammarato end code 
        }
		do_action( 'um_profile_footer', $args ); ?>
	</div>
</div>
