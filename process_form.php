<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto = $_POST['product'];
    $quantidade = $_POST['quantity'];
    $preco_unitario = $_POST['unit-price'];
    $valor_total = $_POST['total-price'];
    $pagamento_dinheiro = $_POST['cash-payment'];
    $pagamento_cartao = $_POST['card-payment'];

    // Abrir conexão
    $conn = OpenCon();

    // Exemplo de inserção segura usando MySQLi
    $stmt = $conn->prepare("INSERT INTO vendas (produto, quantidade, preco_unitario, valor_total, pagamento_dinheiro, pagamento_cartao) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddddd", $produto, $quantidade, $preco_unitario, $valor_total, $pagamento_dinheiro, $pagamento_cartao);
    $stmt->execute();
    $stmt->close();

    // Fechar conexões
    CloseCon($conn);
    echo "<script>alert('Venda finalizada com sucesso!'); window.location.href='formulario_hortifruti.php';</script>";
} else {
    echo "Método de requisição inválido.";
}
?>
