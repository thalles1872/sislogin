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
        <title> LCS Atualizar Senha </title>
    </head>

    <body>
        <h1>Atualizar Senha</h1>

       <?php

         $chave = filter_input(INPUT_GET, 'chave', FILTER_DEFAULT);
         if(!empty($chave)){

         
        // var_dump($chave);

         $query_usuario = "  SELECT id
                                    FROM usuarios  
                                    WHERE recuperar_senha =:recuperar_senha
                                    LIMIT 1";
                $result_usuario = $conn->prepare($query_usuario);
                $result_usuario->bindParam(':recuperar_senha', $chave, PDO::PARAM_STR);
                $result_usuario->execute();

                if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
                    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);    
                    //var_dump($dados);
                    if(!empty($dados['SendNovaSenha'])) {
                        $senha = password_hash($dados['senha'], PASSWORD_DEFAULT);
                        $recuperar_senha = 'NULL';

                         $query_up_usuario = "UPDATE usuarios
                    SET senha =:senha, recuperar_senha = :recuperar_senha
                    WHERE id =:id
                    LIMIT 1";
                    $result_up_usuario = $conn->prepare($query_up_usuario);
                    $result_up_usuario->bindParam(':senha', $senha, PDO::PARAM_STR);
                    $result_up_usuario->bindParam(':recuperar_senha', $recuperar_senha);
                    $result_up_usuario->bindParam(':id', $row_usuario['id'], PDO::PARAM_INT);

                     if($result_up_usuario->execute()){
                        $_SESSION['msg'] = "<p style='color: green'>Senha atualizada com sucesso!</p>";
                    header("Location: index.php");

                    }else{
                        echo "<p style='color: red'>Erro: Tente Novamente!</p>";
                    }

                }

                }else{
                    $_SESSION['msg'] = "<p style='color: red'>Erro: Link inválido!</p>";
                    header("Location: recuperar_senha.php");

                    }

                }else{
                    $_SESSION['msg'] = "<p style='color: red'>Erro: Link inválido!</p>";
                    header("Location: recuperar_senha.php");
                }

       ?>

        <Form method="POST" action="">
            
            <?php 
            $usuario = "";
            if (isset($dados['senha'])){
                $usuario = $dados['senha'];
            }
            ?>
        

            <label>Senha</label>
            <input  type="password" name="senha" placeholder="Digite a nova senha" value="<?php echo $usuario; ?>"><br><br>

            <input type="submit" value="Atualizar" name="SendNovaSenha">

        </form>

        <br>
        Lembrou?<a href="index.php">Click aqui!</a> Para logar

   </body>

</html>