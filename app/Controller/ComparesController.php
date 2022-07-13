<?php
class ComparesController extends AppController
{
    public function index()
    {
        $this->Redirect->urlToNamed();

        $conditions = $this->getSearchConditions(array(
            array('model' => $this->modelClass, 'field' => "src_server", 'type' => 'string', 'view_field' => 'src_server'),            
            array('model' => $this->modelClass, 'field' => "src_db_name", 'type' => 'string', 'view_field' => 'src_db_name'),            
            array('model' => $this->modelClass, 'field' => "dest_server", 'type' => 'string', 'view_field' => 'dest_server'),            
            array('model' => $this->modelClass, 'field' => "dest_db_name", 'type' => 'string', 'view_field' => 'dest_db_name'),            
        ));
        
        $records = $this->paginate($this->modelClass, $conditions);
        
        //debug($records); exit;
        
        $this->set(compact('records'));
        
        $this->afterIndex();
    }
    
    public function add($redirect = ["action" => "index"])
    {
        parent::add($redirect);
        
        $this->render("form");
    }
    
    public function edit($id, $redirect = ["action" => "index"])
    {
        parent::edit($id, $redirect);
        
        $this->render("form");
    }
    
    public function delete($id, $redirect = ["action" => "index"])
    {
        parent::delete($id, $redirect);
    }
    
