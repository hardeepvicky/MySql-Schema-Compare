<div class="page-bar">
    <div class="row">
        <div class="col-md-9 pull-left">
            <h4><?= $title_for_layout ?></h4>
        </div>
    </div>
</div>

<style>
    .src_col
    {
        border-color : #36c6d3;
    }
    
    .blank_col
    {
        background-color: #fbe1e3;
    }
    
    .dest_col
    {
        border-color : #659be0;
    }
</style>

<?php echo $this->Session->flash(); ?>

 <div class="portlet box blue">
    <div class="portlet-body">
        <label class="mt-checkbox mt-checkbox-outline" style="margin-left : 15px;">
            <input type="checkbox" id="sql_gen_new_table" checked="checked" > New Table
            <span></span>
        </label>

        <label class="mt-checkbox mt-checkbox-outline" style="margin-left : 15px;">
            <input type="checkbox" id="sql_gen_delete_table" checked="checked"  > Delete Table
            <span></span>
        </label>

        <br/> 
        <label class="mt-checkbox mt-checkbox-outline" style="margin-left : 15px;">
            <input type="checkbox" id="sql_gen_new_column" checked="checked"  > New Column
            <span></span>
        </label>

        <label class="mt-checkbox mt-checkbox-outline" style="margin-left : 15px;">
            <input type="checkbox" id="sql_gen_delete_column" checked="checked"  > Delete Column
            <span></span>
        </label>

        <label class="mt-checkbox mt-checkbox-outline" style="margin-left : 15px;">
            <input type="checkbox" id="sql_gen_change_column" checked="checked"  > Change Column
            <span></span>
        </label>

        <span id="generate_sql" class="btn blue" style="text-align: right;">Generate SQL</span>
    </div>
 </div>

<ul class="nav nav-tabs" style="margin-top: 10px;">
    <li class="active">
        <a href="#tab_1" data-toggle="tab"> Source Tables </a>
    </li>
    <li>
        <a href="#tab_2" data-toggle="tab"> Compares </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade  fade active in" id="tab_1">
        <div class="row">            
            <?php 
            $total_count = count($records['tables']);
            $limit = ceil($total_count / 4);
            $i = $page = 0;
            
            $table_group_list = [];
            foreach($records['tables'] as $table_name => $column)
            {
                $table_group_list[$page][$i] = $table_name;
                
                $i++;
                if ($i == $limit)
                {
                    $i = 0;
                    $page++;
                }
            }
            ?>
            <h3><?= $total_count ?> Tables</h3>
            <?php foreach($table_group_list as $page => $table_list ): ?>
            <div class="col-md-3">
                <div class="mt-checkbox-list">
                    <?php foreach($table_list as $table_name): ?>
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" class="table_list" id="table_<?= $table_name ?>" checked="checked"> <?= $table_name ?>
                        <span></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="tab-pane" id="tab_2">
        <div class="row">
            <div class="col-md-6">
                <h3>
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" id="compare_show_only_difference_table"> Show Only Difference Table
                        <span></span>
                    </label>

                    <label class="mt-checkbox mt-checkbox-outline" style="margin-left : 15px;">
                        <input type="checkbox" id="compare_show_only_difference_tr" > Show Only Difference Table Row
                        <span></span>
                    </label>
                </h3>
            </div>
        </div>
        <?php 
        foreach($records['tables'] as $table_name => $table_arr): 
        ?>
        <div class="row compare_block" id="compare_<?= $table_name ?>">
            <div class="col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <?= $table_name ?>
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>                            
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover compare_table">
                            <thead>
                                <tr>
                                    <th colspan="9" class="center">Source Table's Column</th>
                                    <th class="blank_col" rowspan="2" width="50px"> Have Difference</th>
                                    <th colspan="8" class="center">Destination Table's Column</th>
                                </tr>
                                <tr>
                                    <th class="src_col center"> # </th>
                                    <th class="src_col"> Column </th>
                                    <th class="src_col" width="100px"> Type </th>
                                    <th class="src_col" width="50px"> Length </th>
                                    <th class="src_col" width="50px"> Null </th>
                                    <th class="src_col" width="50px"> UnSigned </th>
                                    <th class="src_col" width="50px"> Unique </th>
                                    <th class="src_col" width="50px"> Primary </th>
                                    <th class="src_col" width="50px"> Auto Increment </th>
                                    <th class="dest_col" width="100px"> Type </th>
                                    <th class="dest_col" width="50px"> Length </th>
                                    <th class="dest_col" width="50px"> Null </th>
                                    <th class="dest_col" width="50px"> UnSigned </th>
                                    <th class="dest_col" width="50px"> Unique </th>
                                    <th class="dest_col" width="50px"> Primary </th>
                                    <th class="dest_col" width="50px"> Auto Increment </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 0;
                                foreach($table_arr['columns'] as $column_name => $column_options): 
                                    $c++;
                                ?> 
                                <tr>
                                    <td class="src_col center"> <?= $c ?> </th>
                                    <td class="src_col column_name"> <?= $column_name ?> </td>
                                    <?php if (isset($column_options['src'])):  ?>                                        
                                        <td class="src_col data_type"> <?= $column_options['src']['data_type'] ?> </td>
                                        <td class="src_col length"> <?= $column_options['src']['length'] ?> </td>
                                        <td class="src_col null"> <?= $column_options['src']['null'] ?> </td>
                                        <td class="src_col unsigned"> <?= $column_options['src']['unsigned'] ?> </td>
                                        <td class="src_col unique"> <?= $column_options['src']['unique'] ?> </td>
                                        <td class="src_col primary"> <?= $column_options['src']['primary'] ?> </td>
                                        <td class="src_col autoincrement"> <?= $column_options['src']['autoincrement'] ?> </td>
                                    <?php else: ?>
                                        <td class="dest_col" colspan="7">
                                            Column Will Delete
                                        </td>
                                    <?php endif; ?>
                                    
                                    <?php if ($table_arr['is_new']): ?>
                                        <td class="blank_col center have_diff will_create_table"> Yes </td>
                                        <td class="dest_col" colspan="7">
                                            Table Not Present
                                        </td>
                                    <?php else: 
                                            $td_cls = "";
                                    
                                            if (isset($column_options['is_new']))
                                            {
                                                $td_cls .= " will_new_column";
                                            }
                                            
                                            if (isset($column_options['is_delete']))
                                            {
                                                $td_cls .= " will_delete_column";
                                            }
                                            
                                            if (isset($column_options['is_change']))
                                            {
                                                $td_cls .= " have_diff_column";
                                            }
                                            
                                            if ($td_cls)
                                            {
                                                $td_cls .= " have_diff";
                                            }
                                        ?>
                                        
                                        <td class="blank_col center <?= $td_cls ?>"> <?= $td_cls ? "Yes" : "" ?> </td>
                                            
                                        <?php if (isset($column_options['dest'])):  ?>
                                            <td class="dest_col data_type"> <?= $column_options['dest']['data_type'] ?> </td>
                                            <td class="dest_col length"> <?= $column_options['dest']['length'] ?> </td>
                                            <td class="dest_col null"> <?= $column_options['dest']['null'] ?> </td>
                                            <td class="dest_col unsigned"> <?= $column_options['dest']['unsigned'] ?> </td>
                                            <td class="dest_col unique"> <?= $column_options['dest']['unique'] ?> </td>
                                            <td class="dest_col primary"> <?= $column_options['dest']['primary'] ?> </td>
                                            <td class="dest_col autoincrement"> <?= $column_options['dest']['autoincrement'] ?> </td>
                                        <?php else: ?>
                                            <td class="dest_col" colspan="7">
                                                Column Will Create
                                            </td>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        
    </div>
    <div class="tab-pane fade" id="tab_3">
        SQL
    </div>
