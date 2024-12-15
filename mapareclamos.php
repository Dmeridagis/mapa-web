<?php
include 'report/seguridad.php';


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MAPA MUNI</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/leaflet.css">
    <link rel="stylesheet" href="css/MarkerCluster.css">
    <link rel="stylesheet" href="css/MarkerCluster.Default.css">
    <link rel="stylesheet" href="css/L.Control.Locate.css">

	<link href="css/leaflet.draw.css" rel="stylesheet" type="text/css"/>	
	<link rel="stylesheet" href="osm/Control.OSMGeocoder.css"/>
	<link rel="stylesheet" type="text/css" href="css/L.Control.ZoomBar.css"/>
	
    <link rel="stylesheet" href="assets/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.css">
    <link rel="stylesheet" href="assets/css/app.css">

  
	
	
	    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/typeahead.bundle.min.js"></script>
    <script src="js/handlebars.min.js"></script>
    <script src="js/list.min.js"></script>
    <script src="js/leaflet.js"></script>
    <script src="js/leaflet.markercluster-src.js"></script>
    <script src="js/L.Control.Locate.min.js"></script>

	<script type="text/javascript" src="js/L.Control.ZoomBar.js"></script>
	<script src="js/leaflet.draw.js" type="text/javascript"></script>
	<script src="osm/Control.OSMGeocoder.js"></script>
    <script src="assets/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.js"></script>
  </head>

  <body>
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">MAPA MUNI</a>
      </div>
      <div class="navbar-collapse collapse">
        <form class="navbar-form navbar-right" role="search">
          <div class="form-group has-feedback">
              <input id="searchbox" type="text" placeholder="Search" class="form-control">
              <span id="searchicon" class="fa fa-search form-control-feedback"></span>
          </div>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="report/cerrar.php"><i class="fa fa-sign-out"></i>&nbsp;&nbsp;Cerrar sesión</a></li>
        </ul>
      </div><!--/.navbar-collapse -->
    </div>
  </div>

  <div id="container">     
    <div id="map"></div>
  </div>
  <div id="loading">
    <div class="loading-indicator">
      <div class="progress progress-striped active">
        <div class="progress-bar progress-bar-info progress-bar-full"></div>
      </div>
    </div>
  </div>
</body>

    <script src="assets/js/app2.js"></script>
	<script src="js/mapa.js"></script>
	<script src="js/via.js"></script>
  <script src="js/salud.js"></script>
	<script src="js/sitio.js"></script>
  <script src="js/policial.js"></script>
	

  </body>
</html>
