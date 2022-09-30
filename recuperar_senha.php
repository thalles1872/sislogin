<?php
    session_start();
    ob_start();
    include_once 'conexao.php'

    
?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'lib/vendor/autoload.php';
$mail = new PHPMailer(true);
?>

<!DOCTYPE html>
<html lang="pt-br">
    
    <head>
        <meta chartset="UTF-8">
        <link rel="shortcurt icon" href="images/lcs.ico" type="image/x-ico">
        <title> LCS - Recuperar Senha </title>
    </head>

    <body>
        <h1>Recuperar Senha</h1>

        <?php
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            

            if(!empty($dados['SendRecuperarSenha'])){
               // var_dump($dados);
                $query_usuario = "  SELECT id, usuario, email
                                    FROM usuarios  
                                    WHERE email =:email
                                    LIMIT 1";
                $result_usuario = $conn->prepare($query_usuario);
                $result_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                $result_usuario->execute();

                if(($result_usuario) AND ($result_usuario->rowCount()) != 0){
                    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                    $chave_recuperar_senha = password_hash($row_usuario['id'], PASSWORD_DEFAULT);
                   // echo "Chave $chave_recuperar_senha <br> ";
                   $query_up_usuario = "UPDATE usuarios
                    SET recuperar_senha =:recuperar_senha
                    WHERE id =:id
                    LIMIT 1";
                    $result_up_usuario = $conn->prepare($query_up_usuario);
                    $result_up_usuario->bindParam(':recuperar_senha', $chave_recuperar_senha, PDO::PARAM_STR);
                    $result_up_usuario->bindParam(':id', $row_usuario['id'], PDO::PARAM_INT);

                    if($result_up_usuario->execute()){
                        $link = "http://localhost/Thalles/Login/atualizar_senha.php?chave=$chave_recuperar_senha";
                        try {
                            $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
                            $mail->IsSMTP(); 
                                    
                            $mail->Host       = 'smtp.mailtrap.io';                   
                            $mail->SMTPAuth   = true;                                
                            $mail->Username   = '9ce66b81d9777f';                  
                            $mail->Password   = 'd250f36657997c';                        
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           
                            $mail->Port       = 2525;   

                            //Recipients
                            $mail->setFrom('thallespereiradasilva@gmail.com', 'UDF');
                            $mail->addAddress('thallespereiradasilva@gmail.com', $row_usuario['usuario']);     
                            
                            $mail->isHTML(true);                                  //Set email format to HTML
                            $mail->Subject = 'Recuperar Senha';
                            $mail->Body    = 'Prezado(a)' . $row_usuario['nome'] . ".Você solicitou alteração de senha. <br><br> Para continuar o processo de recuperação de senha, clique no link abaixo ou cole o endereço no seu navegador: <br><br>" . $link . "Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma;<br><br>";
                            $mail->AltBody = 'Prezado(a)' . $row_usuario['nome']. "\n\n Você solicitou alteração de senha.\n\nPara continuar o processo de recuperação de senha, clique no link abaixo ou cole o endereço no seu navegador: \n\n " . $link . "Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma. \n\n";

                                $mail->send();

                                $_SESSION['msg'] = "<p style='color: green'>Senha atualizada com sucesso!</p>";
                                 header("Location: index.php");

                        }catch (Exception $e) {
                            echo "Erro: E-mail não enviado com sucesso. Mailer Error: {$mail->ErrorInfo}";
                        }
                        
                    
                    }else{
                        $_SESSION['msg'] = "<p style='color: red'>Erro: Tente Novamente!</p>";
                    }
                }else{
                    $_SESSION['msg'] = "<p style='color: red'>Erro: Email não encontrado!</p>";
                }

            }

             if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
             }

            ?>

         <Form method="POST" action="">
            <?php 
            $usuario = "";
            if (isset($dados['email'])) {
                $usuario = $dados['email'];
            } ?>

            <label>E-mail</label>
            <input  type="email" name="email" placeholder="Digite seu email" value="<?php echo $usuario; ?>"><br><br>

           

            <input type="submit" value="Recuperar" name="SendRecuperarSenha">

        </form>
        <br>
        Lembrou?<a href="index.php">Click aqui!</a> Para logar

    </body>
       
</html>