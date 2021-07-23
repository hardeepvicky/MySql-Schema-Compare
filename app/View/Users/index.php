<?php
$summary_url = $this->Html->url(["action" => $action, 'is_summary' => 1]);
?>
<?= $this->element("summary_header") ?>

<div class="portlet light bordered">
    <div class="portlet-body">
        <?php 
            echo $this->Form->create($model, array(
                'type' => 'GET', 
                'data-base_url' => $summary_url,
                'class' => 'form-horizontal form-row-seperated ajax-search',
                'inputDefaults' => array(
                    'label' => false, 'div' => false, 'div' => false, "escape" => false, 
                    "class" => "form-control", "type" => "text", "required" => false
                )
            ));
        ?>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-6 col-xs-12">Group :</label>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <?= $this->Form->input('group_id', array(
                            "type" => "select",
                            "class" => "form-control select2me submit-search-on-change",
                            'value' => ${$model . "group_id"},
                            "options" => $group_list,
                            "empty" => EMPTY_SELECT
                        ));?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-6 col-xs-12">Username / Name / Email :</label>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <?= $this->Form->input('username', array('value' => ${$model . "username"}));?>
                    </div>
                </div>
            </div>
        </div>
        <div class="action-buttons text-center">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" class="btn blue">Search</button>
                    <a class="btn grey" href="<?= $this->Html->url(array("action" => "clearSearchCache", "admin" => false, $action)) ?>">Clear</a>
                </div> 
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>

<div class="page-summary" data-url="<?= $summary_url ?>">
    <?= $this->element("$controller/$action") ?> 
</div>
