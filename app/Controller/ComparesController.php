<?php
class ComparesController extends AppController
{
    public function index()
    {
        $this->Redirect->urlToNamed();

        $conditions = $this->getSearchConditions(array(
            array('model' => $this->modelClass, 'field' => "src_db_name", 'type' => 'string', 'view_field' => 'src_db_name'),            
            array('model' => $this->modelClass, 'field' => "dest_db_name", 'type' => 'string', 'view_field' => 'dest_db_name'),            
        ));
        
        $records = $this->paginate($this->modelClass, $conditions);
        
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
        
        $src_db = $this->_find_table_meta($record[$this->modelClass]['src_db_name']);
        $dest_db = $this->_find_table_meta($record[$this->modelClass]['dest_db_name']);
        
        $records = [
            'tables' => []
        ];
        
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
                    "autoincrement" => $autoincrement,
                ];
            }
            
            $records['tables'][$table_name]['is_new'] = false;
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
                    "autoincrement" => $autoincrement,
                ];
            }
            
            if (!isset($records['tables'][$table_name]))
            {
                $records['tables'][$table_name]['is_new'] = true;
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
        
        $this->set(compact("records"));
    }
    
    private function _find_table_meta($db_name)
    {
        $result = [
            "tables" => []
        ];
        
        $q = "SHOW FULL TABLES FROM $db_name where Table_Type != 'VIEW'";
        
        $temp = $this->Compare->query($q);
        
        foreach($temp as $arr)
        {
            $table_name = $arr['TABLE_NAMES']["Tables_in_" . $db_name];
            $result["tables"][$table_name] = [];
        }
        
        foreach($result["tables"] as $table_name => $v)
        {
            $q = "SELECT 
                    *
                  FROM 
                    INFORMATION_SCHEMA.COLUMNS
                  WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table_name';";
            
            $temp = $this->Compare->query($q);
            
            foreach($temp as $arr)
            {
                $result["tables"][$table_name]["columns"][$arr['COLUMNS']['COLUMN_NAME']] = $arr['COLUMNS'];
            }
        }
        
        return $result;
    }
}

