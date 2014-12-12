<?php
/*
Plugin Name: Posts by Type Access
Version: 2.1
Plugin URI: 
Author: Greg Ross
Author URI: http://toolstack.com/
Description: Adds a link to drafts, posted, scheduled items and categories under the posts, pages, and other custom post type sections in the admin menu.

Compatible with WordPress 3+.

Read the accompanying readme.txt file for instructions and documentation.

Copyright (c) 2012-14 by Greg Ross

This software is released under the GPL v2.0, see license.txt for details
*/

define( 'PBTA_VER', '2.1' );

function posts_by_type_access() 
	{
	GLOBAL $wpdb;
	
	$post_types = (array)get_post_types( array( 'show_ui' => true ), 'object' );

	$options = get_option( 'posts_by_type_access' );

	$brackets = array( 'open' => '(', 'close' => ')' );

	if( !array_key_exists( 'version', $options ) ) { $options['version'] = ''; }
	
	if( $options['version'] != PBTA_VER )
		{
		$options['version'] = PBTA_VER;
		if( !isset( $options['published'] ) ) { $options['published'] = 1; }
		if( !isset( $options['scheduled'] ) ) { $options['scheduled'] = 1; }
		if( !isset( $options['drafts'] ) ) { $options['drafts'] = 1; }
		if( !isset( $options['numbers'] ) ) { $options['numbers'] = 1; }
		if( !isset( $options['zeros'] ) ) { $options['zeros'] = 1; }

		update_option( 'posts_by_type_access', $options );
		}

	if( $options['square'] == 1 ) { $brackets = array( 'open' => '[', 'close' => ']' ); }
		
	foreach( $post_types as $post_type ) 
		{
		$name = $post_type->name;
		$num_posts = wp_count_posts( $name, 'readable' );

		$path = 'edit.php';
		if( 'post' != $name ) // edit.php?post_type=post doesn't work
			$path .= '?post_type=' . $name;

		if( $options['published'] == 1 )
			{
			$post_status = null;
			$post_status = get_post_status_object( 'publish' );

			$menu_name = __( "Published" );
			if( $options['numbers'] == 1 )
				{
				if( $options['zeros'] == 1 || $num_posts->publish > 0 )
					{
					$menu_name .= " " . $brackets['open'] . number_format_i18n( $num_posts->publish ) . $brackets['close'];
					}
				}

			add_submenu_page( $path, __( 'Published' ), $menu_name, $post_type->cap->edit_posts, "edit.php?post_type=$name&post_status=publish" );
			}

		if( $options['scheduled'] == 1 )
			{
			$post_status = null;
			$post_status = get_post_status_object( 'future' );

			$menu_name = __( "Scheduled" );
			if( $options['numbers'] == 1 )
				{
				if( $options['zeros'] == 1 || $num_posts->future > 0 )
					{
					$menu_name .= " " . $brackets['open'] . number_format_i18n( $num_posts->future ) . $brackets['close'];
					}
				}

			add_submenu_page( $path, __( 'Scheduled' ), $menu_name, $post_type->cap->edit_posts, "edit.php?post_type=$name&post_status=future" );
			}

		if( $options['drafts'] == 1 )
			{
			$post_status = null;
			$post_status = get_post_status_object( 'draft' );
			$menu_name = sprintf( translate_nooped_plural( $post_status->label_count, $num_posts->draft ), number_format_i18n( $num_posts->draft ) );

			$menu_name = __( "Drafts" );
			if( $options['numbers'] == 1 )
				{
				if( $options['zeros'] == 1 || $num_posts->draft > 0 )
					{
					$menu_name .= " " . $brackets['open'] . number_format_i18n( $num_posts->draft ) . $brackets['close'];
					}
				}

			add_submenu_page( $path, __( 'Drafts' ), $menu_name, $post_type->cap->edit_posts, "edit.php?post_type=$name&post_status=draft" );
			}
			
		$args = array( 'orderby' => 'name', 'order' => 'ASC' );
		$categories = get_categories($args);

		foreach($categories as $category) 
			{ 
			$option_name = 'catagory_' . $category->slug;
			if( array_key_exists( $option_name, $options ) ) 
				{
				if( $options[$option_name] == true )
					{
					$menu_name = $category->name;
					if( $options['numbers'] == 1 )
						{
						if( $options['zeros'] == 1 || $num_posts->draft > 0 )
							{
							$cat_count = $wpdb->get_var( "SELECT count(*) FROM {$wpdb->prefix}posts, {$wpdb->prefix}term_relationships WHERE {$wpdb->prefix}posts.ID = {$wpdb->prefix}term_relationships.object_id AND {$wpdb->prefix}posts.post_type = '{$name}' AND {$wpdb->prefix}term_relationships.term_taxonomy_id = '{$category->term_id}'" );
							$menu_name .= " " . $brackets['open'] . number_format_i18n( $cat_count ) . $brackets['close'];
							}
						}
					add_submenu_page( $path, $category->name, $menu_name, $post_type->cap->edit_posts, "edit.php?post_type=$name&post_status=all&cat=" . $category->cat_ID );
					}
				}
			}
		}
	}

