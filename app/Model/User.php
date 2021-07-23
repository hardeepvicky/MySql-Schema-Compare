<?php
class User extends AppModel
{
    public $sanitize = false;
    
    public $actsAs = array(
        //'Acl' => [ 'type' => 'requester', 'enabled' => false ],
        'ContainableExtend',
        "Vcache" => [
            "cache_config" => "day",
            "fields" => ["id", "name"]
        ]
    );

    var $virtualFields = array(
        'name' => 'CONCAT(firstname, " ", lastname)'
    );

    public $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id'
        ),
    );
    
    public $hasOne = array(
    );

    public $hasMany = array(
    );

    public $validate = array(
        'username' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => "Username is required"),
            'isUnique' => array('rule' => 'isUnique', 'message' => "Username alredy exist"),
        ),
        'email' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => "Email is required"),
        ),
        'group_id' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => "Group is required"),
        ),
        'password' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => "Password is required"),
            'minLength' => array('rule' => array('minLength', 6), 'message' => "Min 6 chars required for Password"),
        ),
        'firstname' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => "Firstname is required"),
        ),
    );

    public function beforeSave($options = array())
    {
        parent::beforeSave($options);

        if (isset($this->data["User"]["password"]) && !empty($this->data["User"]["password"]))
        {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        else if (empty($this->data["User"]["password"]))
        {
            unset($this->data["User"]["password"]);
        }

        return true;
    }
    
    public function bindNode($user)
    {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }

    public function parentNode()
    {
        if (!$this->id && empty($this->data))
        {
            return null;
        }
        if (isset($this->data['User']['group_id']))
        {
            $groupId = $this->data['User']['group_id'];
        }
        else
        {
            $groupId = $this->field('group_id');
        }
        if (!$groupId)
        {
            return null;
        }
        return array('Group' => array('id' => $groupId));
    }
    
    public function forgotPassword($id)
    {
        $from_email = self::initSettingModel()->getValueFromName("admin_email");
        
        if (!$from_email)
        {
            $from_email = "admin@gmail.com";
            //throw new Exception("Setting : admin_email is not set yet");
        }
        
        $this->recursive = -1;
        $user = $this->findById($id);

        if (empty($user))
        {
            throw new Exception('Invalid User Id');
        }
        else if (empty($user["User"]["email"]))
        {
            throw new Exception('User does not have email address');
        }

        $new_password = Util::getRandomString(6, "123456789");
        
        $this->id = $id;
        $this->saveField("password", $new_password);

        $placeholder["User.name"] = $user["User"]["name"];
        $placeholder["User.password"] = $new_password;
        $placeholder["User.firstname"] = $user["User"]["firstname"];
        $placeholder["User.lastname"] = $user["User"]["lastname"];
        $placeholder["User.email"] = $user["User"]["email"];

        return $this->sendEmail(EmailType::FORGOT_PASSWORD, $from_email, array($user["User"]["email"]), $placeholder);
    }
}