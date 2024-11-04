function mostrarModal(mensaje, fecha, imagen) {
    document.getElementById('mensajeModal').innerText = mensaje;
    document.getElementById('fechaModal').innerText = fecha;

    const imagenElemento = document.getElementById('imagenModal');
    if (imagen) {
        imagenElemento.src = imagen;
        imagenElemento.style.display = 'block';
    } else {
        imagenElemento.style.display = 'none';
    }

    document.getElementById('miModal').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('miModal').style.display = 'none';
}
