<?= $this->element("page_header", array("title" => "Change Password")); ?>

<?php echo $this->Session->flash(); ?>

<div class="form__structure">
    <?php 
        echo $this->Form->create($model, array(
            'type' => 'file', 
            "class" => "form-horizontal form-row-seperated",
            'inputDefaults' => array(
                'label' => false, 'div' => false, 'div' => false, "escape" => false, 
                "class" => "form-control", "type" => "password", "required"
            )
        ));
    ?>
    <div class="form-body">
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-4 col-xs-12">Current Password <span>*</span> :</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?= $this->Form->input('old_password', array('placeholder' => 'Current Password')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-4 col-xs-12">New Password <span>*</span> :</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?= $this->Form->input('password', array('placeholder' => 'New Password')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-sm-4 col-xs-12">Confirm Password <span>*</span> :</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?= $this->Form->input('confirm_password', array('placeholder' => 'Confirm Password')); ?>
            </div>
        </div>
        <div class="action-buttons text-center">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <button type="submit" class="btn blue">Submit</button>
                <button type="reset" class="btn grey">Cancel</button>
            </div> 
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>