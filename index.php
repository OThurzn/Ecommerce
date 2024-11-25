<?php
// Inicializa a sessão para controle de login
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header("Location: shop.php");
    exit();
}

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "Ecommerce");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Variáveis para mensagens de erro ou sucesso
$msgLogin = $msgRegistro = "";

// Processa o login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $senha = md5($_POST['senha']); // Criptografa a senha com MD5
    
    // Consulta para verificar o login
    $sql = "SELECT Email FROM Cliente WHERE Email = '$email' AND Senha = '$senha'";
    echo $senha;
    $result = $conn->query($sql);
    echo $result->num_rows;
    if ($result->num_rows > 0) {
        // Login bem-sucedido, cria sessão
        $_SESSION['logado'] = true;
        $_SESSION['email'] = $email;
        header("Location: shop.php");
        exit();
    } else {
        $msgLogin = "Email ou senha incorretos.";
    }
}


// Processa o registro
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['registrar'])) {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']); // Criptografa a senha com MD5

    // Verifica se o email já está cadastrado
    $sqlVerifica = "SELECT Email FROM Cliente WHERE Email = '$email'";
    $resultVerifica = $conn->query($sqlVerifica);

    if ($resultVerifica->num_rows > 0) {
        $msgRegistro = "Este email já está cadastrado.";
    } else {
        $sql = "INSERT INTO Cliente (Email, Nome_Cliente, Sobrenome, Senha) VALUES ('$email', '$nome', '$sobrenome', '$senha')";
        if ($conn->query($sql) === TRUE) {
            $msgRegistro = "Registro realizado com sucesso!";
        } else {
            $msgRegistro = "Erro ao registrar: " . $conn->error;
        }
        // Login bem-sucedido, cria sessão
        $_SESSION['logado'] = true;
        $_SESSION['email'] = $email;
        header("Location: shop.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Seguro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="tab-login" data-bs-toggle="pill" href="#pills-login" role="tab"
                    aria-controls="pills-login" aria-selected="true">Login</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-register" data-bs-toggle="pill" href="#pills-register" role="tab"
                    aria-controls="pills-register" aria-selected="false">Registrar</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Aba de Login -->
            <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="loginEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="loginPassword" name="senha" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Entrar</button>
                </form>
                <?php if ($msgLogin) : ?>
                    <div class="alert alert-danger mt-3"><?= $msgLogin ?></div>
                <?php endif; ?>
            </div>

            <!-- Aba de Registro -->
            <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="registerName" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="registerName" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerSobrenome" class="form-label">Sobrenome</label>
                        <input type="text" class="form-control" id="registerSobrenome" name="sobrenome" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="registerEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="registerPassword" name="senha" required>
                    </div>
                    <button type="submit" name="registrar" class="btn btn-primary">Registrar</button>
                </form>
                <?php if ($msgRegistro) : ?>
                    <div class="alert alert-info mt-3"><?= $msgRegistro ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
