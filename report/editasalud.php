<?php
include 'consultasalud.php';
$sa= $salud;
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
    <title>MAPA WEB</title>

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
          <a class="navbar-brand" href="#">MAPA WEB</a>
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
			 <form method="post" id="insert_form_salud">
						<br />
						<label> Codigo</label>
						<input type="text" name="txtcodigo" id="txtcodigo" class="form-control"  readonly />
						<br />
						<label> Departamento</label>
						<input type="text" name="txtdtosalud" id="txtdtosalud" class="form-control"  />
						<br />
						<label> Número de centro de salud</label>
						<input type="text" name="txtnsalud" id="txtnsalud" class="form-control"  placeholder="001"/>
						<br />
						<label> zona</label>
						<input type="text" name="txtestratif" id="txtestratif" class="form-control"  placeholder="rural"/>
						<br />
						<label> Nombre</label>
						<input type="text" name="txtnombresalud" id="ttxtnombresalud" class="form-control"  placeholder="Santa Blanca"/>
						<br />
						<label> Domicilio</label>
						<input type="text" name="txtdomiciliosalud" id="txtdomiciliosalud" class="form-control"  placeholder="SAN MARTIN 1452"/>
						<br />
						<label> Telefono</label>
						<input type="text" name="txttelefonosalud" id="txttelefonosalud" class="form-control"  placeholder="4565874"/>
						<br />
						<label> Coordenadas</label>
						<textarea name="txtgeosalud" id="txtgeosalud" class="form-control" rows="10" readonly ></textarea>
						<br />
						<input type="submit" name="insertsalud" id="insertsalud" value="Actualizar" class="btn btn-success" />
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

  var map = L.map('map').setView([-6.781166,-79.874960], 17);

  var osm = L.tileLayer('http://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
			maxZoom: 21
        }).addTo(map);

	var imagenUrl = '../images/imagen2.jpg';
    var limiteImagen = [[-6.783495,-79.878558], [-6.779442,-79.871314]];
	
	var raster = L.imageOverlay(imagenUrl, limiteImagen, {
		//opacity:0.8,
		attribution:'Habilitacion Urbana El Trebol'}).addTo(map);
		
	//adicionar busqueda
    var osmGeocoder = new L.Control.OSMGeocoder({
            collapsed: true,
            //position: 'bottomright',
            text: 'Search'
        });
    map.addControl(osmGeocoder);
		
		var jsonPHP=JSON.parse('<?php echo $sa;?>');
		//console.log(jsonPHP.toGeoJSON().geometry.coordinates);
		
		var geojson = new L.GeoJSON(jsonPHP, {
			onEachFeature: function(feature, marker) {
				marker.bindPopup('<h4 >'+ feature.properties.descripcion +'</h4>');
			}
		}).addTo(map);
		map.fitBounds(geojson.getBounds());
	
	        //adding drawing elements
        var geojsonnuevositio = new L.FeatureGroup();
        map.addLayer(geojsonnuevositio);

	
	 //configuring what shapes users can draw
        var drawControl = new L.Control.Draw({
			position: 'topright',
			draw: {
				polygon: false,
				polyline: false,
				rectangle: false,
				circle: false,
				marker: true
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
	geojsonnuevositio.addLayer(layer);
	$('#txtgeosalud').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
	});
	
//edit point event
map.on('draw:edited', function (e) {
	var layers = e.layers;
	layers.eachLayer(function (layer) {
		$('#txtgeosalud').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
		});
	});

//delete event
map.on('draw:deleted', function () {
	$('#txtgeosalud').val('');
	});
	

 
 if (jsonPHP){
	 $('#txtcodigo').val(jsonPHP['features'][0]['properties']['id']);
	 $('#txtdtosalud').val(jsonPHP['features'][0]['properties']['n_ctro']);
	 $('#txtnombresalud').val(jsonPHP['features'][0]['properties']['nombre']);
	 $('#txtdomiciliosalud').val(jsonPHP['features'][0]['properties']['domicilio']);
	 $('#txttelefonosalud').val(jsonPHP['features'][0]['properties']['telefono']);
	 $('#txtestratific').val(jsonPHP['features'][0]['properties']['estratific']);
	 $('#txtgeosalud').val(JSON.stringify(jsonPHP['features'][0]['geometry']));
 }
 
$(document).ready(function(){  
      $('#insert_form_salud').on("submit", function(event){
		event.preventDefault();
		if($('#txtdtosalud').val() == "")
		{
			alert("Departamento es requerido");
		}
		else if($('#txtnombresalud').val() == '')
		{
			alert("nombre de centro de salud es requerido"); 
		}
		else if($('#txtgeosalud').val() == '')
		{
			alert("dibuje un centro de slaud"); 
		}
		else
		{
			$.ajax({
				url:"insertasalud.php",
				method:"POST",
				data:$('#insert_form_salud').serialize(),
				beforeSend:function(){
					$('#insertsalud').val("Registrando");
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