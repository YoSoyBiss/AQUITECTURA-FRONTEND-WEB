<!-- resources/views/products/index.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Tus estilos permanecen iguales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e3d7;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #5a3e36;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(90, 62, 54, 0.2);
        }

        .add-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #8d6e63;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .add-button:hover {
            background-color: #795548;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #d7ccc8;
            color: #4e342e;
        }

        tr:nth-child(even) {
            background-color: #f8f5f3;
        }

        .action-button {
            padding: 6px 12px;
            background-color: #6d4c41;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .action-button:hover {
            background-color: #5d4037;
        }

        .delete-icon {
            cursor: pointer;
            color: #c62828;
            font-size: 18px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            width: 400px;
            border-radius: 8px;
            text-align: center;
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-cancel {
            background-color: #ccc;
        }

        .btn-confirm {
            background-color: #c62828;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-bar">
        <h1>Listado de Productos</h1>
        <a href="{{ route('products.create') }}" class="add-button">+ Agregar Producto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Editorial</th>
                <th>Stock</th>
                <th>Acciones</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr id="row-{{ $product['id'] }}">
                    <td>{{ $product['title'] }}</td>
                    <td>{{ $product['author'] }}</td>
                    <td>{{ $product['publisher'] }}</td>
                    <td>{{ $product['stock'] }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product['id']) }}" class="action-button">Editar</a>
                    </td>
                    <td>
                        <span class="delete-icon" onclick="confirmDelete({{ $product['id'] }})">&#128465;</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>¿Estás seguro que deseas eliminar este producto del almacén?</p>
        <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
            <button class="btn-confirm" onclick="deleteProduct()">Eliminar</button>
        </div>
    </div>
</div>

<script>
    let selectedId = null;

    function confirmDelete(id) {
        selectedId = id;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
        selectedId = null;
    }

    function deleteProduct() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`{{ env('API_URL', 'http://127.0.0.1:8000') }}/api/products/${selectedId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                const row = document.getElementById(`row-${selectedId}`);
                if (row) row.remove();
                closeModal();
            } else {
                return response.json().then(data => {
                    alert('Error al eliminar: ' + (data.message || 'Error desconocido'));
                });
            }
        })
        .catch(error => {
            alert('Error en la solicitud: ' + error);
        });
    }
</script>

</body>
</html>
