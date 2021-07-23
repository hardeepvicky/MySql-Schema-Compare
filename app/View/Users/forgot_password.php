<div class="content">
<?php echo $this->Form->create('User', array("class" => "login-form",
    'inputDefaults' => array("label" => false, "div" => false))); ?>

    <?php echo $this->Session->flash(); ?>

    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Username / Email</label>
        <div class="input-icon">
            <span>
                <i class="fa fa-user"></i>
            </span>
            <?php echo $this->Form->input('username', array(
                'class' => 'form-control form-control-solid placeholder-no-fix', 
                'type' => "text", 
                "autocomplete" => "off",
                "placeholder" => "Username",
                "required" => true
            )); ?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn red btn-block uppercase">Submit</button>
    </div>
    
    <div style="text-align: right;">
        <a href="/admin">Login</a>
    </div>
    <?php echo $this->Form->end(); ?>
</div>