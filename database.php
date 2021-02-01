<?php

global $idle_db_version;
$idle_db_version = '0.0.1';

function db_install()
{
    global $wpdb;
    global $idle_db_version;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . "idle_session_indicator";

    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    user_id int(11) NOT NULL,
    session_start datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    session_status varchar(5) DEFAULT 'INIT',
    url varchar(200),
    PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta( $sql );
    add_option( 'idle_db_version', $idle_db_version );
}
