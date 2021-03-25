<?php
/**
* Plugin Name: Student Portal Plugin
* Plugin URI: https://www.stallioni.com/
* Description: The purpose of plugin is combine the Wishlist Member and Learndash LMS plugin together.
* Version: 1.0
* Author: Vijayasanthi E
* Author URI: http://stallioni.com/
**/


/********** add constant values *********/
global $wpdb;
$table_name_ecourse = $wpdb->prefix . 'enrolled_courses';
define( 'ENROLLCOURSE_TBNAME', $table_name_ecourse);

$table_name_cf = $wpdb->prefix . 'cfunnels_encourses';
define( 'CFUNNEL_ECOURSE_TBNAME', $table_name_cf);

$table_name_log = $wpdb->prefix . 'stl_users_log';
define( 'LOG_TBNAME', $table_name_log);


define( 'STUDENTPORTAL_DIR', plugin_dir_path( __FILE__ ) );

###Include our XMLRPC Library###
include_once(STUDENTPORTAL_DIR."/infusionsoft-sdk/xmlrpc-3.0/lib/xmlrpc.inc");

$stl_inf_appname = get_option('stl_inf_appname','oa467');
$stl_inf_encrykey = get_option('stl_inf_encrykey','3b1d8d0a4fb7194773d82b67f9ab7f4e');


###Set our Infusionsoft application as the client###
$client = new xmlrpc_client("https://".$stl_inf_appname.".infusionsoft.com/api/xmlrpc");

###Return Raw PHP Types###
$client->return_type = "phpvals";

###Dont bother with certificate verification###
$client->setSSLVerifyPeer(FALSE);

###Our API Key###
$key = $stl_inf_encrykey;



/********* plugin activation hook *****/

register_activation_hook( __FILE__, 'studentportal_activate' ); 
function studentportal_activate() {

 	global $wpdb;
 	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = ENROLLCOURSE_TBNAME;

	$sql="CREATE TABLE IF NOT EXISTS $table_name (
	 `enroll_id` int(11) NOT NULL AUTO_INCREMENT,
	 `enroll_name` varchar(250) DEFAULT NULL,
	  `infusionsoft_tagid` varchar(250) DEFAULT NULL,
	 `wh_mlevel_id` varchar(500) DEFAULT NULL,
	 `leanrdsk_id` varchar(500) DEFAULT NULL,
	 `status` int(11) NOT NULL DEFAULT 1,
	 `created_on` datetime NOT NULL DEFAULT current_timestamp(),
	 `modified_on` datetime DEFAULT NULL,
	 PRIMARY KEY (`enroll_id`)
	) $charset_collate;";

	
	dbDelta( $sql );

	$table_name1 = CFUNNEL_ECOURSE_TBNAME;
	$sql="CREATE TABLE IF NOT EXISTS $table_name1 (
	 `cf_id` int(11) NOT NULL AUTO_INCREMENT,
	 `enroll_id` int(11) DEFAULT 0,
	 `product_id` varchar(250) DEFAULT NULL,
	 `status` int(11) NOT NULL DEFAULT 1,
	 `created_on` datetime NOT NULL DEFAULT current_timestamp(),
	 `modified_on` datetime DEFAULT NULL,
	 PRIMARY KEY (`cf_id`)
	) $charset_collate;";

	dbDelta( $sql );


	$table_name2 = LOG_TBNAME;
	$sql="CREATE TABLE IF NOT EXISTS $table_name2 (
	 `log_id` int(11) NOT NULL AUTO_INCREMENT,
	 `log_type` varchar(250) DEFAULT NULL,
	 `log_details` varchar(250) DEFAULT NULL,
	 `user_email` varchar(250) DEFAULT NULL,
	 `enrolled_courses` varchar(250) DEFAULT NULL,
	 `created_on` datetime NOT NULL DEFAULT current_timestamp(),
	 PRIMARY KEY (`log_id`)
	) $charset_collate;";

	dbDelta( $sql );


 }

// Plugin deactivation hook
register_deactivation_hook( __FILE__, 'studentportal_deactivate' );
function studentportal_deactivate() {}



// add css and js file for admin
add_action('admin_enqueue_scripts', "sp_admin_js_css");
function sp_admin_js_css() {
	wp_register_script('sp_jvalidation_js', plugins_url ( 'assets/js/jquery.validate.min.js', __FILE__ ));
	wp_register_style ( 'sp_bootstrap_css', plugins_url ( 'bootstrap/css/bootstrap.css', __FILE__ ) );  // register css
	wp_register_style ( 'sp_admin_css', plugins_url ( 'assets/css/admin.css', __FILE__ ) );  // register css
}



// add menu to wp-admin
 add_action( 'admin_menu', 'sp_admin_menu_page' );


