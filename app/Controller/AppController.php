<?php
App::uses('BaseController', 'Controller');
App::uses('AppModel', 'Model');

class AppController extends BaseController
{
    public $authUser, $accountGroups, $voucherTypes;
    //Includes global components array
    public $components = array('Auth', 'Session', 'Redirect');

    //Includes global helper array
    var $helpers = array('Html', 'Form');

    //Changes the view extension name from .ctp to .php
    public $ext = '.php';

    //Sets default pagination for all controllers
    public $paginate = array(
        'limit' => 20,
        'order' => array(
            'id' => 'DESC'
        )
    );

    public function beforeFilter()
    {
        require_once(APP . "vendor/Util.php");
        require_once(APP . "vendor/DateUtility.php");
        require_once(APP . "vendor/ImportUtility.php");
        require_once(APP . "vendor/FileUtility.php");
        require_once(APP . "vendor/AWSFileUtility.php");
        require_once(APP . "vendor/CsvUtility.php");
        
        parent::beforeFilter();
        
        if ( Configure::read("debug") == -1 || HALT_WEB)
        {
            if ($this->params['controller'] != "Dashboards" || $this->params['action'] != "maintence")
            {
                $this->redirect(array("controller" => 'Dashboards', 'action' => 'maintence', 'admin' => false));
            }
        }
        
        $model = $this->modelClass;
        $controller = Inflector::camelize($this->params['controller']);
        $action = $this->params['action'];
        
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->Auth->loginRedirect = array('controller' => 'Compares', 'action' => 'index');

        $this->authUser = $auth_user = $this->Auth->user();
        
        if($this->authUser)
        {
            $this->load_model("User");            
            $this->User->id = $this->authUser["id"];
            $is_active = $this->User->field("is_active");
            
            if (!$is_active)
            {
                $this->Auth->logout();
                $this->Session->setFlash('User is deactivated.', 'flash_failure');
                $this->redirect(array("controller" => 'Users', 'action' => 'login'));
            }
        }

        $this->set(compact('auth_user', 'model', 'controller', 'action'));

        $title_for_layout = Inflector::humanize(str_replace("_", " " ,Inflector::tableize($controller))) . " | " . str_replace("Admin ", "" , Inflector::humanize($action));
        $this->set(compact('title_for_layout'));
            
        if ($this->request->is("ajax"))
        {
            $this->layout = "ajax";
        }

        $this->Auth->allow(array("clearSearchCache"));
        //$this->Auth->allow();
    }
    
    public function afterIndex()
    {
        if ($this->request->is("ajax") && isset($this->params["named"]['is_summary']))
        {
            $this->render("/Elements/" . ucfirst($this->params['controller']) . "/" . $this->params['action']);
        }
    }

    public function beforeRender() 
    {
        parent::beforeRender();
        
        if (!$this->request->is("ajax") && $this->layout != "ajax")
        {
            require_once(APP . "vendor/Menu.php");
            
            $menus = \Menu\Main::get();
            
            $this->set(compact("menus"));
        }
    }
    
    public function afterFilter()
    {
        parent::afterFilter();
    }
    
    /**
     *  Common add record action
     */
    protected function add($redirect = [])
    {
        if ($this->request->is('post'))
        {
            $response = ["status" => 1];
            
            try
            {
                $this->{$this->modelClass}->create();
                
                if ($this->{$this->modelClass}->save($this->request->data))
                {
                    $this->afterSave();
                    
                    $this->Session->setFlash('Record Save Succesfully', 'flash_success');
                    
                    if ( $this->request->is('ajax') )
                    {
                        $this->jsonResonse($response);
                    }
                    else
                    {
                        $this->redirect($redirect);
                    }
                }
                else
                {
                    $response['errors'][$this->modelClass] = $this->{$this->modelClass}->validationErrors;
                    throw new Exception('Unable to add new record');
                }
            }
            catch(Exception $ex)
            {
                $response['status'] = 0;
                $response['msg'] = $ex->getMessage();
                
                if ( $this->request->is('ajax') )
                {
                    $this->jsonResonse($response);
                }
                else
                {
                    $this->Session->setFlash($response['msg'], 'flash_failure');
                }
            }
        }
    }

