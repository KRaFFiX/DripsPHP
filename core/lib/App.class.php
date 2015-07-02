<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.01.15 - 15:41.
 */
namespace DripsPHP;

use DripsPHP\API\Dispatcher;
use DripsPHP\Config\Config;
use DripsPHP\Database\Database;
use DripsPHP\Database\DB;
use DripsPHP\Debug\Bar;
use DripsPHP\Debug\Page;
use DripsPHP\Mail\Mail;
use DripsPHP\Mail\Phpmailer;
use DripsPHP\Routing\Error404;
use DripsPHP\Routing\RequestHandler;
use DripsPHP\Routing\Response;

/**
 * Class App.
 *
 * Main class for the control of the framework. So to say, the command center of
 * DripsPHP.
 */
abstract class App extends Dispatcher
{
    public static $configEnv = 'dev';
    public static $dictionary;
    const VERSION = '0.1';

    /**
     * This is the main function for starting the framework. However, it is only
     * used for the web interface, but not for the CLI.
     *
     * @throws Error404
     */
    public static function run()
    {
        define('DRIPS_START_TIME', microtime(true));
        ob_start();
        self::configuration();
        self::routing();
        $output = ob_get_contents();
        ob_end_clean();
        define('DRIPS_END_TIME', microtime(true));
        define('DRIPS_DURATION', DRIPS_END_TIME - DRIPS_START_TIME);
        // if error occured => error output
        if (Page::mustDebug() && Config::get('debug-on')) {
            Page::create();
        } else {
            // otherwise display output
            echo $output;
            if (Config::get('debug-bar')) {
                if (Response::$type == 'text/html') {
                    Bar::create();
                }
            }
        }
    }

    /**
     * Solves the routing and somti the request handler. Thus, the Framework
     * launch in principle. If no route is found a Err404 event is triggered.
     *
     * @throws Error404
     * @event err404
     */
    private static function routing()
    {
        try {
            RequestHandler::route();
        } catch (Error404 $exception) {
            $packages = self::getPackages();
            if(empty($packages)){
                // TODO: auslagern
                echo "<h1>Drips wurde erfolgreich installiert.</h1>";
                echo "<p>Sie k&ouml;nnen Drips nun konfigurieren.</p>";
                echo "<p>Es sind noch keine Packages vorhanden. Bitte legen Sie ein Package an um weiterfahren zu k&ouml;nnen.</p>";
            }
            self::call('err404');
        }
    }

    /**
     * returns an array of all packages (ignores hidden files starting with an .)
     *
     * @return array
     */
    private static function getPackages()
    {
        $packages = array();
        $directory = "src";
        foreach(scandir($directory) as $file){
            if(substr($file, 0, 1) != "."){
                $packages[] = $file;
            }
        }
        return $packages;
    }

    /**
     * Automatically loads before the script really starts all autoload files.
     * This includes all contained autoload.php files of a package, and all PHP
     * files, which are located in the core/autoload/ directory.
     */
    private static function autoloads()
    {
        $autoloads = array_merge(glob('src/*/autoload.php'), glob('core/autoload/*.php'));
        foreach ($autoloads as $autoloadFile) {
            if (file_exists($autoloadFile)) {
                require_once $autoloadFile;
            }
        }
    }

    /**
     * Performs all necessary configurations, which are necessary.
     */
    public static function configuration()
    {
        Config::$env = self::$configEnv;
        Config::init();
        // set timezone
        date_default_timezone_set(Config::get('date-timezone'));
        self::configDB();
        self::configDebug();
        self::configMail();
        self::autoloads();
    }

    /**
     * Stores were set up automatically if a database connection or not, so that
     * this information can be retrieved later.
     */
    private static function configDebug()
    {
        if(Config::get('db-host') !== null){
            $dbcon = DB::getConnection();
            $_ENV['DB_CONNECTED'] = false;
            $dbcon::on('db-connected', function () {
                $_ENV['DB_CONNECTED'] = true;
            });
        }
    }

    /**
     * This function performs the necessary configuration to be when it is needed
     * to connect to the database.
     */
    private static function configDB()
    {
        if (Config::get('db-host') !== null) {
            $db = new Database(Config::get('db-type'));
            $db->setHost(Config::get('db-host'));
            $db->setUser(Config::get('db-user'));
            $db->setPassword(Config::get('db-password'));
            $db->setDatabase(Config::get('db-database'));
            $db->setPort(Config::get('db-port'));
            DB::connect($db);
        }
    }

    /**
     * This method performs the necessary configuration for phpmailer to send
     * emails.
     */
    private static function configMail()
    {
        $mailer = new Phpmailer();
        if (Config::get('mail-smtp') == true) {
            $mailer->isSMTP();
            $mailer->Host = Config::get('mail-smtp-host');
            $mailer->SMTPAuth = Config::get('mail-smtp-auth');
            $mailer->Username = Config::get('mail-smtp-user');
            $mailer->Password = Config::get('mail-smtp-password');
            $mailer->SMTPSecure = Config::get('mail-smtp-secure');
            $mailer->Port = Config::get('mail-smtp-port');
        }
        Mail::connect($mailer);
    }

    /**
     * This method serves as a Shutdown Hook. About the triggered event, for
     * example, plugins can hook also before exiting the script.
     *
     * @event shutdown
     */
    public static function shutdown()
    {
        self::call('shutdown');
        exit();
    }
}
