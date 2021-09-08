<?php
class Compare extends AppModel
{
    public $validate = array(
        'src_conn_type' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'Source Connection Type is required.'),
        ),
        'src_db_name' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'Source Database Name is required.'),
        ),
        'dest_conn_type' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'Destination Connection Type is required.'),
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
        
        $data = Util::applyAll( $this->data[$this->alias], ["trim"] );
        
        error_reporting(0);
        
        if (isset($data['src_conn_type']))
        {
            if ($data['src_conn_type'] == ConnectionType::LOCAL)
            {
                if ( !in_array($data['src_db_name'], $db_list) )
                {
                    $this->invalidate("src_db_name", "This Database is not present");
                    $result = false;
                }
                $data['src_server'] = $data['src_username'] = $data['src_password'] = $data['src_port'] = "";
            }
            else
            {
                if ( !isset($data['src_server']) )
                {
                    throw new Exception("src_server field is required");
                }
                
                if ( !isset($data['src_username']) )
                {
                    throw new Exception("src_username field is required");
                }
                
                if ( !isset($data['src_password']) )
                {
                    throw new Exception("src_password field is required");
                }
                
                if ( !isset($data['src_port']) )
                {
                    throw new Exception("src_port field is required");
                }
                
                if ($data['src_port'])
                {
                    $link = mysqli_connect($data['src_server'], $data['src_username'], $data['src_password'], $data['src_db_name'], $data['src_port']);
                    
                    if ($link === false)
                    {
                        $this->invalidate("src_server", mysqli_connect_error());
                        $result = false;
                    }
                    else
                    {
                        mysqli_close($link);
                    }
                }
                else
                {
                    $link = mysqli_connect($data['src_server'], $data['src_username'], $data['src_password'], $data['src_db_name']);

                    if ($link === false)
                    {
                        $this->invalidate("src_server", mysqli_connect_error());
                        $result = false;
                    }
                    else
                    {
                        mysqli_close($link);
                    }
                }
            }
        }
        
        if (isset($data['dest_conn_type']))
        {
            if ($data['dest_conn_type'] == ConnectionType::LOCAL)
            {
                if ( !in_array($data['dest_db_name'], $db_list) )
                {
                    $this->invalidate("dest_db_name", "This Database is not present");
                    $result = false;
                }
                
                $data['dest_server'] = $data['dest_username'] = $data['dest_password'] = $data['dest_port'] = "";
            }
            else
            {
                if ( !isset($data['dest_server']) )
                {
                    throw new Exception("dest_server field is required");
                }
                
                if ( !isset($data['dest_username']) )
                {
                    throw new Exception("dest_username field is required");
                }
                
                if ( !isset($data['dest_password']) )
                {
                    throw new Exception("dest_password field is required");
                }
                
                if ( !isset($data['dest_port']) )
                {
                    throw new Exception("dest_port field is required");
                }
                
                if ($data['dest_port'])
                {
                    $link = mysqli_connect($data['dest_server'], $data['dest_username'], $data['dest_password'], $data['dest_db_name'], $data['dest_port']);
                    
                    if ($link === false)
                    {
                        $this->invalidate("dest_server", mysqli_connect_error());
                        $result = false;
                    }
                    else
                    {
                        mysqli_close($link);
                    }
                }
                else
                {
                    $link = mysqli_connect($data['dest_server'], $data['dest_username'], $data['dest_password'], $data['dest_db_name']);

                    if ($link === false)
                    {
                        $this->invalidate("dest_server", mysqli_connect_error());
                        $result = false;
                    }
                    else
                    {
                        mysqli_close($link);
                    }
                }
            }
        }
        
        $this->data[$this->alias] = $data;
        
        error_reporting(E_ALL);
        
        return $result;
    }
}
