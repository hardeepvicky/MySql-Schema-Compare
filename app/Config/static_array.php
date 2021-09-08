<?php

class StaticArray
{
    const YES_NO = [1 => "Yes", 0 => "No"];    
}

class GroupType
{
    const ADMIN = 1;
}

class EmailType
{
    const GENERAL = 1;
    
    public static $list = [
        self::GENERAL => "General"
    ]; 
}

class NotificationType
{
    const NOTI_FROM_SYSTEM = 1;
    const NOTI_FROM_USER = 2;
    
    public static $list = [
        self::NOTI_FROM_SYSTEM => "System",
        self::NOTI_FROM_USER => "User",
    ];
}

class Path
{
    const TEMP = "files/temp/";
    const THEME_PLUGIN = "/assets/global/plugins/";
    const SQL_LOG = "files/SqlLog/";
}

class ConnectionType
{
    const LOCAL = 1;
    const REMOTE = 2;
    
    public static $list = [
        self::LOCAL => "Local",
        self::REMOTE => "Remote",
    ];
}