<div class="row">
    <div style="width:500px; margin: auto;">
        <div class="pull-left" style="font-size: 62px; color: #ec8c8c; width: 25%;">
             401
        </div>
        <div class="pull-left details" style="width: 70%;">
            <?php if ($is_ajax): ?>
            <h3>You are not authorized to use this feature</h3>
            <?php else : ?>
            <h3>You are not authorized to access this page</h3>
            <?php endif; ?>
            
            <h5><b>Controller</b> : <?= $controller ?></h5>
            <h5><b>Action</b> : <?= $action ?></h5>
        </div>
    </div>
</div>