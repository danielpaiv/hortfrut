<?php
include 'db_connection.php';

// Abrir conexão com o banco de dados
$conn = OpenCon();

// Consultar o total de vendas
$sql_total_vendas = "SELECT SUM(valor_total) AS total_vendas FROM vendas";
$result_total_vendas = $conn->query($sql_total_vendas);
$total_vendas = 0;

if ($result_total_vendas->num_rows > 0) {
    $row = $result_total_vendas->fetch_assoc();
    $total_vendas = $row['total_vendas'];
}

// Consultar o total de itens vendidos
$sql_total_itens = "SELECT produto, SUM(quantidade) AS total_quantidade, SUM(valor_total) AS total_valor FROM vendas GROUP BY produto";
$result_total_itens = $conn->query($sql_total_itens);

// Consultar o estoque para mostrar o restante de itens
$sql_estoque = "SELECT produto, quantidade FROM estoque";
$result_estoque = $conn->query($sql_estoque);

// Fechar a conexão após as consultas
CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Vendas - Hortifruti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestão de Vendas - Hortifruti</h2>

        <h3>Total de Vendas: R$ <?php echo number_format($total_vendas, 2, ',', '.'); ?></h3>

        <h3>Itens Vendidos</h3>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Total Vendido (kg)</th>
                    <th>Total Vendido (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_total_itens->num_rows > 0) {
                    while($row = $result_total_itens->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['produto'] . "</td>";
                        echo "<td>" . number_format($row['total_quantidade'], 2, ',', '.') . "</td>";
                        echo "<td>R$ " . number_format($row['total_valor'], 2, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nenhuma venda registrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Estoque Atual</h3>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade em Estoque (kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_estoque->num_rows > 0) {
                    while($row = $result_estoque->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['produto'] . "</td>";
                        echo "<td>" . number_format($row['quantidade'], 2, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhum produto no estoque.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