function sp_admin_menu_page() {
	add_menu_page('Student Portal', 'Student Portal', 'manage_options', 'studentportal_page', 'studentportal_page','dashicons-dashboard',200);
	add_submenu_page('studentportal_page', 'Add Users', 'Add Users', 'manage_options', 'addusers_page', 'addusers_page'); 
	add_submenu_page('studentportal_page', 'Show Users', 'Show Users', 'list_users', 'users.php'); 
	add_submenu_page('studentportal_page', 'Enrolled Courses', 'Enrolled Courses', 'manage_options', 'show_enrolled_courses', 'show_enrolled_courses');
	add_submenu_page('studentportal_page', 'Add Enrolled Courses', 'Add Enrolled Courses', 'manage_options', 'enrolled_courses_page', 'enrolled_courses_page');

	add_submenu_page('studentportal_page', 'Basic Settings', 'Basic Settings', 'manage_options', 'stl_settings', 'stl_settings');
	add_submenu_page('studentportal_page', 'Clickfunnel Settings', 'Clickfunnel Settings', 'manage_options', 'stl_setting_clickfunnel', 'stl_setting_clickfunnel');

	add_submenu_page('studentportal_page', 'Logs', 'Logs', 'manage_options', 'stl_user_log', 'stl_user_log');
	
	
	// array( __( 'Users' ), 'list_users', 'users.php', '', 'menu-top menu-icon-users', 'menu-users', 'dashicons-admin-users' );
	// array( __( 'All Users' ), 'list_users', 'users.php' );
	remove_submenu_page( 'studentportal_page', 'studentportal_page' ); // remove sub menu
}


//plugin settings page
if (!function_exists('stl_settings'))  
{
	function stl_settings(){
		wp_enqueue_style('sp_bootstrap_css');  //include css
		wp_enqueue_style('sp_admin_css');
  		if(file_exists(STUDENTPORTAL_DIR.'stl_settings.php')){
   			include_once(STUDENTPORTAL_DIR.'stl_settings.php');
   		}
	}
}

if (!function_exists('stl_setting_clickfunnel'))  
{
	function stl_setting_clickfunnel(){
		wp_enqueue_style('sp_bootstrap_css');  //include css
		wp_enqueue_style('sp_admin_css');
  		if(file_exists(STUDENTPORTAL_DIR.'stl_setting_clickfunnel.php')){

  			global $wpdb;
			$table_name = CFUNNEL_ECOURSE_TBNAME;
			$table_name1 = ENROLLCOURSE_TBNAME;
			$cf_datas = $wpdb->get_results("SELECT * FROM ".$table_name." as cftb left join ".$table_name1." as entb on entb.enroll_id=cftb.enroll_id WHERE cftb.status = 1");

			
			$enrolled_courses = $wpdb->get_results("SELECT * FROM ".$table_name1." WHERE status = 1");

   			include_once(STUDENTPORTAL_DIR.'stl_setting_clickfunnel.php');
   		}
	}
}

if (!function_exists('stl_user_log'))  
{
	function stl_user_log(){
		wp_enqueue_style('sp_bootstrap_css');  //include css
		wp_enqueue_style('sp_admin_css');
  		if(file_exists(STUDENTPORTAL_DIR.'stl_user_log.php')){

  			global $wpdb;
			$table_name = LOG_TBNAME;
			$table_name1 = ENROLLCOURSE_TBNAME;
			$log_datas = $wpdb->get_results("SELECT * FROM ".$table_name);
			$enrolled_courses = $wpdb->get_results("SELECT * FROM ".$table_name1);
			$enrolled_data = array();
			if($enrolled_courses)
			{
				foreach($enrolled_courses as $enrolled_course)
				{
					$enrolled_data[$enrolled_course->enroll_id] = $enrolled_course->enroll_name;
				}
			}

   			include_once(STUDENTPORTAL_DIR.'stl_user_log.php');
   		}
	}
}



// clickfunnel save function
function cfdata_save(){
	global $wpdb;
	$table_name = CFUNNEL_ECOURSE_TBNAME;
	$enroll_id = $_POST['enroll_id'];
	$product_id = $_POST['product_id'];
	$data=array('enroll_id' => $enroll_id,'product_id' => $product_id, 'status' => 1,'created_on' => date('Y-m-d H:i:s'));
	$wpdb->insert( $table_name, $data);
	echo "true";
	exit();
}
add_action( 'wp_ajax_cfdata_save', 'cfdata_save' );
add_action( 'wp_ajax_nopriv_cfdata_save', 'cfdata_save' );


// clickfunnel delete function
function cfdata_delete(){
	global $wpdb;
	$table_name = CFUNNEL_ECOURSE_TBNAME;
	$data=array('status' => 2,'modified_on' => date('Y-m-d H:i:s'));
	$wpdb->update( $table_name, $data,array('cf_id' => $_POST['cf_id']));
	echo "true";
	exit();
}
add_action( 'wp_ajax_cfdata_delete', 'cfdata_delete' );
add_action( 'wp_ajax_nopriv_cfdata_delete', 'cfdata_delete' );



//add user page
if (!function_exists('addusers_page'))  
{
	function addusers_page(){
		wp_enqueue_style('sp_bootstrap_css');  //include css
		wp_enqueue_style('sp_admin_css');
		wp_enqueue_script('sp_jvalidation_js');

  		if(file_exists(STUDENTPORTAL_DIR.'users/add_user.php')){
  			global $wlm_api_methods;
			$sp_membership_levels =  $wlm_api_methods->get_levels();
			global $wpdb;
			$table_name = ENROLLCOURSE_TBNAME;

			// $call = new xmlrpcmsg("ContactService.retrive", array(
			// 		php_xmlrpc_encode($key), 		#The encrypted API key
			// 		php_xmlrpc_encode($contact)		#The contact array
			// 	));

			// $user = get_user_by( 'email', 'vijayasanthi@stallioni.com' );
			// $rrrrr = findEmailInfusionsoft();
			// echo "<pre>";print_r($user);echo "</pre>";
			$enrolled_courses = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE status = 1");
   			include_once(STUDENTPORTAL_DIR.'users/add_user.php');
 			//  $page_content = file_get_contents(LIMOANYWHERE_DIR.'dashboard.php');

   		}
	}
}

