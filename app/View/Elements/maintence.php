<style>
    div.block
    {
        width : 320px;
        margin: auto;
        text-align: center;
        border-bottom: 1px solid #000;
    }
    
    div.block h1
    {
        font-size: 3em;
    }
</style>

<div class="block">
    <img src="/img/maintenance.png" style="height : 200px;">
    <h1>Maintenance</h1>
    <h5 class="details">Site Under Construction</h5>
    <h5>Reloading in 15 seconds</h5>
</div>

<script type="text/javascript">
    $(document).ready(function()
    {
        setInterval(function ()
        {
            window.location.href = "/";
        }, 15000);
    });
</script>
