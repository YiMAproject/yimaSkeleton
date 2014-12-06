<?php
/**
 * Merged with application.config.php values
 * at very first application run on index.php
 *
 * Note: this config not merged with global config on equal keys
 *       but fully replaced.
 */
return array(
    'application_config' => [
        'modules' => array(
            'yimaAuthorize',
            'yimaStaticUriHelper',
            'yimaTheme',
            'innClinic',
        ),
    ],
);
