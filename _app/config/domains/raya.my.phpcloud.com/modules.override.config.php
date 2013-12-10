<?php
$dsn = sprintf('mysql:dbname=%s;host=%s',
	get_cfg_var('zend_developer_cloud.db.name'),
	get_cfg_var('zend_developer_cloud.db.host')
);

return array(
    'db' => array(
    	'dsn'      => $dsn,
        'username' => get_cfg_var('zend_developer_cloud.db.username'),
        'password' => get_cfg_var('zend_developer_cloud.db.password')
    ),
);