//show users page
if (!function_exists('showusers_page'))  
{
	function showusers_page(){
		wp_enqueue_style('sp_bootstrap_css');  //include css
		wp_enqueue_style('sp_admin_css');
  		if(file_exists(STUDENTPORTAL_DIR.'users/show_users.php')){
  			global $wlm_api_methods;
			$sp_membership_levels =  $wlm_api_methods->get_levels();
			global $wpdb;
			$table_name = ENROLLCOURSE_TBNAME;
   			include_once(STUDENTPORTAL_DIR.'users/show_users.php');
 			//  $page_content = file_get_contents(LIMOANYWHERE_DIR.'dashboard.php');
   		}

	}
}


// Show enrolled courses
if (!function_exists('show_enrolled_courses'))  
{
	function show_enrolled_courses(){
		wp_enqueue_style('sp_bootstrap_css');  //include css
		wp_enqueue_style('sp_admin_css');
  		if(file_exists(STUDENTPORTAL_DIR.'users/show_enrolled_courses.php')){
  			global $wlm_api_methods;
			$sp_membership_levels =  $wlm_api_methods->get_levels();
			global $wpdb;
			$table_name = ENROLLCOURSE_TBNAME;
			$enrolled_courses = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE status = 1");
   			include_once(STUDENTPORTAL_DIR.'users/show_enrolled_courses.php');
   		}
	}
}

//enrolled course save and edit page
if (!function_exists('enrolled_courses_page'))  
{
	function enrolled_courses_page(){
		global $wpdb;
		$table_name = ENROLLCOURSE_TBNAME;
		$course_saved = '';
		if(isset($_POST['encourse_submit']))
		{
			$enroll_id = $_POST['enroll_id'];
			$enroll_name = $_POST['enroll_name'];
			$wh_mlevel_id = $_POST['wh_mlevel_id'];
			$leanrdsk_id = $_POST['leanrdsk_id'];
			$infusionsoft_tagid = $_POST['infusionsoft_tagid'];
			$wh_mlevel_id = implode(',', $wh_mlevel_id);
			$leanrdsk_id = implode(',', $leanrdsk_id);
			if($enroll_id == '')
			{
				$data=array(
		        'enroll_name' => $enroll_name, 
		        'wh_mlevel_id' => $wh_mlevel_id,
		        'leanrdsk_id' => $leanrdsk_id, 
		        'infusionsoft_tagid' => $infusionsoft_tagid,
		        'status' => 1,
		        'created_on' => date('Y-m-d H:i:s'));

		     	$wpdb->insert( $table_name, $data);
			}
			else
			{
				$data=array(
		        'enroll_name' => $enroll_name, 
		        'wh_mlevel_id' => $wh_mlevel_id,
		        'leanrdsk_id' => $leanrdsk_id, 
		        'infusionsoft_tagid' => $infusionsoft_tagid,
		        'status' => 1,
		        'modified_on' => date('Y-m-d H:i:s'));

		     	$wpdb->update( $table_name, $data,array('enroll_id' => $enroll_id));
			}
			$course_saved="<div class='alert alert-success'>Enrolled course saved successfully</div>";
		}
	
		$uenroll_id = $uenroll_name = $uinfusionsoft_tagid = '';
		$uwh_mlevel_id = $uleanrdsk_id = array();
		if(isset($_GET['enroll_id']))
		{
			$enrolled_courses = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE status = 1 and enroll_id = '".$_GET['enroll_id']."'");
			if($enrolled_courses)
			{
				$uenroll_id = $enrolled_courses->enroll_id;
				$uenroll_name = $enrolled_courses->enroll_name;
				$uinfusionsoft_tagid = $enrolled_courses->infusionsoft_tagid;
				$uwh_mlevel_id = $enrolled_courses->wh_mlevel_id;
				$uleanrdsk_id = $enrolled_courses->leanrdsk_id;
				$uwh_mlevel_ids = explode(',',$uwh_mlevel_id);
				$uleanrdsk_ids = explode(',',$uleanrdsk_id);

			}
		}

		wp_enqueue_style('sp_bootstrap_css');  //include css
		wp_enqueue_style('sp_admin_css');
		wp_enqueue_script('sp_jvalidation_js');

  		if(file_exists(STUDENTPORTAL_DIR.'users/enrolled_courses.php')){
  			global $wlm_api_methods;
			$sp_membership_levels =  $wlm_api_methods->get_levels();
   			include_once(STUDENTPORTAL_DIR.'users/enrolled_courses.php');

   		}
	}
}


// enrolled cpurse delete function
function enc_delete(){
	global $wpdb;
	$table_name = ENROLLCOURSE_TBNAME;
	$data=array('status' => 2,'modified_on' => date('Y-m-d H:i:s'));
	$wpdb->update( $table_name, $data,array('enroll_id' => $_POST['enroll_id']));
	echo "true";
	exit();
}
add_action( 'wp_ajax_enc_delete', 'enc_delete' );
add_action( 'wp_ajax_nopriv_enc_delete', 'enc_delete' );


