<?php namespace Brgr2\GeoIpCheck;

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
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootswatch/3.0.2/yeti/bootstrap.min.css">
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
        <a class="navbar-brand" href="#"><i class="fa fa-compass"></i> GeoIP PHP Check</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="active"><a href="<?php echo $_SERVER['PHP_SELF'] ?>"><i class="fa fa-home"></i></a>
            <li>
                <a href="https://github.com/bradberger/php-geoip-check/"><i class="fa fa-github"></i>
                    Project on GitHub
                </a>
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
    <h2>Details</h2>

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
        &copy; 2013 <a href="http://www.bradb.net">Brad Berger</a> and <a href="http://www.brgr2.com">BRGR2</a>.
               Released under the <a href="#" data-toggle="modal" data-target="#licenseModal">MIT license</a>.

                <span class="pull-right">Speical thanks to <a href="//maxmind.com">Maxmind</a>,
                    <a href="//getbootstrap.com">Twitter Bootstrap</a> and <a href="http://fontawesome.io/">Font
                                                                                                            Awesome</a>
                </span>
    </p>
</div>

<div id="licenseModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">The MIT License (MIT)</h4>
            </div>
            <div class="modal-body">

                <p class="lead">Copyright (c) 2013 Brad Berger, BRGR2

                <p>Permission is hereby granted, free of charge, to any person obtaining a copy
                   of this software and associated documentation files (the "Software"), to deal
                   in the Software without restriction, including without limitation the rights
                   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
                   copies of the Software, and to permit persons to whom the Software is
                   furnished to do so, subject to the following conditions:

                <p>The above copyright notice and this permission notice shall be included in
                   all copies or substantial portions of the Software.

                <p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
                   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
                   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
                   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
                   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
                   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
                   THE SOFTWARE.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
</body>
</html>