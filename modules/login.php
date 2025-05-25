<?php
session_start();
require_once 'include/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = readJSON('users.json');
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    foreach ($users as $user) {
        if ($user['nm'] === $username && $user['pass'] === $password) {
            $_SESSION['user'] = $user;
            header('Location: index');
            exit;
        }
    }

    $error = "Invalid username or password";
}
?>