// redirect show user link to wordpress list user page
function showuser_page(){
	header ("Location: ".site_url()."/wp-admin/users.php");
}


//check email unique ajac
function check_unique_email(){
	$email = $_POST['email'];
	$exists = email_exists( $email );
	if ( $exists ) {$status =  true;} 
	else {$status =   false;}
	echo json_encode(array('status' => $status));
	exit();
}
add_action( 'wp_ajax_check_unique_email', 'check_unique_email' );
add_action( 'wp_ajax_nopriv_check_unique_email', 'check_unique_email' );


//check username unique ajax
function check_unique_username(){
	$username = $_POST['username'];
	$exists = username_exists( $username );
	if ( $exists ) {echo "false";} 
	else {echo "true";}
	exit();
}
add_action( 'wp_ajax_check_unique_username', 'check_unique_username' );
add_action( 'wp_ajax_nopriv_check_unique_username', 'check_unique_username' );




//add user tag data to infustionsoft
function addTag($CID, $TID) {
	global $client, $key;

	$call = new xmlrpcmsg("ContactService.addToGroup", array(
		php_xmlrpc_encode($key), 		
		php_xmlrpc_encode($CID),		
		php_xmlrpc_encode($TID),		
	));

	$result = $client->send($call);

	if(!$result->faultCode()) {
		return 1;
	} else {
		return 0;
	}
}


//add user tag data to infustionsoft
// function findEmailInfusionsoft() {
// 	global $client, $key;

// 	$call = new xmlrpcmsg("ContactService.findByEmail", array(
// 		php_xmlrpc_encode($key), 				
// 		php_xmlrpc_encode('vijayasadfsdfsdfdsfnthi@stallioni.com'),
// 		php_xmlrpc_encode(array('email')), 	
// 	));

// 	$result = $client->send($call);

// 	if(!$result->faultCode()) {
// 		return 1;
// 	} else {
// 		return 0;
// 	}
// }


//add user group to infisionsoft
function addCamp($CID, $FUS) {

	global $client, $key;

	$call = new xmlrpcmsg("ContactService.addToCampaign", array(
		php_xmlrpc_encode($key), 		
		php_xmlrpc_encode($CID),		
		php_xmlrpc_encode($FUS),		
	));

	$result = $client->send($call);
	if(!$result->faultCode()) {
		return 1;
	} else {
		// print $result->faultCode() . "<BR>";
		// print $result->faultString() . "<BR>";
		return 0;
	}
}


//Save user details ajax
function sp_save_userdata(){

	/*global $wpdb;
	global $wlm_api_methods;
	$table_name = ENROLLCOURSE_TBNAME;
	$user_memebership = array();
	$user_courses = array();
	$enrolled_courses = $_POST['enrolled_course'];
	$infusionsoft_tagids = array();
	if($enrolled_courses)
	{
		foreach($enrolled_courses as $enrolled_course)
		{
			$enrolled_details = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE enroll_id = '".$enrolled_course."'");
			$wh_mlevel_id = $enrolled_details->wh_mlevel_id;
			$leanrdsk_id = $enrolled_details->leanrdsk_id;
			$infusionsoft_tagid = $enrolled_details->infusionsoft_tagid;
			$infusionsoft_tagid_arr = explode(',',$infusionsoft_tagid);
			$infusionsoft_tagids = array_merge($infusionsoft_tagids,$infusionsoft_tagid_arr);
			$wh_mlevel_ids = explode(',',$wh_mlevel_id);
			$leanrdsk_ids = explode(',',$leanrdsk_id);
			$user_memebership = array_merge($user_memebership,$wh_mlevel_ids);
			$user_courses = array_merge($user_courses,$leanrdsk_ids);
		}
	}

	 $args = array(
        'user_pass'  =>  $_POST['password'],
    	'user_login'    =>  $_POST['username'],
    	'user_email'   =>  $_POST['email'],
    	'first_name'   =>  $_POST['first_name'],
    	'last_name'   =>  $_POST['last_name'], 
        'Levels' => $user_memebership
     );
     $member = $wlm_api_methods->add_member($args);

	if(isset($member['member'])){

     	$user_id = $member['member'][0]['ID'];
     	if(!empty($user_courses))
		{
			foreach($user_courses as $user_course)
			{
				ld_update_course_access($user_id,$user_course);
			}
		}
		$enrolled_courses_txt = implode(',',$enrolled_courses);
		update_user_meta( $user_id, 'enrolled_courses', $enrolled_courses_txt);
		$message = 1;

		if(isset($_POST['check_infusionsoft']))
		{
			global $client, $key;
		
			$contact = array(
				"FirstName" => 	$_POST['first_name'],
				"LastName" => 	$_POST['last_name'],
				"Email" => 		$_POST['email'],
				'_Username1' => $_POST['username'],
				'_Password1' => $_POST['password']
			);

			$call = new xmlrpcmsg("ContactService.add", array(
				php_xmlrpc_encode($key), 		#The encrypted API key
				php_xmlrpc_encode($contact)		#The contact array
			));

			$result = $client->send($call);
			$message = 1;

			if(!$result->faultCode()) {
				$message = 1;
                $conID = $result->value();
                update_user_meta( $user_id, 'infustionsoft_client_id', $conID);
			} else {
				// print $result->faultCode() . "<BR>";
				// print $result->faultString() . "<BR>";
				$message = 'Error in user add to infusionsoft';
			}

			foreach($infusionsoft_tagids as $infusionsoft_tagid)
			{
				$taginsert = addTag($conID,$infusionsoft_tagid);
				if(!$taginsert)
				{
					$message = 'Error in user add to infusionsoft tag save function';
				}
			}
		}
	}
	else
	{
		$message = 'Error in user create';
	}*/

	// $request_datas = $_POST;


	$enarr = $_POST['enrolled_course'];
	if( $enarr !='')
	{
		$enarr = implode(',',$enarr);
	}
	
	$data=array('log_type' => 'Admin','log_state' => 'admin','user_email' => $_POST['email'],'enrolled_courses' => $enarr);
	insert_logdata($data);


	$request_datas = array(
		'enrolled_course' => $_POST['enrolled_course'],
		'password' => $_POST['password'],
		'username' => $_POST['username'],
		'email' => $_POST['email'],
		'first_name' => $_POST['first_name'],
		'last_name' => $_POST['last_name'],
		'check_infusionsoft' => $_POST['check_infusionsoft'],
		'phone' => '',
		'address' => '',
		'city' => '',
		'country' => '',
		'state' => '',
		'zip' => '',
	);

	 $message = save_userdatafn($request_datas);
	echo json_encode(array('message' => $message));
	exit();
}
add_action( 'wp_ajax_sp_save_userdata', 'sp_save_userdata' );
add_action( 'wp_ajax_nopriv_sp_save_userdata', 'sp_save_userdata' );



