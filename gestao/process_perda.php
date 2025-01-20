<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "hortfruti");

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Obtém os dados do formulário
    $data_perda = $_POST['loss-date'];
    $produto = $_POST['product'];
    $quantidade = $_POST['quantity'];
    $valor = $_POST['valor'];
    $motivo = $_POST['reason'];
    $responsavel = $_POST['responsible'];

    // Prepara a query para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO perdas (data_perda, produto, quantidade, valor, motivo, responsavel) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddss", $data_perda, $produto, $quantidade, $valor, $motivo, $responsavel);

    // Executa a query e verifica sucesso
    if ($stmt->execute()) {
       // echo "Perda registrada com sucesso!";
    } else {
        echo "Erro ao registrar perda: " . $stmt->error;
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();

    echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='perdas.php';</script>";
}
?>
