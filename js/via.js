var mapVia = L.map("map3", {
  zoom: 17,
  center: [-6.781412,-79.875024],
  zoomControl: false,
  attributionControl: false
});

//adding drawing elements
var geojsonvia = new L.FeatureGroup();
geojsonvia.addTo(mapVia);

var osm3 = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 21
        }).addTo(mapVia);

var imagenUrl3 = 'images/imagen2.jpg';
var limiteImagen3 = [[-6.783495,-79.878558], [-6.779442,-79.871314]];
	
var raster3 = L.imageOverlay(imagenUrl3, limiteImagen3, {
	//opacity:0.8,
	attribution:'Habilitacion Urbana El Trebol'}).addTo(mapVia);
	
//adicionar busqueda
var osmGeocoder3 = new L.Control.OSMGeocoder({
    collapsed: false,
    //position: 'bottomright',
    text: 'Buscar'
    });
mapVia.addControl(osmGeocoder3);

var barraZoom3 = new L.Control.ZoomBar({position: 'topleft'}).addTo(mapVia);


var manzana2 = L.geoJson(null, {
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
}).addTo(mapVia);
$.getJSON("report/manzana.php", function (data) {
  manzana2.addData(data);
});

var via2 = L.geoJson(null, {
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
}).addTo(mapVia);
$.getJSON("report/vias.php", function (data) {
  via2.addData(data);
});

$(document).ready(function(){  
      $('#insert_form_via').on("submit", function(event){
		event.preventDefault();
		if($('#txttipo').val() == "")
		{
			alert("Tipo de via es requerido");
		}
		else if($('#txtnombre').val() == '')
		{
			alert("nombre de via es requerido"); 
		}
		else if($('#txtgeovia').val() == '')
		{
			alert("dibuje una via"); 
		}
		else
		{
			$.ajax({
				url:"report/insertavia.php",
				method:"POST",
				data:$('#insert_form_via').serialize(),
				beforeSend:function(){
					$('#insertvia').val("Registrando");
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
		polyline: true,
		rectangle: false,
		polygon: false,
		circle: false,
		marker: false
		},
		edit: {
			featureGroup: geojsonvia,
			remove: true
			}
		});
		
mapVia.addControl(drawControl);	

//creating a new point event
mapVia.on('draw:created', function (e) {
	var type = e.layerType,
	layer = e.layer;
	geojsonvia.addLayer(layer);
	$('#txtgeovia').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
	});
	
//edit point event
mapVia.on('draw:edited', function (e) {
	var layers = e.layers;
	layers.eachLayer(function (layer) {
		$('#txtgeovia').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
		});
	});

//delete event
mapVia.on('draw:deleted', function () {
	$('#txtgeovia').val('');
	});

