<?php
// auth_check.php
require_once 'session_config.php';   // replaces the bare session_start()

if (!isset($_SESSION['user_id'])) {
    header('Location: restricted.php');
    exit;
}
