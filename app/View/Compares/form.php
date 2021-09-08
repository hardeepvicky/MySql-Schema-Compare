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
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Source Connection Type <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('src_conn_type', [
                        "id" => "src_conn_type",
                        "type" => 'select',
                        "options" => ConnectionType::$list,
                        "class" => "from-control select2me"
                    ]); ?>
                </div>
            </div>
            <div class="form-group src_remote">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Source Server <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('src_server', ["class" => "form-control required-input"]); ?>
                </div>
            </div>
            <div class="form-group src_remote">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Source Database Username <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('src_username', ["class" => "form-control required-input"]); ?>
                </div>
            </div>
            <div class="form-group src_remote">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Source Database Password <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('src_password', ["class" => "form-control required-input"]); ?>
                </div>
            </div>
            <div class="form-group src_remote">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Source Database Port :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('src_port'); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Source Database Name <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('src_db_name'); ?>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Destination Connection Type <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('dest_conn_type', [
                        "id" => "dest_conn_type",
                        "type" => 'select',
                        "options" => ConnectionType::$list,
                        "class" => "from-control select2me"
                    ]); ?>
                </div>
            </div>
            <div class="form-group dest_remote">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Destination Server <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('dest_server', ["class" => "form-control required-input"]); ?>
                </div>
            </div>
            <div class="form-group dest_remote">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Destination Database Username <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('dest_username', ["class" => "form-control required-input"]); ?>
                </div>
            </div>
            <div class="form-group dest_remote">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Destination Database Username <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('dest_password', ["class" => "form-control required-input"]); ?>
                </div>
            </div>
            <div class="form-group dest_remote">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Destination Database Port  :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('dest_port'); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Destination Database Name <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('dest_db_name'); ?>
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
    $("#src_conn_type").change(function()
    {
        var v = $(this).val();
        
        if (v == '<?= ConnectionType::REMOTE ?>')
        {
            $(".src_remote").find(".required-input").attr("required", true).removeAttr("disabled");
            $(".src_remote").show();
        }
        else
        {
            $(".src_remote").find(".required-input").attr("disabled", true).removeAttr("required");
            $(".src_remote").hide();
        }
    });
    
    $("#dest_conn_type").change(function()
    {
        var v = $(this).val();
        
        if (v == '<?= ConnectionType::REMOTE ?>')
        {
            $(".dest_remote").find(".required-input").attr("required", true).removeAttr("disabled");
            $(".dest_remote").show();
        }
        else
        {
            $(".dest_remote").find(".required-input").attr("disabled", true).removeAttr("required");
            $(".dest_remote").hide();
        }
    });
    
    $("#src_conn_type, #dest_conn_type").trigger("change");
});
</script>