<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos - Estoque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color:rgb(0, 37, 160);
        }
        .container {
            background-color:rgb(181, 179, 199);
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        a{
            text-decoration: none;
        }
    </style>
</head>
<body>
    <button>
    <a href="http://localhost/hortifruti/formulario_hortifruti.php">Voltar</a>
    </button>
    
    <div class="container">
        <h2>Cadastro de Produtos - Estoque</h2>
        <form id="stock-form" action="process_estoque.php" method="post">
            <!-- Produto -->
            <div class="form-group">
                <label for="produto">Produto:</label>
                <input type="text" id="produto" name="produto" required>
            </div>

            <!-- Quantidade -->
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" min="1" required>
            </div>

            <!-- Preço -->
            <div class="form-group">
                <label for="preco">Preço (R$):</label>
                <input type="number" id="preco" name="preco" step="0.01" min="0" required>
            </div>

            <!-- Preço unitario -->
            <div class="form-group">
                <label for="preco_unitario">Preço Unitario (R$):</label>
                <input type="number" id="preco_unitario" name="preco_unitario" step="0.01" min="0" required>
            </div>

            <!-- Descrição -->
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4"></textarea>
            </div>

            <!-- Botão de Submissão -->
            <div class="form-group">
                <button type="submit">Cadastrar Produto</button>
            </div>
        </form>
    </div>
</body>
</html>
