<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4e3d7;
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

    h1 {
      text-align: center;
      color: #5a3e36;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      color: #5a3e36;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin: 8px 0 16px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .btn {
      background-color: #8d6e63;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
      width: 100%;
    }

    .btn:hover {
      background-color: #795548;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #5a3e36;
      text-decoration: none;
    }

    .error {
      color: red;
      margin-bottom: 15px;
    }

    #successModal {
      display: none;
      position: fixed;
      top: 30%;
      left: 50%;
      transform: translate(-50%, -30%);
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      text-align: center;
      z-index: 1000;
    }

    #successModal h2 {
      color: green;
    }

    #successModal button {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Add Product</h1>

    @if ($errors->any())
      <div class="error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="productForm" method="POST" action="{{ url('/products') }}">
      @csrf
      <label for="title">Title:</label>
      <input type="text" id="title" name="title" value="{{ old('title') }}" required>

      <label for="author">Author:</label>
      <input type="text" id="author" name="author" value="{{ old('author') }}" required>

      <label for="publisher">Publisher:</label>
      <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}" required>

      <label for="stock">Stock:</label>
      <input type="number" id="stock" name="stock" min="0" value="{{ old('stock') }}" required>

      <button type="submit" class="btn">Save Product</button>
    </form>

    <a href="{{ url('/products') }}" class="back-link">← Back to product list</a>
  </div>

  <!-- Modal -->
  <div id="successModal">
    <h2>✅ Product added successfully</h2>
    <button onclick="goToProducts()">Continue</button>
  </div>

  <script>
    const form = document.getElementById('productForm');

    form.addEventListener('submit', function (e) {
      e.preventDefault(); // Bloquea el envío
      document.getElementById('successModal').style.display = 'block';
      setTimeout(() => {
        form.submit(); // Envía tras mostrar modal
      }, 1500); // Puedes ajustar el tiempo si quieres que sea más rápido o lento
    });

    function goToProducts() {
      window.location.href = '/products';
    }
  </script>
</body>
</html>