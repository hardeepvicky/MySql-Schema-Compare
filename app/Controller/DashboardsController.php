<?php
class DashboardsController extends AppController
{
    public function index() 
    {
        
    }
    
    public function maintence()
    {
        if ( Configure::read("debug") != -1)
        {
            $this->redirect("/");
        }
            
        $this->layout = "print";
        $this->render("/Elements/maintence");
    }
}