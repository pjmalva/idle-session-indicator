<?php

function idleSessionConfigurations()
{
    echo '<div class="wrap">
    <h1>Idle Usuários</h1>
    <form method="post" action="options.php">';

        settings_fields('idle_indicator_settings'); // settings group name
        do_settings_sections('idle-indicator'); // just a page slug
        submit_button();

    echo '</form></div>';

}

add_action( 'admin_init',  'idle_indicator_register_settings' );
function idle_indicator_register_settings(){
    register_setting(
        'idle_indicator_settings', // settings group name
        'end_session_time', // option name
        'absint' // sanitization function
    );

    register_setting(
        'idle_indicator_settings', // settings group name
        'end_session_message', // option name
        '' // sanitization function
    );

    add_settings_section(
        'idle_indicator', // section ID
        '', // title (if needed)
        '', // callback function (if needed)
        'idle-indicator' // page slug
    );

    add_settings_field(
        'end_session_time',
        'Tempo de Sessão (Seg.)',
        'session_time_field', // function which prints the field
        'idle-indicator', // page slug
        'idle_indicator', // section ID
        array(
            'label_for' => 'end_session_time',
        )
    );

    add_settings_field(
        'end_session_message',
        'Mensagem',
        'session_message_field', // function which prints the field
        'idle-indicator', // page slug
        'idle_indicator', // section ID
        array(
            'label_for' => 'end_session_message',
        )
    );

    wp_register_style('idleStyle', plugins_url('/style/stylesheet.css', __FILE__));
    wp_enqueue_style('idleStyle');
}

function session_time_field(){
    $text = get_option('end_session_time');
    printf(
        '<input type="number" id="end_session_time" name="end_session_time" value="%s" />',
        esc_attr( $text )
    );
}

function session_message_field(){
    $text = get_option('end_session_message');
    printf(
        '<input type="text" maxlength="200 id="end_session_message" name="end_session_message" value="%s" />',
        esc_attr( $text )
    );
}
