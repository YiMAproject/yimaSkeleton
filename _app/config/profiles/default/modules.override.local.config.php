<?php
/**
 * @NOTE: This file is ignored from Git by default with the .gitignore
 * This is a good practice, as it prevents sensitive
 * credentials from accidentally being comitted into version control.
 */
return array(
    'sample_conf' => array(
        'credential' => 'value',
    ),
    'db' => array(
    	'dsn'      => 'mysql:dbname=yima;host=localhost',
        'username' => 'root',
        'password' => '',
    ),
);
