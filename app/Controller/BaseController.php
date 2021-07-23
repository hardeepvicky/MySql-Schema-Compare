<?php
/**
 * @created    26-02-2021
 * @author     Hardeep
 */
class BaseController extends Controller 
{
    private $start_time; 
    
    protected function _queryLog()
    {
        $dbo = $this->{$this->modelClass}->getDatasource();
        return $dbo->getLog();
    }
    
    protected function _queryLastLog()
    {
        $logs = $this->_queryLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }
    
    //function to prevent load model more than 2 times
    protected function load_model($model)
    {
        if (!in_array($model, $this->uses, true)) 
        {
            $this->loadModel($model);
        }
    }
    
    protected function _setMysqlIndianTimeZone()
    {
        $q = "SET SESSION time_zone = '+5:30'";
        
        $this->WebRequestLog->query($q);
    }
    
    protected function _setLargeGroupConcat($model = null)
    {
        if (!$model)
        {
            $model = $this->modelClass;
        }
        
        if (!$model)
        {
            $model = "Setting";
            $this->load_model($model);
        }
        
        $this->{$model}->query("SET SESSION group_concat_max_len = 1000000;");
    }
    
    protected function _setSmallMemory($model = null)
    {
        if (!$model)
        {
            $model = $this->modelClass;
        }
        
        if (!$model)
        {
            $model = "Setting";
            $this->load_model($model);
        }
        
        set_time_limit(600);
        ini_set("memory_limit", "624M");
        ini_set("default_socket_timeout", 600);
        
        $this->{$model}->query("SET session net_read_timeout=300");
        $this->{$model}->query("SET session net_write_timeout=300");
    }
    
    protected function _setLargeMemory($model = null)
    {
        if (!$model)
        {
            $model = $this->modelClass;
        }
        
        if (!$model)
        {
            $model = "Setting";
            $this->load_model($model);
        }
        
        set_time_limit(3600);
        ini_set("memory_limit", "1512M");
        ini_set("default_socket_timeout", 3600);
        
        $this->{$model}->query("SET session net_read_timeout=300");
        $this->{$model}->query("SET session net_write_timeout=1800");
    }
    
    protected function jsonResonse($response, $save_log = true)
    {
        $json = json_encode($response);
        
        echo $json; exit;
    }
}