function zendesk_useradd($user_name = '', $user_email = '',$user_id = ''){

	$stl_zen_subdoamin = get_option('stl_zen_subdoamin','');
	$stl_zen_username = get_option('stl_zen_username','');
	$stl_zen_password = get_option('stl_zen_password','');

	if($stl_zen_subdoamin !='' && $stl_zen_username !='' && $stl_zen_password !='')
	{

		$zendeskGateway = 'https://'.$stl_zen_subdoamin.'.zendesk.com/api/v2/users/create_or_update.json';



		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $zendeskGateway);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{"user": {"name": "'.$user_name.'", "email": "'.$user_email.'"}}');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $stl_zen_username . ':' . $stl_zen_password);

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);

		$result = json_decode($result,true);
		if($result)
		{
			if(isset($result['user']))
			{
				$zendesk_user_id  = $result['user']['id'];
				update_user_meta( $user_id, 'zendesk_user_id', $zendesk_user_id);
			}
		}
		// echo "<pre>";print_r($result);echo "</pre>";exit;


		if (curl_errno($ch)) {
		    // echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

	}

}




function save_userdatafn($request_datas){

	global $wpdb;
	global $wlm_api_methods;
	$table_name = ENROLLCOURSE_TBNAME;
	$user_memebership = array();
	$user_courses = array();
	$enrolled_courses = $request_datas['enrolled_course'];
	$infusionsoft_tagids = array();
	$user_email = $request_datas['email'];
	$exist_user_id = email_exists( $user_email );


	$user_firstname = '';
	$user_email = '';

	if($enrolled_courses)
	{
		foreach($enrolled_courses as $enrolled_course)
		{
			$enrolled_details = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE enroll_id = '".$enrolled_course."'");
			$wh_mlevel_id = $enrolled_details->wh_mlevel_id;
			$leanrdsk_id = $enrolled_details->leanrdsk_id;
			$infusionsoft_tagid = $enrolled_details->infusionsoft_tagid;
			$infusionsoft_tagid_arr = explode(',',$infusionsoft_tagid);
			$infusionsoft_tagids = array_merge($infusionsoft_tagids,$infusionsoft_tagid_arr);
			$wh_mlevel_ids = explode(',',$wh_mlevel_id);
			$leanrdsk_ids = explode(',',$leanrdsk_id);
			$user_memebership = array_merge($user_memebership,$wh_mlevel_ids);
			$user_courses = array_merge($user_courses,$leanrdsk_ids);
		}
	}

	 $args = array(
       // 'user_pass'  =>  $request_datas['password'],
    	//'user_login'    =>  $request_datas['username'],
    	'user_email'   =>  $request_datas['email'],
    	//'first_name'   =>  $request_datas['first_name'],
    	//'last_name'   =>  $request_datas['last_name'], 
        'Levels' => $user_memebership,
        'address1' => $request_datas['address'],
		'city' => $request_datas['city'],
		'country' => $request_datas['country'],
		'state' => $request_datas['state'],
		'zip' => $request_datas['zip'],

     );

	 $conID =  '';
     if($exist_user_id)
	{


		$member = $wlm_api_methods->update_member($exist_user_id,$args);
		$conID = get_user_meta( $exist_user_id, 'infustionsoft_client_id',true);

		$user_tag_id = '508';
	}
	else
	{
		$args['user_login'] =  $request_datas['username'];
		$args['user_pass'] =  $request_datas['password'];
		$args['first_name'] =  $request_datas['first_name'];
		$args['nickname'] =  $request_datas['first_name'];
		$args['display_name'] =  $request_datas['first_name']." ".$request_datas['last_name'];
		$args['last_name'] =  $request_datas['last_name'];
		// $args['user_login'] =  $request_datas['password'];
		$member = $wlm_api_methods->add_member($args);
		$user_tag_id = '506';
	}



     

	if(isset($member['member'])){

     	$user_id = $member['member'][0]['ID'];

     	$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => 'student' ) );


     	if(!empty($user_courses))
		{
			foreach($user_courses as $user_course)
			{
				ld_update_course_access($user_id,$user_course);
			}
		}
		$enrolled_courses_txt = implode(',',$enrolled_courses);
		update_user_meta( $user_id, 'enrolled_courses', $enrolled_courses_txt);
		$message = 1;


