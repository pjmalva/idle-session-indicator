<?php

/*
Plugin Name: IDLE Session Indicator
Plugin URI: idle-session-indicator
Description: Indication of session user IDLE.
Author: Pj Malva
Author URI: https://github.com/pjmalva
Version: v0.0.1
*/

require_once( __DIR__ . '/view.php' );
require_once( __DIR__ . '/logging.php' );
require_once( __DIR__ . '/users.php' );
require_once( __DIR__ . '/database.php' );
require_once( __DIR__ . '/configurations.php' );

register_activation_hook( __FILE__ , 'db_install' );
