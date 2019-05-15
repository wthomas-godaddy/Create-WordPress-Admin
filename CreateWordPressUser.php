<?php
/********************************

USAGE: Create WP Admin User

DESCRIPTION: Creates administrator for WordPress install. If user/email exists will overwrite password and set role. Unlinks upon completion. Uses WordPress core functions. 

LICENSE: GNU General Public License v3.0

NOTES: Replace $user_name and $user_email values with your own. 

********************************/

// hook wordpress
require( dirname( __FILE__ ) . '/wp-load.php' );

// new administrator    
$user_name = 'becauseyouforgot';
$user_email = 'noreply@myadmin.com';

// generate password
$random_password = wp_generate_password( $length=12, $include_standard_special_chars=true );

// create administrator
if( !username_exists( $user_name) && !email_exists( $user_email ) ) {
    $user_id = wp_create_user( $user_name, $random_password, $user_email );
    $user = new WP_User( $user_id );
    $user->set_role( 'administrator' );
    
    if ( is_wp_error( $user_id ) ) { 
        
        print $user_id->get_error_message(); 
    }
    
    echo "user created: <br/>";
    echo $user_name . "<br/>";
    echo $random_password . "<br/>";
}

// update if user exists
else { 
    if( username_exists ( $user_name) ) {
        $user = new WP_User( get_user_by( 'login', $user_name ) );
        $user_data = wp_update_user( array( 'ID' => $user->ID, 'user_pass' => $random_password, 'user_email' => $user_email, 'role' => 'administrator') );

        if ( is_wp_error ( $user_data ) ) {
            print $user_data->get_error_message();
        }
    }  

    if( email_exists ( $user_email) ) {
    $user = new WP_User( get_user_by( 'email', $user_email ) );
    $user_data = wp_update_user( array( 'ID' => $user->ID, 'user_pass' => $random_password, 'user_login' => $user_name, 'role' => 'administrator') );
    
        if ( is_wp_error ( $user_data ) ) {
            print $user_data->get_error_message();
        }
    }
    
    echo "user updated: <br/>";
    echo $user_name . "<br/>";
    echo $random_password . "<br/>";
}

//rm $this->file
unlink( __FILE__ ) or die ('failed to remove file');

?>