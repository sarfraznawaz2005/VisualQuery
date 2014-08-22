<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="<?php echo Flight::get('base'); ?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo Flight::get('base'); ?>/favicon.ico" type="image/x-icon">

    <title><?php echo Flight::get('appname'); ?></title>
    <link href="<?php echo Flight::get('base'); ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Flight::get('base'); ?>/assets/css/font-awesome.css" rel="stylesheet">

    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #eee;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            font-weight: normal;
        }

        .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>

</head>

<body>

<div class="container">
    <form class="form-signin" action="./login" role="form" method="post">
        <?php
        if (getFlashMessage()) {
            $class = (false !== stripos(getFlashMessage(), 'error')) ? 'danger' : 'success';
            $icon = ($class === 'danger') ? 'warning' : 'check-circle';
            ?>
            <div class="bold alert alert-<?php echo $class; ?>">
                <i class="fa fa-<?php echo $icon; ?>"></i> <?php echo getFlashMessage();
                clearFlashMessage(); ?>
            </div>
        <?php } ?>

        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><i class="fa fa-sign-in"></i> Sign in</button>
    </form>
</div>

<script src="<?php echo Flight::get('base'); ?>/assets/js/bootstrap.min.js"></script>

</body>
</html>