document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const tbody = document.getElementById('complaints-tbody');

    // Función para cargar quejas desde el backend
    function loadComplaints(searchQuery = '') {
        fetch('lista.php')
            .then(response => response.json())
            .then(data => {
                tbody.innerHTML = ''; // Limpiar el contenido existente

                // Filtrar los datos si hay una búsqueda activa
                const filteredData = data.filter(row => {
                    const rowData = `${row.id_vecino} ${row.tipo} ${row.estado}`.toLowerCase();
                    return rowData.includes(searchQuery.toLowerCase());
                });

                // Agregar las filas filtradas a la tabla
                filteredData.forEach(row => {
                    const tr = document.createElement('tr');
                    
                    tr.innerHTML = `
                        <td>${row.id_vecino}</td>
                        <td>${row.tipo}</td>
                        <td>${row.mensaje}</td>
                        <td>
                            ${row.imagen ? `<img src="data:image/jpeg;base64,${row.imagen}" alt="Imagen queja" class="complaint-img">` : 'Sin imagen'}
                        </td>
                        <td>${row.fecha}</td>
                        <td>${row.distrito}</td>
                        <td>${row.estado}</td>
                        <td>${row.respuesta || 'Sin respuesta'}</td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error cargando las quejas:', error));
    }

    // Cargar las quejas al cargar la página
    loadComplaints();

    // Añadir el evento de búsqueda en tiempo real
    searchInput.addEventListener('keyup', function () {
        const searchQuery = this.value;
        loadComplaints(searchQuery); // Recargar la tabla con los resultados filtrados
    });
});
