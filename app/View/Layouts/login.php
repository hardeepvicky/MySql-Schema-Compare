<?php header("Cache-Control: max-age=2592000"); ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title><?= SITE_NAME ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for " name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="/assets/global/plugins/select2/css/select2.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/global/css/components.min.css?<?= VERSION_LIB ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/global/css/plugins.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="/assets/pages/css/login-2.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/js/tf-loader/tf-loader.css?<?= VERSION_CSS ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> 
        
        <script src="/assets/global/plugins/jquery.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        
        <style>
            .login .logo img
            {
                width : auto;
                max-width: 150px;
            }
        </style>
    </head>
    <!-- END HEAD -->

    <body class="login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <img src="/img/company_name_black.png" alt="" /> 
        </div>
        <!-- END LOGO -->
        
        <?php echo $this->fetch('content'); ?>
        
        <!--[if lt IE 9]>
        <script src="/assets/global/plugins/respond.min.js"></script>
        <script src="/assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        
        <!-- BEGIN CORE PLUGINS -->
        
        <script src="/assets/global/plugins/bootbox.min.js?<?= VERSION_LIB ?>"></script>
        
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/assets/global/plugins/select2/js/select2.full.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        
        <script src="/js/tf-loader/tf-loader.js?<?= VERSION_JS ?>" type="text/javascript"></script>
        <script src="/js/ajax.js?<?= VERSION_JS ?>" type="text/javascript"></script>
    </body>
</html>
