<div class="page-bar">
    <div class="row">
        <div class="col-md-9 pull-left">
            <h4><?= $title_for_layout ?></h4>
        </div>
        <div class="col-md-3 pull-right" style="text-align: right; padding: 4px;">
            <a href="<?= $this->Html->url(array("action" => "index")); ?>" class="btn btn-circle blue-madison ajax-main">
                <i class="fa fa-angle-left"></i> Back
            </a>
        </div>
    </div>
</div>

<?php echo $this->Session->flash(); ?>