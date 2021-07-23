<?php
class Notification extends AppModel
{
    public $sanitize = false;
    public $actsAs = array(
        'ContainableExtend',        
    );
     
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'from_user_id',
        ),
    );
    
    public $validate = array(
        'type' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'Type is required.'),
        ),
        'to_user_id' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'User ID is required.'),
        ),
        'title' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'Title is required.'),
        ),
    );
}
