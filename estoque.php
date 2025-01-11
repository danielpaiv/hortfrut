<?php
    include 'db_connection.php';

    // Abrir conexão com o banco de dados
    $conn = OpenCon();

    // Consultar os dados da tabela estoque
    $sql_estoque = "SELECT * FROM estoque";
    $result_estoque = $conn->query($sql_estoque);

    // Fechar a conexão após as consultas
    CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque - Hortifruti</title>
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
        .imprimir-btn {
            background-color: #007bff;
        }
        .imprimir-btn:hover {
            background-color: #0056b3;
        }
        a {
            text-decoration: none;
            color: #fff;
        }

        /* Cor de fundo da linha em foco */
tr.focused {
    background-color: #f0f0f0; /* Cor clara, você pode personalizar */
}

    </style>
</head>
<body>
    <button>
        <a href="http://localhost/hortifruti/formulario_hortifruti.php">Voltar</a>
    </button>
    <div class="container">
        <h2>Estoque - Hortifruti</h2>

        <script>

            
            // Verifica se o localStorage contém a chave "focusOnFiltroNome"
            window.onload = function() {
                if (localStorage.getItem("focusOnFiltroNome") === "true") {
                    document.getElementById("filtroNome").focus();
                    // Após definir o foco, você pode limpar a chave no localStorage
                    localStorage.removeItem("focusOnFiltroNome");
                }
            }

            window.onload = function() {
                document.getElementById("filtroNome").focus();
            };

            document.addEventListener("keydown", function(event) {
                if (event.key === "ArrowDown") { // Verifica se a tecla pressionada foi a seta para baixo
                    const botoesEnviar = Array.from(document.querySelectorAll("table#estoqueTabela button"))
                        .filter(btn => btn.offsetParent !== null); // Seleciona apenas os botões visíveis

                    const elementoAtivo = document.activeElement; // Elemento atualmente focado
                    let proximoIndice = 0; // Índice do próximo botão a ser focado

                    // Encontra o índice do botão atualmente ativo
                    for (let i = 0; i < botoesEnviar.length; i++) {
                        if (botoesEnviar[i] === elementoAtivo) {
                            proximoIndice = (i + 1) % botoesEnviar.length; // Avança para o próximo ou retorna ao primeiro
                            break;
                        }
                    }

                    // Foca no próximo botão
                    if (botoesEnviar.length > 0) {
                        const proximoBotao = botoesEnviar[proximoIndice];
                        const linha = proximoBotao.closest('tr'); // Encontrando a linha do botão

                        // Chama a função para mudar a cor da linha
                        mudarCorDaLinha(linha);

                        proximoBotao.focus();
                    }

                    event.preventDefault(); // Evita comportamento padrão
                }
            });


            document.addEventListener("keydown", function(event) {
                // Verifica se a tecla pressionada foi asseta direita simples ou aspas duplas
                if (event.key === 'ArrowRight' || event.key === "'") {
                    window.location.href = "estoque.php"; // Redireciona para o arquivo estoque.php
                }

                
            });

            function mudarCorDaLinha(linha) {
                // Remove a classe "focused" de todas as linhas
                document.querySelectorAll("table#estoqueTabela tr").forEach(function(row) {
                    row.classList.remove("focused");
                });

                // Adiciona a classe "focused" à linha
                linha.classList.add("focused");
            }


            





        </script>

        <label for="filtroNome">Filtrar por nome:</label>
            <input type="text" id="filtroNome" onkeyup="filtrarPorNome()">

        <!-- Tabela de Estoque -->
        <table id="estoqueTabela">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Preço unitário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result_estoque->num_rows > 0) {
                        while($row = $result_estoque->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["produto"] . "</td>";
                            echo "<td>" . $row["quantidade"] . "</td>";
                            echo "<td>R$ " . number_format($row["preco"], 2, ',', '.') . "</td>";
                            echo "<td>R$ " . number_format($row["preco_unitario"], 2, ',', '.') . "</td>";
                            echo "<td><button onclick=\"enviarProduto(" . $row['id'] . ")\">Enviar</button></td>";
                            echo "</tr>";
                        }
                        // Linha em branco com texto dentro
                        echo "<tr><td colspan='5'>Clik no botõa para adicionaar</td></tr>";
                        echo "<tr><td colspan='5'></td></tr>";
                    } else {
                        echo "<tr><td colspan='6'>Nenhum dado encontrado</td></tr>";
                    }
                ?>
            </tbody>
        </table>

        <!-- Botão de Imprimir -->
        <button class="imprimir-btn" onclick="imprimirTabela()">Imprimir Tabela</button>
    </div>

    <script>
        function imprimirTabela() {
            var conteudo = document.querySelector('table').outerHTML;
            var janela = window.open('', '_blank');
            
            // Adicionando o estilo para a fonte Arial
            janela.document.write('<html><head><title>Imprimir Estoque</title>');
            janela.document.write('<style>body { font-family: Arial, sans-serif; }</style>'); // Fonte Arial
            janela.document.write('</head><body>');
            janela.document.write(conteudo);
            janela.document.write('</body></html>');
            
            janela.document.close();
            janela.print();
        }

        function filtrarPorNome() {
            const input = document.getElementById('filtroNome');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('estoqueTabela');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[1]; // coluna "Nome"
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        function irParaBotaoEnviar() {
        const table = document.getElementById('estoqueTabela');
        const tr = table.getElementsByTagName('tr');
        
            for (let i = 1; i < tr.length; i++) {
                if (tr[i].style.display !== 'none') {
                    const button = tr[i].querySelector('button');
                    if (button) {
                        button.focus(); // Move o cursor para o botão
                        return;
                    }
                }
            }
        }

        document.getElementById('filtroNome').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Impede a submissão padrão do formulário
                irParaBotaoEnviar();
            }
        });

        function enviarProduto(id) {
            // Salvar o id do produto no localStorage
            localStorage.setItem('product-id', id);
            
            // Redirecionar para o formulário
            window.location.href = "formulario_hortifruti.php";
        }

    </script>
</body>
</html>
