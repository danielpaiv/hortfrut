<?php
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_STRING);
    $quantidade = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_FLOAT);
    $preco_unitario = filter_input(INPUT_POST, 'unit-price', FILTER_VALIDATE_FLOAT);
    $valor_total = filter_input(INPUT_POST, 'total-price', FILTER_VALIDATE_FLOAT);
    $pagamento_dinheiro = filter_input(INPUT_POST, 'cash-payment', FILTER_VALIDATE_FLOAT);
    $pagamento_cartao = filter_input(INPUT_POST, 'card-payment', FILTER_VALIDATE_FLOAT);

    // Verifique se os dados foram recebidos corretamente
    if ($produto && $quantidade !== false && $preco_unitario !== false && $valor_total !== false) {
        try {
            $stmt = $pdo->prepare("INSERT INTO vendas (produto, quantidade, preco_unitario, valor_total, pagamento_dinheiro, pagamento_cartao, data_venda) VALUES (:produto, :quantidade, :preco_unitario, :valor_total, :pagamento_dinheiro, :pagamento_cartao, data_venda)");
            $stmt->execute([
                ':produto' => $produto,
                ':quantidade' => $quantidade,
                ':preco_unitario' => $preco_unitario,
                ':valor_total' => $valor_total,
                ':pagamento_dinheiro' => $pagamento_dinheiro,
                ':pagamento_cartao' => $pagamento_cartao,
            ]);
            echo "Venda registrada com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao registrar a venda: " . $e->getMessage();
        }
    } else {
        echo "Dados inválidos. Por favor, verifique os valores inseridos.";
    }
}
?>
