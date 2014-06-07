These files are accessible by AssetManager module and configured in `modules.config.php`
_app
  config
    modules.config.php


``php
return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                # Assetic for default template files
                APP_DIR_APPLICATION.DS.'themes'.DS.'www',
            ),
        ),
    ),
);
``