<?php
// prevent page access
if ( !current_user_can( 'manage_options' ) )  {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

?>
<style type="text/css">
.danger {
	color:#FF0004;
}
.plugins-checkbox {
	float:left;
	width:820px;
}
.faqs {
	float:right;
	width:275px;
}
.hidden-installer {
	display:none;	
}
</style>
<?php
// cheeck if plugin installed or not and exclude from installation with an alert message
function plugin_installed($slug) {
	global $installed_text;

	//	get all plugins
	$all_plugins = get_plugins();
	$installed = 0;

	// check each plugin if installed or not by comparing //Folder/Filename
	foreach ($all_plugins as $key => $value) {
		$plugin_file = $key; // folder/file
		$folder = untrailingslashit(plugin_dir_path($plugin_file));  // folder
		if ($slug == $folder) { // if given slug matches the folder
			$installed = 1;
		}
	}

	// alredy installed, show un-checked
	if (true == $installed) {
		$checked = '';
		$installed_text = '<em class="danger">installed</em>';
	// bundle to be installed
	} else {
		$checked = 'checked="checked"';
		$installed_text = '';
	}

echo '
        <tr valign="top">
	        <td><label for="plugin_'.$slug.'">'.$slug.'</label></td>
	        <td>'.$installed_text.'</td>
        </tr>
';

}

// cheeck if plugin exists
function plugin_exists($slug) {

	//	get all plugins
	$all_plugins = get_plugins();
	$installed = 0;

	// check each plugin if installed or not by comparing //Folder/Filename
	foreach ($all_plugins as $key => $value) {
		$plugin_file = $key; // folder/file
		$folder = untrailingslashit(plugin_dir_path($plugin_file));  // folder
		if ($slug == $folder) { // if given slug matches the folder
			$installed = 1;
		}
	}
	return $installed;
}
?>


<h1>My Favorite Plugins Installer</h1>
<?php 
if (!isset($_POST['action'])) {
	$plugins_list = get_option('za_favorite_plugins');
?>

<div class="wrapper">
    <div class="plugins-checkbox">
        <form action="" method="post" id="ajaxfrm" name="ajaxfrm">
        <textarea name="plugin" cols="60" rows="10"><?php echo($plugins_list);?></textarea><br>
		<p>Enter your favorite plugin URL or SLUG (one in each line) <br>
		  Example Full URL: 
	      <em>https://wordpress.org/plugins/contact-form-7/<br>
		  </em>Example SLUG: <em>contact-form-7</em></p>
        <input name="action" type="submit" value="Save Plugins List">
        <input name="action" type="submit" value="Start Installation">

        <?php
		if ($plugins_list != '') {

			// generate form check-box
			$plugin_to_install = $array = preg_split("/\r\n|\n|\r/", $plugins_list);
			$total_plugin_to_install = count($plugin_to_install);
			?>
		  <hr>
		  <table width="100%" cellpadding="5">
			<tr>
			  <td width="30%"><strong>Plugin Name</strong></td>
			  <td width="10%"><strong>Status</strong></td>
			</tr>
			<?php
				// proceed with plugins
				for ($i = 0; $i < $total_plugin_to_install; $i++) {		
					// replace HTTPS/HTTP words
					$slug = str_replace('https://wordpress.org/plugins/', '', $plugin_to_install[$i]);	// replace HTTPS
					$slug = str_replace('http://wordpress.org/plugins/', '', $slug);	// replace HTTP
					$slug = str_replace('https://www.wordpress.org/plugins/', '', $slug);
					$slug = str_replace('http://www.wordpress.org/plugins/', '', $slug);
					$slug = str_replace('/', '', $slug);	// remove last remaining /
					plugin_installed($slug);
				}
			?>
		  </table>
	  </form>
		<?php } ?>
    </div>
    
    <div class="faqs">
    <h2>FAQs</h2>
    <p><strong>Why should I use ZAMFPI?<br>
    </strong>Just one reason that you will not need to waste time into manual downloading, installation and activation of your favorite plugins in wordpress whether that is for client's website, person website or many wordpress websites.</p>
    <p><strong>How does it work?</strong><br>
      Every entered plugin will be <strong> downloaded, installed and activated</strong> automatically through MFPI. You don't have to do anything except click on installation. Additionally, you can save your favorite plugins list as well.</p>
    <p><strong>What if a plugin is already installed?<br>
  </strong>MFPI will display <em class="danger">installed</em>  status if a plugin already exists and uncheck it. Even if plugin is in the list then installer will SKIP that plugin.    </p>
    <p><strong>Can I provide suggestion?</strong></p>
    <p>If you have any  suggestion about  plugin, you can <a href="http://www.zeeshanarshad.com">contact</a> me.</p>
    </div>
</div>

<?php } ?>

<?php
function microtime_float()
{
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}

if (isset($_POST['action'])) { 

	// save plugin list
	if ($_POST['action'] == 'Save Plugins List') {
		$plugins = trim($_POST['plugin']);
		update_option('za_favorite_plugins', $plugins);
		echo '<p>Plugins list has been saved.</p>';
		echo '<a href="">&laquo; Go back</a>';
	}
	
	if ($_POST['action'] == 'Start Installation') {
	
		$plugins = trim($_POST['plugin']);
		update_option('za_favorite_plugins', $plugins);
		$plugin_to_install = $array = preg_split("/\r\n|\n|\r/", $plugins);
		$total_plugin_to_install = count($plugin_to_install);
	
		// if at least one plugin selected
		if ($plugins != '') {
	
			$time_start = microtime_float();
			echo '<h3>Plugins are being installed. Please wait until that installation is complete.</h3>';
	
			// proceed with plugins
			$plugin_no = 1;
			for ($i = 0; $i < $total_plugin_to_install; $i++) {
	
				// replace HTTPS/HTTP words
				$slug = str_replace('https://wordpress.org/plugins/', '', $plugin_to_install[$i]);	// replace HTTPS
				$slug = str_replace('http://wordpress.org/plugins/', '', $slug);	// replace HTTP
				$slug = str_replace('https://www.wordpress.org/plugins/', '', $slug);
				$slug = str_replace('http://www.wordpress.org/plugins/', '', $slug);
				$slug = str_replace('/', '', $slug);	// remove last remaining /

	
				// if plugin does not exist then process the installation
				if (!plugin_exists($slug)) {
	
					// Base Configuration
					$plugin['source'] = 'repo'; // $_GET['plugin_source']; // Plugin source.
					require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Need for plugins_api.
					require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // Need for upgrade classes.
	
					// get plugin information
					$api = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );

					// if error dispay fail	
					if ( is_wp_error( $api ) ) {
						echo "<p>$slug <em class='danger'>(Failed...kindly spellcheck the URL or SLUG)</em></p>";

					// proceed
					} else {

						// Set plugin source to WordPress API link if available.
						if ( isset( $api->download_link ) ) {
							$plugin['source'] = $api->download_link;
						}
					
						// Set type, based on whether the source starts with http:// or https://.
						$type = preg_match( '|^http(s)?://|', $plugin['source'] ) ? 'web' : 'upload';
					
						$nonce = 'install-plugin_' . $api->slug;
				
						// Prefix a default path to pre-packaged plugins.
						$source = ( 'upload' == $type ) ? $this->default_path . $api->download_link : $api->download_link;
					
						// Create a new instance of Plugin_Upgrader.
						$upgrader = new Plugin_Upgrader( $skin = new Plugin_Installer_Skin( compact( 'type', 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
					
						echo "<h2><span id='install-status".$plugin_no."'><img src='".plugins_url( 'images/loader.gif', __FILE__ )."' width='24' height='24'></span> Installing: $api->name ($plugin_no of $total_plugin_to_install)</h2>";
						// Perform the action and install the plugin from the $source urldecode().
		
						echo '<div class="hidden-installer">';
						$upgrader->install( $source );
						echo '</div>';
				
						//	get all plugins
						$all_plugins = get_plugins();
		
						// scan existing plugins
						foreach ($all_plugins as $key => $value) {
		
						// get full path to plugin MAIN file
						
							// folder and filename
							$plugin_file = $key;
							$slash_position = strpos($plugin_file, '/');
							$folder = substr($plugin_file, 0, $slash_position);
		
							// match FOLDER against SLUG
							// if matched then ACTIVATE it
							if ($slug == $folder) {
								// Activate
								$result = activate_plugin( ABSPATH . 'wp-content/plugins/'.$plugin_file);
								if ( is_wp_error( $result ) ) {
									// Process Error
								}
		
							} // if matched
						} // activation
						?>
					<script>
					jQuery("#install-status<?php echo($plugin_no);?>").html('<img src="<?php echo (plugins_url( 'images/tick.png', __FILE__ ));?>" />');
                    </script>
					<?php
					}
				} else { // plugin exists
					echo "<p>$slug <em class='danger'>(already installed)</em></p>";
				}						

			$plugin_no++;
			} // install all plugin sent by form
	
	
			$time_end = microtime_float();
			$time = $time_end - $time_start;
			$time = number_format($time, 2);
			echo "<hr><p><strong>Installation process has been finished in $time seconds.</strong></p><hr>";
			echo '<a href="">&laquo; Return to Installer</a>';
			// Flush plugins cache so we can make sure that the installed plugins list is always up to date.
			wp_cache_flush();
	
		// no plugin selected
		} else {
			echo '<p>No plugin was entered.</p>';
			echo '<a href="">&laquo; Go back</a>';
		} // no 
	
	} // form submit
}
?>