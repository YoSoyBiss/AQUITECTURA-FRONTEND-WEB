<!-- resources/views/sales/createsales.blade.php -->

<form action="{{ route('sales.store') }}" method="POST">
    @csrf

    <label>Total:</label><br>
    <input type="number" name="total" step="0.01" required><br><br>

    <label>ID del Usuario:</label><br>
    <input type="text" name="userId" required><br><br>

    <fieldset style="border: 1px solid #ccc; padding: 15px;">
        <legend>Producto 1</legend>

        <label>ID del Producto:</label><br>
        <input type="number" name="details[0][productId]" required><br><br>

        <label>Cantidad:</label><br>
        <input type="number" name="details[0][quantity]" required><br><br>

        <label>Precio Unitario:</label><br>
        <input type="number" step="0.01" name="details[0][unitPrice]" required><br>
    </fieldset>

    <br>
    <button type="submit">Registrar Venta</button>
</form>
