This is simple function which prints different strings depending on the
location of the HTTP request.

It makes use of the MaxMind GeoIP API for PHP.
See [https://github.com/maxmind/geoip-api-php](github.com/maxmind/geoip-api-php)
for more details about the API.

## Requirements

You must have Composer installed and/or downloaded. Visit [getcomposer.org](http://getcomposer.org) for more info.

PHP 5.3 or newer is also required, primarily due to the reliance on anonymous functions.

## Installation

The MaxMind API was installed here via Composer, and is included in this package.
If you're already using composer, change the require line in example.php
to match the location of your Composer autoload file.

Make sure it's a requirement in your composer.json file.

    {
      "require": {
        "php": ">=5.3.0",
        "geoip/geoip": "*",
        "brgr2/geoipcheck": "*"
      }
    }

Make sure your php includes the file:

    require_once '/path/to/composer/autoload.php';

## Usage

Usage is very simple.

    $geoIp = new GeoIpCheck();

If you want to override the IP address (i.e. not use the IP of the request, simple change the first line to:

    $geoIp = new GeoIpCheck();
    $geoIp->overrideRequestIp('<any valid ipv4 address>');

Then, to run a check, specify both the search type and the value to match.

    $geoIp->check(
        'Name of city/cities/regions/etc.',
        'Type of search',
        function() { /* Callback to execute if the check is true.  */ },
        function() { /* Callback to execute if the check is false. */ }
    );

Valid search types include:

    country_code, country_code3, city, latitude, longitude, area_code, metro_code, region, postal_code, dma_code, continent_code

Valid search values, the first argument, can be a string, a comma separated string,
or an array. All these would be valid.

    $type = 'Boston';
    $type = 'Boston,Cambridge';
    $type = array('Boston', 'Cambridge');

The whole example, if you wanted to check if the current visitor was from Boston or Cambridge:

    $geoIp = new GeoIpCheck();
    $geoIp->check(
        'Boston,Cambridge',
        'city',
        function() { echo "You are not from Boston or Cambridge"; },
        function() { echo "You are from Boston or Cambridge"; }
    );

If the details of the last request are stored in the `GeoIpCheck::$lastResult` variable. So anywhere in your script,
you could access data such as:

    $geoIp->lastRequest->city;
    $geoIp->lastRequest->country_code;
    $geoIp->lastRequest->area_code;
    // And so forth...

## Full Example

You can see an example in the `example/index.php` file. It doesn't have any external requirements other than the code
in this repository, so you can run it using the built-in PHP web-server.

## To Do

Add basic unit testing.

## Suggestions

This was built for a client, and you're seeing the vanilla version here.

Have any suggestions for features? Bugs? Comments? Create an issue in the issue tracker.

