<?php
namespace Raya\Appwork\Config;

/**
 * Ba'zi mavaaghe be serviceLocator dastresi nadaarim vali dar class haa mikhaahim baa 
 * tavajoh be config barnaame amaliaati anjaam dahim, exp. TablePrefixFeature ke prefix 
 * e table haaye database raa az file e config mikhaahim va dastresi be serviceLocator 
 * gheire manteghi be nazar miresad.
 * 
 */
/*
 * In tor tasavor mikonim ke barnaame baa hamin config va tavasote index.php rah andaazi shode ast
*
*/
class Config
{
	protected static $conf;
	
	public static function getAppConfFromFile()
	{
		if (isset(self::$conf)) {
			return self::$conf;
		}
		
		if (!defined('APP_DIR_CONFIG') ||  !defined('APP_HOST')) {
			throw new \Exception('Default Application Consts not defined.');	
		}
		
		$defaultConf = include  APP_DIR_CONFIG .DS. 'application.global.config.php';
		$hostConFile = APP_DIR_CONFIG .DS. 'domains' .DS. APP_HOST .DS. 'application.override.config.php';
		if (file_exists($hostConFile)) {
			$hostConf = include $hostConFile;
			$defaultConf = array_merge($defaultConf, $hostConf);
		}
		
		return self::$conf = $defaultConf;
	}
}
