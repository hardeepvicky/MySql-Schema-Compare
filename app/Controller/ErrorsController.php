<?php
class ErrorsController extends AppController 
{
    public $name = 'Errors';
    
    public function beforeFilter() {   
        parent::beforeFilter();        
        $this->Auth->allow();
    }

    public function error404() {
		
        $this->layout = 'error';
        $back = "/";
        $this->set(compact('back'));
    }
        
    public function methodNotAllowed($is_ajax, $controller, $action)
    {
        if ($is_ajax)
        {
            $this->layout = false;
        }
        else
        {
            $this->layout = 'error';
        }
        
        
        $this->set(compact("is_ajax", "controller", "action"));
        
        $this->render("method_not_allowed");
    }
        
    public function error($msg)
    {
        $this->set(compact("msg"));
    }
    
    public function admin_error404() {
		
        $this->error404();
    }
        
    public function admin_methodNotAllowed($is_ajax, $controller, $action)
    {
        $this->methodNotAllowed($is_ajax, $controller, $action);
    }
        
    public function admin_error($msg)
    {
        $this->set(compact("msg"));
    }
}