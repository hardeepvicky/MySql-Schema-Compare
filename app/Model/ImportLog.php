<?php
class ImportLog extends AppModel
{
    public $sanitize = false;
    
    public function afterFind($results, $primary = false)
    {
        foreach($results as $k => $record)
        {
            if (isset($record["ImportLog"]) && isset($record["ImportLog"]["file"]))
            {
                $results[$k]["ImportLog"]["file"] = FileUtility::get( $record["ImportLog"]["file"] );
            }
        }
        
        return parent::afterFind($results, $primary);
    }
}
