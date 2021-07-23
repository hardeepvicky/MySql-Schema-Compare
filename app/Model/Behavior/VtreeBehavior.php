<?php
class VtreeBehavior extends ModelBehavior
{
    public function setup(Model $model, $config = array())
    {
        $this->settings = $config;
        
        if ( !isset( $this->settings["parent_field"] ) )
        {
            throw new InvalidArgumentException("parent_field is missing in config");
        }
        
        if ( !isset( $this->settings["primary_key"] ) )
        {
            $this->settings["primary_key"] = "id";
        }
    }

    /**
     * return data in tree structure
     * @param array $arr
     * @param int $parent_id
     * @return array
     */
    public function getVtree(Model $model, $arr = array(), $parent_id = 0)
    {
        if (isset($arr["fields"]) && !in_array($this->settings["parent_field"], $arr["fields"]))
        {
            $arr["fields"][] = $this->settings["parent_field"];
        }

        $this->recursive = -1;

        $records = $model->find("all", $arr);

        return $this->getVtreeArray($model, $records, $parent_id);
    }

    /**
     * convert cakephp find records to tree
     * @param array $records
     * @param int $parentId
     * @return array
     */
    public function getVtreeArray(Model $model, array $records, $parentId = 0)
    {
        $data = array();

        foreach ($records as $element)
        {
            if ($element[$model->alias][$this->settings["parent_field"]] == $parentId)
            {
                $children = $this->getVtreeArray($model, $records, $element[$model->alias]['id']);

                if ($children)
                {
                    $element['children'] = $children;
                }

                $data[] = $element;
            }
        }

        return $data;
    }

    public function getVtreeList(Model $model, $value, $conditions = array(), $parent_id = 0, $only_parent = false, $only_child = true, $sep = " | ")
    {
        $fields = array($this->settings["primary_key"], $value);
        $records = $this->getVtree($model, array(
            "fields" => $fields,
            "conditions" => $conditions
        ), $parent_id);

        return $this->getVtreeListArray($model, $records, $value, $only_parent, $only_child, "", $sep);
    }

    /**
     * convert cakephp find records to tree List
     * @param array $tree
     * @return array
     */
    public function getVtreeListArray(Model $model, array $tree, $value, $only_parent = false, $only_child = true, $prefix = "", $sep = " | ")
    {
        $list = array();

        foreach ($tree as $node)
        {
            $id = $node[$model->alias][$this->settings["primary_key"]];
            $name = $prefix . $node[$model->alias][$value];

            if ($only_parent)
            {
                $list[$id] = $name;
            }
            else if ($only_child)
            {
                if (isset($node["children"]) && !empty($node["children"]))
                {
                    $list += $this->getVtreeListArray($model, $node["children"], $value, $only_parent, $only_child, $name . $sep, $sep);
                }
                else
                {
                    $list[$id] = $name;
                }
            }
            else
            {
                $list[$id] = $name;

                if (isset($node["children"]) && !empty($node["children"]))
                {
                    $list += $this->getVtreeListArray($model, $node["children"], $value, $only_parent, $only_child, $name . $sep, $sep);
                }
            }
        }

        return $list;
    }

    /**
     * get list of model which have parent field
     * for use that you have to set parent_field in model
     * @param array $parent_id
     * @return array
     */
    public function getVtreeChildCount(Model $model, $parent_id = 0, $conditions = [])
    {
        $conditions = array_merge($conditions, array(
            $this->settings["parent_field"] => $parent_id
        ));
        
        $count = $model->find("count", array(
            "conditions" => $conditions,
            "recursive" => -1
        ));

        return $count;
    }
}