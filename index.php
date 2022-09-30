<?php
    session_start();
    ob_start();
    include_once 'conexao.php'

    
?>

<!DOCTYPE html>
<html lang="pt-br">
    
    <head>
        <meta chartset="UTF-8">
        <link rel="shortcurt icon" href="images/lcs.ico" type="image/x-ico">
        <title> LCS LEONE </title>
    </head>

    <body>
        <h1>Acesso Restrito</h1>

       <!-- The code that is responsible for the login. -->
        <?php
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if(!empty($dados['SendLogin'])){
                $query_usuario = "  SELECT id, usuario, email, senha
                                    FROM usuarios  
                                    WHERE email =:email
                                    LIMIT 1";
                $result_usuario = $conn->prepare($query_usuario);
                $result_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                $result_usuario->execute();

                if(($result_usuario) AND ($result_usuario->rowCount()) != 0){
                    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                    if(password_verify($dados['senha'], $row_usuario['senha'])){
                        $_SESSION['id'] = $row_usuario['id'];
                        $_SESSION['usuario'] = $row_usuario['usuario'];
                        header("Location: enter.php");
                    }else{
                        echo  "<script>alert('Erro: Usuario ou senha invalida!');</script>";
                        //$_SESSION['msg'] = "<p style='color: red'>Erro: Usuario ou senha invalida!</p>";
                    }
                }else{
                    echo  "<script>alert('Erro: Usuario ou senha invalida!');</script>";
                    //$_SESSION['msg'] = "<p style='color: red'>Erro: Usuario ou senha invalida!</p>";
                }  
            }

            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
        ?>

        <!-- The form that the user will use to login. -->
        <Form method="POST" action="">

            <label>Usuário</label>
            <input  type="email" name="email" placeholder="Digite seu email" value="<?php if(isset($dados['email'])){echo $dados['email'];} ?>"><br><br>

            <label>Senha</label>
            <input  type="password" name="senha" placeholder="Digite sua senha"><br><br>

            <input type="submit" value="Acessar" name="SendLogin">

        </form>

        <a href="recuperar_senha.php">Esqueceu a senha?</a><br>

    </body>

</html>