if(!$exist_user_id)
			{
				// echo "ifffffff";
$user_firstname = $request_datas['first_name'];
				$user_email = $request_datas['email'];
			}
			else
			{
				$exituser = get_user_by( 'email', $request_datas['email'] );
				$exit_user_info = get_userdata($exituser->ID);
				// echo "elssssss";
				$user_firstname = $exit_user_info->first_name;
				$user_email = $request_datas['email'];
			}

// echo "user_firstname = ".$user_firstname;
// echo "user_email = ".$user_email;

			zendesk_useradd($user_firstname, $user_email,$user_id);



		if(isset($request_datas['check_infusionsoft']))
		{
			global $client, $key;
			
			$contact = array(
				//"FirstName" => 	$request_datas['first_name'],
				//'nickname' => $request_datas['first_name'],
				//"LastName" => 	$request_datas['last_name'],
				"Email" => 		$request_datas['email'],
				'Phone1' => $request_datas['phone'],
				'StreetAddress1' => $request_datas['address'],
				'City' => $request_datas['city'],
				'Country' => $request_datas['country'],
				'State' => $request_datas['state'],
				'PostalCode' => $request_datas['zip'],
			);


			if(!$exist_user_id)
			{
				$contact['_Username1'] = $request_datas['username'];
				$contact['_Password1'] = $request_datas['password'];
				$contact['FirstName'] = $request_datas['first_name'];
				$contact['nickname'] = $request_datas['first_name'];
				$contact['LastName'] = $request_datas['last_name'];
				
				

			}
			else
			{
				$exituser = get_user_by( 'email', $request_datas['email'] );
				$exit_user_info = get_userdata($exituser->ID);
				$contact['_Username1'] = $exituser->user_login;
				$contact['_Password1'] = $exituser->user_pass;
				$contact['FirstName'] = $exit_user_info->first_name;
				$contact['nickname'] = $exit_user_info->first_name;
				$contact['LastName'] = $exit_user_info->last_name;

				


			}
			// if($conID == '')
			// {
			// 	// $call = new xmlrpcmsg("ContactService.add", array(
			// 	// 	php_xmlrpc_encode($key), 		#The encrypted API key
			// 	// 	php_xmlrpc_encode($contact)		#The contact array
			// 	// ));

			// 	$call = new xmlrpcmsg("ContactService.addWithDupCheck", array(
			// 		php_xmlrpc_encode($key), 		#The encrypted API key
			// 		php_xmlrpc_encode($contact),		#The contact array
			// 		php_xmlrpc_encode('Email')
			// 	));
				
			// }
			// else
			// {

			// 	$call = new xmlrpcmsg("ContactService.update", array(
			// 		php_xmlrpc_encode($key), 		#The encrypted API key
			// 		php_xmlrpc_encode($conID),
			// 		php_xmlrpc_encode($contact)		#The contact array
			// 	));
			// }





			$call = new xmlrpcmsg("ContactService.addWithDupCheck", array(
					php_xmlrpc_encode($key), 		#The encrypted API key
					php_xmlrpc_encode($contact),		#The contact array
					php_xmlrpc_encode('Email')
				));


			$result = $client->send($call);
			$message = 1;

			if(!$result->faultCode()) {
				$message = 1;
	            $conID = $result->value();
	            update_user_meta( $user_id, 'infustionsoft_client_id', $conID);
			} else {
				// print $result->faultCode() . "<BR>";
				// print $result->faultString() . "<BR>";
				$message = 'Error in user add to infusionsoft';
			}


			foreach($infusionsoft_tagids as $infusionsoft_tagid)
			{
				$taginsert = addTag($conID,$infusionsoft_tagid);
				if(!$taginsert)
				{
					$message = 'Error in user add to infusionsoft tag save function';
				}
			}

			$taginsert = addTag($conID,$user_tag_id);  //add tag for new user or existing user


		}
	}
	else
	{
		$message = 'Error in user create';
	}
	// echo json_encode(array('message' => $message));

	return $message;
	// exit();
}





