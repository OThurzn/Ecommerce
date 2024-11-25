<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit();
}
// Recupera o carrinho do cookie
$carrinho = isset($_COOKIE['carrinho']) ? unserialize($_COOKIE['carrinho']) : [];

// Verifica se o carrinho está vazio
if (empty($carrinho)) {
    $produtos = [];
} else {
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "Ecommerce");
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Consulta o sql com a lista de isbn do cookie
    $isbnList = implode(",", array_map('intval', array_keys($carrinho))); // Converte as chaves para números inteiros
    $sql = "SELECT ISBN13, Nome_Produto, Preco FROM Produtos WHERE ISBN13 IN ($isbnList)";
    $result = $conn->query($sql);

    //coloca tudo bonitinho no array de produtos
    $produtos = [];
    if ($result && $result->num_rows > 0) {
        while ($produto = $result->fetch_assoc()) {
            $produto['quantidade'] = $carrinho[$produto['ISBN13']];
            $produtos[] = $produto;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Carrinho</h1>
            <ul class="list-group">
                <!--  solução pra listar tudo do array sem json -->
                <?php foreach ($produtos as $produto):?>
                    <li class="list-group-item">
                        <strong><?= htmlspecialchars($produto['Nome_Produto']) ?></strong> -
                        Quantidade: <?= htmlspecialchars($produto['quantidade']) ?> -
                        Preço unitário: R$ <?= number_format($produto['Preco'], 2, ',', '.') ?> -
                        Total: R$ <?= number_format($produto['quantidade'] * $produto['Preco'], 2, ',', '.') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="p-2">
            <a href="shop.php" class="btn btn-primary mt-4">Continuar comprando</a>
            <a href="logout.php" class="btn btn-primary mt-4">Sair</a>
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>