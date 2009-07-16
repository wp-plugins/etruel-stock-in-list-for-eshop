<?php
/**
 * @package WordPress_Plugins
 * @subpackage wp-esilfe
*/
if ('edit.php' != basename($_SERVER['HTTP_REFERER']))
     die ('<h2>'.'Direct File Access Prohibited'.'</h2>');
  
define('WP_USE_THEMES', false); // definir WP_USE_THEMES antes de hacer el require  
$diresilfe=str_replace('wp-content/plugins/wp-esilfe/stockchg.php','',$_SERVER['SCRIPT_FILENAME']);
require_once($diresilfe.'wp-load.php');    
if ( !defined('ABSPATH') )	die("NO DEFINIDO");
if(isset($_POST['id']) && isset($_POST['act']))  {
	global $wpdb,$userdata,$current_user,$user_id,$wp_roles, $post;
	$post_id = $_POST['id'];
	$meta_value = $_POST['act'];
	$meta_key = '_Stock Available';
	get_currentuserinfo();
	if(current_user_can('eShop')){
		if (update_post_meta($post_id, $meta_key, $meta_value)) {
			die('YES');
		}else{
			die('NO');
		}
	}else{
		die("NO");
	}
}
die();
?>