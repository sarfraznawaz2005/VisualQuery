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
    <link href="<?php echo Flight::get(
       'base'
    ); ?>/assets/plugins/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link href="<?php echo Flight::get('base'); ?>/assets/plugins/jGrowl/jquery.jgrowl.css" rel="stylesheet">
    <link href="<?php echo Flight::get('base'); ?>/assets/plugins/select2/select2.css" rel="stylesheet">
    <link href="<?php echo Flight::get('base'); ?>/assets/plugins/select2/select2-bootstrap.css"
          rel="stylesheet">
    <link href="<?php echo Flight::get('base'); ?>/assets/plugins/dataTables/dataTables.bootstrap.css"
          rel="stylesheet">

    <!--<link href="<?php /*echo Flight::get('base'); */ ?>/assets/plugins/summernote/summernote.css" rel="stylesheet">-->

    <link href="<?php echo Flight::get('base'); ?>/assets/css/custom.css?v=<?php echo time(); ?>"
          rel="stylesheet">
</head>

<body>
<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <a class="sidebar-brand" href="<?php echo Flight::get(
           'base'
        ); ?>/home"><i class="fa fa-database"></i> <?php echo Flight::get('appname'); ?></a>

        <label for="database">Select Database</label><br/>
        <select name="database" id="database" class="form-control" style="width: 230px; margin-right: 20px;">
            <?php echo Flight::get('databaseOptions'); ?>
        </select>
        <br/>

        <ul class="sidebar-nav">
            <?php echo Flight::get('tables'); ?>
        </ul>
    </div>
    <!-- End Sidebar -->

    <!-- Page content -->
    <div id="page-content-wrapper">

        <div class="content-header">
            <div class="pull-left"><h1><i class="glyphicon <?php echo $icon; ?>"></i> <?php echo $title; ?></h1></div>

            <div class="pull-right" id="addbuttoncontainer">
                <?php if (false !== strpos($_SERVER['REQUEST_URI'], '/table')) { ?>
                    <button rel="hover_popover" data-content="Build Visual Query" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal-visual-query">
                        <i
                           class="fa fa-database"></i> Visual Query
                    </button>

                    <button rel="hover_popover" data-content="Type Custom Query" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal-custom-query">
                        <i
                           class="fa fa-pencil-square-o"></i> Custom Query
                    </button>
                <?php } ?>

                <a rel="hover_popover" data-content="Log Out" href="<?php echo Flight::get(
                   'base'
                ); ?>/login/logout" class="btn btn-danger btn-lg"><i class="fa fa-sign-out"></i> Logout</a>
            </div>

            <div class="clearfix"></div>
        </div>

        <div id="header_stips" class="progress">
            <div class="progress-bar progress-bar-primary" style="width: 25%;"></div>
            <div class="progress-bar progress-bar-success" style="width: 25%;"></div>
            <div class="progress-bar progress-bar-warning" style="width: 25%;"></div>
            <div class="progress-bar progress-bar-danger" style="width: 25%;"></div>
        </div>

        <?php
        if (getFlashMessage()) {
            $class = (false !== stripos(getFlashMessage(), 'error')) ? 'danger' : 'success';
            $icon = ($class === 'danger') ? 'warning' : 'check-circle';
            ?>
            <div class="bold alert alert-<?php echo $class; ?>">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-<?php echo $icon; ?>"></i> <?php echo getFlashMessage();
                clearFlashMessage(); ?>
            </div>
        <?php } ?>