add_action( 'rest_api_init', function () {
	$api_base = 'StudentPortalAPI/v1';




  register_rest_route( $api_base, '/userintegration', array(
    'methods' => 'POST',
    'callback' => 'userintegration',
     // 'permission_callback' => function() {
     //                return check_api_valid();
     //                }, 
  ) );



  register_rest_route( $api_base, '/woocommerceintegration', array(
    'methods' => 'POST',
    'callback' => 'woocommerceintegration',
  ));

  register_rest_route( $api_base, '/woo_get_ecourses', array(
    'methods' => 'POST',
    'callback' => 'woo_get_ecourses',
  ));


  //   register_rest_route( '/funnel_webhooks', '/test', array(
  //   'methods' => 'POST',
  //   'callback' => 'userintegration',
  //    // 'permission_callback' => function() {
  //    //                return check_api_valid();
  //    //                }, 
  // ) );






} );


function woo_get_ecourses(){
	$apipassword = (isset($_POST['apipassword']))?$_POST['apipassword']:'';
	$stl_woo_apipassword = get_option('stl_woo_apipassword','btpestudentpassword');
	if($apipassword == $stl_woo_apipassword)
	{
		global $wpdb;
		$table_name = ENROLLCOURSE_TBNAME;
		$enrolled_courses = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE status = 1");
		return array('satus' => true,'enrolled_courses' => $enrolled_courses);
	}
	else
	{
		return array('satus' => false,'enrolled_courses' => '');
	}
	
	// return array('ss' => 'success','postt' => 'ssssss');
}


function password_generate($chars) 
{
  $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
  return substr(str_shuffle($data), 0, $chars);
}



function userintegration(){

//mail('vijayasanthi@stallioni.com','aa Webhook Data','ssssssssssss');

	 //  header("Content-Type: application/json");

  // if (isset($_SERVER['HTTP_ORIGIN'])) {
  //   header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  //   header('Access-Control-Allow-Credentials: true');
  //   header('Access-Control-Max-Age: 86400');    // cache for 1 day
  // }

  // error_reporting(E_ALL);
  // ini_set('display_errors', TRUE);
  // ini_set('display_startup_errors', TRUE);

 
    // $data = json_decode(file_get_contents("php://input"));

$data = file_get_contents('php://input');
$json_data = json_decode($data);
// $obj = $json_data->payload;

$fname=$json_data->first_name;
$lname=$json_data->last_name;
$email=$json_data->email;
$phone=$json_data->phone;
$additional_info = $json_data->additional_info->purchase->product_ids;
$additional_info = serialize($additional_info);

// $nid=$obj->contact->nationbuilder_id;
// Assemble the body of the email...
$message_body = <<<EOM
first name: $fname \n
last name: $lname \n
email: $email \n
phone: $phone \n
additional_info: $additional_info \n

$data \n

EOM;
//mail('vijayasanthi@stallioni.com','Webhook Data newwwwwwww',$message_body);

global $wpdb;

$table_name = CFUNNEL_ECOURSE_TBNAME;
$enrolled_course_arr = array();

$message = "Error";

if($json_data)
{
	$fname=$json_data->first_name;
	$lname=$json_data->last_name;
	$email=$json_data->email;
	$phone=$json_data->phone;
	$address=$json_data->address;
	$city=$json_data->city;
	$country=$json_data->country;
	$state=$json_data->state;
	$zip=$json_data->zip;

	$additional_info = $json_data->additional_info;
	if($additional_info)
	{
		$purchase = $additional_info->purchase;
		if($purchase)
		{
			$product_ids = $purchase->product_ids;
			$password = password_generate(10);

			foreach($product_ids as $product_id)
			{
			
				$enrolled_courses = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE status = 1 and product_id='".$product_id."'");
				if($enrolled_courses)
				{
					foreach($enrolled_courses as $enrolled_course)
					{
						$enrolled_course_arr[] = $enrolled_course->enroll_id;
					}
				}
				
			}
			if(!empty($enrolled_course_arr))
			{
				$enrolled_course_str = implode(',',$enrolled_course_arr);
				//mail('vijayasanthi@stallioni.com','Webhook Data iff','condition checked');
				$request_datas = array(
					'enrolled_course' => $enrolled_course_arr,
					'password' => $password,
					'username' => $email,
					'email' => $email,
					'first_name' => $fname,
					'last_name' => $lname,
					'check_infusionsoft' => '1',
					'phone' => $phone,
					'address' => $address,
					'city' => $city,
					'country' => $country,
					'state' => $state,
					'zip' => $zip,
				);

				$data=array('log_type' => 'Automated','log_state' => 'clickfunnel','user_email' => $email,'enrolled_courses' => $enrolled_course_str);
				insert_logdata($data);

				$message = save_userdatafn($request_datas);


				


			}
			else
			{
				//mail('vijayasanthi@stallioni.com','Webhook Data elseeee','condition false');
				$data=array(
		        'test_cnt' => 'faill', 

		        'created_on' => date('Y-m-d H:i:s'));

		     	//$wpdb->insert( 'wp_test', $data);

			}
			
		}
		else
		{
			//$data=array('test_cnt' => 'no purchase', 'created_on' => date('Y-m-d H:i:s'));
		    //$wpdb->insert( 'wp_test', $data);
		}
	}
	else
	{
		//$data=array('test_cnt' => 'no additionl info', 'created_on' => date('Y-m-d H:i:s'));
		   // $wpdb->insert( 'wp_test', $data);
	}
}
else
{
	//$data=array('test_cnt' => 'no json data', 'created_on' => date('Y-m-d H:i:s'));
		   // $wpdb->insert( 'wp_test', $data);
}

echo $message;



// global $wpdb;
// // header('Content-Type: application/json');
// // echo "<pre>";print_r($_POST);echo "</pre>";
// $post_data = serialize($_POST);
// echo "<pre>";print_r($post_data);echo "</pre>";exit;
// // $post_data = serialize($_REQUEST);

// // $webhookResponse = json_decode(file_get_contents('php://input'), true);

// // $webhookResponse = serialize($webhookResponse);
// // 
// // $postdata = file_get_contents("php://input");


// //  if($json = json_decode(file_get_contents("php://input"), true)) {
// //      // echo "json".print_r($json);
// //      $postdata = $json;
// //  } else {
// //      // echo "post =".print_r($_POST);
// //      $postdata = $_POST;
// //  }


// // $post_data = serialize($postdata);

// 	$data=array(
// 		        'test_cnt' => 'successss = '.$post_data, 

// 		        'created_on' => date('Y-m-d H:i:s'));

// 		     	$wpdb->insert( 'wp_test', $data);
// 		     	return json_encode(array('ss' => 'success','postt' => $_POST));


}


