<?php

class LogSession
{
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function getTableName()
    {
        return $this->wpdb->prefix . 'idle_session_indicator';
    }

    public function storeLog($status, $user)
    {
        global $wp;
        $this->wpdb->insert(
            $this->getTableName(),
            array(
                'session_start' => current_time( 'mysql' ),
                'user_id' => $user,
                'session_status' => $status,
                'url' => $wp->request
            )
        );
    }

    public static function store($status, $user = NULL)
    {
        $log = new LogSession();
        $log->storeLog($status, $user ?? get_current_user_id());
    }
}
