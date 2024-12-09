var mapSitio = L.map("map4", {
	zoom: 17,
	center: [-33.007593020, -68.6544329100],
	 zoomControl: false,
	attributionControl: false
  });
  
  //adding drawing elements
  var geojsonsitio = new L.FeatureGroup();
  geojsonsitio.addTo(mapSitio);
  
  var osm4= L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			  maxZoom: 21
		  }).addTo(mapSitio);
  
 
	  
 
	  
  //adicionar busqueda
  var osmGeocoder4 = new L.Control.OSMGeocoder({
	  collapsed: false,
	  //position: 'bottomright',
	  text: 'Buscar'
	  });
  mapSitio.addControl(osmGeocoder4);
  
  var barraZoom4 = new L.Control.ZoomBar({position: 'topleft'}).addTo(mapSitio);
  
  
  var manzana3 = L.geoJson(null, {
	style: function (feature) {
	  return {
		color: "green",
		fill: true,
		opacity: 0.4,
		clickable: true
	  };
	},
	onEachFeature: function (feature, layer) {
	  layer.bindPopup("Manzana: "+feature.properties.cod_mzna);
	}	
  }).addTo(mapSitio);
  $.getJSON("report/manzana.php", function (data) {
	manzana3.addData(data);
  });
  
  var via3 = L.geoJson(null, {
	style: function (feature) {
	  return {
		color: "red",
		fill: true,
		opacity: 0.4,
		clickable: true
	  };
	},
	onEachFeature: function (feature, layer) {
	  layer.bindPopup("Via: "+feature.properties.via);
	}	
  }).addTo(mapSitio);
  $.getJSON("report/vias.php", function (data) {
	via3.addData(data);
  });
  
  var sitio = L.geoJson(null, {
	style: function (feature) {
	  return {
		color: "red",
		fill: true,
		opacity: 0.4,
		clickable: true
	  };
	},
	onEachFeature: function (feature, layer) {
	  layer.bindPopup("Descripcion: "+feature.properties.descripcion);
	}	
  }).addTo(mapSitio);
  $.getJSON("report/sitios.php", function (data) {
	sitio.addData(data);
  });
  
  $(document).ready(function(){  
		$('#insert_form_sitio').on("submit", function(event){
		  event.preventDefault();
		  if($('#txttipositio').val() == "")
		  {
			  alert("Tipo de sitio de interes es requerido");
		  }
		  else if($('#txtnombresitio').val() == '')
		  {
			  alert("nombre de sitio de interes es requerido"); 
		  }
		  else if($('#txtgeositio').val() == '')
		  {
			  alert("dibuje un sitio de interes"); 
		  }
		  else
		  {
			  $.ajax({
				  url:"report/insertasitio.php",
				  method:"POST",
				  data:$('#insert_form_sitio').serialize(),
				  beforeSend:function(){
					  $('#insertsitio').val("Registrando");
					  },
				  success:function(data){
					  location.reload();
					  window.location.href = "mapa.php";
					  }
				  });
		  }
	  });
   });  
  
  //configuring what shapes users can draw
  var drawControl = new L.Control.Draw({
	  position: 'topright',
	  draw: {
		  polyline: false,
		  rectangle: false,
		  polygon: false,
		  circle: false,
		  marker: true
		  },
		  edit: {
			  featureGroup: geojsonsitio,
			  remove: true
			  }
		  });
		  
  mapSitio.addControl(drawControl);	
  
  //creating a new point event
  mapSitio.on('draw:created', function (e) {
	  var type = e.layerType,
	  layer = e.layer;
	  geojsonsitio.addLayer(layer);
	  $('#txtgeositio').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
	  });
	  
  //edit point event
  mapSitio.on('draw:edited', function (e) {
	  var layers = e.layers;
	  layers.eachLayer(function (layer) {
		  $('#txtgeositio').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
		  });
	  });
  
  //delete event
  mapSitio.on('draw:deleted', function () {
	  $('#txtgeositio').val('');
	  });
  
  

