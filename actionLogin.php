<?php

    include "conexaoBD.php"; //Inclui o arquivo de conexão com o BD para consultar usuários
    session_start(); //Função para iniciar uma sessão

    $emailUsuario = mysqli_real_escape_string($conn, $_POST['emailUsuario']); //Filtra a entrada de dados
    $senhaUsuario = mysqli_real_escape_string($conn, $_POST['senhaUsuario']);

    //QUERY para buscar dados de login
    $buscarLogin = "SELECT *
                    FROM Usuarios
                    WHERE emailUsuario = '$emailUsuario'
                    AND senhaUsuario = md5('$senhaUsuario') ";

    //Executa a QUERY
    $efetuarLogin = mysqli_query($conn, $buscarLogin);

    //Verifica se a consulta encontrou algum registro associado
    if($registro = mysqli_fetch_assoc($efetuarLogin)){
        //Criar variáveis de sessão
        $_SESSION['idUsuario']    = $registro['idUsuario'];
        $_SESSION['nomeUsuario']  = $registro['nomeUsuario'];
        $_SESSION['emailUsuario'] = $registro['emailUsuario'];
        $_SESSION['nivelUsuario'] = $registro['nivelUsuario'];
        $_SESSION['logado']       = true;

        //Redireciona o usuário para a página inicial
        header("Location: index.php");
        exit();
    }
    else{
        //Redireciona o usuário para a o formLogin
        header("Location: formLogin.php?erroLogin=dadosInvalidos");
        exit();
    }


?>