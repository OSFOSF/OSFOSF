<?php
/**
 * Define constant
 */
$theme = wp_get_theme();

if ( ! empty( $theme['Template'] ) ) {
	$theme = wp_get_theme( $theme['Template'] );
}

if ( ! defined( 'DS' ) ) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

define( 'MAXCOACH_THEME_NAME', $theme['Name'] );
define( 'MAXCOACH_THEME_VERSION', $theme['Version'] );
define( 'MAXCOACH_THEME_DIR', get_template_directory() );
define( 'MAXCOACH_THEME_URI', get_template_directory_uri() );
define( 'MAXCOACH_THEME_ASSETS_URI', get_template_directory_uri() . '/assets' );
define( 'MAXCOACH_THEME_IMAGE_URI', MAXCOACH_THEME_ASSETS_URI . '/images' );
define( 'MAXCOACH_FRAMEWORK_DIR', get_template_directory() . DS . 'framework' );
define( 'MAXCOACH_CUSTOMIZER_DIR', MAXCOACH_THEME_DIR . DS . 'customizer' );
define( 'MAXCOACH_PROTOCOL', is_ssl() ? 'https' : 'http' );
define( 'MAXCOACH_IS_RTL', is_rtl() ? true : false );

define( 'MAXCOACH_ELEMENTOR_DIR', get_template_directory() . DS . 'elementor' );
define( 'MAXCOACH_ELEMENTOR_URI', get_template_directory_uri() . '/elementor' );
define( 'MAXCOACH_ELEMENTOR_ASSETS', get_template_directory_uri() . '/elementor/assets' );

/**
 * Load Framework.
 */
require_once MAXCOACH_FRAMEWORK_DIR . '/class-debug.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-aqua-resizer.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-performance.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-static.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-init.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-helper.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-functions.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-global.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-actions-filters.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-kses.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-notices.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-admin.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-compatible.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-customize.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-nav-menu.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-enqueue.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-image.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-minify.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-color.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-datetime.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-import.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-kirki.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-metabox.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-plugins.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-custom-css.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-templates.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-walker-nav-menu.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-widgets.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-top-bar.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-header.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-title-bar.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-footer.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-post-type.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-post-type-blog.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-post-type-portfolio.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-post-type-lp-course.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-post-type-lp-lesson.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-post-type-event.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-post-type-zoom-meeting.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-booking-search-box.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-membership.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-woo.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-content-protected.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/tgm-plugin-activation.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/tgm-plugin-registration.php';
require_once MAXCOACH_FRAMEWORK_DIR . '/class-tha.php';

require_once MAXCOACH_ELEMENTOR_DIR . '/class-entry.php';

/**
 * Init the theme
 */
Maxcoach_Init::instance()->initialize();
function get_teachers_profiles() {
    // Use get_users to fetch all users.
    // 
    $args = array(
        'role__not_in' => 'administrator',
    );
	
	if (isset($_GET['sfirstname']) || isset($_GET['szip']) || isset($_GET['scity']) || isset($_GET['sstate']) || isset($_GET['scountry']) || isset($_GET['sprofession']) || isset($_GET['smonk']) || isset($_GET['slinkedin']) || isset($_GET['syearsindhamma'])) {
		$args['meta_query'] = array('relation' => 'AND');

		if(isset($_GET['sfirstname']) && !empty($_GET['sfirstname'])){
			$args['meta_query'][] = array(
				'key'     => 'first_name',
				'value'   => sanitize_text_field($_GET['sfirstname']),
				'compare' => 'LIKE'
			);
		}
		
		if(isset($_GET['szip']) && !empty($_GET['szip'])){
			$args['meta_query'][] = array(
				'key'     => 'osf_zip',
				'value'   => sanitize_text_field($_GET['szip']),
				'compare' => 'LIKE'
			);
		}

		if(isset($_GET['scity']) && !empty($_GET['scity'])){
			$args['meta_query'][] = array(
				'key'     => 'osf_city',
				'value'   => sanitize_text_field($_GET['scity']),
				'compare' => 'LIKE'
			);
		}
		if(isset($_GET['sstate']) && !empty($_GET['sstate'])){
			$args['meta_query'][] = array(
				'key'     => 'osf_state',
				'value'   => sanitize_text_field($_GET['sstate']),
				'compare' => 'LIKE'
			);
		}

		if(isset($_GET['scountry']) && !empty($_GET['scountry'])){
			$args['meta_query'][] = array(
				'key'     => 'country',
				'value'   => sanitize_text_field($_GET['scountry']),
				'compare' => 'LIKE'
			);
		}

		if(isset($_GET['sprofession']) && !empty($_GET['sprofession'])){
			$args['meta_query'][] = array(
				'key'     => 'profession',
				'value'   => sanitize_text_field($_GET['sprofession']),
				'compare' => 'LIKE'
			);
		}
		
		if(isset($_GET['scareer']) && !empty($_GET['scareer'])){
			$args['meta_query'][] = array(
				'key'     => 'career',
				'value'   => sanitize_text_field($_GET['scareer']),
				'compare' => 'LIKE'
			);
		}
		
		if(isset($_GET['smonk']) && !empty($_GET['smonk'])){
			$args['meta_query'][] = array(
				'key'     => 'are_you_a_monk',
				'value'   => sanitize_text_field($_GET['smonk']),
				'compare' => 'LIKE'
			);
		}

		if(isset($_GET['slinkedin']) && !empty($_GET['slinkedin'])){
			$args['meta_query'][] = array(
				'key'     => 'linkedin',
				'value'   => sanitize_text_field($_GET['slinkedin']),
				'compare' => 'LIKE'
			);
		}

		if(isset($_GET['syearsindhamma']) && !empty($_GET['syearsindhamma'])){
			$args['meta_query'][] = array(
				'key'     => 'years_in_dhamma',
				'value'   => sanitize_text_field($_GET['syearsindhamma']),
				'compare' => 'LIKE'
			);
		}
	}
    $users = get_users($args);
	
	
	
    // Define a variable to store our user data.
    $output = '';
    $output .= '<div class="osf-teachers-profile"><style>.search-filter-button {
  background: none !important;
  font-weight: 700;
  color: #1463b2 !important;
  text-transform: uppercase;
}
.search-filter-button:hover {
  
  color: #000000 !important;

}
</style>';
    // Loop through each user
    foreach ( $users as $user ) {
        // Set user for Ultimate Member functions.
        um_fetch_user($user->ID);

        // Use get_userdata to get the user's data.
        $user_info = get_userdata( $user->ID );

        // Store the user's data in the output.
        $output .= '<div class="card mb-3">';
        $output .= '<div class="row g-0">';
        $output .= '<div class="col-md-2">';
        // $output .= '<img src="' . um_get_user_avatar_url() . '" class="img-fluid rounded-start" alt="' . esc_attr( $user_info->display_name ) . '">';
		$output .= '<img src="' . get_avatar_url($user->ID) . '" class="img-fluid rounded-start" alt="' . esc_attr( $user_info->display_name ) . '">';
        $output .= '</div>';
        $output .= '<div class="col-md-10">';
        $output .= '<div class="card-body">';
        $output .= '<h5 class="card-title">' . esc_html( $user_info->display_name ) . '</h5>';
        $output .= '<p class="card-text os-scrollable">' . esc_html( $user_info->description ) . '</p>';
        $output .= '<a href="' . um_user_profile_url() . '" class="btn btn-primary search-filter-button">Read more</a>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    $output .= '</div>';
    // Return the user data.
    return $output;
}
add_shortcode('teachersprofiles', 'get_teachers_profiles');
function metform_if_logged_in() {
    if (is_user_logged_in()) {
  // line commented uot to stop popup dhammarato
        return ''; // ' do_shortcode('[metform form_id="6729"]');
    }
    return '';
}
// add_shortcode('metform_logged_in', 'metform_if_logged_in');
function add_custom_script() {
     return '' ;
    ?>
    <div id="popup" class="popup">
        <div class="popup-content">
            <span id="close" class="close">&times;</span>
          <?php
          echo do_shortcode('[metform form_id="6729"]');
        ?>
        </div>
    </div>
    <style>
        .popup {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.popup-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover, .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

    </style>
     <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the button, the popup, the close button and the input field
        var contactBtn = document.getElementById('contact-btn');
        var popup = document.getElementById('popup');
        var closeBtn = document.getElementById('close');
        var inputField = document.getElementById('mf-input-text-f75ce69');

        // When the button is clicked, open the popup and set the input field value to the user ID
        contactBtn.addEventListener('click', function() {
            popup.style.display = 'block';
            var userId = this.getAttribute('data-userid');
            if(inputField) {
                inputField.value = userId;
            }
        });

        // When the close button is clicked, close the popup
        closeBtn.addEventListener('click', function() {
            popup.style.display = 'none';
        });

        // When the user clicks anywhere outside of the popup, close it
        window.addEventListener('click', function(event) {
            if (event.target == popup) {
                popup.style.display = 'none';
            }
        });
        
    });
    function osfEnquiry(userid){
        console.log("HI");
        var inputField = document.getElementById('mf-input-text-f75ce69');
        if(inputField) {
            inputField.value = userid;
        }
    }
    </script>
    <?php
}
add_action('wp_footer', 'add_custom_script');

