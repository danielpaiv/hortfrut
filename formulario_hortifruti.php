<?php
include 'db_connection.php';

// Abrir conexão com o banco de dados
$conn = OpenCon();

// Consultar os produtos no estoque
$sql_estoque = "SELECT produto FROM estoque";
$result_estoque = $conn->query($sql_estoque);

// Fechar a conexão após a consulta
CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Vendas - Hortifruti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color:rgb(0, 37, 160);
           display: flex;
            justify-content: space-between;
            
        }
        .container {
            position: fixed;
            top: 20%;
            background-color:rgb(181, 179, 199);
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            border-collapse: collapse;
            display: flex;
            justify-content: space-between; /* Espaço entre os objetos */
            align-items: center; /* Alinha os objetos verticalmente no centro */
            gap: 50px; /* Espaçamento entre os objetos */

           
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .buttons {
            margin-bottom: 20px;
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
        .cart {
            position: fixed;
            margin-top: 20px;
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
           /* width: 100%;*/
            margin-left:50%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            /*border-collapse: collapse;*/
            width: 50%; /* Ajusta o tamanho das tabelas */
            max-width: 45%; /* Limita a largura para caber lado a lado */
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }

        
    </style>
</head>
<body>
    <div class="buttons">
        <button><a href="formulario_estoque.php" style="color: white; text-decoration: none;">Estoque</a></button>
        <button><a href="gestao.php" style="color: white; text-decoration: none;">Gestão</a></button>
        <button><a href="financeiro.php" style="color: white; text-decoration: none;">Financeiro</a></button>
        <button><a href="vendas.php" style="color: white; text-decoration: none;">Vendas</a></button>
    </div>

    <div class="container">
        <h2>Formulário de Vendas - Hortifruti</h2>
        <form id="sales-form">
            <div class="form-group">
                <label for="product">Produto:</label>
                <select id="product" name="product" required>
                    <option value="">Selecione o produto</option>
                    <?php
                    if ($result_estoque->num_rows > 0) {
                        while($row = $result_estoque->fetch_assoc()) {
                            echo "<option value='" . $row['produto'] . "'>" . $row['produto'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum produto cadastrado no estoque</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantidade (kg):</label>
                <input type="number" id="quantity" name="quantity" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="unit-price">Preço Unitário (R$):</label>
                <input type="number" id="unit-price" name="unit-price" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <button type="button" id="add-to-cart">Adicionar ao Carrinho</button>
            </div>
        </form>
    </div>
        <div class="cart">
            <h3>Carrinho</h3>
            <table id="cart-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade (kg)</th>
                        <th>Preço Unitário (R$)</th>
                        <th>Valor Total (R$)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="form-group">
                <label for="cash-payment">Dinheiro (R$):</label>
                <input type="number" id="cash-payment" step="0.01" min="0">
            </div>

            <div class="form-group">
                <label for="card-payment">Cartão (R$):</label>
                <input type="number" id="card-payment" step="0.01" min="0">
            </div>

            <div class="form-group">
                <button id="finalize-sale">Finalizar Venda</button>
                <button id="print-cart">Imprimir Carrinho</button>
            </div>
        </div>
    

    <script>
        const cartTableBody = document.querySelector("#cart-table tbody");
        const addToCartButton = document.getElementById("add-to-cart");
        const finalizeSaleButton = document.getElementById("finalize-sale");
        const printCartButton = document.getElementById("print-cart");
        const cashPaymentInput = document.getElementById("cash-payment");
        const cardPaymentInput = document.getElementById("card-payment");

        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        function updateCartTable() {
            cartTableBody.innerHTML = "";
            cart.forEach((item, index) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${item.product}</td>
                    <td>${item.quantity}</td>
                    <td>${item.unitPrice}</td>
                    <td>${item.totalPrice}</td>
                    <td><button onclick="removeFromCart(${index})">Remover</button></td>
                `;
                cartTableBody.appendChild(row);
            });

            // Adicionar o subtotal na última linha da tabela
                const subtotal = calculateSubtotal();
                const subtotalRow = document.createElement("tr");
                subtotalRow.innerHTML = `
                    <td colspan="3" style="text-align: right; font-weight: bold;">Subtotal</td>
                    <td>${subtotal}</td>
                    <td></td>
                `;
                cartTableBody.appendChild(subtotalRow);

                // Função para calcular o subtotal
                function calculateSubtotal() {
                    return cart.reduce((sum, item) => sum + parseFloat(item.totalPrice), 0).toFixed(2);
            }
        }

        function addToCart() {
            const product = document.getElementById("product").value;
            const quantity = parseFloat(document.getElementById("quantity").value) || 0;
            const unitPrice = parseFloat(document.getElementById("unit-price").value) || 0;
            const totalPrice = (quantity * unitPrice).toFixed(2);

            if (product && quantity > 0 && unitPrice > 0) {
                cart.push({ product, quantity, unitPrice, totalPrice });
                localStorage.setItem("cart", JSON.stringify(cart));
                updateCartTable();
            } else {
                alert("Preencha todos os campos corretamente.");
            }
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            localStorage.setItem("cart", JSON.stringify(cart));
            updateCartTable();
        }

        function finalizeSale() {
            const total = cart.reduce((sum, item) => sum + parseFloat(item.totalPrice), 0);
            const cash = parseFloat(cashPaymentInput.value) || 0;
            const card = parseFloat(cardPaymentInput.value) || 0;

            if (cash + card === total) {
                // Enviar os dados para o servidor (chame o seu processo de envio para o banco de dados)
                const formData = new FormData();
                
                // Enviar cada item do carrinho como parâmetros individuais
                cart.forEach((item, index) => {
                    formData.append(`product[${index}][name]`, item.product);
                    formData.append(`product[${index}][quantity]`, item.quantity);
                    formData.append(`product[${index}][unitPrice]`, item.unitPrice);
                    formData.append(`product[${index}][totalPrice]`, item.totalPrice);
                });

                // Enviar o valor do pagamento
                formData.append("cash-payment", cash);
                formData.append("card-payment", card);

                fetch("process_form.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    alert("Venda finalizada com sucesso!");
                    // Limpar o carrinho do localStorage
                    localStorage.removeItem("cart");
                    cart = [];  // Limpa o array do carrinho
                    updateCartTable();  // Atualiza a tabela para refletir que o carrinho está vazio

                    // Redireciona de volta para o formulário
                    window.location.href = "formulario_hortifruti.php";
                })
                .catch(error => {
                    alert("Ocorreu um erro ao finalizar a venda. Tente novamente.");
                    console.error(error);
                });
            } else {
                alert("Os valores de pagamento não correspondem ao total do carrinho.");
            }
        }

        function printCart() {

            const cartContent = document.querySelector(".cart").innerHTML; // Captura o conteúdo da div do carrinho
    const originalContent = document.body.innerHTML; // Salva o conteúdo original da página

    // Define o conteúdo da página como apenas o carrinho a ser imprimido
    document.body.innerHTML = `
        <html>
        <head>
            <title>Impressão do Carrinho</title>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
            </style>
        </head>
        <body>
            <div class="cart">
                <h3>Carrinho</h3>
                ${cartContent}
            </div>
        </body>
        </html>
    `;
            window.print();
        }

        addToCartButton.addEventListener("click", addToCart);
        finalizeSaleButton.addEventListener("click", (e) => {
            e.preventDefault();
            finalizeSale();
        });
        printCartButton.addEventListener("click", (e) => {
            e.preventDefault();
            printCart();
        });

        document.addEventListener("DOMContentLoaded", updateCartTable);
    </script>
</body>
</html>
