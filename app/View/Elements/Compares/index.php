<div class="table__structure">
    <?= $this->element("pagination", array("with_info" => true)) ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered order-column">
            <thead>
                <tr>
                    <th style="width : 10%;"> <?= $this->Paginator->sort('id', __('Id'), ["class" => "ajax-summary"]); ?> </th>
                    <th> <?= $this->Paginator->sort('src_server', __('Source Server'), ["class" => "ajax-summary"]); ?> </th>
                    <th> <?= $this->Paginator->sort('src_db_name', __('Source Database Name'), ["class" => "ajax-summary"]); ?> </th>
                    <th> <?= $this->Paginator->sort('src_server', __('Destination Server'), ["class" => "ajax-summary"]); ?> </th>
                    <th> <?= $this->Paginator->sort('dest_db_name', __('Destination Database Name'), ["class" => "ajax-summary"]); ?> </th>
                    <th style="width : 20%;"> Actions </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                <tr class="odd gradeX center">
                    <td><?= $record[$model]['id']; ?></td>
                    <td><?= $record[$model]['src_server']; ?></td>
                    <td><?= $record[$model]['src_db_name']; ?></td>
                    <td><?= $record[$model]['dest_server']; ?></td>
                    <td><?= $record[$model]['dest_db_name']; ?></td>
                    <td>
                        <?php $url = $this->Html->url(array("action" => "compare", $record[$model]['id'])); ?>
                        <a href="<?= $url; ?>" title="Compare Now" class="summary-link ajax-main">
                            Compare Now
                        </a>
                        
                        <?php $url = $this->Html->url(array("action" => "edit", $record[$model]['id'])); ?>
                        <a href="<?= $url; ?>" title="Edit" class="summary-link ajax-main">
                            <i class="fa fa-edit icon blue-madison"></i>
                        </a>
                        
                        <?php $url = $this->Html->url(array("action" => "delete", $record[$model]['id'])); ?>
                        <a href="<?= $url; ?>" class="summary-link ajax-row-delete">
                            <i class="fa fa-trash-o icon font-red-sunglo"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>            
            </tbody>
        </table>
    </div>
    <?= $this->element("pagination") ?>
</div>