<?php
include 'db_connection.php';

// Abrir conexão com o banco de dados
$conn = OpenCon();

// Consultar todos os itens vendidos em ordem decrescente pela data da venda
$sql_vendas = "SELECT * FROM vendas ORDER BY data_venda DESC";
$result_vendas = $conn->query($sql_vendas);

// Fechar a conexão após a consulta
CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens Vendidos - Hortifruti</title>
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
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
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
        <h2>Itens Vendidos - Hortifruti</h2>

        <!-- Tabela de Vendas -->
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade (kg)</th>
                    <th>Preço Unitário (R$)</th>
                    <th>Total (R$)</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_vendas->num_rows > 0) {
                    // Exibir cada venda na tabela
                    while($row = $result_vendas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['produto'] . "</td>";
                        echo "<td>" . $row['quantidade'] . "</td>";
                        echo "<td>" . $row['preco_unitario'] . "</td>";
                        echo "<td>" . $row['valor_total'] . "</td>";
                        echo "<td>" . $row['data_venda'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhuma venda encontrada</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <button onclick="window.print()">Imprimir Tabela</button>
    </div>
</body>
</html>
