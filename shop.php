<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit();
}

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "Ecommerce");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta para buscar os produtos
$sql = "SELECT ISBN13, Nome_Produto, Preco FROM Produtos";
$result = $conn->query($sql);

// Adiciona produtos ao carrinho
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];

    // Recupera o carrinho do cookie
    $carrinho = isset($_COOKIE['carrinho']) ? unserialize($_COOKIE['carrinho']) : [];

    // Adiciona ou atualiza a quantidade no carrinho
    if (isset($carrinho[$isbn])) {
        $carrinho[$isbn]++;
    } else {
        $carrinho[$isbn] = 1;
    }

    // Salva o carrinho atualizado no cookie
    //para consulta: https://www.php.net/manual/pt_BR/language.oop5.serialization.php#:~:text=A%20fun%C3%A7%C3%A3o%20serialize()%20retorna,as%20vari%C3%A1veis%20de%20um%20objeto.
    setcookie("carrinho", serialize($carrinho), time() + (86400 * 30), "/"); // Validade: 30 dias
    header("Location: shop.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">

        <div class='sticky'>
            <h1 class="mb-4">Produtos</h1>
            <div class="p-2">
                <a href="cart.php" class="btn btn-primary mt-4">Carrinho</a>
                <a href="logout.php" class="btn btn-primary mt-4">Sair</a>
            </div>
        </div>
        <div class="row">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($produto = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="imgs/<?= $produto['ISBN13'] ?>.jpg" class="card-img-top"
                                alt="<?= $produto['Nome_Produto'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $produto['Nome_Produto'] ?></h5>
                                <p class="card-text">Preço: R$ <?= number_format($produto['Preco'], 2, ',', '.') ?></p>
                                <form method="POST" action="">
                                    <input type="hidden" name="isbn" value="<?= $produto['ISBN13'] ?>">
                                    <button type="submit" name="adicionar_carrinho" class="btn btn-primary">Adicionar ao
                                        Carrinho</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>