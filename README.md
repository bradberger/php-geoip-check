This is simple function which prints different strings depending on the
location of the HTTP request.

It makes use of the MaxMind GeoIP API for PHP.
See [https://github.com/maxmind/geoip-api-php](github.com/maxmind/geoip-api-php)
for more details about the API.

## Installation

The MaxMind API was installed here via Composer, and is included in this package.
If you're already using composer, change the require line in example.php
to match the location of your Composer autoload file.

For example, change:

    require_once 'vendor/autoload.php';

To:

    require_once '/path/to/composer/autoload.php';

## Usage

Coming soon. In the meantime, see the example.php file. It should be pretty straight forward.

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