add_action('metform_after_store_form_data', 'send_email_to_user', 10, 4);

function send_email_to_user($form_id, $form_data, $form_settings, $attributes) {
    //... your existing code here...

    // Serialize form data array to store it in a single option
    $serialized_form_data = maybe_serialize($form_data);

    // Save form data to the 'my_form_data' option
    update_option('my_form_data', $serialized_form_data);
}
if(isset($_GET['ptest'])){
    $stored_form_data = maybe_unserialize(get_option('my_form_data'));
    print_r($stored_form_data);
    $user = get_userdata($stored_form_data['mf-user']);
    print_r($user);
   die();
}

add_action('metform_after_store_form_data', 'send_email_to_contactetd_user', 10, 4);

function send_email_to_contactetd_user($form_id, $form_data, $form_settings, $attributes) {
    if($form_data['id'] == 6729){
        if(isset($form_data['mf-user'])){
            $user_id = $form_data['mf-user'];
            $user = get_userdata($user_id);
            $current_user = wp_get_current_user();
            $display_name = $current_user->display_name;
            $user_email = $current_user->user_email;
// dhammarato soft.in starts here
          // code addinf to message code dhammarato soft.in
$testemail = "soft.in";
$goto = "/newsite/test/user-update/?r=$user->ID";
if (strpos ($used_email, $testemail) !== false) { // If yes, use header to redirect to the URL
	ob_start(); // start output buffering// your code here
	if (!headers_sent()) {header('Location: '.$goto); exit; } else {
//echo "<a href=\"#\"\"window.open($goto,'_blank',)\">$user->ID</a>" ;
echo '<script type="text/javascript">window.location.href="'
    .$goto.'"</script><noscript><meta http-equiv="refresh" 	
    content="0;url='.$goto.'" /></noscript>';
	exit;}
	ob_end_flush();
} else {
// If no, do something else
//echo "The email does not contain $testemail";
//end dhammatato soft.in
// dhammarato end code     end og soft.in           
          $esubject = $form_data['mf-subject'];
			$emessage = $form_data['mf-textarea'];
            if ($user && $user->user_email) {
                $subject = 'New Contact Enquiry for You';
				$message = '<!DOCTYPE html><html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en"><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><!--[if mso]><xml><o:officedocumentsettings><o:pixelsperinch>96</o:pixelsperinch><o:allowpng></o:officedocumentsettings></xml><![endif]--><style>*{box-sizing:border-box}body{margin:0;padding:0}a[x-apple-data-detectors]{color:inherit!important;text-decoration:inherit!important}#MessageViewBody a{color:inherit;text-decoration:none}p{line-height:inherit}.desktop_hide,.desktop_hide table{mso-hide:all;display:none;max-height:0;overflow:hidden}.image_block img+div{display:none}@media (max-width:620px){.desktop_hide table.icons-inner{display:inline-block!important}.icons-inner{text-align:center}.icons-inner td{margin:0 auto}.row-content{width:100%!important}.mobile_hide{display:none}.stack .column{width:100%;display:block}.mobile_hide{min-height:0;max-height:0;max-width:0;overflow:hidden;font-size:0}.desktop_hide,.desktop_hide table{display:table!important;max-height:none!important}}</style></head><body style="background-color:#fff;margin:0;padding:0;-webkit-text-size-adjust:none;text-size-adjust:none"><table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff"><tbody><tr><td><table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0"><tbody><tr><td><table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:600px" width="600"><tbody><tr><td class="column column-1" width="50%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0"><table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0"><tr><td class="pad" style="width:100%;padding-right:0;padding-left:0"><div class="alignment" align="center" style="line-height:10px"><img src="https://opensanghafoundation.org/wp-content/uploads/2023/05/osf-logo-horizontal.webp" style="display:block;height:auto;border:0;width:300px;max-width:100%" width="300" alt="" title=""></div></td></tr></table></td><td class="column column-2" width="50%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0"><table class="button_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0"><tr><td class="pad"><div class="alignment" align="center"><!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" style="height:42px;width:198px;v-text-anchor:middle" arcsize="10%" stroke="false" fillcolor="#187fb7"><w:anchorlock><v:textbox inset="0px,0px,0px,0px"><center style="color:#fff;font-family:Arial,sans-serif;font-size:16px"><![endif]--><div style="text-decoration:none;display:inline-block;color:#fff;background-color:#187fb7;border-radius:4px;width:auto;border-top:0 solid transparent;font-weight:undefined;border-right:0 solid transparent;border-bottom:0 solid transparent;border-left:0 solid transparent;padding-top:5px;padding-bottom:5px;font-family:Arial,Helvetica,sans-serif;font-size:16px;text-align:center;mso-border-alt:none;word-break:keep-all"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;letter-spacing:normal"><span style="margin:0;word-break:break-word;line-height:32px">Find Dhamma Friends</span></span></div><!--[if mso]><![endif]--></div></td></tr></table></td></tr></tbody></table></td></tr></tbody></table><table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0"><tbody><tr><td><table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:600px" width="600"><tbody><tr><td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0"><table class="heading_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0"><tr><td class="pad"><h1 style="margin:0;color:#187fb7;direction:ltr;font-family:Arial,Helvetica,sans-serif;font-size:26px;font-weight:700;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0"><span class="tinyMce-placeholder">New Contact Enquiry</span></h1></td></tr></table><table class="text_block block-2" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word"><tr><td class="pad"><div style="font-family:sans-serif"><div class style="font-size:12px;font-family:Arial,Helvetica,sans-serif;mso-line-height-alt:14.399999999999999px;color:#555;line-height:1.2"><p style="margin:0;font-size:14px;text-align:left;mso-line-height-alt:16.8px"><span style="font-size:17px"><strong>Name:</strong>'.$display_name.'</span></p></div></div></td></tr></table><table class="paragraph_block block-3" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word"><tr><td class="pad"><div style="color:#101112;direction:ltr;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:400;letter-spacing:0;line-height:120%;text-align:left;mso-line-height-alt:19.2px"><p style="margin:0"><strong>Email:</strong>'.$user_email.'</p></div></td></tr></table><table class="paragraph_block block-4" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word"><tr><td class="pad"><div style="color:#101112;direction:ltr;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:400;letter-spacing:0;line-height:120%;text-align:left;mso-line-height-alt:19.2px"><p style="margin:0"><strong>Subject:</strong>'.$esubject.'</p></div></td></tr></table><table class="paragraph_block block-5" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word"><tr><td class="pad"><div style="color:#101112;direction:ltr;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:400;letter-spacing:0;line-height:120%;text-align:left;mso-line-height-alt:19.2px"><p style="margin:0"><strong>Message:</strong>'.$emessage.'</p></div></td></tr></table></td></tr></tbody></table></td></tr></tbody></table><table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0"><tbody><tr><td><table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#187fb7;color:#000;width:600px" width="600"><tbody><tr><td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0"><table class="text_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word"><tr><td class="pad"><div style="font-family:sans-serif"><div class style="font-size:12px;font-family:Arial,Helvetica,sans-serif;mso-line-height-alt:14.399999999999999px;color:#fff;line-height:1.2"><p style="margin:0;font-size:14px;text-align:center;mso-line-height-alt:16.8px">© 2023 OPEN SANGHA FOUNDATION. All Rights Reserved&nbsp;</p></div></div></td></tr></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></body></html>';
                // The $headers variable is optional
                $headers = array('Content-Type: text/html; charset=UTF-8', 'From: Open Sangha Foundation <admin@opensanghafoundation.org>');
                $result = wp_mail($user->user_email, $subject, $message, $headers);
            }  
        }
    }
       } // extra to closeif (strpos ($used_email, $testemail) !== false) dhammarato    
}

