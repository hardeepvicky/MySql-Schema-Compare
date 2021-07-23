<div class="content">
    <?php echo $this->Form->create('User', array("class" => "login-form",
        'inputDefaults' => array("label" => false, "div" => false, "required" => false)));
    ?>

    <div class="form-title">
        <span class="form-title">Welcome.</span>
        <span class="form-subtitle">Please login.</span>
    </div>

    <?php echo $this->Session->flash(); ?>
    
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Username</label>
        <div class="input-icon">
            <span>
                <i class="fa fa-user"></i>
            </span>
            <?= $this->Form->input('username', array(
                "id" => "username",
                'type' => "text", 
                'class' => 'form-control form-control-solid placeholder-no-fix',
                "autocomplete" => "off", 
                "placeholder" => "Username"
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <div class="input-icon">
            <span>
                <i class="fa fa-key"></i>
            </span>
            <?= $this->Form->input('password', array(
                "id" => "password",
                'class' => 'form-control form-control-solid placeholder-no-fix',
                'type' => "password", 
                "autocomplete" => "off", 
                "placeholder" => "Password"
            ));
            ?> 
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn red btn-block uppercase">Login</button>
    </div>

    <div style="text-align: right;">
        <a href="/Users/forgot_password">Forgot Password</a>
    </div>

<?php echo $this->Form->end(); ?>
</div>