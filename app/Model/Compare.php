<?php
class Compare extends AppModel
{
    public $validate = array(
        'src_db_name' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'Source Database Name is required.'),
            'comboUnique' => array('rule' => 'comboUnique', 'uniqueWith' => ['dest_db_name'], 'message' => "Source Database with Destination Database already exist"),
        ),
        'dest_db_name' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'Destination Database Name is required.'),
        ),
    );
    
    public function beforeSave($options = array())
    {
        $result = parent::beforeSave($options);
        
        $records = $this->query("SHOW DATABASES");
        
        $db_list = Set::classicExtract($records, "{n}.SCHEMATA.Database");
        
        if ( isset($this->data[$this->alias]['src_db_name'] ))
        {
            $db_name = trim($this->data[$this->alias]['src_db_name']);
            
            if ( !in_array($db_name, $db_list) )
            {
                $this->invalidate("src_db_name", "This Database is not present");
                $result = false;
            }
        }
        
        if ( isset($this->data[$this->alias]['dest_db_name'] ))
        {
            $db_name = trim($this->data[$this->alias]['dest_db_name']);
            
            if ( !in_array($db_name, $db_list) )
            {
                $this->invalidate("dest_db_name", "This Database is not present");
                $result = false;
            }
        }
        
        return $result;
    }
}
