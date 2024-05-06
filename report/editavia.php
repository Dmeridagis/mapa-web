<?php
include 'consultavia.php';
$calle= $vias;
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
    <title>MANFLOC GIS</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/leaflet.css">
    <link rel="stylesheet" href="../css/MarkerCluster.css">
    <link rel="stylesheet" href="../css/MarkerCluster.Default.css">
    <link rel="stylesheet" href="../css/L.Control.Locate.css">

	<link href="../css/leaflet.draw.css" rel="stylesheet" type="text/css"/>	
	<link rel="stylesheet" href="../osm/Control.OSMGeocoder.css"/>
	<link rel="stylesheet" type="text/css" href="../css/L.Control.ZoomBar.css"/>
	
    <link rel="stylesheet" href="../assets/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.css">
    <link rel="stylesheet" href="../assets/css/app.css">

    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/favicon-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../assets/img/favicon-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../assets/img/favicon-152.png">
    <link rel="icon" sizes="196x196" href="../assets/img/favicon-196.png">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <div class="navbar-icon-container">
            <a href="#" class="navbar-icon pull-right visible-xs" id="nav-btn"><i class="fa fa-bars fa-lg white"></i></a>
            <a href="#" class="navbar-icon pull-right visible-xs" id="sidebar-toggle-btn"><i class="fa fa-search fa-lg white"></i></a>
          </div>
          <a class="navbar-brand" href="#">MANFLOC GIS</a>
        </div>
        <div class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="search">
            <div class="form-group has-feedback">
                <input id="searchbox" type="text" placeholder="Search" class="form-control">
                <span id="searchicon" class="fa fa-search form-control-feedback"></span>
            </div>
          </form>
          <ul class="nav navbar-nav">
            <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="about-btn"><i class="fa fa-question-circle white"></i>&nbsp;&nbsp;About</a></li>
            <li class="dropdown">
              <a id="toolsDrop" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe white"></i>&nbsp;&nbsp;Herramientas <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="full-extent-btn"><i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;Zoom extension total</a></li>
                <li class="divider hidden-xs"></li>				
                <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="nuevo-btn" onclick="document.getElementById('nuevoModal').style.display='block'; setTimeout(mapi.invalidateSize(), 1000);"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Nueva manzana</a></li>
              </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" id="downloadDrop" href="#" role="button" data-toggle="dropdown"><i class="fa fa-cloud-download white"></i>&nbsp;&nbsp;Descargar <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="data/manzana.php" download="manzana.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Manzanas</a></li>
                  <li><a href="data/subways.geojson" download="subways.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Subway Lines</a></li>
                  <li><a href="data/DOITT_THEATER_01_13SEPT2010.geojson" download="theaters.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Theaters</a></li>
                  <li><a href="data/DOITT_MUSEUM_01_13SEPT2010.geojson" download="museums.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Museums</a></li>
                </ul>
            </li>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <div id="container">
		<div class="row">
			<div class="col-md-3" style="padding-left: 30px;">
			 <form method="post" id="insert_form_via">
						<br />
						<label> Codigo</label>
						<input type="text" name="txtcodigo" id="txtcodigo" class="form-control"  readonly />
						<br />
						<label> Tipo</label>
						<input type="text" name="txttipo" id="txttipo" class="form-control" />
						<br />
						<label> Nombre</label>
						<input type="text" name="txtnombre" id="txtnombre" class="form-control"  placeholder="001"/>
						<br />
						<label> Coordenadas</label>
						<textarea name="txtgeovia" id="txtgeovia" class="form-control" rows="10" readonly ></textarea>
						<br />
						<input type="submit" name="insertvia" id="insertvia" value="Actualizar" class="btn btn-success" />
					</form>
			</div>
			<div class="col-md-9">
			  <div id="map" style="height: 600px"></div>
			</div>
			
       </div>
    </div>

    <script src="../js/jquery-2.1.4.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/typeahead.bundle.min.js"></script>
    <script src="../js/handlebars.min.js"></script>
    <script src="../js/list.min.js"></script>
    <script src="../js/leaflet.js"></script>

	<script src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/L.Control.ZoomBar.js"></script>
	<script src="../js/leaflet.draw.js" type="text/javascript"></script>
	<script src="../osm/Control.OSMGeocoder.js"></script>
	
<script>

  var map = L.map('map').setView([-33.00759, -68.654432], 17);

  var osm = L.tileLayer('http://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
			maxZoom: 21
        }).addTo(map);

	

	//adicionar busqueda
    var osmGeocoder = new L.Control.OSMGeocoder({
            collapsed: true,
            //position: 'bottomright',
            text: 'Search'
        });
    map.addControl(osmGeocoder);
		
		var jsonPHP=JSON.parse('<?php echo $calle;?>');
		//console.log(jsonPHP.toGeoJSON().geometry.coordinates);
		
		var geojson = new L.GeoJSON(jsonPHP, {
			onEachFeature: function(feature, marker) {
				marker.bindPopup('<h4 >'+ feature.properties.via +'</h4>');
			}
		}).addTo(map);
		map.fitBounds(geojson.getBounds());
	
	        //adding drawing elements
        var geojsonnuevavia = new L.FeatureGroup();
        map.addLayer(geojsonnuevavia);

	
	 //configuring what shapes users can draw
        var drawControl = new L.Control.Draw({
			position: 'topright',
			draw: {
				polygon: false,
				polyline: true,
				rectangle: false,
				circle: false,
				marker: false
			},
			edit: {
				featureGroup: geojson,
				remove: true
			}
		});
        map.addControl(drawControl);
		
 
//creating a new point event
map.on('draw:created', function (e) {
	var type = e.layerType,
	layer = e.layer;
	geojsonnuevavia.addLayer(layer);
	$('#txtgeovia').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
	});
	
//edit point event
map.on('draw:edited', function (e) {
	var layers = e.layers;
	layers.eachLayer(function (layer) {
		$('#txtgeovia').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
		});
	});

//delete event
map.on('draw:deleted', function () {
	$('#txtgeovia').val('');
	});
	

 
 if (jsonPHP){
	 $('#txtcodigo').val(jsonPHP['features'][0]['properties']['id']);
	 $('#txttipo').val(jsonPHP['features'][0]['properties']['tipo']);
	 $('#txtnombre').val(jsonPHP['features'][0]['properties']['nombre']);
	 $('#txtgeovia').val(JSON.stringify(jsonPHP['features'][0]['geometry']));
 }
 
$(document).ready(function(){  
      $('#insert_form_via').on("submit", function(event){
		event.preventDefault();
		if($('#txttipo').val() == "")
		{
			alert("tipo de via es requerido");
		}
		else if($('#txtnombre').val() == '')
		{
			alert("nombre es requerido"); 
		}
		else if($('#txtgeovia').val() == '')
		{
			alert("Dibuje una via"); 
		}
		else
		{
			$.ajax({
				url:"../report/insertavia.php",
				method:"POST",
				data:$('#insert_form_via').serialize(),
				beforeSend:function(){
					$('#insertvia').val("Registrando");
					},
				success:function(data){
					location.reload();
					window.location.href = "../mapa.php";
					}
				});
		}
	});

 }); 
 
	</script>
</body>
</html>