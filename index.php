<?php
namespace
{
    (!defined('PHP_VERSION_ID') or PHP_VERSION_ID < 50306 ) and
    exit('Needs at least PHP5.3 but you have ' . phpversion() . '.');

    /**
     * This makes our life easier when dealing with paths. Everything is relative
     * to the application root now.
     */
    chdir(__DIR__);

    /**
     * Application Consistencies and AutoLoad
     *
     */
    require 'index.consist.php';

    // Run the application!
    try {
        $availableProfiles = include APP_DIR_CONFIG .DS. 'profiles' .DS. 'application.profiles.php';
        if (!isset($availableProfiles['profiles']) && !is_array($availableProfiles['profiles']))
            throw new \Exception('No Profiles Defined.');

        // Detect Profile ------------------------------------------------------------------------------------
        if (!defined('APP_PROFILE')) {
            foreach ($availableProfiles['profiles'] as $profile => $val)
            {
                if (isset($val['check']))
                    $checkProfile = $val['check'];

                $executeProfCallable = function ($callable) {
                    switch ($callable) {
                        case ($callable instanceof \Closure):
                            return $callable();
                            break;
                        default: throw new \Exception('Invalid Profile Callable.');
                    }
                };

                if ($executeProfCallable($checkProfile)) {
                    // run profiles callable
                    define('APP_PROFILE', $profile);
                    break;
                }
            }
        }

        if (!defined('APP_PROFILE'))
            throw new \Exception('No Profile Matched.');

        // Startup preparation -------------------------------------------------------------------------------
        $appBootstrap = APP_DIR_CONFIG .DS. 'profiles' .DS. 'bootstrap.php';
        (!file_exists($appBootstrap)) ?: include $appBootstrap;

        // Execute Profile -----------------------------------------------------------------------------------
        $p = APP_PROFILE;
        $profile = $availableProfiles['profiles'][$p];
        if (!isset($profile['exec']))
            while(isset($availableProfiles['aliases'][$p]))
                $p = $availableProfiles['aliases'][$p];

        if (!isset($availableProfiles['profiles'][$p]['exec']))
            throw new \Exception(sprintf(
                'Profile "%s" Exec Attribute Not Executable or not Defined.'
                , APP_PROFILE
            ));

        $exec = $availableProfiles['profiles'][$p]['exec'];
        $exec();
    }
    catch (Exception $er) {
        try
        {
            /*// Set Accrued Exception as MVC Error
            $APP->getMvcEvent()->setError($er);
            $APP->getEventManager()->trigger('error', $APP->getMvcEvent());
            // with default SendExceptionListener
            // Throw accrued exception so we may don't reach this lines below
            // ...
            $APP->run();*/
            throw $er;
        }
        catch(\Exception $e)
        {
            ob_start();
            include 'error'.DS.'general.phtml';
            ob_end_flush();
        }
    }
}
