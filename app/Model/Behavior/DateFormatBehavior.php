<?php
class DateFormatBehavior extends ModelBehavior
{
    public function setup(Model $model, $config = array())
    {
        $this->settings[$model->alias] = $config;
    }

    public function afterFind(Model $model, $results, $primary = false)
    {
        parent::afterFind($model, $results, $primary);

        foreach ($results as $key => $record)
        {
            foreach ($this->settings[$model->alias] as $field => $format)
            {
                if (isset($record[$model->alias]) && isset($results[$key][$model->alias][$field]))
                {
                    $results[$key][$model->alias][$field] = DateUtility::getDate($record[$model->alias][$field], $format);
                }
            }
        }

        return $results;
    }

    public function beforeSave(Model $model, $options = array())
    {
        foreach ($this->settings[$model->alias] as $field => $format)
        {
            if (isset($model->data[$model->alias][$field]))
            {
                $model->data[$model->alias][$field] = DateUtility::getDate($model->data[$model->alias][$field], DateUtility::DATETIME_FORMAT);
            }
        }
        
        return parent::beforeSave($model, $options);
    }
}