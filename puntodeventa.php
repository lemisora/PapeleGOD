<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header('Location: login.php');
    exit();
}

include('conexion.php');
$sql = "SELECT id_producto, nombre, precio, stock FROM productos";
$result = $conn->query($sql);
$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta - Papelería</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Asegura que el contenido principal se expanda y el footer se mantenga al fondo */
        html, body {
            height: 100%; /* Establece la altura de la página al 100% */
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1; /* Esto hace que el contenedor ocupe todo el espacio disponible */
        }

        footer {
            margin-top: auto; /* Esto asegura que el footer se quede al final */
        }
        #recibo {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 8px;
}

#recibo ul {
    list-style-type: none;
    padding: 0;
}

#recibo ul li {
    margin-bottom: 8px;
}

    </style>
</head>
<body>
    <!-- Encabezado -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Bienvenido, Cajero</h1>
        <p>Selecciona una opción para continuar</p>
    </header>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Papelería</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="promocionesc.php"><i class="fas fa-tags"></i> Promociones y Descuentos</a></li>
                    <li class="nav-item"><a class="nav-link" href="gproductoscajero.php"><i class="fas fa-boxes"></i> Gestión de Productos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="puntodeventa.php"><i class="fas fa-shopping-cart"></i> Punto de Venta</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfilc.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Punto de Venta</h4>
        </div>
        <div class="card-body">
            <!-- Formulario para agregar productos -->
            <form id="ventaForm" class="mb-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="producto" class="form-label">Producto:</label>
                        <select id="producto" class="form-select" required>
                            <option value="">Seleccionar Producto</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?= $producto['id_producto'] ?>" 
                                        data-nombre="<?= $producto['nombre'] ?>" 
                                        data-precio="<?= $producto['precio'] ?>" 
                                        data-stock="<?= $producto['stock'] ?>">
                                    <?= $producto['nombre'] ?> - $<?= number_format($producto['precio'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" id="cantidad" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-success w-100" onclick="agregarProducto()">
                            <i class="fas fa-plus"></i> Añadir
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabla de productos seleccionados -->
            <div class="table-responsive mb-4">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="listaProductos"></tbody>
                </table>
            </div>

            <!-- Sección de recibo -->
            <div class="row">
                <div class="col-md-8">
                    <!-- Vacío, la tabla de productos se encuentra aquí -->
                </div>
                <div class="col-md-4">
                    <h4>Recibo</h4>
                    <div id="recibo" class="border p-3" style="min-height: 200px;">
    <p class="text-center">No hay productos añadidos aún</p>
    <p><strong>Total: $0.00</strong></p>
    <p><strong>Monto Pagado: $0.00</strong></p>
    <p><strong>Cambio: $0.00</strong></p>
</div>

                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
    <h4>Total: <span id="total">$0.00</span></h4>
    <div id="pagoEfectivo">
        <label for="dineroDado" class="form-label">Monto dado por el cliente:</label>
        <input type="number" id="dineroDado" class="form-control w-50" placeholder="Ej. 50.00" min="0">
    </div>
    <button type="button" class="btn btn-primary" onclick="procesarPago()">
        <i class="fas fa-check"></i> Procesar Pago
    </button>
</div>

        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
    </footer>

<script>
// Variables para el manejo de la venta
let productosVenta = [];
let total = 0;

// Función para agregar producto a la tabla y al recibo
function agregarProducto() {
    const productoSelect = document.getElementById('producto');
    const cantidad = parseInt(document.getElementById('cantidad').value);
    const productoId = productoSelect.value;

    if (!productoId) return alert("Selecciona un producto");

    const nombre = productoSelect.options[productoSelect.selectedIndex].getAttribute('data-nombre');
    const precio = parseFloat(productoSelect.options[productoSelect.selectedIndex].getAttribute('data-precio'));
    const stock = parseInt(productoSelect.options[productoSelect.selectedIndex].getAttribute('data-stock'));

    if (cantidad > stock) {
        alert("Stock insuficiente");
        return;
    }

    // Realizar consulta para obtener si el producto tiene una promoción
    fetch('obtener-promocion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productoId })
    })
    .then(response => response.json())
    .then(data => {
        let precioFinal = precio;
        let descuento = 0;

        // Si el producto tiene una promoción, aplicar el descuento
        if (data.promocion) {
            descuento = data.promocion.descuento;
            precioFinal = precio - (precio * (descuento / 100));  // Aplicamos el descuento al precio
        }

        const subtotal = cantidad * precioFinal;
        productosVenta.push({ id: productoId, nombre, cantidad, precio, precioFinal, subtotal, descuento });
        total += subtotal;

        // Agregar el producto a la tabla de productos seleccionados
        const fila = `<tr data-producto-id="${productoId}">
            <td>${nombre} ${descuento > 0 ? `<span class="badge bg-success">-${descuento}%</span>` : ''}</td>
            <td>${cantidad}</td>
            <td>$${subtotal.toFixed(2)}</td>
            <td><button class="btn btn-danger btn-sm" onclick="eliminarProducto(${subtotal}, ${productoId})">Eliminar</button></td>
        </tr>`;
        
        document.getElementById('listaProductos').insertAdjacentHTML('beforeend', fila);
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;

        // Actualizar el recibo
        actualizarRecibo();
    })
    .catch(error => {
        console.error('Error al obtener la promoción:', error);
        alert("Error al obtener la promoción");
    });
}

// Función para actualizar el recibo
function actualizarRecibo() {
    let reciboHtml = '<ul>';
    productosVenta.forEach(producto => {
        reciboHtml += `<li>${producto.nombre} - ${producto.cantidad} x $${producto.precioFinal.toFixed(2)} = $${producto.subtotal.toFixed(2)}</li>`;
    });
    reciboHtml += `</ul><p><strong>Total: $${total.toFixed(2)}</strong></p>`;

    // Verificar si se ingresó el monto pagado
    const dineroDado = parseFloat(document.getElementById('dineroDado').value) || 0;

    if (dineroDado > 0) {
        const cambio = dineroDado - total;
        reciboHtml += `<p><strong>Monto Pagado: $${dineroDado.toFixed(2)}</strong></p>`;
        reciboHtml += `<p><strong>Cambio: $${(cambio >= 0 ? cambio.toFixed(2) : 0)}</strong></p>`;
    }

    document.getElementById('recibo').innerHTML = reciboHtml;
}



// Función para eliminar un producto
function eliminarProducto(subtotal, productoId) {
    // Restar el subtotal del producto eliminado
    total -= subtotal;

    // Actualizar el total en la vista
    document.getElementById('total').textContent = `$${total.toFixed(2)}`;

    // Eliminar el producto de la lista de productosVenta
    productosVenta = productosVenta.filter(producto => producto.id !== String(productoId));

    // Eliminar la fila correspondiente en la tabla
    const fila = document.querySelector(`tr[data-producto-id="${productoId}"]`);
    if (fila) {
        fila.remove();
    }

    // Actualizar el recibo
    actualizarRecibo();
}

document.getElementById('dineroDado').addEventListener('input', actualizarRecibo);

// Función para procesar el pago y registrar la venta
function procesarPago() {
    const dineroDado = parseFloat(document.getElementById('dineroDado').value);

    if (dineroDado < total) {
        alert("El monto proporcionado no es suficiente para cubrir el total.");
        return;
    }

    const cambio = dineroDado - total;
    alert(`Pago procesado con éxito. Cambio: $${cambio.toFixed(2)}`);

    // Aquí puedes registrar la venta en el backend
    fetch('procesar-venta.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productos: productosVenta, total, metodoPago: 'efectivo', cambio })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Venta registrada con éxito.");
            // Limpiar los campos después de procesar la venta
            limpiarCampos();  // Llamar a la función para limpiar los campos
        } else {
            alert("Error al registrar la venta.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error al registrar la venta.");
    });
}

// Función para limpiar los campos después de procesar la venta
function limpiarCampos() {
    productosVenta = [];
    total = 0;

    document.getElementById('listaProductos').innerHTML = '';
    document.getElementById('total').textContent = '$0.00';
    document.getElementById('recibo').innerHTML = ` 
        <p class="text-center">No hay productos añadidos aún</p>
        <p><strong>Total: $0.00</strong></p>
        <p><strong>Monto Pagado: $0.00</strong></p>
        <p><strong>Cambio: $0.00</strong></p>
    `;
    document.getElementById('dineroDado').value = '';
    document.getElementById('producto').value = '';
    document.getElementById('cantidad').value = 1;
}



</script>

</body>
</html>