<?php
/**
 * @created    06-03-2017
 * @copyright  Copyright (C) 2017
 * @license    Proprietary
 */

App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class AppModel extends Model
{
    public $recursive = -1;
    public $sanitize = true;

    public $actsAs = array(
        'ContainableExtend',        
    );
    
    /**
     * @var array 
     */
    public $unique_fields = array();
    
    /**
     * @var array 
     */
    public $non_update_fields = array();
    
    public static $authUser = array();
    
    public static $settingModel = null;
    
    /**----------------------- Default Callbacks -----------------------------**/
    
    /**
     * @param array $options
     * @return Boolean
     */
    public function beforeSave($options = array())
    {
        if ($this->non_update_fields && ($this->id || $this->data[$this->alias]["id"]) )
        {
            foreach($this->data[$this->alias] as $field => $value)
            {
                if (in_array($field, $this->non_update_fields))
                {
                    unset($this->data[$this->alias][$field]);
                }
            }
        }
        
        if (self::$authUser)
        {
            //Checks if created_by field is exists and it's not record edit mode
            if (!$this->id && $this->hasField('created_by') && !isset($this->data[$this->alias]['created_by']))
            {
                $this->data[$this->alias]['created_by'] = self::$authUser["id"];
            }

            //Checks if modified_by field is exists
            if ($this->hasField('modified_by') && !isset($this->data[$this->alias]['modified_by']))
            {
                $this->data[$this->alias]['modified_by'] = self::$authUser["id"];
            }
        }
        
        if ($this->sanitize)
        {
            $this->data = Sanitize::clean($this->data, ["escape" => false]);
        }
        
        foreach($this->data as $alias => $data)
        {
            foreach($data as $field => $value)
            {
                if (is_string($value))
                {
                    $this->data[$alias][$field] = trim($value);
                }
            }
        }

        return parent::beforeSave();
    }
    
    /**
     * Customer Validation Rule
     * @param array $data
     * @param array $rule
     * @return Boolean
     * @throws Exception
     */
    public function comboUnique($data, $rule)
    {
        if ( !isset($rule['uniqueWith']) )
        {
            throw new Exception("uniqueWith is present in validate rule in nameUnique");
        }
        
        if ( !is_array($rule['uniqueWith']) )
        {
            $rule['uniqueWith'] = [$rule['uniqueWith']];
        }
        
        $key = key($data);
        $value = $data[$key];
        
        if ( isset($rule['allowEmpty']) && $rule['allowEmpty'] && empty($value))
        {
            return true;
        }
        
        $conditions[$key] = $value;
        
        if ($this->id)
        {
            $conditions['NOT']['id'] = $this->id;
        }
        else if ( isset($this->data[$this->alias]['id']) )
        {
            $conditions['NOT']['id'] = $this->data[$this->alias]['id'];
        }
            
        foreach($rule['uniqueWith'] as $field)
        {
            if ( isset($this->data[$this->alias][$field]) )
            {
                $conditions[$field] = $this->data[$this->alias][$field];
            }
            else
            {
                if ( isset($conditions['NOT']['id']) )
                {
                    $this->id = $conditions['NOT']['id'];
                    $conditions[$field] = $this->field($field);
                }
                else
                {
                    throw new Exception("nameUnique : $field is not present in data in " . $this->alias);
                }
            }
        }
        
        $count = $this->find("count", [
            'conditions' => $conditions
        ]);
        
        return $count == 0;
    }
    
    /**------------------------- Cache Find ---------------------------------**/
    public function getList($key = "id", $value = "name", $conditions = array(), $order = array())
    {
        if (empty($order))
        {
            $order = array("$value ASC");
        }
        
        return $this->find("list", [
            "fields" => [$key, $value],
            "conditions" => $conditions,
            "order" => $order
        ]);
    }
    
    /**------------------- Soft Delete ---------------------------------------*/
    public function softDelete($delete = 1)
    {
        $this->softDeleteAll(array("id" => $this->id), $delete);
    }
    
    public function softDeleteAll($conditions, $delete = 1)
    {
        if ($this->hasField("is_deleted"))
        {
            $list = $this->find("list", array(
                "fields" => array("id"),
                "conditions" => $conditions
            ));
            
            foreach($list as $id)
            {
                $this->id = $id;     
                
                if ($delete)
                {
                    if ($this->beforeSoftDelete())
                    {
                        $this->saveField("is_deleted", $delete);
                        $this->afterSoftDelete();
                    }
                }
                else
                {
                    $this->saveField("is_deleted", $delete);
                }
            }
            
            foreach ($this->hasMany as $alias => $hasMany)
            {
                if (isset($hasMany['dependent']) && $hasMany['dependent'])
                {
                    $this->{$alias}->softDeleteAll(array($hasMany['foreignKey'] => $list), $delete);
                }
            }
        }
    }
    
    public function beforeSoftDelete()
    {
        return true;
    }
    
    public function afterSoftDelete()
    {
    }
    
    /** --------------- Save on basis of unique fields ----------------------*/
    public function saveOrUpdate($data)
    {
        if ( !isset( $data[$this->primaryKey] ))
        {
            $id = $this->getIDOnBasisOfUniqueField($data);
            
            if ($id)
            {
                $this->id = $id;
            }
            else
            {
                $this->create();
            }
        }
        
        return $this->save($data);
    }
    
    public function getIDOnBasisOfUniqueField($data)
    {
        $conditions = array();

        foreach($this->unique_fields as $field)
        {
            if (!isset($data[$field]))
            {
                throw new Exception("getIDOnBasisOfUniqueField : $field is not present in data");
            }

            $conditions[$field] = $data[$field];
        }

        $record = $this->find("first", array(
            "fields" => array("id"),
            "conditions" => $conditions,
            "recursive" => -1
        ));

        if ($record)
        {
            return $record[$this->alias][$this->primaryKey];
        }
        
        return false;
    }
        
    /*
     * Prevents Delete and Inactive if child data available
     * @param id
     * @return Boolean
     */
    public function preventDeleteAndInactive($id)
    {
        $has = array();
        
        if (isset($this->hasMany) && is_array($this->hasMany))
        {
            $has = array_merge($has, $this->hasMany);
        }
        
        if (isset($this->hasOne) && is_array($this->hasOne))
        {
            $has = array_merge($has, $this->hasOne);
        }
        
        if ($has)
        {
            foreach ($has as $key => $value)
            {
                if (!isset($value["dependent"]) || !$value["dependent"])
                {
                    $this->{$key}->recursive = -1;
                    $childRecords = $this->{$key}->find('count', array(
                        'conditions' => array(
                            $key . "." . $value['foreignKey'] => $id
                        )
                    ));

                    if (isset($childRecords) && $childRecords > 0)
                    {
                        return FALSE;
                    }
                }
            }
        }
        
        return TRUE;
    }
    
    public function getNextAutoIncreamentId() 
    {
        $db = $this->getDataSource()->config['database'];
        $result = $this->query("SELECT Auto_increment FROM information_schema.tables AS schema_table  WHERE table_name='" . $this->table . "' AND table_schema='" . $db . "'");
        return $result[0]['schema_table']['Auto_increment'];
    }
    
    public function saveNotification($data)
    {
        if ( !isset($this->Notification))
        {
            $this->Notification = ClassRegistry::init('Notification');
        }
        
        $save['type'] = $data['type'];
        
        if (isset($data['from_user_id']))
        {
            $save['from_user_id'] = $data['from_user_id'];
        }
        
        if (isset($data['to_user_id']))
        {
            $save['to_user_id'] = $data['to_user_id'];
        }
    
        $save['title'] = $data['title'];
        $save['detail'] = isset($data['detail']) ? $data['detail'] : "";
        
        $this->Notification->create();
        $this->Notification->save(array("Notification" => $save));
    }
    
    public function sendEmail($email_code, $from_email, array $to_emails, array $placeholders, array $files = array()) 
    {
        if ( !isset($this->EmailTemplate))
        {
            $this->EmailTemplate = ClassRegistry::init('EmailTemplate');           
        }
        
        if ( !isset($this->EmailLog))
        {
            $this->EmailLog = ClassRegistry::init('EmailLog');           
        }
        
        $email_template = $this->EmailTemplate->findByCode($email_code);
        if (!$email_template)
        {
            return false;
        }
        
        $subject = $email_template["EmailTemplate"]["subject"];
        $content = $email_template["EmailTemplate"]["body"];
        
        foreach($placeholders as $placeholder => $value)
        {
            $subject = str_replace("@" . $placeholder, $value, $subject);
            $content = str_replace("@" . $placeholder, $value, $content);
        }
        
        $this->EmailLog->create();
        $this->EmailLog->save(array(
            "code" => $email_code,
            "from_email" => $from_email,
            "to_email" => implode(", ", $to_emails),
            "subject" => $subject,
            "body" => $content
        ));
        
        $email = new CakeEmail('smtp');
        $email->emailFormat('html');
        $email->from(array($from_email => SITE_NAME));
        $email->to($to_emails);
        $email->subject($subject);

        if ($files)
        {
            $email->attachments($files);
        }

        if ( !$email->send($content) )
        {
            return false;
        }

        return true;
    }
    
    public function sp($name, $inputParams = array(), $outputParams = array())
    {
        $arr = array();
        foreach ($inputParams as $val)
        {
            if (is_null($val))
            {
                $val = "NULL";
            }
            else if (is_bool($val))
            {
                $val = $val ? 1 : 0;
            }
            else if (is_array($val))
            {
                if (!empty($val))
                {
                    $val = "'" . implode(",", $val) . "'";
                }
                else
                {
                    $val = "NULL";
                }
            }
            else
            {
                $val = "'" . $val . "'";
            }

            $arr[] = $val;
        }

        $params = array_merge($arr, $outputParams);

        $params = implode(",", $params);

        //Gets call result
        $q = "CALL {$name}({$params});";
        //debug($q); exit;
        $callResult = $this->query($q);

        $outputParams = implode(",", $outputParams);

        //Gets output result
        $outputResult = array();
        if (!empty($outputParams))
        {
            $outputResult = $this->query("SELECT {$outputParams};");
        }
        //Returns the combined result
        return array_merge($callResult, $outputResult);
    }
    
    private function _set_sp_inputs($sp_keys, $inputs)
    {
        $params = array();
        foreach($sp_keys as $key)
        {
            if (isset($inputs[$key]))
            {
                $params[] = $inputs[$key];
            }
            else
            {
                $params[] = NULL;
            }
        }
        
        return $params;
    }
}
