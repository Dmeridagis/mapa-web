var mapi = L.map("map2", {
  zoom: 14,
  center: [-33.00759, -68.654432],
  zoomControl: false,
  attributionControl: false
});

//adding drawing elements
var geojsonnew = new L.FeatureGroup();
geojsonnew.addTo(mapi);

var osm2 = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 21
        }).addTo(mapi);

var imagenUrl2 = 'images/imagen2.jpg';
var limiteImagen2 = [[-6.783495,-79.878558], [-6.779442,-79.871314]];
	
var raster2 = L.imageOverlay(imagenUrl2, limiteImagen2, {
	//opacity:0.8,
	attribution:'Habilitacion Urbana El Trebol'}).addTo(mapi);
	
//adicionar busqueda
var osmGeocoder2 = new L.Control.OSMGeocoder({
    collapsed: false,
    //position: 'bottomright',
    text: 'Buscar'
    });
mapi.addControl(osmGeocoder2);

var barraZoom2 = new L.Control.ZoomBar({position: 'topleft'}).addTo(mapi);


var manzana = L.geoJson(null, {
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
}).addTo(mapi);
$.getJSON("report/manzana.php", function (data) {
  manzana.addData(data);
});

$(document).ready(function(){  
      $('#insert_form').on("submit", function(event){
		event.preventDefault();
		if($('#txtidsector').val() == "")
		{
			alert("sector es requerido");
		}
		else if($('#txtcodmanzana').val() == '')
		{
			alert("manzana es requerido"); 
		}
		else if($('#txtgeo').val() == '')
		{
			alert("dibuje un poligono"); 
		}
		else
		{
			$.ajax({
				url:"report/insertamanzana.php",
				method:"POST",
				data:$('#insert_form').serialize(),
				beforeSend:function(){
					$('#insert').val("Registrando");
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
		polygon: {
			allowIntersection: false,
			showArea: true,
			drawError: {
				color: '#b00b00',
				timeout: 1000
				},
				shapeOptions: {
					color: 'red'
					}
				},
		circle: false,
		marker: false
		},
		edit: {
			featureGroup: geojsonnew,
			remove: true
			}
		});
		
mapi.addControl(drawControl);	

//creating a new point event
mapi.on('draw:created', function (e) {
	var type = e.layerType,
	layer = e.layer;
	geojsonnew.addLayer(layer);
	$('#txtgeo').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
	});
	
//edit point event
mapi.on('draw:edited', function (e) {
	var layers = e.layers;
	layers.eachLayer(function (layer) {
		$('#txtgeo').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
		});
	});

//delete event
mapi.on('draw:deleted', function () {
	$('#txtgeo').val('');
	});

