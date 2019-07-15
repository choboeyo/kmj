<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>404 Page Not Found</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <style type="text/css">
        html, body { background-color: #fff; margin:0; font-family: Nunito, sans-serif; color: #636b6f; height:100vh; }
        .error-wrap {height: 100vh;display:-ms-flex;display:-webkit-flex; display:flex;justify-content:center;align-items: center;position:relative; flex-direction: column}
        .error-wrap .code {font-size:26px; padding:0 15px; text-align:center;}
        .error-wrap .message {padding:10px; font-size:14px; text-align: left}
    </style>
</head>
<body>
<div class="error-wrap">
    <div class="code"><?php echo $heading; ?></div>
    <div class="message"><?php echo $message; ?></div>
</div>
</body>
</html>