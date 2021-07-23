<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title><?= (isset($title_for_layout) ? $title_for_layout : "") ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Seven Rocks International" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?= Path::THEME_PLUGIN ?>font-awesome/css/font-awesome.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="<?= Path::THEME_PLUGIN ?>simple-line-icons/simple-line-icons.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="<?= Path::THEME_PLUGIN ?>bootstrap/css/bootstrap.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="<?= Path::THEME_PLUGIN ?>bootstrap-switch/css/bootstrap-switch.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?= Path::THEME_PLUGIN ?>bootstrap-datepicker/css/bootstrap-datepicker3.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="<?= Path::THEME_PLUGIN ?>bootstrap-dialog/bootstrap-dialog.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="<?= Path::THEME_PLUGIN ?>bootstrap-datepicker/css/bootstrap-datepicker3.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="<?= Path::THEME_PLUGIN ?>select2/css/select2.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="<?= Path::THEME_PLUGIN ?>select2/css/select2-bootstrap.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/global/css/components.min.css?<?= VERSION_LIB ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/global/css/plugins.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <link href="<?= Path::THEME_PLUGIN ?>fancybox/dist/jquery.fancybox.min.css?<?= VERSION_LIB ?>" type="text/css" rel="stylesheet"  />
        <link href="<?= Path::THEME_PLUGIN ?>/fancybox/src/css/slideshow.css?<?= VERSION_LIB ?>" type="text/css" rel="stylesheet"  />
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/assets/layouts/layout/css/layout.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout/css/themes/darkblue.min.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" id="style_color" />
        <link href="/assets/layouts/layout/css/custom.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        
        <link href="/node_modules/sr-basic-feature/dist/sr-basic-feature.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/node_modules/sr-bootstrap-components/dist/sr-ajax-file-upload.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        <link href="/node_modules/sr-bootstrap-components/dist/sr-datatable.css?<?= VERSION_LIB ?>" rel="stylesheet" type="text/css" />
        
        <link href="/css/bootstrap-extend.css?<?= VERSION_CSS ?>" rel="stylesheet" type="text/css" />
        <link href="/css/style.css?<?= VERSION_CSS ?>" rel="stylesheet" type="text/css" />
        
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="/favicon.ico" /> 
        <script src="<?= Path::THEME_PLUGIN ?>jquery.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/node_modules/jquery-toast-plugin/dist/jquery.toast.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
    </head>
        <!-- END HEAD -->
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div id="text-to-speech" style="display: none;"></div>
        <div class="page-wrapper">
            <?php echo $this->element("header"); ?>
            <div class="clearfix"> </div>
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <?php echo $this->element("sidebar"); ?>
                <div class="page-content-wrapper">
                    <div class="page-content" id="main">
                        <div class="main-page">
                            <?php echo $this->fetch("content"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END CONTAINER -->
            <?php echo $this->element("footer"); ?>            
        </div>
        <!--[if lt IE 9]>
        <script src="<?= Path::THEME_PLUGIN ?>/respond.min.js"></script>
        <script src="<?= Path::THEME_PLUGIN ?>/excanvas.min.js"></script> 
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?= Path::THEME_PLUGIN ?>bootstrap/js/bootstrap.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>        
        <script src="<?= Path::THEME_PLUGIN ?>bootstrap-switch/js/bootstrap-switch.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="<?= Path::THEME_PLUGIN ?>bootstrap-confirmation/bootstrap-confirmation.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        
        <!-- BEGIN THEME GLOBAL SCRIPTS -->        
        <script src="<?= Path::THEME_PLUGIN ?>bootstrap-datepicker/js/bootstrap-datepicker.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="<?= Path::THEME_PLUGIN ?>bootstrap-dialog/bootstrap-dialog.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="<?= Path::THEME_PLUGIN ?>select2/js/select2.full.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="<?= Path::THEME_PLUGIN ?>bootbox.min.js?<?= VERSION_LIB ?>"></script>
        <script src="<?= Path::THEME_PLUGIN ?>File-Validator/file-validator.js?<?= VERSION_LIB ?>" type="text/javascript" charset="utf-8"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        
        <script src="<?= Path::THEME_PLUGIN ?>/fancybox/dist/jquery.fancybox.min.js?<?= VERSION_LIB ?>" type="text/javascript" ></script>
        <script src="<?= Path::THEME_PLUGIN ?>/fancybox/src/js/slideshow.js?<?= VERSION_LIB ?>" type="text/javascript" ></script>
        <script src="<?= Path::THEME_PLUGIN ?>/fancybox/src/js/wheel.js?<?= VERSION_LIB ?>" type="text/javascript" ></script>

        <script src="<?= Path::THEME_PLUGIN ?>/jquery.form.min.js?<?= VERSION_LIB ?>" type="text/javascript" ></script>
        
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/assets/layouts/layout/scripts/layout.min.js?<?= VERSION_LIB ?>" type="text/javascript"></script>        
        <!-- END THEME LAYOUT SCRIPTS -->
        
        <script src="/node_modules/sr-basic-feature/dist/sr-basic-functions.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/node_modules/sr-basic-feature/dist/sr-basic-feature.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/node_modules/sr-bootstrap-components/dist/sr-datatable.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        <script src="/node_modules/sr-bootstrap-components/dist/sr-ajax-file-upload.js?<?= VERSION_LIB ?>" type="text/javascript"></script>
        
        <script src="/js/basic_functions.js?<?= VERSION_JS ?>" type="text/javascript"></script>
        <script src="/js/jquery-extend.js?<?= VERSION_JS ?>" type="text/javascript"></script>                
        <script src="/js/bootstrap-extend.js?<?= VERSION_JS ?>" type="text/javascript"></script>                
        <script src="/js/jquery-input-validate.js?<?= VERSION_JS ?>" type="text/javascript"></script>
        <script src="/js/ajax.js?<?= VERSION_JS ?>" type="text/javascript"></script>        
        
        <script src="/js/default.js?<?= VERSION_JS ?>" type="text/javascript"></script>
    </body>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $("body").srLoader();
            crud.init("#main");
        });
    </script>   
</html>