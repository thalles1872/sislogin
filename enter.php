<?php
    session_start();
    ob_start();
    include_once 'conexao.php';

    if((!isset($_SESSION['id'])) AND (!isset($_SESSION['usuario']))){
    
        $_SESSION['msg'] = "<p style='color: red'>Erro: Necessario realizar login!</p>";
        header("Location: index.php");
       
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    
    <head>
        <meta chartset="UTF-8">
        <link rel="shortcurt icon" href="images/lcs.ico" type="image/x-ico">
        <title> LCS LEONE</title>
    </head>

    <body>
        <h1>Bem vindo, <?php echo $_SESSION['usuario']; ?> </h1>
        <a href="sair.php">Sair</a>
    </body>

</html>