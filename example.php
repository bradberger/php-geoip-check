<?php

// Include the functions and the MaxMind GeoIP library first.
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Brgr2/GeoIpCheck/GeoIpCheck.class.php';

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('html_errors', true);

$ip    = empty($_REQUEST['ip']) ? '23.34.54.234' : $_REQUEST['ip'];
$type  = empty($_REQUEST['type']) ? 'city' : $_REQUEST['type'];
$match = empty($_REQUEST['match']) ? 'Cambridge' : $_REQUEST['match'];
$geoIp = new \Brgr2\GeoIpCheck();
$geoIp->overrideRequestIp($ip);
?>

<!DOCTYPE html>
<html>
<head>
    <title>GeoIP PHP Demo</title>
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.2/angular.min.js"></script>
</head>
<body style="padding-top: 6em;">
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">PHP GeoIP Demo</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#"><i class="fa fa-github"></i> GitHub</a></li>
        </ul>
        <form class="navbar-form navbar-left" role="search" action="example.php" method="post">
            <div class="form-group">
                <input type="text" name="ip" class="form-control" placeholder="IP Address" value="<?php echo $ip ?>">
            </div>
            <div class="form-group">
                <select name="type" class="form-control">
                    <optgroup label="Search Type">
                        <option value="city">City</option>
                        <option value="region">Region</option>
                        <option value="country_name">Country Name</option>
                        <option value="country_code">Country Code</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="match" class="form-control" placeholder="Equals" value="<?php echo $match ?>">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
    <!-- /.navbar-collapse -->
</nav>
<div class="container">
    <?php
    $geoIp->check(
          $match,
          $type,
          function () {
              global $type, $match;
              echo "
                  <div class='alert alert-success'>
                    <h1 class='text-center'>Congrats, it's a match!</h1>
                  </div>
              ";
          },
          function() {
              global $type, $match;
              echo "
                  <div class='alert alert-danger'>
                      <h1 class='text-center'>Bummer, no match :(</h1>
                  </div>
              ";
          }
    );
    ?>
    <h2>Details</h2>
    <pre><?php print_r($geoIp->lastResult); ?></pre>
</div>
</body>
</html>