This is simple function which prints different strings depending on the
location of the HTTP request.

It makes use of the MaxMind GeoIP API for PHP.
See [https://github.com/maxmind/geoip-api-php](github.com/maxmind/geoip-api-php)
for more details about the API.

## Installation

The MaxMind API was installed here via Composer, and is included in this package.
If you're already using composer, change the require line in geoip-functions.php
to match the location of your Composer autoload file.

For example, change:

    require_once 'vendor/autoload.php';

To:

    require_once '/path/to/composer/autoload.php';

## Usage
Currently, there is only one function, geoIpCountryCheckPrint().

    geoIpCountryCheckPrint($country, $printIfTrue, $printIfFalse)

The first argument is the iso 3166 country code for the MaxMind GeoIP
database. See [https://dev.maxmind.com/geoip/legacy/codes/iso3166/](//dev.maxmind.com/geoip/legacy/codes/iso3166/)
for the list of codes. This can be a string or an array of strings. For example, the following are valid:

    // Country as a string.
    $country = 'US';
    // Country as a array.
    $country = array('US', 'GB', 'AU');

    geoIpCountryCheckPrint($country, '...', '...');

This function prints the second argument, $printIfTrue, if the country of the
request matched the first argument, $country. If the country does not match,
it prints the third argument, $printIfFalse.

For example, if you wanted to print "Zdravo" to Croatian users, and "Hello" to all
other users, you could use the following:

    geoIpCountryCheckPrint('HR', 'Zdravo', 'Hello');

## Full Example

You can see an example in the example.php file. For convenience, it is reprinted here.

    <?php

    // Include the functions and the MaxMind GeoIP library first.
    require_once 'geoip-functions.php';

    // Example usage, using a string for the country.
    geoIpCountryCheckPrint(
        'US',
        'You are from the US.',
        'You are not from the US.'
    );

    // Example usage, using an array for the country.
    geoIpCountryCheckPrint(
        array('US', 'GB', 'CA', 'AU'),
        'English may likely be your first language.',
        'English may not be your first language.'
    );

    ?>


## Suggestions

This was built for a client, and you're seeing the vanilla version here.

Have any suggestions for features? Bugs? Comments? Create an issue in the issue tracker.

