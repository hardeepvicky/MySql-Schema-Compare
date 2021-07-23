<div class="page-bar">
    <div class="row">
        <div class="col-md-9 pull-left">
            <?php echo $this->element("breadcum"); ?>
        </div>
        <div class="col-md-3 pull-right" style="text-align: right; margin-top: 4px;">
            <a href="<?= $this->Html->url(array("action" => "add")); ?>" class="btn btn-circle green-meadow">
                <i class="fa fa-plus"></i> Add
            </a>
        </div>
    </div>
</div>

<?= $this->element("page_header", array("title" => $title_for_layout)); ?>

<?php echo $this->Session->flash(); ?>


<div class="table__structure">
    <div class="table-responsive">
        <table class="table table-striped table-bordered order-column sr-databtable">
            <thead>
                <tr>
                    <th style="width : 10%;"> Id </th>
                    <th data-search="1">Group</th>
                    <th style="width : 12%;"> Actions </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                <tr class="odd gradeX center">
                    <td><?= $record[$model]['id']; ?></td>
                    <td><?= $record[$model]['name']; ?></td>
                    <td>
                        <?php $url = $this->Html->url(array("action" => "admin_edit", $record[$model]['id'])); ?>
                        <a href="<?= $url; ?>" title="Edit" class="summary-link">
                            <i class="fa fa-edit icon blue-madison"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