function posts_by_type_access_admin_page()
	{
	$args = array( 'orderby' => 'name', 'order' => 'ASC' );
	$categories = get_categories($args);
	
	if( array_key_exists( 'posts_by_type_access', $_POST ) ) 
		{
		if( array_key_exists( 'numbers', $_POST ) )
			{
			$_POST['posts_by_type_access']['numbers'] = 1;
			}
		
		if( $_POST['posts_by_type_access']['numbers'] != 1 )
			{
			$_POST['posts_by_type_access']['zeros'] = 0;
			}

		if( !isset( $_POST['posts_by_type_access']['published'] ) ) { $_POST['posts_by_type_access']['published'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['scheduled'] ) ) { $_POST['posts_by_type_access']['scheduled'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['drafts'] ) ) { $_POST['posts_by_type_access']['drafts'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['numbers'] ) ) { $_POST['posts_by_type_access']['numbers'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['zeros'] ) ) { $_POST['posts_by_type_access']['zeros'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['square'] ) ) { $_POST['posts_by_type_access']['square'] = 0; }
			
		update_option( 'posts_by_type_access', $_POST['posts_by_type_access'] );
		
		print "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>Settings saved.</strong></p></div>\n";
		}

		$options = get_option( 'posts_by_type_access' );

	//***** Start HTML
	?>
<div class="wrap">
	
	<fieldset style="border:1px solid #cecece;padding:15px; margin-top:25px" >
		<legend><span style="font-size: 24px; font-weight: 700;">Posts by Type Access Options</span></legend>
		<form method="post">

				<div><input name="posts_by_type_access[published]" type="checkbox" id="posts_by_type_access_published" value="1" <?php checked('1', $options['published']); ?> /> <?php _e('Add published link to menus'); ?></div>

				<div><input name="posts_by_type_access[scheduled]" type="checkbox" id="posts_by_type_access_scheduled" value="1" <?php checked('1', $options['scheduled']); ?> /> <?php _e('Add scheduled link to menus'); ?></div>

				<div><input name="posts_by_type_access[drafts]" type="checkbox" id="posts_by_type_access_drafts" value="1" <?php checked('1', $options['drafts']); ?> /> <?php _e('Add drafts link to menus'); ?></div>

<?php
	foreach($categories as $category) 
		{ 
		$option_name = 'catagory_' . $category->slug;
		if( !array_key_exists( $option_name, $options ) ) { $options[$option_name] = ''; }
		
		echo '<div><input name="posts_by_type_access[' . $option_name . ']" type="checkbox" id="posts_by_type_access_' . $option_name . '" value="1"' . checked('1', $options[$option_name], false) . ' /> ' . __('Add') . ' "' . $category->name . '" ' . __('category link to the menus') . '</div>';
		echo "\n\n";
		}
?>

				<div>&nbsp;</div>
				
				<div><input name="posts_by_type_access[numbers]" type="checkbox" id="posts_by_type_access_numbers" value="1" <?php checked('1', $options['numbers']); ?> /> <?php _e('Show number of posts to the right of the menu items'); ?></div>
				
				<div style="margin-left: 20px;"><input name="posts_by_type_access[zeros]" type="checkbox" id="posts_by_type_access_zeros" value="1" <?php checked('1', $options['zeros']); ?> /> <?php _e('Show zeros when no post items'); ?></div>

				<div style="margin-left: 20px;"><input name="posts_by_type_access[square]" type="checkbox" id="posts_by_type_access_square" value="1" <?php checked('1', $options['square']); ?> /> <?php _e('Use square brackets'); ?></div>
				
			<div class="submit"><input type="submit" class="button button-primary" name="info_update" value="<?php _e('Update Options') ?>" /></div>
		</form>
		
	</fieldset>
	
	<fieldset style="border:1px solid #cecece;padding:15px; margin-top:25px" >
		<legend><span style="font-size: 24px; font-weight: 700;">About</span></legend>
		<h2><?php echo __('Posts By Type Access Version') . ' ' . PBTA_VER;?></h2>
		<p><?php echo __('by');?> Greg Ross</p>
		<p>&nbsp;</p>
		<p><?php printf(__('Licenced under the %sGPL Version 2%s'), '<a href="http://www.gnu.org/licenses/gpl-2.0.html" target=_blank>', '</a>');?></p>
		<p><?php printf(__('To find out more, please visit the %sWordPress Plugin Directory page%s or the plugin home page on %sToolStack.com%s'), '<a href="http://wordpress.org/plugins/posts-by-type-access/" target=_blank>', '</a>', '<a href="http://toolstack.com/postsbytypeaccess" target=_blank>', '</a>');?></p>
		<p>&nbsp;</p>
		<p><?php printf(__("Don't forget to %srate and review%s it too!"), '<a href="http://wordpress.org/support/view/plugin-reviews/posts-by-type-access" target=_blank>', '</a>');?></p>
</fieldset>
</div>
	<?php
	//***** End HTML
	}
	
function posts_by_type_admin()
{
	add_options_page( 'Posts by Type Access', 'Posts by Type Access', 'manage_options', basename( __FILE__ ), 'posts_by_type_access_admin_page');
}

if ( is_admin() )
	{
	add_action( 'admin_menu', 'posts_by_type_admin', 1 );
	add_action( 'admin_menu', 'posts_by_type_access' );
	}
?>