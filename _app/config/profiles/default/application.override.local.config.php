<?php
/**
 * Merged with application.global.config.php values
 * at very first application run on index.php
 *
 * Note: this config not merged with global config on equal keys
 *       but fully replaced.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore
 * This is a good practice, as it prevents sensitive
 * credentials from accidentally being comitted into version control.
 */
return array(
    'modules' => array(
        'AssetManager',
        'Application',    // in reason of top note, you must enter full list of modules -
                          //  including Application as well
        'yimaAuthorize',
        'yimaStaticUriHelper',
        'yimaWidgetator',
        'yimaLocali',
        'yimaLocalize',
        'yimaAdminor',
        'yimaSkeletonModule',
        'yimaSettings',
//        'yimaJquery',
        'yimaTheme',
        'typoPages',
        'Analytics',
    ),
);