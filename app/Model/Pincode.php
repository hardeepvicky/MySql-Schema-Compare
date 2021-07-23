<?php
class Pincode extends AppModel
{
    public $model_cache_config = "month", $model_cache_key = 'Pincode';
    public $unique_fields = array("city_id", "name");
    
    public $belongsTo = array(
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id'
        ),
    );
    
    public $validate = array(
        'name' => array(
            'notBlank' => array('rule' => array('notBlank'), 'message' => 'Pincode is required.'),
            'isUnique' => array('rule' => 'isUnique', 'message' => "Pincode alredy exist"),
        ),
    );
}
