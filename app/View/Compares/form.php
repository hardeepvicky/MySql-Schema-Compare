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
                <label class="control-label col-md-3 col-sm-4 col-xs-12">Source Database Name <span>*</span> :</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $this->Form->input('src_db_name'); ?>
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