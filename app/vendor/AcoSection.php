<?php
class SectionAco
{
    private $sections = [];
    private static function _getURL($controller, $action)
    {
        return "controllers/$controller/$action";
    }
    
    private static function _getUrlArray($controller, array $actions)
    {
        $ret = [];
        
        foreach($actions as $name => $action)
        {
            $ret[$name] = self::_getURL($controller, $action);
        }
        
        return $ret;
    }
    
    private static function _getCommonUrlArray($controller, array $excepts = [], $admin = true)
    {
        if ($admin)
        {
            $comman_actions = [
                'Summary' => 'admin_index',
                'Add' => 'admin_add',
                'Edit' => 'admin_edit',
                'Delete' => 'admin_delete',
            ];
        }
        else
        {
            $comman_actions = [
                'Summary' => 'index',
                'Add' => 'add',
                'Edit' => 'edit',
                'Delete' => 'delete',
            ];
        }
        
        foreach($excepts as $except)
        {
            $key = array_search($except, $comman_actions);
            if ($key)
            {
                unset($comman_actions[$key]);
            }
        }
        
        return self::_getUrlArray($controller, $comman_actions);
    }
    
    
    public function get()
    {
        $this->sections = [];
        
        $this->Email();
        
        $this->User();
        
        $this->_extra();
        
        return $this->sections;
    }
    
    private function Email()
    {
        $title = "Email Placeholder"; $controller = "EmailPlaceholders";
        $this->sections[$title] = self::_getCommonUrlArray($controller);
    
        $title = "Email Templates"; $controller = "EmailTemplates";
        $this->sections[$title] = self::_getCommonUrlArray($controller);
    }
    
    private function User()
    {
        $title = "User"; $controller = "Users";
        
        $this->sections[$title]['Summary'] = self::_getURL($controller, "admin_index");
        $this->sections[$title]['Add'] = self::_getURL($controller, "admin_add");
        $this->sections[$title]['Edit'] = self::_getURL($controller, "admin_edit");
        $this->sections[$title]['Toggle Active'] = self::_getURL($controller, "ajaxToggleStatus");
    }
    
    private function _extra()
    {
        $this->sections['City'] = self::_getCommonUrlArray("Cities");
        $this->sections['Pincodes'] = self::_getCommonUrlArray("Pincodes");
        $this->sections['State']['Summary'] = self::_getURL("States", "admin_index");
        
        $title = "Log"; $controller = "Logs";
        $this->sections[$title]["Cron Job"] = self::_getURL($controller, "admin_crons");
        $this->sections[$title]["Email"] = self::_getURL($controller, "admin_email_logs");
        $this->sections[$title]["Import"] = self::_getURL($controller, "admin_import_logs");
        $this->sections[$title]["SQL"] = self::_getURL($controller, "admin_sql_logs");
        $this->sections[$title]["Web Api"] = self::_getURL($controller, "admin_web_api");
        $this->sections[$title]["Web Request"] = self::_getURL($controller, "admin_web_requests");
    }
}