<?php
/**
 * Use to known profiles by application an determine canonical names
 * when you are on localhost or any other profile name, application
 * use this names to load related profile config and run application
 * with this config.
 */
return array(
    // 'profile_name' => \Closure() | callable | classObject or autoLoadName instanceof yimaBase\Profile\Detection
    'console' => function () { return (PHP_SAPI == 'cli'); },
    'default' => function () { return true; },
);
