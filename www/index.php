<?php

require_once __DIR__ . '/vendor/autoload.php'; 

use Symfony\Component\Dotenv\Dotenv; 

$dotenv = new Dotenv(); 
$dotenv->loadEnv(__DIR__ . '/.env'); 

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0    ">
    <title>Assignment Backend 0825</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
</head>
<body>
    <div class="container">
<?php 
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    echo "<h1>Welcome to the Assignment Backend 0825</h1>";
    echo "<p>Current request URI: <strong>{$requestUri}</strong></p>";
    echo "<p>To view the profile creation page, please navigate to <a href='/pages/profiles/create.php'>Create Profile Page</a>.</p>";  
    echo "<p>To list the profile creation page, please navigate to <a href='/pages/profiles/list.php'>List Profile Page</a>.</p>";  
    include __DIR__ . '/pages/page_end.php';
    exit;
} else {
    echo "<p>Go <a href='/'>home</a>.</p>";
}
