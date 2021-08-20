<?php
$summary_url = $this->Html->url(["action" => "index"]);
$disabled = $action == "edit" ? "disabled" : "";
?>

<?= $this->element("form_header") ?>

<div class="portlet light bordered">
    <div class="portlet-body">
        <?php
            echo $this->Form->create($model, array(
                'type' => 'file',
                'data-redirect_url' => $summary_url,
                "class" => "form-horizontal form-row-seperated ajax-submit",
                'inputDefaults' => array(
                    'label' => false, 'div' => false, 'div' => false, "escape" => false,
                    "class" => "form-control invalid-sql-char", "type" => "text"
                )
            ));

            echo $this->Form->hidden('id');
        ?>
        <div class="form-body">
            <section class="section-head">
                <h3>Login Details</h3>
            </section>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Username <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('username', array('placeholder' => 'Username')); ?>
                </div>
            </div>
            
            <?php if (!$disabled): ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Password <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('password', array('type' => 'password')); ?>
                </div>
            </div>
            <?php endif; ?>

            <section class="section-head">
                <h3>Basic Details</h3>
            </section>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">First Name <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('firstname', array('placeholder' => 'First Name')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Last Name :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('lastname', array('placeholder' => 'Last Name')); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Status :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="mt-checkbox-inline">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <?= $this->Form->input('is_active', array('type' => 'checkbox')); ?> Active
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="action-buttons">
            <div class="row">
                <div class="col-md-offset-4 col-md-8 col-sm-offset-4 col-sm-8 col-xs-12">
                    <button type="submit" class="btn blue">Submit</button>
                    <button type="reset" class="btn grey">Reset</button>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
    $("select#group_id").change(function()
    {
        if ($(this).val() == "<?= GroupType::ADMIN ?>")
        {
            $("div.sub-admin").hide().find("select.required-input, input.required-input").removeAttr("required").attr("disabled", true);
        }
        else
        {
            $("div.sub-admin").show().find("select.required-input, input.required-input").attr("required", true).removeAttr("disabled");
        }
    });
    
    $("select#group_id").trigger("change");
});
</script>