<div class="table__structure">
    <?= $this->element("pagination", array("with_info" => true)) ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered order-column">
            <thead>
                <tr>
                    <th style="width : 10%;"> <?= $this->Paginator->sort('id', __('Id'), ["class" => "ajax-summary"]); ?> </th>
                    <th> <?= $this->Paginator->sort('firstname', __('First Name'), ["class" => "ajax-summary"]); ?> </th>
                    <th> <?= $this->Paginator->sort('lastname', __('Last Name'), ["class" => "ajax-summary"]); ?> </th>
                    <th> Group </th>
                    <th> <?= $this->Paginator->sort('username', __('Username'), ["class" => "ajax-summary"]); ?> </th>
                    <th style="width : 8%;"> <?= $this->Paginator->sort('is_active', __('Status'), ["class" => "ajax-summary"]); ?> </th>
                    <th style="width : 12%;"> Actions </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                <tr class="odd gradeX center">
                    <td><?= $record[$model]['id']; ?></td>
                    <td><?= $record[$model]['firstname']; ?></td>
                    <td><?= $record[$model]['lastname']; ?></td>
                    <td><?= $group_list[$record[$model]['group_id']]; ?></td>
                    <td><?= $record[$model]['username']; ?></td>
                    <td>
                        <?php $url = $this->Html->url(array("action" => "ajaxToggleStatus", $record[$model]['id'], "admin" => false)); ?>
                        <a href="<?= $url; ?>" class="toggle-tinyfield" data-field="is_active" data-value="<?= (int) $record[$model]['is_active'] ?>">
                            <i class="fa <?= $record[$model]['is_active'] ? "fa-check-circle-o font-green-meadow icon" : "fa-times-circle-o font-red-sunglo icon" ?>"></i>
                        </a>
                    </td>
                    <td>
                        <?php $url = $this->Html->url(array("action" => "edit", $record[$model]['id'])); ?>
                        <?php if ($url) : ?>
                        <a href="<?= $url; ?>" title="Edit" class="summary-link ajax-main">
                            <i class="fa fa-edit icon blue-madison"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>            
            </tbody>
        </table>
    </div>
    <?= $this->element("pagination") ?>
</div>