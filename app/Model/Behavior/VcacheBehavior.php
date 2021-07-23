<?php
class VcacheBehavior extends ModelBehavior
{
    /**
     * @var string 
     */
    private $cachePrefix = "model_";
    
    public function setup(Model $model, $config = array())
    {
        $this->settings = $config;
        
        if (!isset($this->settings["cache_config"]))
        {
            throw new InvalidArgumentException("cache_config is not present in config");
        }
    }

    
    /**
     * @param array $order
     * @return array
     */
    public function findCache(Model $model, $order = array())
    {
        $data = array();

        $data = Cache::read($this->cachePrefix . $model->alias, $this->settings["cache_config"]);

        if (!$data)
        {
            $model->recursive = -1;
            
            $model->contain(array());
            
            $arr = [
                "order" => $order
            ];
            
            if (isset($this->settings["fields"]))
            {
                $arr["fields"] = $this->settings["fields"];
            }
            
            if (isset($this->settings["conditions"]))
            {
                $arr["conditions"] = $this->settings["conditions"];
            }
            
            $data = $model->find("all", $arr);

            Cache::write($this->cachePrefix . $model->alias, $data, $this->settings["cache_config"]);
        }

        return $data;
    }
    
    /**
     * return list
     * @param string $key
     * @param string $value
     * @param array $order
     * @return array
     */
    public function findCacheList(Model $model, $key = "id", $value = "name", $order = array())
    {
        if (empty($order))
        {
            if ($model->hasField("dispaly_order"))
            {
                $order = ["$value ASC"];
            }
            else
            {
                $order = ["$value ASC"];
            }
        }
        
        $data = $this->findCache($model, $order);
        
        return Hash::combine($data, "{n}.{s}.$key", "{n}.{s}.$value");
    }
    
    /**
     * delete the cache
     * @return boolean
     */
    public function deleteCache(Model $model)
    {
        Cache::delete($this->cachePrefix . $model->alias, $this->settings["cache_config"]);            

        if ( isset($this->settings["view_models"]) && $this->settings["view_models"])
        {
            foreach($this->settings["view_models"] as $view_alias)
            {
                Cache::delete($this->cachePrefix . $view_alias, $this->settings["cache_config"]);            
            }
        }
        
        return true;
    }
    
    /**--------------------------Callbacks------------------------------------*/
    public function afterSave(Model $model, $created, $options = array()) 
    {
        return $this->deleteCache($model);
    }

    public function afterDelete(Model $model)
    {
        return $this->deleteCache($model);
    }
}