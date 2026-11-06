<?php
// session_config.php
// Include this ONCE at the top of every page that uses sessions.

session_set_cookie_params([
    'lifetime' => 0,        // ends when the browser closes
    'path'     => '/',
    'secure'   => true,     // send only over HTTPS (set false on plain http://localhost if the session won't stick)
    'httponly' => true,     // JavaScript cannot read the cookie
    'samesite' => 'Lax',    // limits cross-site sending (CSRF)
]);

ini_set('session.use_strict_mode', 1);

session_start();