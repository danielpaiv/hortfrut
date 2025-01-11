<?php
    include 'db_connection.php';

    // Abrir conexão com o banco de dados
    $conn = OpenCon();

    // Data atual
    $data_atual = date('Y-m-d');

    // Inicializar variáveis de período
    $data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
    $data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

    // Condição para filtrar por data ou período
    $where_clause = "WHERE DATE(data_venda) = '$data_atual'";
    if (!empty($data_inicio) && !empty($data_fim)) {
        $where_clause = "WHERE DATE(data_venda) BETWEEN '$data_inicio' AND '$data_fim'";
    }

    // Calcular o total de vendas
    $sql_total_vendas = "SELECT SUM(valor_total) AS total_vendas FROM vendas $where_clause";
    $result_total_vendas = $conn->query($sql_total_vendas);
    $total_vendas = 0;

    if ($result_total_vendas->num_rows > 0) {
        $row = $result_total_vendas->fetch_assoc();
        $total_vendas = $row['total_vendas'];
    }

    // Calcular o total investido no estoque (sem filtro de data, pois é um valor fixo)
    $sql_total_estoque = "SELECT SUM(preco) AS total_investido FROM estoque";
    $result_total_estoque = $conn->query($sql_total_estoque);
    $total_investido = 0;

    if ($result_total_estoque->num_rows > 0) {
        $row = $result_total_estoque->fetch_assoc();
        $total_investido = $row['total_investido'];
    }

    // Calcular o total de cada forma de pagamento
    $sql_pagamentos = "
        SELECT 
            SUM(pagamento_dinheiro) AS total_dinheiro,
            SUM(pagamento_cartao) AS total_cartao,
            SUM(pagamento_pix) AS total_pix
        FROM faturamento $where_clause";
    $result_pagamentos = $conn->query($sql_pagamentos);

    $total_dinheiro = $total_cartao = $total_pix = 0;

    if ($result_pagamentos->num_rows > 0) {
        $row = $result_pagamentos->fetch_assoc();
        $total_dinheiro = $row['total_dinheiro'];
        $total_cartao = $row['total_cartao'];
        $total_pix = $row['total_pix'];
    }

    // Calcular o lucro parcial
    $lucro_parcial = $total_vendas - $total_investido;

    // Fechar a conexão após as consultas
    CloseCon($conn);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro - Hortifruti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: rgb(0, 37, 160);
        }
        .container {
            background-color: rgb(181, 179, 199);
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        h2 {
            text-align: center;
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
        a {
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>
<body>
    <button>
        <a href="http://localhost/hortifruti/formulario_hortifruti.php">Voltar</a>
    </button>
    <div class="container">
        <h2>Relatório Financeiro - Hortifruti</h2>

        <!-- Formulário para Filtro de Período -->
        <form method="GET" action="">
            <label for="data_inicio">Data Início:</label>
            <input type="date" name="data_inicio" id="data_inicio" value="<?php echo $data_inicio; ?>">
            <label for="data_fim">Data Fim:</label>
            <input type="date" name="data_fim" id="data_fim" value="<?php echo $data_fim; ?>">
            <button type="submit">Filtrar</button>
        </form>

        <h3>Resumo Financeiro</h3>
        <table>
            <thead>
                <tr>
                    <th>Total de Vendas (R$)</th>
                    <th>Total Investido (R$)</th>
                    <th>Lucro Parcial (R$)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>R$ <?php echo number_format($total_vendas, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($total_investido, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($lucro_parcial, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <h3>Formas de Pagamento</h3>
        <table>
            <thead>
                <tr>
                    <th>Dinheiro (R$)</th>
                    <th>Cartão (R$)</th>
                    <th>PIX (R$)</th>
                    <th>Subtotal (R$)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>R$ <?php echo number_format($total_dinheiro, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($total_cartao, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($total_pix, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($total_dinheiro + $total_cartao + $total_pix, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
