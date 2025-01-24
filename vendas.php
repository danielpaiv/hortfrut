<?php
    session_start();

   
    // Verificar se a sessão contém os dados esperados
    if (isset($_SESSION['user_id']) && isset($_SESSION['nome'])) {
        echo 'ID : ' . $_SESSION['user_id'] . '<br>';
        echo 'Nome : ' . $_SESSION['nome'] . '<br>';
    } else {
        echo 'Nenhum dado de usuário encontrado na sessão.';
    }


    include 'db_connection.php';

    // Verificar se o usuário está autenticado
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');  // Redirecionar para a página de login caso o usuário não esteja logado
        exit();
    }

    // Abrir conexão com o banco de dados
    $conn = OpenCon();

    // Definir o fuso horário para Brasília
    date_default_timezone_set('America/Sao_Paulo');

    // Obter a data atual
    $data_atual = date('Y-m-d');

    // Consultar as vendas realizadas no dia atual para o usuário logado
    $sql_vendas = "SELECT * FROM vendas WHERE DATE(data_venda) = ? AND user_id = ? ORDER BY data_venda DESC";
    $stmt = $conn->prepare($sql_vendas);
    $stmt->bind_param('ss', $data_atual, $_SESSION['user_id']);  // Usar o ID do usuário logado para filtrar as vendas
    $stmt->execute();
    $result_vendas = $stmt->get_result();

    // Buscar a data e hora da última venda (caso exista)
    $ultima_data_venda = null;
    if ($result_vendas->num_rows > 0) {
        $row = $result_vendas->fetch_assoc();
        $ultima_data_venda = $row['data_venda'];
        // Reposicionar o ponteiro para o início do resultado
        $result_vendas->data_seek(0);
    }

    // Obter a data atual ou a data fornecida pelo usuário
    $data_filtro = isset($_GET['filter-date']) ? $_GET['filter-date'] : $data_atual;

    // Consultar as vendas realizadas na data selecionada para o usuário logado
    $sql_vendas = "SELECT * FROM vendas WHERE DATE(data_venda) = ? AND user_id = ? ORDER BY data_venda DESC";
    $stmt = $conn->prepare($sql_vendas);
    $stmt->bind_param('ss', $data_filtro, $_SESSION['user_id']);  // Usar o ID do usuário logado para filtrar as vendas
    $stmt->execute();
    $result_vendas = $stmt->get_result();

     //esse codigo é responsável por criptografar a pagina viinculado ao codigo teste login.
     // Verificar se as variáveis de sessão 'email' e 'senha' não estão definidas
     if (!isset($_SESSION['nome']) || !isset($_SESSION['senha'])) {
         unset($_SESSION['nome']);
         unset($_SESSION['senha']);
         header('Location: index.php');
         exit();  // Importante adicionar o exit() após o redirecionamento
     }
     
    // Fechar a conexão após a consulta
    $stmt->close();
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
<button><a href="formulario_hortifruti.php" style="color: white; text-decoration: none;">Voltar</a></button>

    <div class="container">
        <h2>Itens Vendidos - Hortifruti</h2>

            <!-- Formulário para selecionar a data -->
            <form method="GET" action="">
                <label for="filter-date">Selecione uma data:</label>
                <input type="date" id="filter-date" name="filter-date" value="<?php echo htmlspecialchars($data_filtro); ?>" required>
                <button type="submit">Filtrar</button>
            </form>
            <br>
            <!-- Botões de Ação -->
            <div>
                <button onclick="filterLastSale()">Filtrar Última Venda</button>
                <button onclick="printSelected()">Imprimir Selecionados</button>
                <!-- Botão para gerar o relatório -->
                <button><a href="relatorio.php" class="btn-relatorio">Gerar Relatório</a></button>
            </div>
            <br>

        <!-- Tabela de Vendas -->
        <form id="sales-form">
            <table>
                <thead>
                    <tr>
                        <th>Selecionar</th>
                        <th>Produto</th>
                        <th>Quantidade (kg)</th>
                        <th>Preço Unitário (R$)</th>
                        <th>Total (R$)</th>
                        <!--<th>Data</th>-->
                    </tr>
                </thead>
                <tbody id="sales-table-body">
                    <?php
                    if ($result_vendas->num_rows > 0) {
                        // Exibir cada venda na tabela
                        while($row = $result_vendas->fetch_assoc()) {
                            echo "<tr data-venda='" . $row['data_venda'] . "'>";
                            echo "<td><input type='checkbox' class='select-sale'></td>";
                            echo "<td>" . $row['produto'] . "</td>";
                            echo "<td>" . $row['quantidade'] . "</td>";
                            echo "<td>" . $row['preco_unitario'] . "</td>";
                            echo "<td>" . $row['valor_total'] . "</td>";
                            //echo "<td>" . $row['data_venda'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Nenhuma venda encontrada</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>

    <script>

         // Função para capturar o pressionamento da tecla ESC
         document.addEventListener('keydown', function(event) {
                if (event.key === 'ArrowLeft') {  // Se a tecla pressionada for 'ESC'
                    window.location.href = 'formulario_hortifruti.php';  // Redireciona para o formulário
                }
            });

            
        // Função para filtrar os itens da última venda
        function filterLastSale() {
            const lastSaleDate = '<?php echo $ultima_data_venda; ?>';
            const rows = document.querySelectorAll("#sales-table-body tr");
            rows.forEach((row) => {
                if (row.getAttribute("data-venda") === lastSaleDate) {
                    row.style.display = ""; // Mostrar
                } else {
                    row.style.display = "none"; // Ocultar
                }
            });
        }

        // Função para imprimir os itens selecionados com subtotal
        function printSelected() {
            const selectedRows = document.querySelectorAll(".select-sale:checked");
            if (selectedRows.length === 0) {
                alert("Nenhum item selecionado para impressão.");
                return;
            }

            // Inicializa o subtotal
            let subtotal = 0;

            
            // Clona apenas as linhas selecionadas
            const printWindow = window.open("", "_blank");

            
            printWindow.document.write("<html><head><title>Imprimir Selecionados</title></head><body>");
            printWindow.document.write("<table border='1' style='width:0%; border-collapse:collapse;'>");
            printWindow.document.write("<tr><th>Produto</th><th>Quantidade</th><th>Preço Unitário</th><th>Total</th>");

            selectedRows.forEach((checkbox) => {
                const row = checkbox.closest("tr").cloneNode(true);
                row.removeChild(row.firstChild); // Remove a coluna de checkbox
                
                // Obtém o valor total da linha para o subtotal
                const totalCell = row.children[3]; // Coluna de "Total (R$)"
                subtotal += parseFloat(totalCell.textContent);

                printWindow.document.write(row.outerHTML);
            });

            

            // Adiciona o subtotal na impressão
            printWindow.document.write("<tr>");
            printWindow.document.write("<td colspan='3' style='text-align:right; font-weight:bold;'>Subtotal:</td>");
            printWindow.document.write(`<td colspan='2' style='font-weight:bold;'>R$ ${subtotal.toFixed(2)}</td>`);
            printWindow.document.write("</tr>");

            // Adiciona uma linha em branco abaixo do subtotal
            printWindow.document.write("<tr>");
            printWindow.document.write("<td colspan='5' style='height:20px;'></td>");
            printWindow.document.write("</tr>");

            printWindow.document.write("</table></body></html>");
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>
</html>
