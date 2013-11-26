<?php
/**
 * This script uses the MaxMind GeoIP API for PHP to display or hide content based
 * on location of the request. Built to use GeoIP Country database.
 *
 * Requires database to be installed. If you're using Ubuntu, it's as simple as:
 *      sudo apt-get install geoip-database-contrib
 *
 * @author Brad Berger <brad@bradb.net>
 * @see    https://github.com/bradberger/php-geoip-script/
 * @see    https://github.com/maxmind/geoip-api-php
 */
namespace Brgr2;
class GeoIpCheck {

    var $dbFile;
    var $db;
    var $type = 'country_code';
    var $ifTrue = '';
    var $ifFalse = '';
    var $lastResult;
    var $requestIp;
    var $region_names;
    var $files_to_try = array(
        '/usr/local/share/GeoIP/GeoIP.dat',
        '/usr/local/share/GeoIP/GeoLiteCity.dat',
        '/usr/local/share/GeoIP/GeoIPCity.dat',
        '/usr/share/GeoIP/GeoIP.dat',
        '/usr/share/GeoIP/GeoLiteCity.dat',
        '/usr/share/GeoIP/GeoIPCity.dat',
    );

    function __construct($dbFile='') {

        // Need this.
        global $GEOIP_REGION_NAME;
        if(! empty($GEOIP_REGION_NAME)) {
            $this->region_names = &$GEOIP_REGION_NAME;
        } else {
            trigger_error(sprintf('%s::%s Error: Geoip region info not available. Make sure the
            geo-api-php/src/geoipregionvars.php file is loaded.', __CLASS__, __METHOD__), E_USER_WARNING);
        }

        $this->files_to_try[] = realpath(__DIR__) . PATH_SEPARATOR . 'GeoIP.dat';
        $this->files_to_try[] = realpath(__DIR__) . PATH_SEPARATOR . 'GeoLiteCity.dat';
        $this->files_to_try[] = realpath(__DIR__) . PATH_SEPARATOR . 'GeoIPCity.dat';
        if(! empty($dbFile)) {
            $this->files_to_try[] = $dbFile;
        }

        // Loop through all possible locations of the Mindmax file and stop on first success.
        foreach(array_reverse($this->files_to_try) as $file) {
            if(file_exists($file) && is_readable($file)) {
                $this->dbFile = $file;
                break;
            }
        }

        // Try to load the database.
        if(empty($this->dbFile)) {
            trigger_error(
                sprintf('%s::%s Error: GeoIP.dat database file (%s) does not exist or is not readable. Download it
                from http://dev.maxmind.com/geoip/legacy/geolite/ or check file permissions if it exists.',
                    __CLASS__, __METHOD__, $this->dbFile, E_USER_ERROR)
            );
        } else if(! function_exists('geoip_country_code_by_addr')) {
            trigger_error(sprintf('%s::%s Error: Maxmind Geoip-api-php is not loaded. See https://github
            .com/maxmind/geoip-api-php for downloads and instructions.'), E_USER_ERROR);
        } else {
            $this->db = geoip_open($this->dbFile, GEOIP_STANDARD);
        }

        // Set the request IP initially
        $this->getRequestIp();

    }

    function overrideRequestIp($ip) {
        $this->requestIp = $ip;
    }

    function getRequestIp()
    {
        // Return override if applicable.
        if(!empty($this->requestIp)) {
            return $this->requestIp;
        }

        // Otherwise try to find real request up.
        foreach (array(
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR'
                 ) as $key) {
            if (!empty($_SERVER[$key])) {
                return $this->requestIp = $_SERVER[$key];
            }
        }

        // Fallback
        return $this->requestIp = $_SERVER['SERVER_ADDR'];

    }

    function check($area, $type='', $ifTrue='', $ifFalse='') {

        if(empty($this->db)) {
            return false;
        }
        if(empty($ifTrue) && !empty($this->ifTrue)) {
            $ifTrue = $this->ifTrue;
        }
        if(empty($ifFalse) && !empty($this->ifFalse)) {
            $ifFalse = $this->ifFalse;
        }
        if(empty($type)) {
            $type = $this->type;
        }
        if(empty($area)) {
            trigger_error(sprintf('%s::%s Error: Area can not be empty. Must be a string', __CLASS__, __METHOD__));
        }

        // Get the detailed info.
        $requestDetails = geoip_record_by_addr($this->db, $this->requestIp);

        // Store the last result for fun.
        $this->lastResult = $requestDetails;

        $result = false;
        $area = array_filter(explode(',', $area));
        foreach ($area as &$thisArea) {
            $thisArea = trim($thisArea);
            if (strtoupper($thisArea) === strtoupper($requestDetails->{$type})) {
                $result = true;
                break;
            }
        }

        $callback = $result ? $ifTrue : $ifFalse;
        $callbackType = gettype($callback);
        if($callbackType == 'string') {
            echo $callback;
        } elseif($callbackType == 'object') {
            $callback();
        } else if(! empty($callback)) {
            trigger_error(sprintf('%s::%s Error: Callback is type of %s, must be string or function', __CLASS__,
                    __METHOD__, $callbackType));
        }


    }

    function setType($type) {
        $this->type = $type;
    }

    function setCallback($bool, $action) {
        $actionType = getType($action);
        if($actionType != 'string' || $actionType != 'function') {
            trigger_error(sprintf('%s::%s invalid callback type. Must be string or function, was %s'), __CLASS__,
                __METHOD__, $actionType);
            return;
        }
        if($bool) {
            $this->ifTrue = $action;
        } else {
            $this->ifFalse = $action;
        }
    }
    function __destruct() {
        if(! empty($this->db)) {
            geoip_close($this->db);
        }
    }
}