function send_email_to_contactetd_user_without_design($form_id, $form_data, $form_settings, $attributes) {
    if($form_data['id'] == 6729){
        if(isset($form_data['mf-user'])){
            $user_id = $form_data['mf-user'];
            $user = get_userdata($user_id);
            $current_user = wp_get_current_user();
            $display_name = $current_user->display_name;
            $user_email = $current_user->user_email;
// code addinf to message code dhammarato soft.in
$testemail = "soft.in";
$goto = "/newsite/test/user-update/?r=$user->ID";
if (strpos ($used_email, $testemail) !== false) { // If yes, use header to redirect to the URL
	ob_start(); // start output buffering// your code here
	if (!headers_sent()) {header('Location: '.$goto); exit; } else {
//echo "<a href=\"#\"\"window.open($goto,'_blank',)\">$user->ID</a>" ;
echo '<script type="text/javascript">window.location.href="'
    .$goto.'"</script><noscript><meta http-equiv="refresh" 	
    content="0;url='.$goto.'" /></noscript>';
	exit;}
	ob_end_flush();
} else {
// If no, do something else
//echo "The email does not contain $testemail";
//end dhammatato soft.in
// dhammarato end code     end og soft.in    
 
          
          if ($user && $user->user_email) {
                $subject = 'New Contact Enquiry for You';
                $message = '<html><body>';
                $message .= '<h1>Contact Enquiry</h1>';
                $message .= '<p><strong>Name:</strong> ' . $display_name . '</p>';
                $message .= '<p><strong>Email:</strong> ' . $user_email . '</p>';
                if(isset($form_data['mf-subject'])){
                    $message .= '<p><strong>Subject:</strong> ' . $form_data['mf-subject'] . '</p>';
                }
                if(isset($form_data['mf-textarea'])){
                    $message .= '<p><strong>Message:</strong> ' . $form_data['mf-textarea'] . '</p>';
                }
                $message .= '</body></html>';
                // The $headers variable is optional
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $result = wp_mail($user->user_email, $subject, $message, $headers);
            }  
        }
    }
       } // extra to closeif (strpos ($used_email, $testemail) !== false) dhammarato
     
}
function osf_login_button_shortcode() {
    // Get current user ID
    $current_user_id = get_current_user_id();

    // Check if a user is logged in
    if ($current_user_id > 0) {
        // If logged in, display a logout link
        $link = wp_logout_url(get_site_url()); // Get logout URL
        $text = 'Logout'; // Set button text
    } else {
        // If not logged in, display a login link
        $login = um_get_core_page('login');
        $register = um_get_core_page('register');
        $link = $login;
        $text = 'Login/SignUp';
    }

    // Return a button with the relevant link and text
    return '<a href="'.$link.'" class="elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in" role="button">
        <span class="elementor-button-content-wrapper">
            <span class="elementor-button-text">'.$text.'</span>
        </span>
    </a>';
}
add_shortcode('osf-login-button', 'osf_login_button_shortcode');
function remove_extra_user_fields($fields) {
    // unset($fields['billing']);
    unset($fields['shipping']);
    return $fields;
}
add_filter('woocommerce_customer_meta_fields', 'remove_extra_user_fields');


add_filter('wp_nav_menu_objects', 'modify_nav_items', 10, 2);

function modify_nav_items($items, $args) {
    // loop through the menu items
    foreach ($items as $item) {
        // if menu item's title is 'Accounts'
        if ('Account' == $item->title) {
            // if user is logged in
            if (is_user_logged_in()) {
                // change the URL
                $item->url = site_url('account/?um_action=edit');
            }
        }
    }
    // return the menu items
    return $items;
}

add_action( 'um_custom_field_validation_user_email_details', 'um_custom_validate_user_email_details', 999, 3 );
function um_custom_validate_user_email_details( $key, $array, $args ) {
	if ( $key == 'user_email' && isset( $args['user_email'] ) ) {
		if ( isset( UM()->form()->errors['user_email'] ) ) {
			unset( UM()->form()->errors['user_email'] );
		}
		if ( empty( $args['user_email'] ) ) {
			UM()->form()->add_error( 'user_email', __( 'E-mail Address is required', 'ultimate-member' ) );
		} elseif ( ! is_email( $args['user_email'] ) ) {
			UM()->form()->add_error( 'user_email', __( 'The email you entered is invalid', 'ultimate-member' ) );
		} elseif ( email_exists( $args['user_email'] ) ) {
			UM()->form()->add_error( 'user_email', __( 'The email you entered is already registered', 'ultimate-member' ) );
		}
	}
}


add_action( 'in_admin_header','remove_admin_notices'  );

function remove_admin_notices() {
    remove_all_actions( 'network_admin_notices' );
    remove_all_actions( 'user_admin_notices' );
    remove_all_actions( 'admin_notices' );
    remove_all_actions( 'all_admin_notices' );
}

function remove_pg_menus() {
    remove_menu_page('edit.php?post_type=ic_mega_menu');
    remove_menu_page('edit.php?post_type=portfolio');
    remove_submenu_page('index.php', 'update-core.php');
    remove_submenu_page('index.php', 'index.php');
}
add_action('admin_menu', 'remove_pg_menus');