    /**
     *  Common edit record action
     */
    protected function edit($id, $redirect = [])
    {
        if (!$id)
        {
            throw new NotFoundException(__('Missing id '));
        }

        $record = $this->{$this->modelClass}->findById($id);

        if (!$record)
        {
            throw new NotFoundException(__('Invalid ' . $this->modelClass));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            $response = ["status" => 1];
            
            try
            {
                $this->{$this->modelClass}->id = $id;

                if ($this->{$this->modelClass}->save($this->request->data))
                {
                    $this->afterSave();
                    
                    $this->Session->setFlash('Record has been updated.', 'flash_success');

                    if ( $this->request->is('ajax') )
                    {
                        $this->jsonResonse($response);
                    }
                    else
                    {
                        $this->redirect($redirect);
                    }
                }
                else 
                {
                    $response['errors'][$this->modelClass] = $this->{$this->modelClass}->validationErrors;
                    throw new Exception('Unable to update the record');
                }
            }
            catch(Exception $ex)
            {
                $response['status'] = 0;
                $response['msg'] = $ex->getMessage();
                    
                if ( $this->request->is('ajax') )
                {
                    $this->jsonResonse($response);
                }
                else
                {
                    $this->Session->setFlash($response['msg'], 'flash_failure');
                }
            }
        }

        //Handles GET request
        if (!$this->request->data)
        {
            $this->request->data = $record;
        }
    }
    
    /**
     *  Common edit record action
     */
    protected function copy($id, $redirect = array())
    {
        if (!$id)
        {
            throw new NotFoundException(__('Invalid ' . $this->modelClass));
        }

        $record = $this->{$this->modelClass}->findById($id);

        if (!$record)
        {
            throw new NotFoundException(__('Invalid ' . $this->modelClass));
        }

        if ($this->request->is('post') || $this->request->is('put'))
        {
            $response = ["status" => 1];
            
            try
            {
                $this->{$this->modelClass}->create();

                if ($this->{$this->modelClass}->save($this->request->data))
                {
                    $this->afterSave();
                    
                    $this->Session->setFlash('Record has been updated.', 'flash_success');

                    if ( $this->request->is('ajax') )
                    {
                        $this->jsonResonse($response);
                    }
                    else
                    {
                        $this->redirect($redirect);
                    }
                }
                else 
                {
                    $response['errors'] = $this->{$this->modelClass}->validationErrors;
                    throw new Exception('Unable to update the record');
                }
            }
            catch(Exception $ex)
            {
                $response['status'] = 0;
                $response['msg'] = $ex->getMessage();
                    
                $this->Session->setFlash($response['msg'], 'flash_failure');
                
                if ( $this->request->is('ajax') )
                {
                    $this->jsonResonse($response);
                }
            }
        }

        //Handles GET request
        if (!$this->request->data)
        {
            $this->request->data = $record;
        }
    }
    
    protected function afterSave()
    {
    }

