
<?php
// dhammarato soft.in starts here
// code addinf to message code dhammarato soft.in
// Define the function osftestemail that takes the email and the goto url as parameters dhammarato
function osfEnquireyTE($user_id) {
    $testemail = "soft.in";
    $goto = "/newsite/test/Email-bad/?r=$user->ID";    // Check if the email is valid using filter_var
    $user_email  =  $user->user_email ;
if (strpos ($user_email, $testemail) !== false) { // If yes, use header to redirect to the URL
	ob_start(); // start output buffering// your code here
	if (!headers_sent()) {header('Location: '.$goto); exit; } else {
        //echo "<a href=\"#\"\"window.open($goto,'_blank',)\">$user->ID</a>" ;
        echo '<script type="text/javascript">window.location.href="'
        .$goto.'"</script><noscript><meta http-equiv="refresh" 	
        content="0;url='.$goto.'" /></noscript>';
	    exit;}
	ob_end_flush();
} else {
    osfEnquirey ($user->ID);   
} 
}
// If no, do something else
//echo "The email does not contain $testemail";
//end dhammatato soft.in
// dhammarato end code     end og soft.in     
?>

<?php
function maxcoach_child_enqueue_styles() {
// Get the parent theme's stylesheet URL
$parent_style = 'maxcoach-style';
// Enqueue the parent theme's stylesheet
wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
// Enqueue the child theme's stylesheet
wp_enqueue_style( 'maxcoach-child-style',
get_stylesheet_directory_uri() . '/style.css',
array( $parent_style ),
wp_get_theme()->get('Version')
);
}
// Add the function to the wp_enqueue_scripts action
add_action( 'wp_enqueue_scripts', 'maxcoach_child_enqueue_styles' );
?>

  
 <?php
function debug_to_console($data) {
$output = $data;
if (is_array($output))
$output = implode(',', $output);
echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
?>