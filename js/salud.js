var mapSalud = L.map("map5", {
    zoom: 17,
    center: [-33.007593020, -68.6544329100],
     zoomControl: false,
    attributionControl: false
  });
  
  //adding drawing elements
  var geojsalud = new L.FeatureGroup();
  geojsonsalud.addTo(mapSalud);
  
  var osm4= L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 21
          }).addTo(mapSalud);
  
 
      
  //adicionar busqueda
  var osmGeocoder4 = new L.Control.OSMGeocoder({
      collapsed: false,
      //position: 'bottomright',
      text: 'Buscar'
      });
  mapSalud.addControl(osmGeocoder4);
  
  var barraZoom4 = new L.Control.ZoomBar({position: 'topleft'}).addTo(mapSalud);
  
  
  var manzana4 = L.geoJson(null, {
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
    manzana4.addData(data);
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
  }).addTo(mapSalud);
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
  }).addTo(mapSalud);
  $.getJSON("report/salud.php", function (data) {
    salud.addData(data);
  });
  
  
  $(document).ready(function() {
    $('#insert_form_salud').on("submit", function(event) {
        event.preventDefault();
        if ($('#txtdto').val() == "") {
            alert("DTO es requerido");
        } else if ($('#txtn_ctro').val() == '') {
            alert("N° Centro es requerido");
        } else if ($('#txtestratif').val() == '') {
            alert("Estratificación es requerida");
        } else if ($('#txtnombre').val() == '') {
            alert("Nombre es requerido");
        } else if ($('#txtdomicilio').val() == '') {
            alert("Domicilio es requerido");
        } else if ($('#txttelefono').val() == '') {
            alert("Teléfono es requerido");
        } else if ($('#txtgeosalud').val() == '') {
            alert("Dibuje un centro de salud en el mapa");
        } else {
            $.ajax({
                url: "report/insertarsalud.php",
                method: "POST",
                data: $('#insert_form_salud').serialize(),
                beforeSend: function() {
                    $('#insertsalud').val("Registrando");
                },
                success: function(data) {
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
        featureGroup: geojsonSalud,
        remove: true
    }
});

mapSalud.addControl(drawControl);

mapSalud.on('draw:created', function(e) {
    var type = e.layerType,
        layer = e.layer;
    geojsonSalud.addLayer(layer);
    $('#txtgeosalud').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
});

mapSalud.on('draw:edited', function(e) {
    var layers = e.layers;
    layers.eachLayer(function(layer) {
        $('#txtgeosalud').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
    });
});

mapSalud.on('draw:deleted', function() {
    $('#txtgeosalud').val('');
});