<?php include "header.php" ?>

    <?php
        //Verifica se o método de envio das informações do form é "POST"
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //Cria variáveis para armazenar as informações recebidas do array $_POST
            $fotoUsuario = $nomeUsuario = $dataNascimentoUsuario = $cidadeUsuario = $emailUsuario = $senhaUsuario = $confirmarSenhaUsuario = "";

            //Variável booleana para controle de erros de preenchimento
            $erroPreenchimento = false;

            //Validação do campo nomeUsuario
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["nomeUsuario"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>NOME</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Se o $_POST["nomeUsuario"] não estiver vazio, é filtrado e armazenado na variável PHP
                $nomeUsuario = filtrar_entrada($_POST["nomeUsuario"]);
            }

            //Validação do campo dataNascimentoUsuario
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["dataNascimentoUsuario"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>DATA DE NASCIMENTO</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Se o $_POST["dataNascimentoUsuario"] não estiver vazio, é filtrado e armazenado na variável PHP
                $dataNascimentoUsuario = filtrar_entrada($_POST["dataNascimentoUsuario"]);
            }

            //Validação do campo cidadeUsuario
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["cidadeUsuario"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>CIDADE</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Se o $_POST["cidadeUsuario"] não estiver vazio, é filtrado e armazenado na variável PHP
                $cidadeUsuario = filtrar_entrada($_POST["cidadeUsuario"]);
            }

            //Validação do campo emailUsuario
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["emailUsuario"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>EMAIL</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Se o $_POST["emailUsuario"] não estiver vazio, é filtrado e armazenado na variável PHP
                $emailUsuario = filtrar_entrada($_POST["emailUsuario"]);
            }

            //Validação do campo senhaUsuario
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["senhaUsuario"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>SENHA</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Se o $_POST["senhaUsuario"] não estiver vazio, é filtrado e armazenado na variável PHP
                $senhaUsuario = filtrar_entrada($_POST["senhaUsuario"]);
            }

            //Validação do campo confirmarSenhaUsuario
            //Utiliza a função empty() para verificar se o campo está vazio
            if(empty($_POST["confirmarSenhaUsuario"])){
                echo "<div class='alert alert-warning text-center'>O campo <strong>CONFIRMAR SENHA</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            }
            else{
                //Se o $_POST["confirmarSenhaUsuario"] não estiver vazio, é filtrado e armazenado na variável PHP
                $confirmarSenhaUsuario = filtrar_entrada($_POST["confirmarSenhaUsuario"]);
            }

        }
        else{
            //Usa a função header() para redirecionar o usuário para o formUsuario.php
            header("location:formUsuario.php");
        }

        //Função para filtrar entrada de dados e evitar SQL Injection
        function filtrar_entrada($dado){
            $dado = trim($dado); //Remove espaços desnecessários
            $dado = stripslashes($dado); //Remove barras invertidas
            $dado = htmlspecialchars($dado); //Converte caracteres especiais em entidades HTML

            //Após o dado passar pelos filtros, é retornado
            return($dado);
        }
    ?>

<?php include "footer.php" ?>