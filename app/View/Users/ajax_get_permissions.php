<div class="table-responsive">
    <table class="table table-striped table-bordered order-column sr-databtable" id="permission">
        <thead>
            <tr>
                <th data-search-clear="1"style="width : 6%">#</th>
                <th data-search="1" style="width : 25%">Section</th>
                <th data-search="1" style="width : 40%">Action</th>
                <th data-search="1">Parent Screen</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 0;
            foreach($sections as $section_name => $section_subs): 
                $section_name = trim($section_name);
                $section_name_str = str_replace(" ", "_", $section_name);
            ?>
                <?php 
                $a = 0;
                foreach($section_subs as $section_sub_name => $arr):
                    $a++;
                    $i++;

                    $section_sub_name = trim($section_sub_name);
                    $section_sub_name_str = str_replace(" ", "_", $section_sub_name);

                    $checked = "";

                    if (is_array($arr))
                    {
                        $url = isset($arr["url"]) ? $arr["url"] : "";
                    }
                    else
                    {
                        $url = $arr;
                    }

                    if (in_array($url, $aro_acos_list))
                    {
                        $checked = 'checked="checked"';
                    }

                    $tr_cls = $a == 1 ? "first-row" : "";
                ?>
                <tr class="<?= $tr_cls ?>">
                    <td class="text-center"><?= $i ?></td>
                    <td>
                        <?php if ($a == 1): ?>
                            <label class="for-checkbox">
                                <input type="checkbox" class="chk-select-all" data-sr-chkselect-children="input.controller-<?= $section_name_str ?>">
                                <b><?= $section_name ?></b>
                            </label>
                        <?php else: ?>
                            <?= $section_name ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($url): ?>
                        <label class="for-checkbox">
                            <input type="checkbox" <?= $checked ?> 
                                   id="action-<?= $section_name_str ?>-<?= $section_sub_name_str ?>"
                                   class="checkbox-css-toggler controller-<?= $section_name_str ?>" 
                                   name="data[Permissions][<?= $url ?>]" 
                                   data-toggler-target="span.realted-action-<?= $section_name_str ?>-<?= $section_sub_name_str ?>" 
                                   data-toggler-class="font-green-meadow" />
                            <?= $section_sub_name ?>
                        </label>
                        <?php else: ?>
                            <?= $section_sub_name ?>
                            <br/>URL not Found
                        <?php endif; ?>
                    </td>
                    <?php if (is_array($arr) && isset($arr["related"])) : ?>
                        <td>
                            <?php
                            foreach($arr["related"] as $inner_arr):
                                $inner_arr["title"] = trim($inner_arr["title"]);
                                $inner_arr["sub_title"] = trim($inner_arr["sub_title"]);

                                $realted_name = $inner_arr["title"] . "->" . $inner_arr["sub_title"];

                                $related_section_name = str_replace(" ", "_", $inner_arr["title"]);
                                $related_sub_name = str_replace(" ", "_", $inner_arr["sub_title"]);
                            ?>
                                <span class="realted-action realted-action-<?= $related_section_name ?>-<?= $related_sub_name ?>" data-section="<?= $related_section_name ?>" data-section-sub="<?= $related_sub_name ?>">
                                    <?= $realted_name ?>
                                </span>
                                <br/>
                            <?php endforeach; ?>
                        </td>
                    <?php else: ?>
                        <td></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function()
    {
        $("span.realted-action").click(function()
        {
            $("table#permission").find(".icon-search-clear").trigger("click");
            
            var section = $(this).data("section");
            var section_sub = $(this).data("section-sub");
            var id = "input#action-" + section + "-" + section_sub;            
            var pos = $(id).position();
            
            $("html, body").animate({
                'scrollTop' : pos.top - 300
            }, 
            function()
            {
                $(id).parents("td").pulsate({
                    color : "#36C6D3",
                    repeat: 3,
                });
            });
        });
    });
</script>