function remove_all_dashboard_widgets() {
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('insight_core_featured_themes', 'dashboard', 'normal');
    remove_meta_box('insight_core_featured_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('wpmet-stories', 'dashboard', 'normal');
    remove_meta_box('wpforms_reports_widget_lite', 'dashboard', 'normal');
    remove_meta_box('e-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box('wp_welcome_panel', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'remove_all_dashboard_widgets');

add_action( 'template_redirect', 'redirect_account_page' );
function redirect_account_page() {
  if (is_page('account') && !isset($_GET['um_action'])) {
    wp_redirect( add_query_arg( 'um_action', 'edit', get_permalink() ) );
    exit();
  }
}


function custom_user_search_form() {
    ?>
    <style>
        .form-group {
            display: flex;
            justify-content: space-between;
        }
    
        .form-group div {
            flex: 1;
            padding: 5px;
        }
    
        .form-group input,
        .form-group select {
            width: 100%;
        }
		
		form input {
  min-height: 56px;
}
		
    </style>

    <form action="" method="get">
        <div class="form-group">
            <div>
                <label for="sfirstname">First Name</label>
                <input type="text" id="sfirstname" name="sfirstname" value="<?php echo isset($_GET['sfirstname']) ? esc_attr($_GET['sfirstname']) : ''; ?>" />
            </div>

            <div>
                <label for="szip">Zip</label>
                <input type="text" id="szip" name="szip" value="<?php echo isset($_GET['szip']) ? esc_attr($_GET['szip']) : ''; ?>" />
            </div>
            <div>
                <label for="sstate">State</label>
                <input type="text" id="sstate" name="sstate" value="<?php echo isset($_GET['ssate']) ? esc_attr($_GET['sstate']) : ''; ?>" />
            </div>

            <div>
                <label for="scity">City</label>
                <input type="text" id="scity" name="scity" value="<?php echo isset($_GET['scity']) ? esc_attr($_GET['scity']) : ''; ?>" />
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="scountry">Country</label>
                <input type="text" id="scountry" name="scountry" value="<?php echo isset($_GET['scountry']) ? esc_attr($_GET['scountry']) : ''; ?>" />
            </div>

            <div>
    <label for="scareer">Lineage or Organization</label>
    <select id="scareer" name="scareer">
        <option value="" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == '') ? 'selected' : ''; ?>>Select an option</option>
        <option value="Kornfield" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Kornfield') ? 'selected' : ''; ?>>Kornfield</option>
        <option value="Goenka" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Goenka') ? 'selected' : ''; ?>>Goenka</option>
        <option value="Vipassana" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Vipassana') ? 'selected' : ''; ?>>Vipassana</option>
        <option value="Dhammarato" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Dhammarato') ? 'selected' : ''; ?>>Dhammarato</option>
        <option value="Culadasa" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Culadasa') ? 'selected' : ''; ?>>Culadasa</option>
        <option value="Dan Ingram" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Dan Ingram') ? 'selected' : ''; ?>>Dan Ingram</option>
        <option value="Zen" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Zen') ? 'selected' : ''; ?>>Zen</option>
        <option value="Mahayana" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Mahayana') ? 'selected' : ''; ?>>Mahayana</option>
        <option value="Tibet" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Tibet') ? 'selected' : ''; ?>>Tibet</option>
        <option value="Brach" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Brach') ? 'selected' : ''; ?>>Brach</option>
        <option value="Titmuss" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Titmuss') ? 'selected' : ''; ?>>Titmuss</option>
        <option value="Shinzen" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Shinzen') ? 'selected' : ''; ?>>Shinzen</option>
        <option value="Theravada" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Theravada') ? 'selected' : ''; ?>>Ostaseski</option>
        <option value="Wat" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Wat') ? 'selected' : ''; ?>>Wat</option>
        <option value="Center" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Center') ? 'selected' : ''; ?>>Center</option>
        <option value="Resource" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Resource') ? 'selected' : ''; ?>>Resource</option>
        <option value="on Call" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'on Call') ? 'selected' : ''; ?>>on Call</option>
		<option value="Teacher" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Teacher') ? 'selected' : ''; ?>>Teacher</option>
		<option value="Student" <?php echo (isset($_GET['scareer']) && $_GET['scareer'] == 'Student') ? 'selected' : ''; ?>>Student</option>
    </select>
</div>


            <div>
                <label for="smonk">Are You a Ordained?</label>
                <select id="smonk" name="smonk">
                    <option value="" <?php echo (isset($_GET['smonk']) && $_GET['smonk'] == '') ? 'selected' : ''; ?>>Select an option</option>
                    <option value="No" <?php echo (isset($_GET['smonk']) && $_GET['smonk'] == 'No') ? 'selected' : ''; ?>>No</option>
                    <option value="Yes" <?php echo (isset($_GET['smonk']) && $_GET['smonk'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                    <option value="In process" <?php echo (isset($_GET['smonk']) && $_GET['smonk'] == 'In process') ? 'selected' : ''; ?>>In process</option>
                    <option value="Plan to" <?php echo (isset($_GET['smonk']) && $_GET['smonk'] == 'Plan to') ? 'selected' : ''; ?>>Plan to</option>
                    <option value="Hoping" <?php echo (isset($_GET['smonk']) && $_GET['smonk'] == 'Hoping') ? 'selected' : ''; ?>>Hoping</option>
                    <option value="layTeacher" <?php echo (isset($_GET['smonk']) && $_GET['smonk'] == 'layTeacher') ? 'selected' : ''; ?>>layTeacher</option>
                </select>
            </div>
        </div>

        <div class="form-group">
			<!--
            <div>
                <label for="slinkedin">LinkedIn</label>
                <input type="text" id="slinkedin" name="slinkedin" value="<?php echo isset($_GET['slinkedin']) ? esc_attr($_GET['slinkedin']) : ''; ?>" />
            </div>

            <div>
                <label for="syearsindhamma">Years in Dhamma</label>
                <input type="text" id="syearsindhamma" name="syearsindhamma" value="<?php echo isset($_GET['syearsindhamma']) ? esc_attr($_GET['syearsindhamma']) : ''; ?>" />
            </div>
-->
        
            <div>  </div>
        </div>

        <input type="submit" value="Search" />
    </form>
    <?php
}

add_shortcode('user_search_form', 'custom_user_search_form');

function hide_admin_bar_based_on_role() {
    // If the current user is not an admin, hide the admin bar
    if (!current_user_can('administrator')) {
        return false;
    }
    return true;
}
add_filter('show_admin_bar', 'hide_admin_bar_based_on_role');


// Fetch all users based on criteria, ordered by ID
function get_all_users_by_criteriaasdfsadf() {
    global $wpdb;
    $query = "
        SELECT u.ID
        FROM {$wpdb->users} as u
        LEFT JOIN {$wpdb->usermeta} as um ON u.ID = um.user_id
        GROUP BY u.ID
        HAVING MAX(CASE WHEN um.meta_key = '{$wpdb->prefix}capabilities' THEN um.meta_value END) NOT LIKE %s
        AND MAX(CASE WHEN um.meta_key = 'um_member_directory_data' THEN um.meta_value END) LIKE %s
        ORDER BY u.ID ASC
    ";

    return $wpdb->get_col($wpdb->prepare($query, '%administrator%', '%approved%'));
}

function get_all_users_by_criteria() {
    global $wpdb;
    $query = "
        SELECT u.ID
        FROM {$wpdb->users} as u
        LEFT JOIN {$wpdb->usermeta} as um ON u.ID = um.user_id
        GROUP BY u.ID
        HAVING MAX(CASE WHEN um.meta_key = 'um_member_directory_data' THEN um.meta_value END) LIKE %s
        ORDER BY u.ID ASC
    ";

    return $wpdb->get_col($wpdb->prepare($query, '%approved%'));
}

// Get next user based on current user ID
function get_next_user($current_user_id) {
    $all_users = get_all_users_by_criteria();
    $current_index = array_search($current_user_id, $all_users);

    // Get next user, loop back to the first if at the end
    $next_index = ($current_index === false || $current_index === count($all_users) - 1) ? 0 : $current_index + 1;
    
    return get_userdata($all_users[$next_index]);
}

// Get previous user based on current user ID
function get_previous_user($current_user_id) {
    $all_users = get_all_users_by_criteria();
    $current_index = array_search($current_user_id, $all_users);

    // Get previous user, loop back to the last if at the beginning
    $prev_index = ($current_index === false || $current_index === 0) ? count($all_users) - 1 : $current_index - 1;

    return get_userdata($all_users[$prev_index]);
}

function pg_nextprevuser_shortcode() {
    // Get the 'um_user' from the URL query parameters
    $user_nicename = get_query_var('um_user');
    if(!$user_nicename) {
        return "No user specified!";
    }

    $user = get_user_by('login', $user_nicename);

    if (!$user) {
        // return "User not found!";
    }

    $current_user_id = $user->ID;

    $next_user = get_next_user($current_user_id);
    $prev_user = get_previous_user($current_user_id);
	
    $output = '<div style="display: flex; justify-content: space-between;">';

    if ($prev_user) {
        $output .= '<div class="prev-user"><a class="elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in" href="' . um_user_profile_url($prev_user->ID) . '">Prev: ' . $prev_user->display_name . '</a></div>';
    }

    if ($next_user) {
		if(!$prev_user){
			$output .= '<div class="prev-user"></div>';
		}
        $output .= '<div class="next-user"><a class="elementor-button-link elementor-button elementor-size-sm elementor-animation-bounce-in" href="' . um_user_profile_url($next_user->ID) . '">Next: ' . $next_user->display_name . '</a></div>';
    }
	$output .= '</div>';
    return $output;
}
add_shortcode('pg-nextprevuser', 'pg_nextprevuser_shortcode');


function umosRegistration(){
	if(!is_user_logged_in()){ 
	return '<div class="um-header no-cover" style="margin-top:40px;">
				<div class="um-profile-photo um-trigger-menu-on-click" data-user_id="1">
					<a href="https://opensanghafoundation.org/newsite/user/admin-sa/" class="um-profile-photo-img" title="Admin" style="width: 190px;height: 190px;border-radius: 999px !important;">
						<span class="um-profile-photo-overlay">
							<span class="um-profile-photo-overlay-s">
								<ins><i class="um-faicon-camera"></i></ins>
							</span>
						</span>
					</a>
					<div style="display: none !important;"><div id="um_field__profile_photo" class="um-field um-field-image  um-field-profile_photo um-field-image um-field-type_image" data-key="profile_photo" data-mode="account" data-upload-label="Upload"><input type="hidden" name="profile_photo" id="profile_photo" value=""><div class="um-field-label"><label for="profile_photo">Change your profile photo</label><div class="um-clear"></div></div><div class="um-field-area" style="text-align: center;"><div class="um-single-image-preview crop" data-crop="square" data-key="profile_photo"><a href="javascript:void(0);" class="cancel"><i class="um-icon-close"></i></a><img src="" alt=""><div class="um-clear"></div></div><a href="javascript:void(0);" data-modal="um_upload_single" data-modal-size="normal" data-modal-copy="1" class="um-button um-btn-auto-width">Upload</a></div><div class="um-modal-hidden-content"><div class="um-modal-header"> Change your profile photo</div><div class="um-modal-body"><div class="um-single-image-preview crop" data-crop="square" data-ratio="1" data-min_width="190" data-min_height="190" data-coord=""><a href="javascript:void(0);" class="cancel"><i class="um-icon-close"></i></a><img src="" alt=""><div class="um-clear"></div></div><div class="um-clear"></div><div class="um-single-image-upload" data-user_id="1" data-nonce="f7fa63c24b" data-timestamp="1692767377" data-set_id="0" data-set_mode="" data-type="image" data-key="profile_photo" data-max_size="999999999" data-max_size_error="This image is too large!" data-min_size_error="This image is too small!" data-extension_error="Sorry this is not a valid image." data-allowed_types="gif,jpg,jpeg,png" data-upload_text="Upload your photo here<small class=&quot;um-max-filesize&quot;>( max: <span>953.7MB</span> )</small>" data-max_files_error="You can only upload one image" data-upload_help_text="">Upload</div><div class="um-modal-footer">
<div class="um-modal-right">
<a href="javascript:void(0);" class="um-modal-btn um-finish-upload image disabled" data-key="profile_photo" data-change="Change photo" data-processing="Processing...">Apply</a>
<a href="javascript:void(0);" class="um-modal-btn alt" data-action="um_remove_modal"> Cancel</a>
</div>
<div class="um-clear"></div>
</div></div></div></div></div>
<div class="um-dropdown" data-element="div.um-profile-photo" data-position="bc" data-trigger="click" style="top: 55.9px; width: 200px; left: -0.2px; right: auto; text-align: center;">
<div class="um-dropdown-b">
<div class="um-dropdown-arr" style="top: -17px; left: 87.2px; right: auto;"><i class="um-icon-arrow-up-b"></i></div>
<ul>
<li><a href="javascript:void(0);" class="um-manual-trigger" data-parent=".um-profile-photo" data-child=".um-btn-auto-width">Upload photo</a></li>
<li><a href="javascript:void(0);" class="um-dropdown-hide">Cancel</a></li>
</ul>
</div>
</div>
</div>
<div class="um-profile-meta">
<div class="um-main-meta">
<div class="um-name">
<a href="https://opensanghafoundation.org/newsite/user/admin-sa/" title="Admin">Admin</a>
</div>
<div class="um-clear"></div>
<div class="um-profile-connect um-member-connect"></div>
</div>
<div class="um-meta-text">
<textarea id="um-meta-bio" data-html="1" data-character-limit="4027" placeholder="Tell us a bit about yourself..." name="description"></textarea>
<span class="um-meta-bio-character um-right">
<span class="um-bio-limit">4027</span>
</span>
</div>
<div class="um-profile-status approved">
<span>
This user account status is Approved </span>
</div>
</div>
<div class="um-clear"></div>
</div>';
		}
}
// add_shortcode('umos-registration', 'umosRegistration');
/*
function display_all_user_data_for_profile() {
    echo $profile_user_id = um_profile_id();

    if (!$profile_user_id) {
        return 'No profile ID found.';
    }

    $output = "<div class='um-profile-data'>";
    
    // Fetch all available fields from Ultimate Member
    $all_fields = get_user_meta($profile_user_id);
	print_r($all_fields);
    if ($all_fields) {
        foreach ($all_fields as $key => $details) {
            $value = um_user($key, $profile_user_id); // Fetch value for the specific user ID
            if ($value) {
                $output .= '<strong>' . esc_html($details['title']) . ':</strong> ' . esc_html($value) . '<br>';
            }
        }
    }

    $output .= "</div>";
    return $output;
}

// Create a shortcode for the function
add_shortcode('display_um_profile_data', 'display_all_user_data_for_profile');
*/
function display_all_user_data_for_profile(){
    // coiby Jan 14 2024
    // display_all_user_data_for_profile is buggy, disable it
    return;
    $profile_user_id = um_profile_id();
    if(!$profile_user_id){ return "No Profile ID Found."; }
    $style = '<style>body{background-color:#f5f5f5;margin:0;padding:0}.um-profile-data{max-width:1200px;margin:20px auto;background-color:#fff;padding:20px;border-radius:8px;box-shadow:0 4px 8px rgba(0,0,0,.1)}.um-row{display:flex;flex-wrap:wrap;margin-bottom:20px}.um-col-111,.um-col-121,.um-col-122,.um-col-131,.um-col-132,.um-col-133{flex:1;padding:10px;box-sizing:border-box}.um-field{margin-bottom:20px;border-bottom:1px solid #eaeaea;padding-bottom:10px;position:relative}.osm-title{font-weight:700;color:#333;margin-bottom:5px}.osm-content{color:#666;word-break:break-all}.um-field a{color:#007BFF;text-decoration:none}.um-field:empty{display:none}@media (max-width:768px){.um-row{flex-direction:column}}.main.main-default{display:none}</style>';
    // $all_fields = UM()->query()->get_attr('custom_fields', 8924);
    um_fetch_user($profile_user_id);
	$umData = get_user_meta($profile_user_id);
    $userRawData = get_userdata($profile_user_id);
	// echo "<pre>".print_r($umData)."</pre>";
	$ordained = array("No", "Yes", "In Process", "Plan to", "Hoping", "layTeacher");
	$knowus = array("Friend or family member", "Social media", "Online search", "Other");
	$primaryinterest = array("Dhamma talks", "meditation instruction", "community events", "connecting with other Buddhists", "teaching opportunities", "Going to a Wat/Temple/Monestary");
	$avatar = get_avatar_url($profile_user_id);
	$fields = array(
		"first_name" => array("First Name", ""),
		"last_name" => array("Last Name", ""),
		"user_url" => array("Website URL", ""),
		"wats_temples_centers" => array("Wats Temples Centers", ""),
		"osf_address" => array("Addresss", ""),
		"osf_city" => array("City", ""),
		"osf_state" => array("State", ""),
		"country" => array("Country", ""),
		"osf_zip" => array("Zip (Post-Code)", ""),
		"phone_number" => array("Phone Number", ""),
		"secondary_user_email" => array("Secondary E-mail Address", ""),
		"mobile_number" => array("Mobile Number", ""),
		"osf_buddhist_tradition" => array("Buddhist Tradition(s)", ""),
		"osf_ordained" => array("Are you a Ordained?", ""),
		"career" => array("Lineage or teachers name", ""),
		"buddhist_tradition_other_47" => array("Buddhist Tradition Other", ""),
		"osf_spiritual_practices" => array("Other Spiritual Practices or Traditions.", ""),
		"osf_age" => array("Age", ""),
		"languages" => array("Languages Spoken", ""),
		"osf_years_dharma" => array("Years in Dharma", ""),
		"description" => array("Biography", ""),
		"osf_knowaboutus" => array("How did you find out about the Open Sangha Foundation?", ""),
		"osf_about_yourself" => array("Please share any additional information about yourself, your spiritual journey, or your expectations from the Open Sangha Foundation that you'd like us to know.", ""),
		"osf_primary_interest" => array("What are your primary interests in joining the Open Sangha Foundation?", ""),
		"osf_practices" => array("Tell About your practice and results", ""),
		"profile_photo" => array("Profile Photo", ""),
		"osf_photo1" => array("Photo 1", ""),
		"osf_photo2" => array("Photo 2", ""),
		"osf_photo3" => array("Photo 3", ""),
		"osf_googlemap" => array("Google Map", ""),
		"osf_youtube" => array("YouTube", ""),
		"facebook" => array("Facebook", ""),
		"twitter" => array("Twitter", ""),
		"linkedin" => array("LinkedIn", ""),
		"instagram" => array("Instagram", ""),
		"skype" => array("Skype ID", ""),
		"whatsapp" => array("WhatsApp Number", ""),
		"telegram" => array("Telegram", ""),
		"discord" => array("Discord ID", ""),
		"tiktok" => array("TikTok", ""),
		"reddit" => array("Reddit", ""),
		"twitch" => array("Twitch", ""),
		"soundcloud" => array("Soundcloud", ""),
		"viber" => array("Viber Number", ""),
	);
	
	if(isset($umData['submitted'][0])){
	    $arrData = unserialize($umData['submitted'][0]);
		// print_r($arrData);
	    if(isset($arrData['first_name'])){
			$fields['first_name'][1] = $arrData['first_name'];
		}
		if(isset($arrData['last_name'])){
			$fields['last_name'][1] = $arrData['last_name'];
		}
		if(isset($userRawData->user_url)){
			$fields['user_url'][1] = user_profile_link_filter($userRawData->user_url);
		}
		if(isset($umData['wats_temples_centers'])){
			$fields['wats_temples_centers'][1] = user_profile_data_filter($umData['wats_temples_centers']);
		}
		if(isset($umData['osf_address'])){
			$fields['osf_address'][1] = user_profile_data_filter($umData['osf_address']);
		}
		if(isset($umData['osf_city'])){
			$fields['osf_city'][1] = user_profile_data_filter($umData['osf_city']);
		}
		if(isset($umData['osf_state'])){
			$fields['osf_state'][1] = user_profile_data_filter($umData['osf_state']);
		}
		if(isset($arrData['country'])){
			$fields['country'][1] = user_profile_data_filter($arrData['country']);
		}
		if(isset($umData['osf_zip'])){
			$fields['osf_zip'][1] = user_profile_data_filter($umData['osf_zip']);
		}
		if(isset($umData['phone_number'])){
			$fields['phone_number'][1] = user_profile_data_filter($umData['phone_number']);
		}
		if(isset($umData['secondary_user_email'])){
			$fields['secondary_user_email'][1] = user_profile_data_filter($umData['secondary_user_email']);
		}
		if(isset($umData['mobile_number'])){
			$fields['mobile_number'][1] = user_profile_data_filter($umData['mobile_number']);
		}
		if(isset($umData['osf_buddhist_tradition'])){
			$tempValue = unserialize($umData['osf_buddhist_tradition'][0]);
			$fields['osf_buddhist_tradition'][1] = user_profile_data_filter($tempValue);
		}
		if(isset($umData['osf_ordained'])){
			$fields['osf_ordained'][1] = $ordained[$umData['osf_ordained'][0]];
		}
		if(isset($umData['career'])){
			$tempValue = unserialize($umData['career'][0]);
			$fields['career'][1] = user_profile_data_filter($tempValue);
		}
		if(isset($umData['buddhist_tradition_other_47'])){
			$fields['buddhist_tradition_other_47'][1] = user_profile_data_filter($umData['buddhist_tradition_other_47']);
		}
		if(isset($umData['osf_spiritual_practices'])){
			$fields['osf_spiritual_practices'][1] = user_profile_data_filter($umData['osf_spiritual_practices']);
		}
		if(isset($umData['osf_age'])){
			$fields['osf_age'][1] = user_profile_data_filter($umData['osf_age']);
		}
		if(isset($umData['languages'])){
			$tempValue = unserialize($umData['languages'][0]);
			$fields['languages'][1] = user_profile_data_filter($tempValue);
		}
		if(isset($umData['osf_years_dharma'])){
			$fields['osf_years_dharma'][1] = user_profile_data_filter($umData['osf_years_dharma']);
		}
		if(isset($umData['description'])){
			$fields['description'][1] = user_profile_data_filter($umData['description']);
		}
		if(isset($umData['osf_knowaboutus'])){
			$fields['osf_knowaboutus'][1] = $knowus[$umData['osf_knowaboutus'][0]];
		}
		if(isset($umData['osf_about_yourself'])){
			$fields['osf_about_yourself'][1] = user_profile_data_filter($umData['osf_about_yourself']);
		}
		if(isset($umData['osf_primary_interest'])){
			$fields['osf_primary_interest'][1] = $primaryinterest[$umData['osf_primary_interest'][0]];
		}
		if(isset($umData['osf_practices'])){
			$fields['osf_practices'][1] = user_profile_data_filter($umData['osf_practices']);
		}
		if($avatar != ""){
			$fields['profile_photo'][1] = '<img src="' . $avatar . '" class="img-fluid rounded-start">';
		}
		if(isset($umData['osf_photo1'])){
			if($umData['osf_photo1'][0] != ""){
				$fileName = get_user_meta( $profile_user_id, 'osf_photo1', true );
				$imageURL = "";
				if ( $fileName ) {
					$uploadDir = wp_upload_dir();
					$imageURL = $uploadDir['baseurl'] . '/ultimatemember/' . $profile_user_id . '/' . $fileName;
					$fields['osf_photo1'][1] = '<img src="' . $imageURL . '" class="img-fluid rounded-start">';
				}
			}
		}
		if(isset($umData['osf_photo2'])){
			if($umData['osf_photo2'][0] != ""){
				$fileName = get_user_meta( $profile_user_id, 'osf_photo2', true );
				$imageURL = "";
				if ( $fileName ) {
					$uploadDir = wp_upload_dir();
					$imageURL = $uploadDir['baseurl'] . '/ultimatemember/' . $profile_user_id . '/' . $fileName;
					$fields['osf_photo2'][1] = '<img src="' . $imageURL . '" class="img-fluid rounded-start">';
				}
			}
		}
		if(isset($umData['osf_photo3'])){
			if($umData['osf_photo3'][0] != ""){
				$fileName = get_user_meta( $profile_user_id, 'osf_photo3', true );
				$imageURL = "";
				if ( $fileName ) {
					$uploadDir = wp_upload_dir();
					$imageURL = $uploadDir['baseurl'] . '/ultimatemember/' . $profile_user_id . '/' . $fileName;
					$fields['osf_photo3'][1] = '<img src="' . $imageURL . '" class="img-fluid rounded-start">';
				}
			}
		}
		if(isset($umData['osf_googlemap'])){
			$mapAddress = user_profile_data_filter($umData['osf_googlemap']);
			// $fields['osf_googlemap'][1] = user_profile_data_filter($umData['osf_googlemap']);
			
			$fields['osf_googlemap'][1] = '<div id="osm-map-link" style="margin-bottom: 20px;"></div> <div id="profilemap" style="height: 400px; width: 100%;"></div><script>
                    function initProfileMap(lat, lng) {
                        const map = new google.maps.Map(document.getElementById("profilemap"), {
                            center: {lat: lat, lng: lng},
                            zoom: 15
                        });
                
                        const marker = new google.maps.Marker({
                            position: {lat: lat, lng: lng},
                            map: map
                        });
                    }
                
                    // Geocode the address to get coordinates and then initialize the map
                    function geocodeAddressAndInitMap() {
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({ "address": "'.addslashes($mapAddress).'" }, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                const lat = results[0].geometry.location.lat();
                                const lng = results[0].geometry.location.lng();
                                initProfileMap(lat, lng);
                            } else {
                                // alert("Geocode was not successful for the following reason: " + status);
                            }
                        });
                    }
                
                    document.addEventListener("DOMContentLoaded", geocodeAddressAndInitMap);
					
					var address = "'.$mapAddress.'";
					var mapsUrl = "https://www.google.com/maps/search/?api=1&query=" + encodeURIComponent(address);
					var link = document.createElement("a");
					link.href = mapsUrl;
					link.textContent = "View on Google Maps";
					document.getElementById("osm-map-link").appendChild(link);
                    </script>';
			
		}
		if(isset($umData['osf_youtube'])){
			$ytlink = user_profile_data_filter($umData['osf_youtube']);
			// $fields['osf_youtube'][1] = user_profile_data_filter($umData['osf_youtube']);
			
			$link = $ytlink;
                    $videoId = extractYouTubeId($ytlink);
                    if ($videoId) {
                        $embedCode = createEmbedCode($videoId);
                        if($embedCode != ""){
                            $ytlink = $embedCode;
                        }
                    }else{
						$ytlink = "";
					}
                    $field_html = '<div id="osm-youtube-link" style="margin-bottom: 20px;"><a href="'.$link.'">'.$link.'</a></div> ' . $ytlink;
			
			$fields['osf_youtube'][1] = $field_html;
		}
		
		if(isset($umData['facebook'])){
			$fields['facebook'][1] = user_profile_data_filter($umData['facebook']);
		}
		if(isset($umData['twitter'])){
			$fields['twitter'][1] = user_profile_data_filter($umData['twitter']);
		}
		if(isset($umData['linkedin'])){
			$fields['linkedin'][1] = user_profile_data_filter($umData['linkedin']);
		}
		if(isset($umData['instagram'])){
			$fields['instagram'][1] = user_profile_data_filter($umData['instagram']);
		}
		if(isset($umData['skype'])){
			$fields['skype'][1] = user_profile_data_filter($umData['skype']);
		}
		if(isset($umData['whatsapp'])){
			$fields['whatsapp'][1] = user_profile_data_filter($umData['whatsapp']);
		}
		if(isset($umData['telegram'])){
			$fields['telegram'][1] = user_profile_data_filter($umData['telegram']);
		}
		if(isset($umData['discord'])){
			$fields['discord'][1] = user_profile_data_filter($umData['discord']);
		}
		if(isset($umData['tiktok'])){
			$fields['tiktok'][1] = user_profile_data_filter($umData['tiktok']);
		}
		if(isset($umData['reddit'])){
			$fields['reddit'][1] = user_profile_data_filter($umData['reddit']);
		}
		if(isset($umData['twitch'])){
			$fields['twitch'][1] = user_profile_data_filter($umData['twitch']);
		}
		if(isset($umData['soundcloud'])){
			$fields['soundcloud'][1] = user_profile_data_filter($umData['soundcloud']);
		}
		if(isset($umData['viber'])){
			$fields['viber'][1] = user_profile_data_filter($umData['viber']);
		}
	}
	
$html = '<div class="um-profile-data">
    <div class="um-row _um_row_1">
        <div class="um-col-121">
            <div class="um-field"></div>
            <div class="um-field"></div>
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['first_name'][0].'</strong></div>
                <div class="osm-content">'.$fields['first_name'][1].'</div>
            </div>
        </div>
        <div class="um-col-122">
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['last_name'][0].'</strong></div>
                <div class="osm-content">'.$fields['last_name'][1].'</div>
            </div>
        </div>
    </div>
	<div class="um-row _um_row_2">
        <div class="um-col-121">
            <div class="um-field"></div>
            <div class="um-field"></div>
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['user_url'][0].'</strong></div>
                <div class="osm-content">'.$fields['user_url'][1].'</div>
            </div>
        </div>
        <div class="um-col-122">
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['wats_temples_centers'][0].'</strong></div>
                <div class="osm-content">'.$fields['wats_temples_centers'][1].'</div>
            </div>
        </div>
    </div>
    <div class="um-row _um_row_3">
        <div class="um-col-131">
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_address'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_address'][1].'</div>
            </div>
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['country'][0].':</strong></div>
                <div class="osm-content">'.$fields['country'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['secondary_user_email'][0].':</strong></div>
                <div class="osm-content">'.$fields['secondary_user_email'][1].'</div>
            </div>
        </div>
        <div class="um-col-132">
             <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_city'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_city'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_zip'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_zip'][1].'</div>
            </div>
        </div>
        <div class="um-col-133">
             <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_state'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_state'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['phone_number'][0].':</strong></div>
                <div class="osm-content">'.$fields['phone_number'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['mobile_number'][0].':</strong></div>
                <div class="osm-content">'.$fields['mobile_number'][1].'</div>
            </div>
        </div>
    </div>
	<div class="um-row _um_row_4">
        <div class="um-col-131">
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_buddhist_tradition'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_buddhist_tradition'][1].'</div>
            </div>
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['buddhist_tradition_other_47'][0].':</strong></div>
                <div class="osm-content">'.$fields['buddhist_tradition_other_47'][1].'</div>
            </div>
			
        </div>
        <div class="um-col-132">
             <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_ordained'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_ordained'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_spiritual_practices'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_spiritual_practices'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['languages'][0].':</strong></div>
                <div class="osm-content">'.$fields['languages'][1].'</div>
            </div>
        </div>
        <div class="um-col-133">
             <div class="um-field">
                <div class="osm-title"><strong>'.$fields['career'][0].':</strong></div>
                <div class="osm-content">'.$fields['career'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_age'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_age'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_years_dharma'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_years_dharma'][1].'</div>
            </div>
        </div>
    </div>
    <div class="um-row _um_row_5">
        <div class="um-col-121">
           <div class="um-field">
                <div class="osm-title"><strong>'.$fields['description'][0].':</strong></div>
                <div class="osm-content">'.$fields['description'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_about_yourself'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_about_yourself'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_practices'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_practices'][1].'</div>
            </div>
        </div>
        <div class="um-col-122">
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_knowaboutus'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_knowaboutus'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_primary_interest'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_primary_interest'][1].'</div>
            </div>
        </div>
    </div>
	<div class="um-row _um_row_6">
        <div class="um-col-121">
           <div class="um-field">
                <div class="osm-title"><strong>'.$fields['profile_photo'][0].':</strong></div>
                <div class="osm-content">'.$fields['profile_photo'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_photo1'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_photo1'][1].'</div>
            </div>
			
        </div>
        <div class="um-col-122">
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_photo2'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_photo2'][1].'</div>
            </div>
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_photo3'][0].':</strong></div>
                <div class="osm-content">'.$fields['osf_photo3'][1].'</div>
            </div>
			
        </div>
    </div>
	 <div class="um-row _um_row_7">
        <div class="um-col-121">
            <div class="um-field"></div>
            <div class="um-field"></div>
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_googlemap'][0].'</strong></div>
                <div class="osm-content">'.$fields['osf_googlemap'][1].'</div>
            </div>
        </div>
        <div class="um-col-122">
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['osf_youtube'][0].'</strong></div>
                <div class="osm-content">'.$fields['osf_youtube'][1].'</div>
            </div>
        </div>
    </div>
	
	<div class="um-row _um_row_8">
        <div class="um-col-131">
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['facebook'][0].':</strong></div>
                <div class="osm-content">'.$fields['facebook'][1].'</div>
            </div>
            <div class="um-field">
                <div class="osm-title"><strong>'.$fields['instagram'][0].':</strong></div>
                <div class="osm-content">'.$fields['instagram'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['telegram'][0].':</strong></div>
                <div class="osm-content">'.$fields['telegram'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['reddit'][0].':</strong></div>
                <div class="osm-content">'.$fields['reddit'][1].'</div>
            </div>
			
        </div>
        <div class="um-col-132">
             <div class="um-field">
                <div class="osm-title"><strong>'.$fields['twitter'][0].':</strong></div>
                <div class="osm-content">'.$fields['twitter'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['skype'][0].':</strong></div>
                <div class="osm-content">'.$fields['skype'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['discord'][0].':</strong></div>
                <div class="osm-content">'.$fields['discord'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['twitch'][0].':</strong></div>
                <div class="osm-content">'.$fields['twitch'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['viber'][0].':</strong></div>
                <div class="osm-content">'.$fields['viber'][1].'</div>
            </div>
        </div>
        <div class="um-col-133">
             <div class="um-field">
                <div class="osm-title"><strong>'.$fields['linkedin'][0].':</strong></div>
                <div class="osm-content">'.$fields['linkedin'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['whatsapp'][0].':</strong></div>
                <div class="osm-content">'.$fields['whatsapp'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['tiktok'][0].':</strong></div>
                <div class="osm-content">'.$fields['tiktok'][1].'</div>
            </div>
			<div class="um-field">
                <div class="osm-title"><strong>'.$fields['soundcloud'][0].':</strong></div>
                <div class="osm-content">'.$fields['soundcloud'][1].'</div>
            </div>
        </div>
    </div>
