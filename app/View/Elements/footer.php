<div class="page-footer">
    <div class="page-footer-inner"> 2021 © <?php echo SITE_NAME ; ?>
    </div>
    <div class="scroll-to-top" style="display: block;">
        <i class="icon-arrow-up"></i>
    </div>
</div>

<style>
    table.cake-sql-log
    {
        width : 100%;
    }
</style>
<div style="background-color: #fff; padding-bottom: 20px;">
<?php 
    if (Configure::read("debug") > 0)
    {
        //echo $this->element('sql_dump');
    }
?>
</div>