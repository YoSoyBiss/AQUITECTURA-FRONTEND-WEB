<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4e3d7;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(90, 62, 54, 0.2);
        }

        h2 {
            text-align: center;
            color: #5a3e36;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Registrar Nueva Venta</h2>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <label for="total">Total:</label>
        <input type="number" name="total" id="total" step="0.01" value="{{ old('total') }}" required readonly>

        <label for="userId">ID del Usuario:</label>
        <input type="text" name="userId" value="{{ old('userId') }}" required>

        <fieldset style="border: 1px solid #ccc; padding: 15px; border-radius: 5px;">
            <legend>Producto</legend>

            <label for="productId">Seleccionar Producto:</label>
            <select name="details[0][productId]" id="productId" required>
                <option value="">-- Selecciona un producto --</option>
                @foreach ($products as $product)
                    <option value="{{ $product['id'] }}"
                        data-price="{{ $product['price'] }}">
                        ID {{ $product['id'] }} - {{ $product['title'] }} (Stock: {{ $product['stock'] }})
                    </option>
                @endforeach
            </select>

            <label for="quantity">Cantidad:</label>
            <input type="number" name="details[0][quantity]" id="quantity" min="1" value="1" required>

            <label for="unitPrice">Precio Unitario:</label>
            <input type="number" name="details[0][unitPrice]" id="unitPrice" step="0.01" readonly required>
        </fieldset>

        <br>
        <button type="submit" class="btn">Registrar Venta</button>
    </form>
</div>

<script>
    const productSelect = document.getElementById('productId');
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unitPrice');
    const totalInput = document.getElementById('total');

    function updatePriceAndTotal() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const quantity = parseInt(quantityInput.value) || 0;

        unitPriceInput.value = price.toFixed(2);
        totalInput.value = (price * quantity).toFixed(2);
    }

    productSelect.addEventListener('change', updatePriceAndTotal);
    quantityInput.addEventListener('input', updatePriceAndTotal);

    // Inicializar con valores por defecto
    updatePriceAndTotal();
</script>

</body>
</html>
