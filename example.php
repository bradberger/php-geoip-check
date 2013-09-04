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

