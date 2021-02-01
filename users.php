<?php

function listUsers()
{
    global $wpdb;
    $usersTable = $wpdb->prefix . "users";
    $users = $wpdb->get_results("SELECT ID, user_nicename, user_email FROM $usersTable");
    return prepareUsers($users);
}

function listUserUsage($id=NULL, $period=NULL)
{
    global $wpdb;
    $userActivityTable = $wpdb->prefix . "idle_session_indicator";

    $query = "SELECT id, session_start, session_status, url FROM $userActivityTable";
    $queryFilter = [];

    if($id) {
        array_push($queryFilter, "user_id = $id");
    }

    if($period) {
        array_push($queryFilter, "session_start between '$period[0]' and '$period[1]'");
    }

    if($queryFilter) {
        $queryFilter = join(" and ", $queryFilter);
        $query .= " where " . $queryFilter;
    }

    $userActivities = $wpdb->get_results($query);
    return prepareUserActivity($userActivities);
}

function statusToText($status)
{
    switch ($status) {
        case 'INIT':
            return "<div class='badge badge-success'>INICIOU</div>";
        case 'CLOSE':
            return "<div class='badge badge-danger'>FINALIZOU</div>";
        default:
            return "<div class='badge badge-warning'>OUTRO</div>";
    }
}

function prepareUserActivity($activities)
{
    $activitiesHtml = "
    <table class='widefat fixed' cellspacing='0'>
    <thead>
        <tr>
            <th class='manage-column column-columnname' scope='col'>#</th>
            <th class='manage-column column-columnname' scope='col'>Data / Hora</th>
            <th class='manage-column column-columnname' scope='col'>Ação</th>
        </tr>
    </thead>
    <tbody>
    ";

    foreach ($activities as $activity) {
        $activitiesHtml .= "
            <tr class='alternate'>
                <th class='column-columnname' scope='row'>#". $activity->id ."</th>
                <td class='column-columnname'>". $activity->session_start ."</td>
                <td class='column-columnname'>". statusToText($activity->session_status) ."</td>
            </tr>
        ";
    }

    $activitiesHtml .= "</tbody>
    <table>";

    return $activitiesHtml;
}


function prepareUsers($users)
{
    $usersHtml = "
    <table class='widefat fixed' cellspacing='0'>
    <thead>
        <tr>
            <th class='manage-column column-columnname' scope='col'>#</th>
            <th class='manage-column column-columnname' scope='col'>Nome</th>
            <th class='manage-column column-columnname' scope='col'>Email</th>
            <th class='manage-column column-columnname' scope='col'></th>
        </tr>
    </thead>
    <tbody>
    ";

    foreach ($users as $user) {
        $usersHtml .= "
            <tr class='alternate'>
                <th class='column-columnname' scope='row'>#". $user->ID ."</th>
                <td class='column-columnname'>". $user->user_nicename ."</td>
                <td class='column-columnname'>". $user->user_email ."</td>
                <td class='column-columnname'>
                    <div class='row-actions'>
                        <span><a href='?page=user-log-access&user=". $user->ID ."'>Visualizar</a></span>
                    </div>
                </td>
            </tr>
        ";
    }

    $usersHtml .= "</tbody>
    <table>";

    return $usersHtml;
}
