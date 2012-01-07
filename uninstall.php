<?php
if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	{
	// Remove options from the database
	delete_option( 'posts_by_type_access' );
	}
?>