    /*
     * Common action for delete record
     */
    protected function delete($id, $redirect = array())
    {
        if (!$id)
        {
            throw new NotFoundException(__('Invalid ' . $this->modelClass));
        }

        $this->{$this->modelClass}->recursive = -1;
        $record = $this->{$this->modelClass}->find("first", array(
            "fields" => "id",
            "conditions" => array("id" => $id),
            "recursive" => -1,
            "contain" => array()
        ));
        
        if (!$record)
        {
            throw new NotFoundException(__('Invalid ' . $this->modelClass));
        }

        $this->{$this->modelClass}->id = $id;

        try
        {
            if (!$this->{$this->modelClass}->preventDeleteAndInactive($id))
            {
                throw new Exception('The record cannot be deleted, as it has associated data');
            }

            $this->{$this->modelClass}->id = $id;

            $result = false;
            if ($this->{$this->modelClass}->hasField('is_deleted'))
            {
                $result = $this->{$this->modelClass}->softDelete();
            }
            else
            {
                $result = $this->{$this->modelClass}->delete($id);
            }

            if (!$result)
            {
                throw new Exception("Failed to delete");
            }
            
            $this->afterDelete();
            
            if ($this->request->is('ajax'))
            {
                $this->jsonResonse(['status' => 1]);
            }
            else
            {
                $this->Session->setFlash('The record deleted Successfully', 'flash_success');
                $this->redirect($redirect);
            }
        }
        catch(Exception $ex)
        {
            if ($this->request->is('ajax'))
            {
                $this->jsonResonse([ 'status' => 0, 'msg' => $ex->getMessage() ]);
            }
            else
            {
                $this->Session->setFlash($ex->getMessage(), 'flash_failure');
                $this->redirect($redirect);
            }
        }
    }
    
    protected function afterDelete()
    {
        
    }

