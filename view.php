<?php


add_action("admin_menu", "addMenu");
function addMenu()
{
    add_menu_page(
        "Sessão de Usuários",
        "Sessão de Usuários",
        4,
        "idle-session-indicator",
        "idleSessionMenu"
    );

    add_submenu_page(
        NULL,
        'Log de acesso do usuário',
        'Log de acesso do usuário',
        4,
        'user-log-access',
        'listUserDetails'
    );

    add_options_page(
        // 'idle-session-indicator',
        'Idle Usuários',
        'Idle Usuários',
        4,
        'idle-session-indicator-configuration',
        'idleSessionConfigurations'
    );
}

function listUserDetails()
{
    echo "<div class='wrap'>";
    echo "<h1>Listagem de acesso do usuário:</h1>";
    echo listUserUsage($_GET['user']);
    echo "</div>";
}

function idleSessionMenu()
{
    echo <<<'EOD'
    <div class='wrap'>
    <h1>Sessão dos usuários</h1>
    <p>Acompanhamento do log de utilização da plataforma por usuário</p>
    EOD;
    echo listUsers();
    echo "</div>";
}

function listUserDetail()
{
    echo '1';
}

add_filter('wp_authenticate_user', 'userAuthenticated', 30, 3);
function userAuthenticated($user, $password)
{
    LogSession::store('INIT', $user->id);
    return $user;
}

add_action('wp_logout', 'userLogout', 10);
function userLogout($user)
{
    LogSession::store('CLOSE', $user);
}

add_action('wp_footer', 'startIdle');
function startIdle()
{
    if(is_user_logged_in()) {
        $time = get_option('end_session_time');
        $message = get_option('end_session_message');
        $url = getLogoutUrl();
        ?>
        <script type="text/javascript">
            var IDLE_TIMEOUT = <?php echo $time; ?>; //seconds
            var _idleSecondsTimer = null;
            var _idleSecondsCounter = 0;

            document.onclick = function() {
                _idleSecondsCounter = 0;
            };

            document.onmousemove = function() {
                _idleSecondsCounter = 0;
            };

            document.onkeypress = function() {
                _idleSecondsCounter = 0;
            };

            _idleSecondsTimer = window.setInterval(checkIdleTime, 2000);

            function checkIdleTime() {
                _idleSecondsCounter += 2;
                console.log(_idleSecondsCounter)
                if (_idleSecondsCounter >= IDLE_TIMEOUT) {
                    window.clearInterval(_idleSecondsTimer);
                    window.location.href = "<?php echo $url ?>"
                    alert("<?php echo $message; ?>")
                }
            }
        </script>
        <?php
    }
}

add_action( 'check_admin_referer', 'logout_without_confirmation', 1, 2);
function logout_without_confirmation($action, $result){
    if(!$result && ($action == 'log-out')){
        wp_safe_redirect(getLogoutUrl());
        exit();
    }
}

function getLogoutUrl($redirectUrl = ''){
    if(!$redirectUrl) $redirectUrl = site_url();
    $return = str_replace("&amp;", '&', wp_logout_url($redirectUrl));
    return $return;
}
