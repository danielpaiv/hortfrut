<?php
    include 'db_connection.php';

    // Abrir conexão com o banco de dados
    $conn = OpenCon();

    // Calcular o total de vendas
    $sql_total_vendas = "SELECT SUM(valor_total) AS total_vendas FROM vendas";
    $result_total_vendas = $conn->query($sql_total_vendas);
    $total_vendas = 0;

    if ($result_total_vendas->num_rows > 0) {
        $row = $result_total_vendas->fetch_assoc();
        $total_vendas = $row['total_vendas'];
    }

    // Calcular o total investido no estoque
    $sql_total_estoque = "SELECT SUM(preco) AS total_investido FROM estoque";
    $result_total_estoque = $conn->query($sql_total_estoque);
    $total_investido = 0;

    if ($result_total_estoque->num_rows > 0) {
        $row = $result_total_estoque->fetch_assoc();
        $total_investido = $row['total_investido'];
    }

    // Calcular o total de vendas no dinheiro
    $sql_total_dinheiro = "SELECT SUM(pagamento_dinheiro) AS total_dinheiro FROM vendas";
    $result_total_dinheiro = $conn->query($sql_total_dinheiro);
    $total_dinheiro = 0;

    if ($result_total_dinheiro->num_rows > 0) {
        $row = $result_total_dinheiro->fetch_assoc();
        $total_dinheiro = $row['total_dinheiro'];
    }

    // Calcular o total de vendas no cartão
    $sql_total_cartao = "SELECT SUM(pagamento_cartao) AS total_cartao FROM vendas";
    $result_total_cartao = $conn->query($sql_total_cartao);
    $total_cartao = 0;

    if ($result_total_cartao->num_rows > 0) {
        $row = $result_total_cartao->fetch_assoc();
        $total_cartao = $row['total_cartao'];
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
            background-color:rgb(0, 37, 160);
        }
        .container {
            background-color:rgb(181, 179, 199);
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
        <h2>Relatório Financeiro - Hortifruti</h2>

        <h3>Resumo Financeiro</h3>
        <table>
            <thead>
                <tr>
                    <th>Total de Vendas (R$)</th>
                    <th>Total Investido (R$)</th>
                    <th>Lucro Parcial (R$)</th>
                    <th>Total Vendas em Dinheiro (R$)</th>
                    <th>Total Vendas no Cartão (R$)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>R$ <?php echo number_format($total_vendas, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($total_investido, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($lucro_parcial, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($total_dinheiro, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($total_cartao, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
