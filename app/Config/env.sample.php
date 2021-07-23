<?php
Configure::write('debug', 2);
Configure::write('ip_allow', []);

define('HALT_WEB', FALSE);
define('HALT_WEB_API', FALSE);
define('HALT_CRON_JOB', FALSE);
define("ENV_S3", false);
