<?php
class UsersController extends AppController
{
    public function beforeFilter()
    {
        if (!in_array($this->params['action'], array('logout', "forgot_password")))
        {
            parent::beforeFilter();
        }

        $this->Auth->allow('login', 'logout', "forgot_password"); 
    }

    public function index()
    {
        //Converts querystring to named parameter
        $this->Redirect->urlToNamed();

        // Sets Search Parameters
        $conditions = $this->getSearchConditions(array(
            array('model' => $this->modelClass, 'field' => "group_id", 'type' => 'integer', 'view_field' => 'group_id'),            
            array('model' => $this->modelClass, 'field' => array('username', "firstname", "lastname", "email"), 'type' => 'string', 'view_field' => 'username')
        ));
        
        $records = $this->paginate($this->modelClass, $conditions);
        
        $this->_setList();
        
        //setting variables
        $this->set(compact('records'));
        
        $this->afterIndex();
    }

    public function add($redirect = ["action" => "admin_index"])
    {
        parent::add($redirect);
        
        $this->_setList();

        $this->render('form');
    }
    
    public function edit($id, $redirect = ["action" => "admin_index"])
    {
        parent::edit($id, $redirect);
        
        $this->_setList();

        $this->render('form');
    }
    
    public function ajaxToggleStatus($id)
    {
        $response["status"] = 1;
        
        try
        {
            if (!isset($this->request->data["is_active"]))
            {
                throw new Exception("missing is_active field in post");
            }
            
            $is = (int) $this->request->data["is_active"];
            $is = (int) !$is;
            
            if ($id == $this->authUser['id'])
            {
                if ($is)
                {
                    throw new Exception("You can not activate your self");
                }
                else
                {
                    throw new Exception("You can not de-activate your self");
                }
            }
            
            $this->{$this->modelClass}->id = $id;
            $this->{$this->modelClass}->save(["is_active" => $is]);

            $response["is_active"] = $is;
        }
        catch(Exception $ex)
        {
            $response["status"] = 0;
            $response["msg"] = $ex->getMessage();
        }
        
        $this->jsonResonse($response);
    }
    
    public function login()
    {
        $this->layout = 'login';
        
        if ($this->request->is('post'))
        {
            if ($this->Auth->login())
            {
                $this->authUser = AppModel::$authUser = $this->Auth->user();
                
                if(!$this->authUser["is_active"])
                {
                    $this->authUser = array();
                    $this->Auth->logout();
                    $this->Session->setFlash('User is deactivated.', 'flash_failure');
                }
            }
            else
            {
                $this->Session->setFlash('Username or password was incorrect.', 'flash_failure');
            }
        }

        if ($this->authUser)
        {
            $this->redirect(["controller" => "Dashboards"]);
        }
    }
    
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    public function change_password()
    {
        if (!empty($this->request->data))
        {
            $cansave = true;
            $this->User->recursive = -1;
            $user = $this->User->findById($this->authUser["id"], array("fields" => "password"));

            if (!$user)
            {
                $this->User->validationErrors['username'] = 'Username not found';
                $cansave = false;
            }
            else if ($user['User']['password'] != $this->Auth->password($this->request->data['User']['old_password']))
            {
                $this->User->validationErrors['old_password'] = 'Password is incorrect';
                $cansave = false;
            }

            if ($this->request->data['User']['password'] != $this->request->data['User']['confirm_password'])
            {
                $this->User->validationErrors['confirm_password'] = "Password didn't match";
                $cansave = false;
            }

            if ($cansave)
            {
                $this->User->id = $this->authUser["id"];
                if ($this->User->save(array('password' => $this->request->data['User']['password'])))
                {
                    $this->Session->setFlash('Password changed successfully', "flash_success");
                    $this->redirect($this->referer());
                }
                else
                {
                    $this->Session->setFlash("Password could not be changed", "flash_failure");
                }
            }
        }
    }

    public function forgot_password()
    {
        $this->layout = "login";
        
        $from_email = AppModel::initSettingModel()->getValueFromName("admin_email");
        
        if (!$from_email)
        {
            //die("Setting : admin_email is not set yet");
        }
        
        if ($this->request->is('post'))
        {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    "OR" => array(
                        'User.username' => $this->request->data["User"]['username'],
                    )
                ),
                "recursive" => -1
            ));

            if (empty($user))
            {
                $this->Session->setFlash('Invalid Username', 'flash_failure');
            }
            else if (empty($user["User"]["email"]))
            {
                $this->Session->setFlash('User does not have email address', 'flash_failure');
            }
            else
            {
                $status = $this->User->forgotPassword($user['User']['id']);

                if ($status)
                {
                    $this->Session->setFlash('Email has been sent successfully.', 'flash_success');
                }
                else
                {
                    $this->Session->setFlash('Failed to send Email.', 'flash_failure');
                }
            }

            $this->redirect($this->referer());
        }
    }

    private function _setList()
    {
    }
}