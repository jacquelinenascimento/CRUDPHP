<?php
session_start();
ob_start();
include_once './conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    $_SESSION['msg'] = "Usuário não encontrado";
    header("Location: index.php");
    exit();
}

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
  
    
    <h2>Edição do Cadastro de Usuário</h2>

    <?php
        $query_usuario = "SELECT id, nome, email FROM usuarios WHERE id = $id LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->execute();
        
        if(($result_usuario) AND ($result_usuario->rowCount() != 0)) {
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        
        }else {
            $_SESSION['msg'] = "Usuário não encontrado";
            header("Location: index.php");
            exit();
        }

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        var_dump($dados);

        if(!empty($dados['EditUsuario'])){
            $empty_input = false;
            $dados = array_map('trim', $dados);
            if(in_array("", $dados)){
                $empty_input = true;
                echo "Favor preencher todos os campos.";
            }elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)){
                $empty_input = true;
                echo "Favor preencher e-mail válido.";
            }

            if(!$empty_input){
                $query_up_usuario = "UPDATE usuarios SET nome=:nome, email=:email WHERE id=:id";
                $edit_usuario = $conn->prepare($query_up_usuario);
                $edit_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
                $edit_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                $edit_usuario->bindParam(':id', $dados['id'], PDO::PARAM_INT);
                if($edit_usuario->execute()) {
                    $_SESSION['msg'] = "Cadastro editado com sucesso.<br>";
                    header("Location: index.php");
                }else{
                    echo("ERRO: Não foi possível salvar as alterações.");
                }
            }
        }
    ?>

    <form id="edit-usuario" method="POST" action="">
        <label>Nome: </label>
        <input type="text" name="nome" id="nome" placeholder="Nome completo" value="<?php
        if(isset($dados['nome'])) {
            echo $dados['nome'];
        }elseif (isset($row_usuario['nome'])) { 
            echo $row_usuario['nome']; 
        } 
        ?>" required><br><br>

        <label>E-mail: </label>
        <input type="email" name="email" id="email" placeholder="E-mail" value="<?php
        if(isset($dados['email'])) {
            echo $dados['email'];
        }elseif (isset($row_usuario['email'])) {
            echo $row_usuario['email']; 
        } 
        ?>" required><br><br>

        <input type="submit" value="Salvar" name="EditUsuario">
    </form>

        

            
</body>
</html>