    protected function getSearchConditions($inputs, $cache = true)
    {
        $conditions = $searchArray = array();
        $search_key = "search-" . $this->params['controller'] . "-" . $this->params['action'] . "-" . $this->authUser['id'];
        $old_params = Cache::read($search_key, "month");        
        $params = $this->params['named'];
        
        //Looping the input data
        foreach ($inputs as $i => $input)
        {
            //Setting value in local variables
            $model = isset($input['model']) ? $input['model'] : "";
            $fields = $input['field'];
            $type = strtolower($input['type']);
            $view_field = $input['view_field'];
            
            if ($cache && !empty($old_params) && empty($this->params['named']) && isset($old_params[$view_field]))
            {
                $params[$view_field] = $old_params[$view_field];
            }
            
            //Checking data type and constructing conditions array
            if (isset($params[$view_field]) && 
                    (
                        (is_array($params[$view_field]) && $params[$view_field])
                        || (!is_array($params[$view_field]) && strlen(trim($params[$view_field])) > 0)
                    )
                )
            {
                
                if (is_array($params[$view_field]))
                {
                    if ($type == 'string' || $type == 'string_exact')
                    {
                        foreach($params[$view_field] as $k => $v)
                        {
                            if (substr($v, 0, 1) != "'")
                            {
                                $params[$view_field][$k] = json_encode($v);
                            }
                        }
                        
                        //change type
                        $type = "int";
                    }
                }
                else
                {
                    $params[$view_field] = trim($params[$view_field]);
                }

                if (is_array($fields))
                {
                    foreach($fields as $field)
                    {
                        $model_field = $field;
                        if ($model)
                        {
                            $model_field = $model . "." . $field;
                        }
                        
                        if ($type == 'string')
                        {
                            $conditions[$i]["OR"][$model_field . " LIKE"] = "%" . $params[$view_field] . "%";
                        }
                        else if ($type == 'string_exact')
                        {
                            $conditions[$model_field] = "'" . $params[$view_field] . "'";
                        }
                        else if ($type == 'integer' || $type == 'int')
                        {
                            $conditions[$i]["OR"][$model_field] = $params[$view_field];
                        }
                        else if ($type == 'from_integer' || $type == 'from_int')
                        {
                            $conditions[$i]["OR"][$model_field . " >="] = $params[$view_field];
                        }
                        else if ($type == 'to_integer' || $type == 'to_int')
                        {
                            $conditions[$i]["OR"][$model_field . " <="] = $params[$view_field];
                        }
                        else if (($type == 'boolean' || $type == 'bool') && $params[$view_field])
                        {
                            $conditions[$i]["OR"][$model_field] = 1;
                        }
                        else if ($type == 'date')
                        {
                            $conditions[$i]["OR"]["$model_field"] = DateUtility::getDate($params[$view_field], DateUtility::DATE_FORMAT);
                        }
                        else if ($type == 'from_date')
                        {
                            $conditions[$i]["OR"]["date($model_field) >="] = DateUtility::getDate($params[$view_field], DateUtility::DATE_FORMAT);
                        }
                        else if ($type == 'to_date')
                        {
                            $conditions[$i]["OR"]["date($model_field) <="] = DateUtility::getDate($params[$view_field], DateUtility::DATE_FORMAT);
                        }
                        else if ($type == 'from_datetime')
                        {
                            $conditions[$i]["OR"]["$model_field >="] = DateUtility::getDate($params[$view_field]);
                        }
                        else if ($type == 'to_datetime')
                        {
                            $conditions[$i]["OR"]["$model_field <="] = DateUtility::getDate($params[$view_field]);
                        }
                        else if ($type == 'find_in_set')
                        {
                            $conditions[$i]["OR"][] = "FIND_IN_SET(" . $params[$view_field] . ", $model_field)";
                        }
                    }
                }
                else
                {
                    $field = $fields;
                    $model_field = $field;
                    if ($model)
                    {
                        $model_field = $model . "." . $field;
                    }
                    
                    if ($type == 'string')
                    {
                        $conditions[$model_field . " LIKE"] = "%" . $params[$view_field] . "%";
                    }
                    else if ($type == 'string_exact')
                    {
                        $conditions[$model_field] = "'" . $params[$view_field] . "'";
                    }
                    else if ($type == 'integer' || $type == 'int')
                    {
                        $conditions[$model_field] = $params[$view_field];
                    }
                    else if ($type == 'from_integer' || $type == 'from_int')
                    {
                        $conditions[$model_field . " >="] = $params[$view_field];
                    }
                    else if ($type == 'to_integer' || $type == 'to_int')
                    {
                        $conditions[$model_field . " <="] = $params[$view_field];
                    }
                    else if (($type == 'boolean' || $type == 'bool') && $params[$view_field])
                    {
                        $conditions[$model_field] = 1;
                    }
                    else if ($type == 'date')
                    {
                        $conditions["$model_field"] = DateUtility::getDate($params[$view_field], DateUtility::DATE_FORMAT);
                    }
                    else if ($type == 'from_date')
                    {
                        $conditions["date($model_field) >="] = DateUtility::getDate($params[$view_field], DateUtility::DATE_FORMAT);
                    }
                    else if ($type == 'to_date')
                    {
                        $conditions["date($model_field) <="] = DateUtility::getDate($params[$view_field], DateUtility::DATE_FORMAT);
                    }
                    else if ($type == 'from_datetime')
                    {
                        $conditions["$model_field >="] = DateUtility::getDate($params[$view_field]);
                    }
                    else if ($type == 'to_datetime')
                    {
                        $conditions["$model_field <="] = DateUtility::getDate($params[$view_field]);
                    }
                    else if ($type == 'find_in_set')
                    {
                        $conditions[] = "FIND_IN_SET(" . $params[$view_field] . ", $model_field)";
                    }
                }

                if (is_array($params[$view_field]))
                {
                    $params[$view_field] = implode(",", $params[$view_field]);
                }
                
                $searchArray[$model . $view_field] = $params[$view_field];
            }
            else
            {
                $searchArray[$model . $view_field] = "";
            }
        }
        
        if ($cache && $params)
        {
            Cache::write($search_key, $params, "month");
        }
        
        $this->set($searchArray);
        $this->set("search", $params);
        
        return $conditions;
    }
    
    public function clearSearchCache($action)
    {
        $actions = explode(",", $action);
        
        foreach($actions as $action)
        {
            $search_key = "search-" . $this->params['controller'] . "-$action-" . $this->authUser['id'];
            Cache::delete($search_key, "month");
        }
        
        $action = $actions[0];
        $is_admin = strpos($action, "admin_") !== false ? true : false;
        $action = str_replace("admin_", "", $action);
        $this->redirect(array("action" => $action, "admin" => $is_admin));
    }
}