</div>

<script type="text/javascript">
    var records = JSON.parse('<?= json_encode($records) ?>');
    $(document).ready(function()
    {
        $("input#compare_show_only_difference_tr").change(function()
        {
            var will_hide = this.checked;
            
            $("table.compare_table:visible").each(function()
            {
                $(this).find("tbody > tr").each(function()
                {
                    if ( $(this).find("td.have_diff").length == 0 )
                    {
                        if (will_hide)
                        {
                            $(this).hide();
                        }
                        else
                        {
                            $(this).show();
                        }
                    }
                });
            });
        });
        
        $("input#compare_show_only_difference_table").change(function()
        {
            if (this.checked)
            {
                $("#compare_show_only_difference_tr").parents(".mt-checkbox").show();
                
                $("table.compare_table").each(function()
                {
                    if ( $(this).find("td.have_diff").length == 0 )
                    {
                        $(this).parents(".compare_block").hide();
                    }
                });
            }
            else
            {
                $("#compare_show_only_difference_tr").parents(".mt-checkbox").hide();
                $(".compare_block").show();
            }
        });
        
        $("#compare_show_only_difference_tr").parents(".mt-checkbox").hide();
        
        $("#generate_sql").click(function()
        {
            generate_sql();
        });
        
        function generate_sql()
        {
            var sql = "";
            
            for(var table_name in records['tables'])
            {
                var is_check = $("#table_" + table_name).prop("checked");
                
                if (is_check)
                {
                    if (records['tables'][table_name]['is_new'])
                    {
                        sql_create_table(table_name, records['tables'][table_name]['columns'])
                    }
                }
            }
        }
        
        function sql_create_table(table_name, columns)
        {
            console.log(table_name);
            console.log(columns);
        }
    });
</script>