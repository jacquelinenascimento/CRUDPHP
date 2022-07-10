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
    
        <h2>Listagem de Usuários</h2>

        <?php
            $query_usuarios = "SELECT id, nome, email FROM usuarios";
            $result_usuarios = $conn->prepare($query_usuarios);
            $result_usuarios->execute();

            if(($result_usuarios) AND ($result_usuarios->rowCount() != 0)) {
                while($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)) {
                    
                    extract($row_usuario);
                    echo "ID: $id <br>";
                    echo "Nome: $nome <br>";
                    echo "E-mail: $email <br>";
                    echo "<a href='editar.php?id=$id'>Editar</a><br>";
                    echo "<a href='excluir.php?id=$id'>Apagar</a><br>";
                    echo "<hr>";
                }

            }else{
                echo "Nenhum usuário foi localizado.";
            }
        ?>

            
</body>
</html>