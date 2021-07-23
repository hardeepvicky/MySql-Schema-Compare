<?php
class EmailConfig {

	public $default = array(
		'transport' => 'Mail',
		'from' => 'developer.support@sevenrocks.in',
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
	);

	public $smtp = array(
		'transport' => 'Smtp',
		'from' => array('developer.support@sevenrocks.in' => 'Sevenrock Developer'),
		'host' => 'mail.sevenrocks.in',
		'port' => 25,
		'timeout' => 30,
		'username' => 'developer.support@sevenrocks.in',
		'password' => 'sevenrocks123',
		'client' => null,
		'log' => true,
		'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);
        
        public $gmail = array(
            'host' => 'ssl://smtp.gmail.com',
            'port' => 465,
            'username' => 'hardeepsevenrocks@gmail.com',
            'password' => 'hardeep23121991',
            'transport' => 'Smtp',
            'timeout' => '30'
        );

	public $fast = array(
		'from' => 'you@localhost',
		'sender' => null,
		'to' => null,
		'cc' => null,
		'bcc' => null,
		'replyTo' => null,
		'readReceipt' => null,
		'returnPath' => null,
		'messageId' => true,
		'subject' => null,
		'message' => null,
		'headers' => null,
		'viewRender' => null,
		'template' => false,
		'layout' => false,
		'viewVars' => null,
		'attachments' => null,
		'emailFormat' => null,
		'transport' => 'Smtp',
		'host' => 'localhost',
		'port' => 25,
		'timeout' => 30,
		'username' => 'user',
		'password' => 'secret',
		'client' => null,
		'log' => true,
		//'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);

}