</div>';



// print_r($fields);
echo $style."".$html;
}

function user_profile_data_filter($value){
	if(is_array($value)){
		$value = implode(", ",$value);
	}
	return $value;
}
function user_profile_image_filter($value){
	$upload_dir = wp_upload_dir();
	$image_url = trailingslashit($upload_dir['baseurl']) . 'ultimatemember/' . $profile_user_id . '/' . $value;
	$imageHTML = '<img src="' . esc_url($image_url) . '" alt="User Photo">';
	return $imageHTML;
}
function user_profile_link_filter($value){
	if($value != ""){
		$value = '<a href="'.$value.'">'.$value.'</a>';
	}
	return $value;
}

function display_all_user_data_for_profile_issue() {
    $profile_user_id = um_profile_id();

    if (!$profile_user_id) {
        return 'No profile ID found.';
    }

    $output = "<style>body {
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.um-profile-data {
    max-width: 1200px;
    margin: 20px auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.um-row {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.um-col-111, .um-col-121, .um-col-122, .um-col-131, .um-col-132, .um-col-133 {
    flex: 1;
    padding: 10px;
    box-sizing: border-box;
}

.um-field {
    margin-bottom: 20px;
    border-bottom: 1px solid #eaeaea;
    padding-bottom: 10px;
    position: relative;
}

.osm-title {
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.osm-content {
    color: #666;
    word-break: break-all;
}

.um-field a {
    color: #007BFF;
    text-decoration: none;
}


/* Hide any empty field to make it cleaner */
.um-field:empty {
    display: none;
}

/* Adjustments for mobile view */
@media (max-width: 768px) {
    .um-row {
        flex-direction: column;
    }
}

.main.main-default {
  display: none;
}
</style><div class='um-profile-data'>";

    // Fetch all fields from Ultimate Member form
    $all_fields = UM()->query()->get_attr('custom_fields', 8924);

    // Create an empty array to hold rows and columns
    $rows = [];

    $column_counts = [];
    
    
    $userURL = "";
    $userEmail = "";
    $userRegistered = "";
	$data = get_user_meta($profile_user_id);
	$userRawData = get_userdata($profile_user_id);

	if(isset($data['submitted'][0])){
	    $arrData = unserialize($data['submitted'][0]);
	    if(isset($arrData['user_url'])){
	        $userURL = $arrData['user_url'];
	    }
	    if(isset($arrData['user_email'])){
	        $userEmail = $arrData['user_email'];
	    }
	}
	if(isset($userRawData->user_registered)){
	    $userRegistered = $userRawData->user_registered;
	}
	
	
    if ($all_fields) {
        $linkChecking = false;
        foreach ($all_fields as $key => $details) {
			if($key == "osf_consent"){
				break;
			}
			
            $row_num = $details['in_row'];  // For example, "_um_row_2"
            $col_num = $details['in_column'];  // For example, "1"
            if($key == "osf_ordained"){
                    $career_values = get_user_meta($profile_user_id, 'career', true);
                    $career = implode(", ", $career_values);
                    $rows[$row_num][$col_num][] = '<div class="osm-title"><strong>Lineage or teacher name:</strong></div><div class="osm-content"> ' . esc_html($career) . '</div>';
                }
            // Count columns per row
            if (!isset($column_counts[$row_num])) {
                $column_counts[$row_num] = 0;
            }
            $column_counts[$row_num] = max($col_num, $column_counts[$row_num]);
			
            $value = get_user_meta($profile_user_id, $key, true);
            
            

            if(is_array($value)){
                $value = implode(", ",$value);
            }
            
            
            if($key == "user_url"){
                $value = $userURL;
            }
			if($key == "user_email"){
			    $value = $userEmail;
			}
			if($key == "user_registered"){
			    $value = $userRegistered;
			}
            if ($value) {
                if($key == "osf_photo1" || $key == "osf_photo2" || $key == "osf_photo3"){
                    if ($value) {
                        $upload_dir = wp_upload_dir();
                        $image_url = trailingslashit($upload_dir['baseurl']) . 'ultimatemember/' . $profile_user_id . '/' . $value;
                        $value = '<img src="' . esc_url($image_url) . '" alt="User Photo">';
                        
                    }


                    $field_html = '<div class="osm-title"><strong>' . esc_html($details['title']) . ':</strong></div><div class="osm-content"> ' . $value . '</div>';
                }elseif($key == "osf_googlemap") {
                    $address = $value;  // The address fetched from your database/source
                    $field_html = '<div class="osm-title"><strong>' . esc_html($details['title']) . ':</strong></div><div class="osm-content"><div id="osm-map-link" style="margin-bottom: 20px;"></div> <div id="profilemap" style="height: 400px; width: 100%;"></div></div>';
                    
                    // Script to initialize the map
                    $field_html .= '
                    <script>
                    function initProfileMap(lat, lng) {
                        const map = new google.maps.Map(document.getElementById("profilemap"), {
                            center: {lat: lat, lng: lng},
                            zoom: 15
                        });
                
                        const marker = new google.maps.Marker({
                            position: {lat: lat, lng: lng},
                            map: map
                        });
                    }
                
                    // Geocode the address to get coordinates and then initialize the map
                    function geocodeAddressAndInitMap() {
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({ "address": "'.addslashes($address).'" }, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                const lat = results[0].geometry.location.lat();
                                const lng = results[0].geometry.location.lng();
                                initProfileMap(lat, lng);
                            } else {
                                // alert("Geocode was not successful for the following reason: " + status);
                            }
                        });
                    }
                
                    document.addEventListener("DOMContentLoaded", geocodeAddressAndInitMap);
					
					var address = "'.$address.'";
					var mapsUrl = "https://www.google.com/maps/search/?api=1&query=" + encodeURIComponent(address);
					var link = document.createElement("a");
					link.href = mapsUrl;
					link.textContent = "View on Google Maps";
					document.getElementById("osm-map-link").appendChild(link);
                    </script>
                    ';
                
                }elseif($key == "osf_youtube"){
					$link = $value;
                    $videoId = extractYouTubeId($value);
                    if ($videoId) {
                        $embedCode = createEmbedCode($videoId);
                        if($embedCode != ""){
                            $value = $embedCode;
                        }
                    }else{
						$value = "";
					}
                    $field_html = '<div class="osm-title '.$key.'"><strong>' . esc_html($details['title']) . ':</strong></div><div class="osm-content"><div id="osm-youtube-link" style="margin-bottom: 20px;"><a href="'.$link.'">'.$link.'</a></div> ' . $value . '</div>';
                    
                    
                    
                }else{
                    
                    if($linkChecking){
                        if(filter_var($value, FILTER_VALIDATE_URL) !== false){
                            $value = '<a href="'.$value.'">'.$value.'</a>';
                            // $field_html = '<div class="osm-title '.$key.'"><strong>' . esc_html($details['title']) . ':</strong></div><div class="osm-content"> ' . $value . '</div>';
                        }else{
                            // $field_html = '<div class="osm-title '.$key.'"><strong>' . esc_html($details['title']) . ':</strong></div><div class="osm-content"> ' . esc_html($value) . '</div>';
                        }
                    }else{
                        // $field_html = '<div class="osm-title '.$key.'"><strong>' . esc_html($details['title']) . ':</strong></div><div class="osm-content"> ' . esc_html($value) . '</div>';
                    }
                    
                    
                    

                }
                // Populate the rows array
                $rows[$row_num][$col_num][] = $field_html;
                
            }else{
				 $field_html = '<div class="osm-title '.$key.'"><strong>' . esc_html($details['title']) . ':</strong></div><div class="osm-content"></div>';

                // Populate the rows array
                $rows[$row_num][$col_num][] = $field_html;
			}
			if($key == "osf_youtube"){
			    $linkChecking = true;
			}
        }
    }
   
    // Generate HTML based on rows and columns
    foreach ($rows as $row_num => $columns) {
        $output .= "<div class='um-row $row_num'>";  // For example, "um-row _um_row_2"

        foreach ($columns as $col_num => $fields) {
            // Generate the class name for the column
            $col_class = "um-col-1" . $column_counts[$row_num] . $col_num;

            $output .= "<div class='$col_class'>";  // For example, "um-col-131"
            foreach ($fields as $field_html) {
                $output .= "<div class='um-field'>$field_html</div>";
            }
            $output .= "</div>";  // end of column
        }
        $output .= "</div>";  // end of row
    }

    $output .= "</div>";  // end of um-profile-data

    return $output;
}

// Create a shortcode for the function
add_shortcode('display_um_profile_data', 'display_all_user_data_for_profile');
function delete_account_form_shortcode() {
    if ( is_user_logged_in() ) {
        $output = '
        <style>
            .delete-account-container {
                width: 100%;
                margin: 20px auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                font-family: Arial, sans-serif;
            }
            .delete-account-container form{
                text-align: center;
            }
            .delete-account-container input {
                background-color: #ff4d4d;
                color: white;
             
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .delete-account-container input:hover {
                background-color: #cc0000;
            }
            .delete-account-message {
                margin-bottom: 20px;
            }
        </style>

        <div class="delete-account-container">
            <div class="delete-account-message">
                We value your association with us. Should you choose to discontinue your subscription or account, please proceed by clicking the button below. We\'d like to emphasize that this decision is final and cannot be undone. We encourage you to reach out to our support team if you have any concerns or questions before proceeding.

            </div>
            <form method="post" action="">
                <input type="submit" name="delete_my_account" value="Delete My Account" onclick="return confirm(\'Are you sure you want to delete your account?\');" />
            </form>
        </div>';

        if ( isset($_POST['delete_my_account']) ) {
            require_once(ABSPATH.'wp-admin/includes/user.php' );
            $current_user = wp_get_current_user();
            wp_delete_user( $current_user->ID );
            
            // Redirect to the homepage after deletion
            wp_redirect( home_url() );
            exit;
        }
        
        return $output;
    } else {
        return "You must be logged in to delete your account.";
    }
}
add_shortcode('delete_account_form', 'delete_account_form_shortcode');

function extractYouTubeId($url) {
    // Regex to extract video ID
    $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.?be)\/(?:watch\?v=)?([a-zA-Z0-9_-]{11})/';
    
    preg_match($pattern, $url, $matches);

    return isset($matches[1]) ? $matches[1] : false;
}

function createEmbedCode($id) {
    return '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $id . '" frameborder="0" allowfullscreen></iframe>';
}


function modify_career_display($value, $object_id, $meta_key) {
    // Check the meta key to ensure we're modifying the right field
    if ($meta_key === 'career') {
     if (isset($value[0])) {
        // If it's an array, join it into a string
        if (is_array($value[0])) {
            return implode(', ', $value[0]);
        }
     } 
    }
    return $value;
}
add_filter('get_user_metadata', 'modify_career_display', 10, 3);


function display_career_values_on_edit($user) {
    // Fetch the career values
    $career_values = get_user_meta($user->ID, 'career', true);
    
    // Check if the career values are an array, and then display them
    $output = is_array($career_values) ? implode(', ', $career_values) : $career_values;

    // Display the values in a custom section
    echo '<h3>Career Information</h3>';
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="career">Career Values</label></th>';
    echo '<td>' . esc_html($output) . '</td>';
    echo '</tr>';
    echo '</table>';
}
add_action('show_user_profile', 'display_career_values_on_edit');
add_action('edit_user_profile', 'display_career_values_on_edit');


//dhammarato added 10.20.23
add_action('um_submit_form_errors_hook_', 'dh_custom_code', 10, 1);

// Define the custom code function
function dh_custom_code($args) {
// Get the username from the form
$username = $args['user_login'];
echo "there are errors on this page";
// Check if the username already exists
if (username_exists($username)) {
// Display a message
echo "The username $username is already taken. Please choose a different one.";
}
}
