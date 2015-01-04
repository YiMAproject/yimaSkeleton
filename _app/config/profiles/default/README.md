Every config file here will merged with moduleLoader merged file
and keep in mind this is last merging process.
note: at first we include modules.override.config.php

- Mapping:
  'modules.override.{,local.}config.php'
  '{,*.}{global,local}.php'
  '{,*.}{,*.}{global.,local.}config.php'
  
  Files with extension *.local.config.php and *.local.php will ignored for commit by .gitignore