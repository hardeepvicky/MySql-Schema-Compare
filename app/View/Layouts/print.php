<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Seven Rocks International" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/global/css/components.min.css?<?= VERSION_LIB ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/global/css/plugins.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        
        <link href="/css/style.css?<?= VERSION_CSS ?>" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap-extend.css?<?= VERSION_CSS ?>" rel="stylesheet" type="text/css" />
        
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="/favicon.ico" /> 
        <script src="/assets/global/plugins/jquery.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
    </head>
        <!-- END HEAD -->
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <?php echo $this->fetch("content"); ?>
    </body>
</html>