<?php
session_start();
ob_start();
include_once './conexao.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
</head>
<body>
    
    <a href="index.php">Listar</a><br>
    <a href="cadastrar.php">Cadastrar</a><br>
    

    <h2>Cadastro de Usuários</h2>
   
        <?php

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(!empty($dados['cadUsuario'])){
                $empty_input = false;

                $dados = array_map('trim', $dados);
                if (in_array("", $dados)) {
                    $empty_input = true;
                    echo "ERRO: Necessário preencher todos os campos!";
                }elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                    $empty_input = true;
                    echo "ERRO: E-mail inválido!";
                }

                if(!$empty_input) {
                    $query_usuario = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email) ";
                    $cad_usuario = $conn->prepare($query_usuario);
                    $cad_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
                    $cad_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                    $cad_usuario->execute();
                    if($cad_usuario->rowCount()) {
                        echo "Usuario cadastrado com sucesso!<br>";
                        unset($dados);
                    }else{
                        echo "ERRO: Usuario não cadastrado.<br>";
                    }
                }
                
            }

        ?>
   

    <form name="cad-usuario" method="POST" action="">
        <label>Nome:</label>
        <input type="text" name="nome" id="nome" placeholder="Digite o nome completo" value="<?php 
        if (isset($dados['nome'])) {
            echo $dados['nome'];
        }
        
        ?>"><br><br>

        <label>E-mail:</label>
        <input type="email" name="email" id="email" placeholder="Digite o seu e-mail" value="<?php 
        if (isset($dados['email'])) {
            echo $dados['email'];
        }
        
        ?>"><br><br>

        <input type="submit" value="Cadastrar" name="cadUsuario">
    </form>
</body>
</html>