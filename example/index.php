<?php namespace BitolaCo\GeoIpCheck;

// Include the functions and the MaxMind GeoIP library first.

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$ip = empty($_REQUEST['ip']) ? '23.34.54.234' : $_REQUEST['ip'];
$type = empty($_REQUEST['type']) ? 'city' : $_REQUEST['type'];
$match = empty($_REQUEST['match']) ? 'Cambridge' : $_REQUEST['match'];
$geoIp = new GeoIpCheck();
$geoIp->overrideRequestIp($ip);
?>

<!DOCTYPE html>
<html>
<head>
    <title>GeoIP PHP Check Example</title>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/fontawesome/4.2.0/css/font-awesome.min.css ">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/bootswatch/3.3.1.2/yeti/bootstrap.min.css ">
</head>
<body style="padding-top: 6em;">
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><i class="fa fa-map-marker"></i> GeoIP PHP Check</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li>
                <a href="https://github.com/BitolaCo/php-geoip-check">
                    <i class="fa fa-github"></i>
                </a>
            </li>
        </ul>
        <form class="navbar-form navbar-right"
              data-role="search"
              action="<?php echo $_SERVER['PHP_SELF'] ?>"
              method="post">
            <div class="form-group">
                <input type="search" name="ip" class="form-control" placeholder="IP Address" value="<?php echo $ip ?>">
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
                <input type="text"
                       name="match"
                       class="form-control"
                       placeholder="Area Equals/Includes"
                       value="<?php echo $match ?>">
            </div>
            <button type="submit" class="btn btn-info btn-sm">&raquo;</button>
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
                  echo "
                  <div class='alert alert-success'>
                    <h1 class='text-center'>Yes, it's a match!</h1>
                  </div>
              ";
              },
              function () {
                  echo "
                  <div class='alert alert-danger'>
                      <h1 class='text-center'>Sorry, no match.</h1>
                  </div>
              ";
              }
    );
    ?>
    <h2>Details for <?php echo $ip; ?></h2>

    <div class="row">
        <?php
        foreach ($geoIp->lastResult as $k => $v) {
            echo sprintf(
                "<div class='col-md-3'><strong>%s</strong></div><div class='col-md-3'>%s</div>",
                ucfirst(str_replace('_', ' ', $k)),
                $v
            );
        }
        ?>
    </div>
    <?php if (!empty($geoIp->lastResult->latitude) && !empty($geoIp->lastResult->longitude)) { ?>
        <hr>
        <iframe style="height: 360px; width: 100%; border: 0; margin-bottom: 2em;"
                src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=+&amp;q=<?php echo $geoIp->lastResult->latitude ?>,<?php echo $geoIp->lastResult->longitude ?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;ll=42.3626,-71.0843&amp;output=embed"></iframe>
    <?php } ?>
    <hr>
    <p>
        &copy; 2015 <a href="http://www.bradb.net">Brad Berger</a>
        and <a href="https://bitola.co">Bitola Software Co.</a>.
        Released under the <a href="https://www.mozilla.org/MPL/">MPL license</a>.
    </p>
</div>

<script src="//cdn.jsdelivr.net/g/jquery@2,bootstrap@3"></script>
</body>
</html>