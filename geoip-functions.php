<?php
/**
 * This script uses the MaxMind GeoIP API for PHP to display or hide content based
 * on location of the request. Built to use GeoIP Country database.
 *
 * Requires database to be installed.
 *
 * @author Brad Berger <brad@bradb.net>
 * @see    https://github.com/maxmind/geoip-api-php
 */
require_once 'vendor/autoload.php';

// The path to the GeoIP database file. Default is same directory as this script.
define('GEOIP_DATABASE_PATH', realpath(dirname(__FILE__)));

/**
 * Returns the IP address of the request.
 *
 * @return string IP address.
 */
// Function to get the client ip address
function getRequestIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
                return $_SERVER['HTTP_X_FORWARDED'];
            } else {
                if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                    return $_SERVER['HTTP_FORWARDED_FOR'];
                } else {
                    if (!empty($_SERVER['HTTP_FORWARDED'])) {
                        return $_SERVER['HTTP_FORWARDED'];
                    } else {
                        if (!empty($_SERVER['REMOTE_ADDR'])) {
                            return $_SERVER['REMOTE_ADDR'];
                        } else {
                            return $_SERVER['SERVER_ADDR'];
                        }
                    }
                }
            }
        }
    }
}

function geoIpCountryCheckPrint($country, $echoIfTrue='', $echoIfFalse='')
{
    // Ensure database file exists.
    $dbFile = GEOIP_DATABASE_PATH . '/GeoIP.dat';
    if (file_exists($dbFile) && is_readable($dbFile)) {
        $gi      = geoip_open($dbFile, GEOIP_STANDARD);
        $requestCountry = geoip_country_code_by_addr($gi, getRequestIp());
        // Uncomment the following line to test for any set IP.
        // $requestCountry = geoip_country_code_by_addr($gi, '8.8.8.8');
        if(is_string($country)) {
            $country = strtoupper($country);
            echo ($country === strtoupper($requestCountry)) ? $echoIfTrue : $echoIfFalse;
        } elseif(is_array($country)) {
            $found = false;
            foreach($country as $thisCountry) {
                if(strtoupper($thisCountry) === strtoupper($requestCountry)) {
                    $found = true;
                    break;
                }
            }
            echo ($found) ? $echoIfTrue : $echoIfFalse;
        }
        geoip_close($gi);
    } else {
        trigger_error(
            'GeoIP.dat database file does not exist. Download it from http://dev.maxmind.com/geoip/legacy/geolite/'
        );
    }
}