    public function compare($id)
    {
        $this->_setLargeMemory();
        
        $record = $this->{$this->modelClass}->findById($id);
        
        if (!$record)
        {
            throw new Exception("Invalid Id : $id");
        }
        
        $mysqlSrc = $mysqlDest = null;
        
        require_once(APP . "vendor/MysqlCustom.php");
        
        $records = $this->Compare->query("SHOW DATABASES");
        
        $db_list = Set::classicExtract($records, "{n}.SCHEMATA.Database");
        
        try
        {
            $src_db_name = $record[$this->modelClass]['src_db_name'];
            $dest_db_name = $record[$this->modelClass]['dest_db_name'];
            if ($record[$this->modelClass]['src_conn_type'] == ConnectionType::LOCAL)
            {
                if ( !in_array($src_db_name, $db_list) )
                {
                    throw new Exception("Database $src_db_name is exist in local");
                }
                
                $project_config = new DATABASE_CONFIG();
                $config = [
                    "server" => $project_config->default['host'],
                    "username" => $project_config->default['login'],
                    "password" => $project_config->default['password'],
                    "database" => $src_db_name,
                ];

                if ($project_config->default['port'])
                {
                    $config['port'] = $project_config->default['port'];
                }
                else
                {
                    $config['port'] = 3306;
                }
                
                $mysqlSrc = new MysqlCustom($config);
            }
            else
            {
                error_reporting(0);

                $config = [
                    "server" => $record[$this->modelClass]['src_server'],
                    "username" => $record[$this->modelClass]['src_username'],
                    "password" => $record[$this->modelClass]['src_password'],
                    "database" => $src_db_name,
                ];

                if ($record[$this->modelClass]['src_port'])
                {
                    $config['port'] = $record[$this->modelClass]['src_port'];
                }
                else
                {
                    $config['port'] = 3306;
                }

                $mysqlSrc = new MysqlCustom($config);
            }

            if ($record[$this->modelClass]['dest_conn_type'] == ConnectionType::LOCAL)
            {
                if ( !in_array($dest_db_name, $db_list) )
                {
                    throw new Exception("Database $dest_db_name is exist in local");
                }
                
                $project_config = new DATABASE_CONFIG();
                $config = [
                    "server" => $project_config->default['host'],
                    "username" => $project_config->default['login'],
                    "password" => $project_config->default['password'],
                    "database" => $dest_db_name,
                ];

                if ($project_config->default['port'])
                {
                    $config['port'] = $project_config->default['port'];
                }
                else
                {
                    $config['port'] = 3306;
                }
                
                $mysqlDest = new MysqlCustom($config);
            }
            else
            {
                error_reporting(0);

                $config = [
                    "server" => $record[$this->modelClass]['dest_server'],
                    "username" => $record[$this->modelClass]['dest_username'],
                    "password" => $record[$this->modelClass]['dest_password'],
                    "database" => $dest_db_name,
                ];

                if ($record[$this->modelClass]['dest_port'])
                {
                    $config['port'] = $record[$this->modelClass]['dest_port'];
                }
                else
                {
                    $config['port'] = 3306;
                }

                $mysqlDest = new MysqlCustom($config);
            }

            $src_db = $this->_find_db_meta($mysqlSrc);
            $dest_db = $this->_find_db_meta($mysqlDest);

            $records = [
                'tables' => [],                
            ];

            $dest_table_list = [];
            foreach($dest_db['tables'] as $table_name => $table_arr)
            {
                $dest_table_list[] = $table_name;
            }
            
            foreach($src_db['tables'] as $table_name => $table_arr)
            {
                $columns = [];
                foreach($table_arr['columns'] as $field_name => $field_options)
                {
                    $length = "";
                    switch($field_options['DATA_TYPE'])
                    {
                        case "int";
                            $length = $field_options['NUMERIC_PRECISION'];
                            break;
                        case "varchar";
                            $length = $field_options['CHARACTER_MAXIMUM_LENGTH'];
                            break;
                    }

                    $null = "";
                    if ( strtoupper( $field_options['IS_NULLABLE'] ) == 'YES' )
                    {
                        $null = "Yes";
                    }

                    $unsigned = "";
                    if ( strpos($field_options['COLUMN_TYPE'], "unsigned") !== false )
                    {
                        $unsigned = "Yes";
                    }

                    $unique = "";
                    if ( strpos($field_options['COLUMN_KEY'], "UNI") !== false )
                    {
                        $unique = "Yes";
                    }

                    $primary = "";

                    if ( strpos($field_options['COLUMN_KEY'], "PRI") !== false )
                    {
                        $primary = "Yes";
                    }

                    $index = "";
                    if ( strpos($field_options['COLUMN_KEY'], "MUL") !== false )
                    {
                        $index = "Yes";
                    }

                    $autoincrement = "";

                    if ( strpos($field_options['EXTRA'], "auto_increment") !== false )
                    {
                        $autoincrement = "Yes";
                    }

                    $columns[$field_name]["src"] = [
                        "data_type" => $field_options['DATA_TYPE'],
                        "null" => $null,
                        "length" => $length,
                        "unsigned" => $unsigned,
                        "unique" => $unique,
                        "primary" => $primary,
                        "index" => $index,
                        "autoincrement" => $autoincrement,
                    ];
                }

                if ( !in_array($table_name, $dest_table_list))
                {
                    $records['tables'][$table_name]['is_new'] = true;
                }

                $records['tables'][$table_name]['columns'] = $columns;
            }

            foreach($dest_db['tables'] as $table_name => $table_arr)
            {
                $columns = [];
                foreach($table_arr['columns'] as $field_name => $field_options)
                {
                    $length = "";
                    switch($field_options['DATA_TYPE'])
                    {
                        case "int";
                            $length = $field_options['NUMERIC_PRECISION'];
                            break;
                        case "varchar";
                            $length = $field_options['CHARACTER_MAXIMUM_LENGTH'];
                            break;
                    }

                    $null = "";
                    if ( strtoupper( $field_options['IS_NULLABLE'] ) == 'YES' )
                    {
                        $null = "Yes";
                    }

                    $unsigned = "";
                    if ( strpos($field_options['COLUMN_TYPE'], "unsigned") !== false )
                    {
                        $unsigned = "Yes";
                    }

                    $unique = "";
                    if ( strpos($field_options['COLUMN_KEY'], "UNI") !== false )
                    {
                        $unique = "Yes";
                    }

                    $primary = "";

                    if ( strpos($field_options['COLUMN_KEY'], "PRI") !== false )
                    {
                        $primary = "Yes";
                    }

                    $index = "";
                    if ( strpos($field_options['COLUMN_KEY'], "MUL") !== false )
                    {
                        $index = "Yes";
                    }

                    $autoincrement = "";

                    if ( strpos($field_options['EXTRA'], "auto_increment") !== false )
                    {
                        $autoincrement = "Yes";
                    }

                    $columns[$field_name]["dest"] = [
                        "data_type" => $field_options['DATA_TYPE'],
                        "null" => $null,
                        "length" => $length,
                        "unsigned" => $unsigned,
                        "unique" => $unique,
                        "primary" => $primary,
                        "index" => $index,
                        "autoincrement" => $autoincrement,
                    ];
                }

                if (!isset($records['tables'][$table_name]))
                {
                    $records['tables'][$table_name]['is_delete'] = true;
                    $records['tables'][$table_name]['columns'] = $columns;
                }
                else
                {
                    $records['tables'][$table_name]['columns'] = array_merge_recursive($records['tables'][$table_name]['columns'], $columns);

                    foreach($records['tables'][$table_name]['columns'] as $field_name => $arr)
                    {
                        if ( !isset($arr['dest']) )
                        {
                            $records['tables'][$table_name]['columns'][$field_name]['is_new'] = true;
                        }

                        if ( !isset($arr['src']) )
                        {
                            $records['tables'][$table_name]['columns'][$field_name]['is_delete'] = true;
                        }

                        if ( isset($arr['src']) && isset($arr['dest']) )
                        {
                            foreach($arr['src'] as $attr_key => $attr_value)
                            {
                                if ( $attr_value != $arr['dest'][$attr_key] )
                                {
                                    $records['tables'][$table_name]['columns'][$field_name]['is_change'] = true;
                                }
                            }
                        }
                    }
                }
            }
            
            if ($this->request->is("post"))
            {
                //debug($this->request->data);
                $dest_db_name = $mysqlDest->getDBName();

                $q = "SELECT default_character_set_name FROM information_schema.SCHEMATA WHERE schema_name = '$dest_db_name';";

                $temp = $mysqlDest->select($q);

                if (!$temp)
                {
                    throw new Exception("Default Charset not found");
                }

                $default_char_set = $temp[0]['default_character_set_name'];


                $records['sql'] = [
                    "new_table" => [],
                    "delete_table" => [],
                    "new_column" => [],
                    "delete_column" => [],
                    "change_column" => [],

                    "new_view" => [],
                    "change_view" => [],
                    "delete_view" => [],

                    "new_sp" => [],
                    "update_sp" => [],
                    "delete_sp" => [],

                    "new_function" => [],
                    "update_function" => [],
                    "delete_function" => [],                
                ];

                foreach($records['tables'] as $table_name => $table_arr)
                {
                    if ( isset($table_arr['is_new']) )
                    {
                        $attr_list = [];
                        foreach($table_arr['columns'] as $column_name => $column_arr)
                        {
                            $attr_list[] = $this->_create_table_field($column_name, $column_arr['src']);
                        }

                        foreach($table_arr['columns'] as $column_name => $column_arr)
                        {
                            $str = $this->_create_table_field_for_primary($column_name, $column_arr['src']);

                            if ($str)
                            {
                                $attr_list[] = $str;
                            }
                        }

                        foreach($table_arr['columns'] as $column_name => $column_arr)
                        {
                            $str = $this->_create_table_field_for_unique($column_name, $column_arr['src']);

                            if ($str)
                            {
                                $attr_list[] = $str;
                            }
                        }

                        foreach($table_arr['columns'] as $column_name => $column_arr)
                        {
                            $str = $this->_create_table_field_for_index($column_name, $column_arr['src']);

                            if ($str)
                            {
                                $attr_list[] = $str;
                            }
                        }

                        $sql_str = "CREATE TABLE `$dest_db_name`.`$table_name` (";
                        $sql_str .= implode(",", $attr_list);
                        $sql_str .= ")ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$default_char_set";

                        $records['sql']['new_table'][$table_name] = $sql_str;
                    }
                    else if ( isset($table_arr['is_delete']) )
                    {
                        $records['sql']['delete_table'][$table_name] = "DROP TABLE `$dest_db_name`.`$table_name`";
                    }
                    else
                    {
                        $prev_col_name = "";
                        foreach($table_arr['columns'] as $column_name => $column_arr)
                        {
                            if ( isset ($column_arr['is_new']) )
                            {
                                $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name` ";

                                $sql_str .= $this->_new_field($column_name, $column_arr['src'], $prev_col_name);

                                $records['sql']['new_column'][$table_name][] = $sql_str;
                            }
                            else if ( isset ($column_arr['is_change']) )
                            {	
                                if (
                                    $column_arr['src']['data_type'] != $column_arr['dest']['data_type'] 
                                    || $column_arr['src']['length'] != $column_arr['dest']['length'] 
                                    || $column_arr['src']['null'] != $column_arr['dest']['null'] 
                                    || $column_arr['src']['unsigned'] != $column_arr['dest']['unsigned'] 
                                    || $column_arr['src']['autoincrement'] != $column_arr['dest']['autoincrement'] 
                                )
                                {
                                    $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name`";

                                    $sql_str .= " CHANGE COLUMN `$column_name` " . $this->_create_table_field($column_name, $column_arr['src']);

                                    $records['sql']['change_column'][$table_name][] = $sql_str;
                                }

                                if (
                                    $column_arr['src']['unique'] != $column_arr['dest']['unique'] 
                                )
                                {
                                    if ($column_arr['src']['unique'] == "")
                                    {
                                        $index_list = $this->_find_index_on_column($src_db_name, $table_name, $column_name);

                                        foreach($index_list as $index_name)
                                        {                                
                                            $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name`";

                                            $sql_str .= " DROP INDEX `$index_name`";

                                            $records['sql']['change_column'][$table_name][] = $sql_str;
                                        }
                                    }
                                    else
                                    {
                                        $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name`";

                                        $sql_str .= " ADD UNIQUE INDEX `$column_name" . "_UNIQUE` (`$column_name` ASC)";

                                        $records['sql']['change_column'][$table_name][] = $sql_str;
                                    }
                                }

                                if (
                                    $column_arr['src']['primary'] != $column_arr['dest']['primary'] 
                                )
                                {
                                    if ($column_arr['src']['primary'] == "")
                                    {
                                        $index_list = $this->_find_index_on_column($src_db_name, $table_name, $column_name);

                                        foreach($index_list as $index_name)
                                        {                                
                                            $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name`";

                                            $sql_str .= " DROP INDEX `$index_name`";

                                            $records['sql']['change_column'][$table_name][] = $sql_str;
                                        }
                                    }
                                    else
                                    {
                                        $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name`";

                                        $sql_str .= " ADD PRIMARY KEY (`$column_name`)";

                                        $records['sql']['change_column'][$table_name][] = $sql_str;
                                    }
                                }

                                if (
                                    $column_arr['src']['index'] != $column_arr['dest']['index'] 
                                )
                                {
                                    if ($column_arr['src']['index'] == "")
                                    {
                                        $index_list = $this->_find_index_on_column($src_db_name, $table_name, $column_name);

                                        foreach($index_list as $index_name)
                                        {                                
                                            $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name`";

                                            $sql_str .= " DROP INDEX `$index_name`";

                                            $records['sql']['change_column'][$table_name][] = $sql_str;
                                        }
                                    }
                                    else
                                    {
                                        $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name`";

                                        $sql_str .= " ADD KEY (`$column_name`)";

                                        $records['sql']['change_column'][$table_name][] = $sql_str;
                                    }
                                }
                            }
                            else if ( isset ($column_arr['is_delete']) )
                            {
                                $sql_str = "ALTER TABLE `$dest_db_name`.`$table_name` DROP COLUMN `$column_name`";

                                $index_list = $this->_find_index_on_column($dest_db_name, $table_name, $column_name);

                                foreach($index_list as $index_name)
                                {                                
                                    $sql_str .= ", DROP INDEX `$index_name`";
                                }

                                $records['sql']['delete_column'][$table_name][] = $sql_str;
                            }

                            $prev_col_name = $column_name;
                        }
                    }
                }

                $src_view_list = [];
                foreach($src_db['views'] as $view_name => $view_arr)
                {
                    $src_view_list[] = $view_name;
                    $found = false;
                    foreach($dest_db['views'] as $dest_view_name => $dest_view_arr)
                    {

                        if ($view_name == $dest_view_name)
                        {
                            $found = true;
                        }
                    }

                    $sql = "CREATE OR REPLACE VIEW `" . $view_arr['TABLE_NAME'] . "` AS " . $view_arr['VIEW_DEFINITION'];
                    $sql = str_replace("'", '"', $sql);

                    if ($found)
                    {
                        $records['sql']['change_view'][$view_name] = $sql;
                    }
                    else
                    {
                        $records['sql']['new_view'][$view_name] = $sql;
                    }
                }

                foreach($dest_db['views'] as $dest_view_name => $dest_view_arr)
                {
                    if (!in_array($view_name, $src_view_list))
                    {
                        $records['sql']['delete_view'][$view_name] = $view_arr['sql'];
                    }
                }

                $src_sp_list = [];
                foreach($src_db['stored_procedures'] as $sp_name => $sp_arr)
                {
                    $src_sp_list[] = $sp_name;
                    $found = false; $is_modified = false;
                    foreach($dest_db['stored_procedures'] as $dest_sp_name => $dest_sp_arr)
                    {
                        if ($sp_name == $dest_sp_name)
                        {
                            $found = true;

                            $is_modified = DateUtility::compare($sp_arr['modified'], $dest_sp_arr['modified']) > 0;
                        }
                    }

                    $sql = "DELIMITER $$ \n";                    
                    $sql .= 'CREATE PROCEDURE `' . $sp_arr['name'] . '` (';
                    $sql .= $sp_arr['param_list'];
                    $sql .= ") " . $sp_arr['body_utf8'] . '$$ ';
                    $sql .= "\nDELIMITER ;";
                    $sql = str_replace("'", '"', $sql);
                    //debug($sql); exit;

                    if ($found)
                    {
                        if ($is_modified)
                        {
                            $drop_sql = 'DROP PROCEDURE IF EXISTS `' . $sp_arr['name'] . '`;';
                            $records['sql']['update_sp'][$sp_name] = $drop_sql . $sql;
                        }
                    }
                    else
                    {
                        $records['sql']['new_sp'][$sp_name] = $sql;
                    }
                }

                foreach($dest_db['stored_procedures'] as $dest_sp_name => $dest_sp_arr)
                {
                    if (!in_array($dest_sp_name, $src_sp_list))
                    {
                        $sql = 'DROP PROCEDURE `' . $dest_sp_name . '`;';

                        $records['sql']['delete_sp'][$dest_sp_name] = $sql;
                    }
                }

                $src_function_list = [];
                foreach($src_db['functions'] as $function_name => $function_arr)
                {
                    $src_function_list[] = $function_name;
                    $found = false; $is_modified = false;
                    foreach($dest_db['functions'] as $dest_function_name => $dest_function_arr)
                    {
                        if ($function_name == $dest_function_name)
                        {
                            $found = true;

                            $is_modified = DateUtility::compare($function_arr['modified'], $dest_function_arr['modified']) > 0;
                        }
                    }

                    $sql = "DELIMITER $$ \n";
                    $sql .= 'CREATE FUNCTION `' . $function_arr['name'] . '` (';
                    $sql .= $function_arr['param_list'] . ")";
                    $sql .= " RETURNS " . $function_arr['returns'] . " ";
                    $sql .= $function_arr['body_utf8'] . '$$ ';
                    $sql .= "\nDELIMITER ;";

                    $sql = str_replace("'", '"', $sql);
                    if ($found)
                    {
                        if ($is_modified)
                        {
                            $drop_sql = 'DROP FUNCTION IF EXISTS `' . $function_arr['name'] . '`;';
                            $records['sql']['update_function'][$function_name] = $drop_sql . $sql;
                        }
                    }
                    else
                    {
                        $records['sql']['new_function'][$function_name] = $sql;
                    }
                }

                foreach($dest_db['functions'] as $dest_function_name => $dest_function_arr)
                {
                    if (!in_array($dest_function_name, $src_function_list))
                    {
                        $sql = 'DROP PROCEDURE `' . $dest_function_name . '`;';

                        $records['sql']['delete_function'][$dest_function_name] = $sql;
                    }
                }

                //debug($records['sql']); exit;
                
                $sql_text = "-- Sql For Database : " . $dest_db_name;
                $sql_text .= "\n-- CREATED : " . date(DateUtility::DATETIME_OUT_FORMAT);
                $sql_text .= "\n\n-- TABLES";
                
                $sql_text .= "\n\n-- New TABLES\n";
                if ( isset($this->request->data['config']['table']['new']) )
                {
                    foreach($records['sql']['new_table'] as $sql)
                    {
                        $sql_text .= "\n" . $sql . ";";
                    }
                }
                
                $sql_text .= "\n\n-- -- Drop TABLES\n";
                if ( isset($this->request->data['config']['table']['delete']) )
                {
                    foreach($records['sql']['delete_table'] as $sql)
                    {
                        $sql_text .= "\n" . $sql . ";";
                    }
                }
                
                $sql_text .= "\n\n-- -- New TABLE Column\n";
                if ( isset($this->request->data['config']['table_column']['new']) )
                {
                    foreach($records['sql']['new_column'] as $table_name => $sql_list)
                    {
                        $sql_text .= "\n-- -- Table : $table_name";
                        $sql_text .= "\n" . implode(";\n", $sql_list) . ";";
                    }
                }
                
                $sql_text .= "\n\n-- Delete TABLE Column\n";
                if ( isset($this->request->data['config']['table_column']['delete']) )
                {
                    foreach($records['sql']['delete_column'] as $table_name => $sql_list)
                    {
                        $sql_text .= "\n-- -- Table : $table_name";
                        $sql_text .= "\n" . implode(";\n", $sql_list) . ";";
                    }
                }
                
                $sql_text .= "\n\n-- Change TABLE Column\n";
                if ( isset($this->request->data['config']['table_column']['change']) )
                {
                    foreach($records['sql']['change_column'] as $table_name => $sql_list)
                    {
                        $sql_text .= "\n-- -- Table : $table_name";
                        $sql_text .= "\n" . implode(";\n", $sql_list) . ";";                       
                    }
                }
                
                
                $sql_text .= "\n\n-- Functions";
                $sql_text .= "\n\n-- -- New Function\n";
                if ( isset($this->request->data['config']['function']['new']) )
                {
                    foreach($records['sql']['new_function'] as $sql)
                    {
                        $sql_text .= "\n" . $sql;
                    }
                }
                
                $sql_text .= "\n\n-- -- Updated Function\n";
                if ( isset($this->request->data['config']['function']['change']) )
                {
                    foreach($records['sql']['update_function'] as $sql)
                    {
                        $sql_text .= "\n" . $sql;
                    }
                }
                
                $sql_text .= "\n\n-- -- Drop Function\n";
                if ( isset($this->request->data['config']['function']['delete']) )
                {
                    foreach($records['sql']['delete_function'] as $sql)
                    {
                        $sql_text .= "\n" . $sql;
                    }
                }
                
                $sql_text .= "\n\n-- Views";
                $sql_text .= "\n\n-- -- New Views\n";
                if ( isset($this->request->data['config']['view']['new']) )
                {
                    foreach($records['sql']['new_view'] as $sql)
                    {
                        $sql_text .= "\n" . $sql . ';';
                    }
                }
                
                $sql_text .= "\n\n-- -- Change Views\n";
                if ( isset($this->request->data['config']['view']['change']) )
                {
                    foreach($records['sql']['change_view'] as $sql)
                    {
                        $sql_text .= "\n" . $sql . ';';
                    }
                }
                
                $sql_text .= "\n\n-- -- Drop Views\n";
                if ( isset($this->request->data['config']['view']['delete']) )
                {
                    foreach($records['sql']['delete_view'] as $sql)
                    {
                        $sql_text .= "\n" . $sql . ';';
                    }
                }
                
                $sql_text .= "\n\n-- Stored Procedures";
                $sql_text .= "\n\n-- -- New Procedures\n";
                if ( isset($this->request->data['config']['sp']['new']) )
                {
                    foreach($records['sql']['new_sp'] as $sql)
                    {
                        $sql_text .= "\n" . $sql;
                    }
                }
                
                $sql_text .= "\n\n-- -- Updated Procedures\n";
                if ( isset($this->request->data['config']['sp']['change']) )
                {
                    foreach($records['sql']['update_sp'] as $sql)
                    {
                        $sql_text .= "\n" . $sql;
                    }
                }
                
                $sql_text .= "\n\n-- -- Drop Procedures\n";
                if ( isset($this->request->data['config']['sp']['delete']) )
                {
                    foreach($records['sql']['delete_sp'] as $sql)
                    {
                        $sql_text .= "\n" . $sql;
                    }
                }
                
                FileUtility::createFolder("files/");
                $file = "files/sql_for_db_" . $dest_db_name . "-" . date(DateUtility::DATE_OUT_FORMAT) . ".sql";
                
                file_put_contents($file, $sql_text);
                
                download_start($file, 'application/text');
            }
        }
        catch(Exception $ex)
        {
            if ($mysqlSrc)
            {
                $mysqlSrc->close();
            }
            
            if ($mysqlDest)
            {
                $mysqlDest->close();
            }
        }
        
        $this->set(compact("records", "src_db_name", "dest_db_name"));
    }
    
    private function _create_table_field($field_name, $attr)
    {
        $str = "`$field_name`";
        
        if ($attr['data_type'] == 'int' || $attr['data_type'] == 'tinyint' || $attr['data_type'] == 'smallint' || $attr['data_type'] == 'varchar')
        {
            if (!$attr['length'])
            {
                $attr['length'] = 1;
            }
            
            $str .= " " . $attr['data_type'] . "(" . $attr['length'] . ")";
        }
        else
        {
            $str .= " " . $attr['data_type'];
        }
        
        if ($attr['unsigned'])
        {
            $str .= " unsigned";
        }
        
        if ($attr['null'])
        {
            $str .= " NULL";
        }
        else
        {
            $str .= " NOT NULL";
        }
            
        if ($attr['autoincrement'])
        {
            $str .= " AUTO_INCREMENT";
        }
        
        return $str;
    }
    
    private function _new_field($field_name, $attr, $prev_col)
    {
        $sql_str = "ADD COLUMN " . $this->_create_table_field($field_name, $attr) . " AFTER `$prev_col`";
        
        $str = $this->_create_table_field_for_primary($field_name, $attr);
        if ($str)
        {
            $sql_str .= ", " . $str;
        }
        
        $str = $this->_alter_table_field_for_unique($field_name, $attr);
        if ($str)
        {
            $sql_str .= ", " . $str;
        }
        
        if ($attr['unique'])
        {
            $sql_str .= ", ADD INDEX (`$field_name`)";
        }
        
        return $sql_str;
    }
    
    private function _create_table_field_for_primary($field_name, $attr)
    {
        $str = "";
        if ($attr['primary'])
        {
            $str = "PRIMARY KEY (`$field_name`)";
        }
        
        return $str;
    }
    
    private function _create_table_field_for_unique($field_name, $attr)
    {
        $str = "";
        if ($attr['unique'])
        {
            $str = "UNIQUE KEY `$field_name" . "_UNIQUE` (`$field_name`)";
        }
        
        return $str;
    }
    
    private function _alter_table_field_for_unique($field_name, $attr)
    {
        $str = "";
        if ($attr['unique'])
        {
            $str = "ADD UNIQUE INDEX `$field_name" . "_UNIQUE` (`$field_name` ASC)";
        }
        
        return $str;
    }
    
    private function _create_table_field_for_index($field_name, $attr)
    {
        $str = "";
        if ($attr['unique'])
        {
            $str = "KEY `$field_name` (`$field_name`)";
        }
        
        return $str;
    }
    
    private function _find_index_on_column($db, $table, $col)
    {
        $q = "SELECT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA ='$db' AND `TABLE_NAME` = '$table' AND `COLUMN_NAME` = '$col'";
        
        $temp = $this->{$this->modelClass}->query($q);
        
        $list = [];
        
        foreach($temp as $arr)
        {
            $list[] = $arr['STATISTICS']['INDEX_NAME'];
        }
             
        return $list;
    }
    
    private function _find_db_meta(MysqlCustom $conn)
    {
        $result = [
            "tables" => [],
            "views" => [],
            "stored_procedures" => [],
            "functions" => [],
        ];
        
        $db_name = $conn->getDBName();
        
        $q = "SHOW FULL TABLES FROM $db_name where Table_Type != 'VIEW'";
        
        $temp = $conn->select($q);
        
        foreach($temp as $arr)
        {
            $table_name = $arr["Tables_in_" . $db_name];
            $result["tables"][$table_name] = [];
        }
        
        foreach($result["tables"] as $table_name => $v)
        {
            $q = "SELECT 
                    *
                  FROM 
                    INFORMATION_SCHEMA.COLUMNS
                  WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table_name';";
            
            $temp = $conn->select($q);
            
            foreach($temp as $arr)
            {
                $result["tables"][$table_name]["columns"][$arr['COLUMN_NAME']] = $arr;
            }
        }
        
        $q = "SELECT 
                *
            FROM 
                mysql.proc
            WHERE db = '$db_name'";

        $temp = $conn->select($q);
        
        foreach($temp as $arr)
        {
            $arr['param_list'] = preg_replace('!\s+!', ' ', $arr['param_list']); 
            $arr['body_utf8'] = preg_replace('!\s+!', ' ', $arr['body_utf8']); 
            if ($arr['type'] == 'FUNCTION')
            {
                $result["functions"][$arr['name']] = $arr;
            }
            
            if ($arr['type'] == 'PROCEDURE')
            {
                $result["stored_procedures"][$arr['name']] = $arr;
            }
        }
        
        $q = "SELECT 
                *
            FROM 
                INFORMATION_SCHEMA.VIEWS
            WHERE TABLE_SCHEMA = '$db_name'";
        
        $temp = $conn->select($q);
        
        foreach($temp as $arr)
        {
            $arr['VIEW_DEFINITION'] = preg_replace('!\s+!', ' ', $arr['VIEW_DEFINITION']);             
            $result["views"][$arr['TABLE_NAME']] = $arr;
        }
        
        return $result;
    }
}

