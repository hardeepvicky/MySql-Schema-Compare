<style>
    .page-header.navbar .page-logo .logo-default {
        margin-top: 0px;
        max-width: 166px;
        height : 47px;
    }
    
    .page-header.navbar .top-menu .navbar-nav>li.dropdown-extended .dropdown-menu
    {
        max-width: 370px;
        width : 370px;
        border : 1px solid #b4bcc8;
        border-top : none;
    }
    
    .page-header.navbar .top-menu .navbar-nav>li.dropdown-user .dropdown-toggle>img
    {
        width : 30px;
    }
    
    .dropdown-menu-list li
    {
        width : 360px;
        padding: 10px;
        border-bottom: 1px solid #b4bcc8;
    }
    
    @media (max-width : 500px)
    {
        .page-header.navbar .top-menu .navbar-nav>li.dropdown-extended .dropdown-menu
        {
            max-width: 320px;
            width : 320px;
        }
        
        .dropdown-menu-list li
        {
            width : 300px;
        }
    }
    
    .dropdown-menu-list .title
    {
        font-weight: bold;
        font-size: 14px;        
    }
    
    .dropdown-menu-list .name
    {
        font-weight: bold;
        font-size: 11px;
        width : 56%;
        float: left;
    }
    
    .dropdown-menu-list .time
    {
        font-weight: bold;
        font-size: 10px;
        width : 40%;
        float: left;
        text-align: right;
    }
    
    .dropdown-menu-list .name-time
    {
        background-color : #EEE; 
        padding: 5px;
    }
    
    .dropdown-menu-list .detail
    {
        font-size: 12px;
        margin: 5px;
    }
    
    @media (max-width: 767px)
    {
        .page-header.navbar .top-menu .navbar-nav>li.dropdown-notification .dropdown-menu{
            margin-right: 0 !important;
        }
        
        .page-header.navbar .top-menu .navbar-nav>li.dropdown .dropdown-menu:after{
            right : -176px;
        }
    }
    
</style>
<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner ">
        <div class="page-logo">
            
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="username username-hide-on-mobile"> <?php echo trim($auth_user["firstname"] . " " . $auth_user["lastname"]); ?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <?php 
                                echo $this->Html->link("<i class='fa fa-lock'></i> Change Password", 
                                    array( "controller" => "users", "action" => "change_password"),
                                    array( "escape" => false)
                                );
                            ?>
                        </li>
                        <li>
                            <a href="/users/logout"> <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="clearfix"> </div>