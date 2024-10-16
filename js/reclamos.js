// Inicializar el mapa cuando se carga la página o cuando se abre el modal
var mapReclamo = L.map('map5', {
    center: [-33.007593020, -68.6544329100],  // Cambia por las coordenadas iniciales que prefieras
    zoom: 17
});

// Añadir la capa base de OpenStreetMap
L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(mapReclamo);


// Variable para almacenar el marcador
var marker;

// Evento para capturar el clic en el mapa y mostrar las coordenadas
mapReclamo.on('click', function (e) {
    // Si ya existe un marcador, eliminarlo
    if (marker) {
        mapReclamo.removeLayer(marker);
    }

    // Añadir un nuevo marcador en la posición del clic
    marker = L.marker(e.latlng).addTo(mapReclamo);

    // Mostrar las coordenadas en el campo de texto
    $('#txtgeoreclamo').val(e.latlng.lat + ',' + e.latlng.lng);
});

// Configuración para el formulario de reclamos
$(document).ready(function () {
    $('#reclamo_form').on("submit", function (event) {
        event.preventDefault();

        // Validaciones
        if ($('#txttipoReclamo').val() == "") {
            alert("Tipo de reclamo es requerido");
        } else if ($('#txtDetalleReclamo').val() == '') {
            alert("Descripción del reclamo es requerida");
        } else if ($('#txtgeoreclamo').val() == '') {
            alert("Por favor, haga clic en el mapa para marcar la ubicación");
        } else {
            // Crear un objeto FormData para enviar la imagen y los datos del formulario
            var formData = new FormData(this);

            // Envío del formulario a través de AJAX
            $.ajax({
                url: "report/insertarreclamo.php",
                method: "POST",
                data: formData,
                contentType: false, // No establecer ningún tipo de contenido, jQuery lo manejará
                processData: false, // No procesar los datos, dejarlos tal como están
                beforeSend: function () {
                    $('#insertreclamo').val("Registrando");
                },
                success: function (data) {
                    location.reload();
                    window.location.href = "pageuser.php"; // Cambiar a la página deseada después de guardar
                }
            });
        }
    });
});



