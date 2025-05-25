<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login');
    exit;
}
$user = $_SESSION['user'];