function woocommerceintegration(){

	global $wpdb;
	$message = "Error";
	$apipassword = (isset($_POST['apipassword']))?$_POST['apipassword']:'';
	$stl_woo_apipassword = get_option('stl_woo_apipassword','btpestudentpassword');
	if($apipassword == $stl_woo_apipassword)
	{

		$post_data = serialize($_POST);
		//mail('vijayasanthi@stallioni.com','woocommerce Data','ssssssssssss = '.$post_data);

		$fname =(isset($_POST['first_name']))?$_POST['first_name']:'';
		$lname = (isset($_POST['last_name']))?$_POST['last_name']:'';
		$email = (isset($_POST['email']))?$_POST['email']:'';
		$phone = (isset($_POST['phone']))?$_POST['phone']:'';
		$address = (isset($_POST['address']))?$_POST['address']:'';
		$city = (isset($_POST['city']))?$_POST['city']:'';
		$country = (isset($_POST['country']))?$_POST['country']:'';
		$state = (isset($_POST['state']))?$_POST['state']:'';
		$zip = (isset($_POST['zip']))?$_POST['zip']:'';
		$product_ids = (isset($_POST['product_ids']))?$_POST['product_ids']:'';
		$enrolled_course = (isset($_POST['enrolled_course']))?$_POST['enrolled_course']:'';

		$enrolled_course_arr = explode(',',$enrolled_course);

		$password = password_generate(10);


		if(!empty($enrolled_course_arr))
		{
			$enrolled_course_str = implode(',',$enrolled_course_arr);
			//mail('vijayasanthi@stallioni.com','woocommerce Data iff','condition checked');
			$request_datas = array(
				'enrolled_course' => $enrolled_course_arr,
				'password' => $password,
				'username' => $email,
				'email' => $email,
				'first_name' => $fname,
				'last_name' => $lname,
				'check_infusionsoft' => '1',
				'phone' => $phone,
				'address' => $address,
				'city' => $city,
				'country' => $country,
				'state' => $state,
				'zip' => $zip,
			);


			$data=array('log_type' => 'Automated','log_state' => 'woocommerce','user_email' => $email,'enrolled_courses' => $enrolled_course_str);
				insert_logdata($data);


			$message = save_userdatafn($request_datas);

			


		}
		else
		{
			//mail('vijayasanthi@stallioni.com','woocommerce Data elseeee','condition false');
			$data=array('test_cnt' => 'woocommerce faill', 'created_on' => date('Y-m-d H:i:s'));
			//$wpdb->insert( 'wp_test', $data);


		}	
	
	}
	else
	{
		$data=array('test_cnt' => 'no woocommerce post data', 'created_on' => date('Y-m-d H:i:s'));
			  //  $wpdb->insert( 'wp_test', $data);
	}
	echo $message;

}

function check_api_valid(){
	return true;
}



function insert_logdata($insert_data){
	global $wpdb;
	$table_name = LOG_TBNAME;
	$log_state = $insert_data['log_state'];
	$exist_user_id = email_exists( $insert_data['user_email'] );
	//echo "<pre>";print_r($exist_user_id);echo "</pre>";
// echo "<pre>";print_r($insert_data);echo "</pre>";

	if(!$exist_user_id)
	{
		if($log_state == 'clickfunnel')
		{
			$log_details = 'New user created from clickfunnel webhook';
		}
		else if($log_state == 'woocommerce')
		{
			$log_details = 'New user created from woocommerce product purchased hook';
		}
		else
		{
			$log_details = 'Admin create new user from student portal';
		}
		
	}
	else
	{
		if($log_state == 'clickfunnel')
		{
			$log_details = 'User details updated from clickfunnel webhook';
		}
		else if($log_state == 'woocommerce')
		{
			$log_details = 'User details updated from woocommerce product purchased hook';
		}
		else
		{
			$log_details = 'Admin update a user details from student portal';
		}
	}
	$data=array('log_type' => $insert_data['log_type'],'log_details' => $log_details,'user_email' => $insert_data['user_email'],'enrolled_courses' => $insert_data['enrolled_courses'],'created_on' => date('Y-m-d H:i:s'));
	$wpdb->insert( $table_name, $data);
}
?>
