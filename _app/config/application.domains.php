<?php
/**
 * Use to known domains by application an determine canonical names
 * when you are on localhost or any other domain name, application
 * use this names to load related domain config and run application
 * with this config.
 */
return array(
	'domains' => array(
		'localhost',
		//'other-host-example.com',

		'canonical_domains'=> array (
            // canonical domain names, means 127.0.0.1 is equal to localhost
			'127.0.0.1'      => 'localhost',
            'raya-media.com' => 'localhost',
		),
	),
);
