


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Vendas - Hortifruti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
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
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .payment-group {
            display: flex;
            gap: 10px;
        }
        .payment-group div {
            flex: 1;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulário de Vendas - Hortifruti</h2>
        <form id="sales-form" action="processa_venda.php" method="post">
            <!-- Produto -->
            <div class="form-group">
                <label for="product">Produto:</label>
                <select id="product" name="product" required>
                    <option value="">Selecione o produto</option>
                    <option value="banana">Banana</option>
                    <option value="maça">Maçã</option>
                    <option value="laranja">Laranja</option>
                    <option value="tomate">Tomate</option>
                    <option value="limao">Limão</option>
                    <option value="cebola">Cebola</option>
                </select>
            </div>

            <!-- Quantidade -->
            <div class="form-group">
                <label for="quantity">Quantidade (kg):</label>
                <input type="number" id="quantity" name="quantity" step="0.01" min="0" required>
            </div>

            <!-- Preço unitário -->
            <div class="form-group">
                <label for="unit-price">Preço Unitário (R$):</label>
                <input type="number" id="unit-price" name="unit-price" step="0.01" min="0" required>
            </div>

            <!-- Total -->
            <div class="form-group">
                <label for="total-price">Valor Total (R$):</label>
                <input type="number" id="total-price" name="total-price" step="0.01" min="0" readonly>
            </div>

            <!-- Formas de Pagamento -->
            <div class="form-group">
                <label>Forma de Pagamento:</label>
                <div class="payment-group">
                    <div>
                        <label for="cash-payment">Dinheiro (R$):</label>
                        <input type="number" id="cash-payment" name="cash-payment" step="0.01" min="0">
                    </div>
                    <div>
                        <label for="card-payment">Cartão (R$):</label>
                        <input type="number" id="card-payment" name="card-payment" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <!-- Botão de Submissão -->
            <div class="form-group">
                <button type="submit">Finalizar Venda</button>
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById('sales-form');
        const quantityInput = document.getElementById('quantity');
        const unitPriceInput = document.getElementById('unit-price');
        const totalPriceInput = document.getElementById('total-price');
        const cashPaymentInput = document.getElementById('cash-payment');
        const cardPaymentInput = document.getElementById('card-payment');

        // Atualizar o valor total automaticamente
        function updateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(unitPriceInput.value) || 0;
            totalPriceInput.value = (quantity * unitPrice).toFixed(2);
        }

        // Validar pagamentos
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const total = parseFloat(totalPriceInput.value) || 0;
            const cash = parseFloat(cashPaymentInput.value) || 0;
            const card = parseFloat(cardPaymentInput.value) || 0;

            if (cash + card !== total) {
                alert('Os valores de pagamento não correspondem ao total. Por favor, revise os valores.');
            } else {
                alert('Venda finalizada com sucesso!');
                form.reset();
            }
        });

        // Eventos para atualizar o total automaticamente
        quantityInput.addEventListener('input', updateTotal);
        unitPriceInput.addEventListener('input', updateTotal);
    </script>
</body>
</html>
