<ul class="page-breadcrumb">
    <?php if ($breadcum): ?>
        <li>
            <a href="<?= $this->Html->url($home_link) ?>">  
                <i class="fa fa-home"></i>Home
            </a>
        </li>
        <?php foreach($breadcum as $arr): ?>
        <li>
            <span> <i class="<?= $arr['icon_class']; ?>"></i><?= $arr['title']; ?></span>
        </li>
        <?php endforeach; ?>
    <?php else: ?>
        <span class="error-message">Breadcum not found</span>
    <?php endif; ?>
</ul>