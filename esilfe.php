<?php
/*
Plugin Name: etruel Stock in list for eshop plugin
Plugin URI: http://www.netmdp.com/2009/07/esilfe-plugin/
Description: Show a checkbox clickable of "out of Stock" in one column in the list of posts then you do not need edit a post only for change this value.  If the user can´t (eshop) then only see if have stock.
Version: 0.1
Author: etruel
Author URI: http://www.netmdp.com
*/

/*  Copyright 2009	Esteban  Truelsegaard(email : esteban@netmdp.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( is_admin() ) {   // only for backend

// Version of the plugin
define('STOCK_IN_LIST_CURRENT_VERSION', '0.1' );
define('STOCK_IN_LIST_COLUMN', 'control_stock_in_list');

// i18n plugin domain 
define('STOCK_IN_LIST_I18N_DOMAIN', 'wp-esilfe');
if ( !defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( !defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
/* Define the plugin path slug  */
define("SIL_PLUGINPATH", "/" . plugin_basename( basename( dirname(__FILE__) ) ) . "/");
/* Define the plugin full url   */
define("SIL_PLUGINFULLURL", WP_PLUGIN_URL . SIL_PLUGINPATH );

/* Initialize and load the plugin textdomain   (for future releases ? )   */
function esilfe_load_textdomain() {
	if ( function_exists('load_plugin_textdomain') ) {
		if ( !defined('WP_PLUGIN_DIR') ) {
				load_plugin_textdomain('wp-esilfe', str_replace( ABSPATH, '', dirname(__FILE__) ) . '/');
		} else {
			load_plugin_textdomain('wp-esilfe', false, dirname(plugin_basename(__FILE__)) . '/');
		}
	}
}
/**
 * Plugin activation
 */
add_action('activate_wp-etruel-silfe/wp-etruel-silfe.php','stock_in_list_plugin_activation');
function stock_in_list_plugin_activation() {
	// Add all options, nothing already installed
}

add_action('admin_head-edit.php','admin_sil_col_css');
function admin_sil_col_css() {  
?><style type="text/css">.fixed .control_stock_in_list, #control_stock_in_list{width:3.3em;} 
.column-title{width:365px;}</style> <!-- ACAET -->
<script type="text/javascript">

	function stockchg(post,curval) {
		jQuery("#stk"+post).html('<img src="<?php echo SIL_PLUGINFULLURL; ?>loader.gif">');
		if (curval=='0') {
			curval='Yes';
			var imagen= '<?php echo SIL_PLUGINFULLURL; ?>yes.png';
			var imagenno= '<?php echo SIL_PLUGINFULLURL; ?>no.png';
		}else{
			curval='0';
			var imagen= '<?php echo SIL_PLUGINFULLURL; ?>no.png';
			var imagenno= '<?php echo SIL_PLUGINFULLURL; ?>yes.png';
		}
		jQuery.post("<?php echo SIL_PLUGINFULLURL; ?>stockchg.php",{id:post,act:curval},function(datos){
					if(datos!="YES"){
						alert("ERROR: El valor no fue grabado en la base de datos");
						jQuery("#stk"+post).html('<a href="JavaScript:void(0);" onclick="stockchg('+post+',\''+curval+'\');return false;"><img src="'+imagenno+'"></a>');
					}else{ 
						jQuery("#stk"+post).html('<a href="JavaScript:void(0);" onclick="stockchg('+post+',\''+curval+'\');return false;"><img src="'+imagen+'"></a>');
					}
				});  //mando los datos
	}
	
</script><?php
}

//  Add a column in the post listing */
add_filter('manage_posts_columns', 'silfe_add_stock_in_list_column');
add_action('manage_posts_custom_column', 'silfe_make_checkbox', 100, 2);
function silfe_add_stock_in_list_column($columns) {
	$columns[STOCK_IN_LIST_COLUMN] = __('Stock');
	return $columns;
}

function silfe_make_checkbox($column_name, $id) {
	if ($column_name == STOCK_IN_LIST_COLUMN) {
	 	get_currentuserinfo() ;
		if(current_user_can('eShop')){
			echo '<div id="stk'.$id.'"><a href="JavaScript:void(0);" onclick="stockchg('.$id.',\''.silfe_instock_meta($id).'\');return false;"><img src="' . SIL_PLUGINFULLURL . ((silfe_instock_meta($id)=='Yes') ? 'yes.png' : 'no.png') .' "></a></div>';
		}else{
			echo '<div id="stk'.$id.'"><img src="' . SIL_PLUGINFULLURL . ((silfe_instock_meta($id)=='Yes') ? 'yes.png' : 'no.png') .' "></div>';
		}
	}
}

/* Return the meta information _Stock Available of a post  */
function silfe_instock_meta($id) {
	$key = '_Stock Available';
	$meta_value = get_post_meta($id, $key, true);
	return $meta_value;
}

}  // is_admin

?>