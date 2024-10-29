<?php
/*
Plugin Name: Admin bar AutoHider
Plugin URI: https://www.coffee-break-designs.com/service/admin_bar_autohider/
Description: Auto Hide and choose position.
Author: Wada Minoru
Version: 1.0
Author URI: http://www.coffee-break-designs.com/
License: GPL2

Copyright 2013 Wada Minoru(email : wada@coffee-break-designs.com)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
class ADMIN_BAR_AUTOHIDER {
	private $VERSION = "1.01";

	function __construct() {
		$this -> add_action('get_header', 'remove_admin_login_header');
		$this -> add_action('wp_enqueue_scripts', 'wp_enqueue_scripts' );
		$this -> add_action('wp_head', 'wp_head' );

		// admin
		$this -> add_action('admin_menu', 'admin_bar_autohider_create_menu');
	}

	function add_action($action, $function = '', $priority = 10, $accepted_args = 1) {
		add_action($action, array(&$this, $function == '' ? $action : $function), $priority, $accepted_args);
	}
	function add_filter($filter, $function = '', $priority = 10, $accepted_args = 1) {
		add_filter($filter, array(&$this, $function == '' ? $filter : $function), $priority, $accepted_args);
	}
	function remove_admin_login_header() {
		remove_action('wp_head', '_admin_bar_bump_cb');
	}
	function wp_enqueue_scripts() {
		if (is_user_logged_in()) {
			// $purl = plugins_url().'/admin_bar_autohider';
			wp_enqueue_script('admin_bar_autohider', plugin_dir_url( __FILE__ ).'/js/main.js' ,array(),'',true);
			wp_enqueue_style( 'admin_bar_autohider', plugin_dir_url( __FILE__ ).'/css/style.css' ,array('admin-bar'));
		}
	}
	function wp_head() {
		if (is_user_logged_in()) {
			$pos      = get_option( 'admin_bar_autohider-position','top');
			$autohide = get_option( 'admin_bar_autohider-autohide','off');
			$autohide_time = esc_attr( get_option('admin_bar_autohider-autohide_time','1000') );
			?>
				<style>
				#wpadminbar { opacity: 0 }
				</style>
				<script>
				window.GLOBAL_admin_bar_autohider_autohide = "<?php echo $autohide; ?>";
				window.GLOBAL_admin_bar_autohider_pos = "<?php echo $pos; ?>";
				window.GLOBAL_admin_bar_autohider_autohide_time = "<?php echo $autohide_time; ?>";
				</script>
			<?php
		}
	}

	/*
		Admin
	 */
	function admin_bar_autohider_create_menu(){
		add_options_page('Admin_Bar_Setting', 'Admin bar AutoHider', 'administrator', __FILE__, array ($this, 'admin_bar_autohider_setting'));
		$this -> add_action( 'admin_init', 'register_mysettings' );
	}
	function register_mysettings() {
		//register our settings
		register_setting( 'admin_bar_autohider-settings-group', 'admin_bar_autohider-position' );
		register_setting( 'admin_bar_autohider-settings-group', 'admin_bar_autohider-autohide' );
		register_setting( 'admin_bar_autohider-settings-group', 'admin_bar_autohider-autohide_time' );
	}
	function admin_bar_autohider_setting() {
		?>
		<style>
			.donate_me {
				display: block;
				background-color: #fff;
				border: 1px solid #EEE;
				text-align: center;
				font-size: 1rem;
				line-height: 1.5;
				border-radius: 5px;
				/*max-width: 237px;*/
				padding: 10px;
			}
			.donate_me a.btn{
				display: inline-block;
				background: -moz-linear-gradient(top,#0099CC 0%,#006699);
				background: -webkit-gradient(linear, left top, left bottom, from(#0099CC), to(#006699));
				border: 2px solid #EEE;
				color: #FFF;
				border-radius: 4px;
				text-shadow: 0px 0px 3px rgba(0,0,0,0.5);
				padding: 4px 10px;
				text-decoration: none;
				transition: all 0.5s ease;
				margin-left: 1em;
			}
			.donate_me a.btn:hover {
				opacity: 0.8;
				border-color: #006699;
				font-size: 110%;
			}
		</style>
		<div class="wrap">
			<h2>WP Admin Bar Custom <?php echo $this->VERSION; ?></h2>
			<div class="donate_me">
				Your contribution is needed for making this plugin better.<a href="http://www.amazon.co.jp/registry/wishlist/2GTPGRG7TARC1/ref=cm_sw_r_tw_ws_e80dxbWVFZ83B" target="_blank" class="btn">Amazon.co.jp - Wish List &raquo;</a>
			</div>
			<form method="post" action="options.php">
			    <?php settings_fields(      'admin_bar_autohider-settings-group' ); ?>
			    <?php do_settings_sections( 'admin_bar_autohider-settings-group' ); ?>

			    <table class="form-table">
			    	<?php
			    		$pos      = esc_attr( get_option('admin_bar_autohider-position', 'top') );
			    		$autohide = esc_attr( get_option('admin_bar_autohider-autohide', 'off') );
			    		$autohide_time = esc_attr( get_option('admin_bar_autohider-autohide_time','1000') );
			    	 ?>

			        <tr valign="top">
			        <th scope="row">AutoHide</th>
			        <td>
			        	<label>
			        		<input type="radio" name="admin_bar_autohider-autohide" value="on"  <?php if($autohide == 'on' ){echo "checked";} ?>> on
			        	</label><br>
			        	<label>
			        		<input type="radio" name="admin_bar_autohider-autohide" value="off"  <?php if($autohide == 'off'){echo "checked";} ?>> off
			        	</label>
			        </td>
			        </tr>
			        <tr valign="top">
			        <th scope="row">AutoHide Delay</th>
			        <td>
			        	<input type="number" name="admin_bar_autohider-autohide_time" value="<?php echo $autohide_time; ?>"> ms
			        </td>
			        </tr>

			        <tr valign="top">
			        <th scope="row">POSITION</th>
			        <td>
			        	<select name="admin_bar_autohider-position" id="">
			        		<option value="top"    <?php if($pos == 'top'   ){echo "selected";} ?> >top</option>
			        		<option value="bottom" <?php if($pos == 'bottom'){echo "selected";} ?> >bottom</option>
			        	</select>
			        </td>
			        </tr>

			    </table>

			    <?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

}

$admin_bar_autohider = new ADMIN_BAR_AUTOHIDER;
