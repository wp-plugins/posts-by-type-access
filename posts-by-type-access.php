<?php
/*
Plugin Name: Posts by Type Access
Version: 1.1
Plugin URI: 
Author: Greg Ross
Author URI: 
Description: Adds a link to Drafts, posted and scheduled items under the Posts, Pages, and other custom post type sections in the admin menu.

Compatible with WordPress 3+.

Read the accompanying readme.txt file for instructions and documentation.

Copyright (c) 2012 by Greg Ross

This software is released under the GPL v2.0, see license.txt for details
*/

define( 'PBTA_VER', '1.0' );

function posts_by_type_access() 
	{
	$post_types = (array)get_post_types( array( 'show_ui' => true ), 'object' );

	$options = get_option( 'posts_by_type_access' );
	
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
					$menu_name .= " (" . number_format_i18n( $num_posts->publish ) . ")";
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
					$menu_name .= " (" . number_format_i18n( $num_posts->future ) . ")";
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
					$menu_name .= " (" . number_format_i18n( $num_posts->draft ) . ")";
					}
				}

			add_submenu_page( $path, __( 'Drafts' ), $menu_name, $post_type->cap->edit_posts, "edit.php?post_type=$name&post_status=draft" );
			}
		}
	}

function posts_by_type_access_admin_page()
	{
	if( array_key_exists('posts_by_type_access', $_POST) )
		{
		if( $_POST['posts_by_type_access']['numbers'] != 1 )
			$_POST['posts_by_type_access']['zeros'] = 0;

		if( !isset( $_POST['posts_by_type_access']['published'] ) ) { $_POST['posts_by_type_access']['published'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['scheduled'] ) ) { $_POST['posts_by_type_access']['scheduled'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['drafts'] ) ) { $_POST['posts_by_type_access']['drafts'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['numbers'] ) ) { $_POST['posts_by_type_access']['numbers'] = 0; }
		if( !isset( $_POST['posts_by_type_access']['zeros'] ) ) { $_POST['posts_by_type_access']['zeros'] = 0; }
			
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

				<div>&nbsp;</div>
				
				<div><input name="posts_by_type_access[numbers]" type="checkbox" id="posts_by_type_access_numbers" value="1" <?php checked('1', $options['numbers']); ?> /> <?php _e('Show number of posts to the left of the menu items'); ?></div>
				
				<div style="margin-left: 20px;"><input name="posts_by_type_access[zeros]" type="checkbox" id="posts_by_type_access_zeros" value="1" <?php checked('1', $options['zeros']); ?> /> <?php _e('Show zeros when no post items'); ?></div>
				
			<div class="submit"><input type="submit" name="info_update" value="<?php _e('Update Options') ?> &raquo;" /></div>
		</form>
		
	</fieldset>
	
	<fieldset style="border:1px solid #cecece;padding:15px; margin-top:25px" >
		<legend><span style="font-size: 24px; font-weight: 700;">About</span></legend>
		<p>Posts By Type Access Version 1.1</p>
		<p>by Greg Ross</p>
		<p>&nbsp;</p>
		<p>Licenced under the <a href="http://www.gnu.org/licenses/gpl-2.0.html" target=_blank>GPL Version 2</a></p>
		<p>Visit the plug-in site at <a href="http://ToolStack.com/PostsByTypeAccess" target=_blank>ToolStack.com</a>!</p>
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