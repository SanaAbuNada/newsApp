<?php
require_once __DIR__ . '/../helpers.php';
session_unset();
session_destroy();
header('Location: login.php');
exit;
