<?php 
    $action = str_replace("admin_", "", $action);
?>
<div class="page-sidebar navbar-collapse collapse">
    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="100" style="padding-top: 20px">
        <li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li>
        
        <?php if (isset($menus) && $menus): ?>
            <?php foreach($menus as $parent_menu): ?>
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="<?= $parent_menu["icon_class"] ?>"></i>
                    <span class="title"><?= $parent_menu["title"] ?></span>
                    <span class="selected"></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <?php 
                        foreach($parent_menu["links"] as $menu)
                        {
                            $active = false;
                            if (isset($menu["link"]))
                            {
                                $active = ($menu["link"]["controller"] == $controller && $menu["link"]["action"] == $action);
                            }

                            if (isset($menu["other_links"]))
                            {
                                foreach($menu["other_links"] as $other_link)
                                {
                                    if (!$active)
                                    {
                                        $active = ($other_link["controller"] == $controller && $other_link["action"] == $action);
                                    }
                                }
                            }
                            else if (isset($menu["links"]))
                            {
                                foreach($menu["links"] as $menu2)
                                {
                                    $active = ($menu2["link"]["controller"] == $controller && $menu2["link"]["action"] == $action);
                                    if (isset($menu2["other_links"]))
                                    {
                                        foreach($menu2["other_links"] as $other_link)
                                        {
                                            if (!$active)
                                            {
                                                $active = ($other_link["controller"] == $controller && $other_link["action"] == $action);
                                            }
                                        }
                                    }
                                }
                            }
                    ?>
                        <li class="nav-item <?= $active ? "active open" : "" ?>">
                            <?php if (isset($menu["link"])): ?>
                                <a href="<?= $this->Html->url($menu["link"]) ?>" class="nav-link <?= $menu["css_class"] ?>">
                                    <?php 
                                    if (!is_array($menu["icon_class"]))
                                    {
                                        $menu["icon_class"] = array($menu["icon_class"]);
                                    }
                                    ?>
                                    <?php foreach($menu["icon_class"] as $fa_class): ?>
                                        <i class="<?= $fa_class ?>"></i>
                                    <?php endforeach; ?>
                                    <span class="title"><?= $menu['title'] ?></span>
                                </a>
                            <?php elseif ($menu["links"]): ?>

                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="<?= $menu["icon_class"] ?>"></i>
                                    <span class="title"><?= $menu["title"] ?></span>
                                    <span class="selected"></span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                <?php 
                                foreach($menu["links"] as $menu2) :

                                    $active = ($menu2["link"]["controller"] == $controller && $menu2["link"]["action"] == $action);
                                    if (isset($menu2["other_links"]))
                                    {
                                        foreach($menu2["other_links"] as $other_link)
                                        {
                                            if (!$active)
                                            {
                                                $active = ($other_link["controller"] == $controller && $other_link["action"] == $action);
                                            }
                                        }
                                    }
                                ?>
                                <li class="nav-item <?= $active ? "active open" : "" ?>">
                                    <a href="<?= $this->Html->url($menu2["link"]) ?>" class="nav-link  <?= $menu2["css_class"] ?>">
                                        <span class="title"><?= $menu2['title'] ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php } ?>
                </ul>

            </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
<script type="text/javascript">
    var parent = $("ul.sub-menu li.active").parents("li");
    parent.addClass("active open");
    parent.find(".arrow").addClass("open");
    
    $(document).ready(function()
    {
        $("a.ajax-menu").click(function()
        {
            $(".nav-item").removeClass("active open");
            $(this).parents(".nav-item").addClass("active open");
        });
    });
    
</script>