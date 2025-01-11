<?php
// Conexão com o banco de dados
$servername = "localhost"; // Alterar conforme necessário
$username = "root";        // Alterar conforme necessário
$password = "";            // Alterar conforme necessário
$database = "hortfruti"; // Alterar conforme necessário

$conn = new mysqli($servername, $username, $password, $database);

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Função para salvar o faturamento
function salvarFaturamento($dinheiro, $cartao, $pix) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO faturamento (pagamento_dinheiro, pagamento_cartao, pagamento_pix) VALUES (?, ?, ?)");
    $stmt->bind_param("ddd", $dinheiro, $cartao, $pix);

    if ($stmt->execute()) {
        echo "Faturamento registrado com sucesso!";
    } else {
        echo "Erro ao salvar faturamento: " . $stmt->error;
    }

    $stmt->close();
}

// Capturar os valores do formulário (exemplo de valores recebidos via POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dinheiro = $_POST["cash-payment"] ?? 0;
    $cartao = $_POST["card-payment"] ?? 0;
    $pix = $_POST["pix-payment"] ?? 0;

    salvarFaturamento($dinheiro, $cartao, $pix);
}

// Fechar conexão
$conn->close();
?>
