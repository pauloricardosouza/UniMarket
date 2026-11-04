<?php include "header.php" ?>

<?php
    //Verifica se o método de envio do formAnuncio é POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //Cria variáveis para armazenar as informações passadas pelo $_POST[]
        $fotoAnuncio = $tituloAnuncio = $descricaoAnuncio = $categoriaAnuncio = $valorAnuncio = "";

        //Variável booleana para controle de erros de preenchimento
        $erroPreenchimento = false;

        //Variáveis para receber através da função date() a data e a hora do anúncio
        $dataAnuncio = date("Y-m-d");
        $horaAnuncio = date("H:i:s");

        //Validação do campo tituloAnuncio
        //Utiliza a função empty() para verificar se o $_POST["tituloAnuncio"] está vazio
        if(empty($_POST["tituloAnuncio"])){
            //Se estiver vazio, exibe alerta e altera a variável $erroPreenchimento para true
            echo "<div class='alert alert-warning text-center'>O campo <strong>TÍTULO DO ANÚNCIO</strong> é obrigatório!</div>";
            $erroPreenchimento = true;
        }
        else{
            //Se não estiver vazio, o dado é filtrado e armazenado na variável PHP
            $tituloAnuncio = filtrar_entrada($_POST["tituloAnuncio"]);
        }

        //Validação do campo descricaoAnuncio
        //Utiliza a função empty() para verificar se o $_POST["descricaoAnuncio"] está vazio
        if(empty($_POST["descricaoAnuncio"])){
            //Se estiver vazio, exibe alerta e altera a variável $erroPreenchimento para true
            echo "<div class='alert alert-warning text-center'>O campo <strong>DESCRIÇÃO DO ANÚNCIO</strong> é obrigatório!</div>";
            $erroPreenchimento = true;
        }
        else{
            //Se não estiver vazio, o dado é filtrado e armazenado na variável PHP
            $descricaoAnuncio = filtrar_entrada($_POST["descricaoAnuncio"]);
        }

        //Validação do campo categoriaAnuncio
        //Utiliza a função empty() para verificar se o $_POST["categoriaAnuncio"] está vazio
        if(empty($_POST["categoriaAnuncio"])){
            //Se estiver vazio, exibe alerta e altera a variável $erroPreenchimento para true
            echo "<div class='alert alert-warning text-center'>O campo <strong>CATEGORIA</strong> é obrigatório!</div>";
            $erroPreenchimento = true;
        }
        else{
            //Se não estiver vazio, o dado é filtrado e armazenado na variável PHP
            $categoriaAnuncio = filtrar_entrada($_POST["categoriaAnuncio"]);
        }

        //Validação do campo valorAnuncio
        //Utiliza a função empty() para verificar se o $_POST["valorAnuncio"] está vazio
        if(empty($_POST["valorAnuncio"])){
            //Se estiver vazio, exibe alerta e altera a variável $erroPreenchimento para true
            echo "<div class='alert alert-warning text-center'>O campo <strong>VALOR DO ANÚNCIO</strong> é obrigatório!</div>";
            $erroPreenchimento = true;
        }
        else{
            //Se não estiver vazio, o dado é filtrado e armazenado na variável PHP
            $valorAnuncio = filtrar_entrada($_POST["valorAnuncio"]);
        }

        //Início da validação do campo fotoAnuncio
        $diretorio    = "assets/img/"; //Define para qual diretório as imagens serão movidas
        $fotoAnuncio  = $diretorio . basename($_FILES['fotoAnuncio']['name']); //Montar o nome a ser salvo no BD (assets/img/nomeDoArquivo.jpg)
        $tipoDaImagem = strtolower(pathinfo($fotoAnuncio, PATHINFO_EXTENSION)); //strtolower torna as letras minúsculas / pathinfo pega a extensão do arquivo
        $erroUpload   = false; //Variável para controle de erros do upload da fotoAnuncio

        //Verifica se o tamanho do arquivo é diferente de ZERO
        if($_FILES['fotoAnuncio']['size'] != 0){
            //Início das validações do campo fotoAnuncio

            //Verifica se o tamanho da foto é maior do que 5MB (MegaBytes) [medida em bytes]
            if($_FILES['fotoAnuncio']['size'] > 5000000){
                echo "<div class='alert alert-warning text-center'>O tamanho da <strong>FOTO</strong> deve ser menor do que 5MB!</div>";
                $erroUpload = true;
            }

            //Verifica se a imagem está nos formatos JPG, JPEG, PNG ou WEBP
            if($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp"){
                echo "<div class='alert alert-warning text-center'>A <strong>FOTO</strong> deve estar nos formatos JPG, JPEG, PNG ou WEBP!</div>";
                $erroUpload = true;
            }

            //Verifica se a imagem foi movida para o diretório (assets/img), utilizando a função move_uploaded_file()
            if(!move_uploaded_file($_FILES['fotoAnuncio']['tmp_name'], $fotoAnuncio)){
                echo "<div class='alert alert-danger text-center'>Erro ao tentar mover a <strong>FOTO</strong> para o diretório $diretorio!</div>";
                $erroUpload = true;
            }
        }
        else{
            echo "<div class='alert alert-warning text-center'>A <strong>FOTO</strong> é obrigatória!</div>";
            $erroUpload = true;
        }

        //Verifica se não há erros de preenchimento ou erros de upload da foto
        if(!$erroPreenchimento && !$erroUpload){

            //Cria uma variável para armazenar a QUERY que realiza a inserção de dados do Usuário na tabela Anuncios
            $inserirAnuncio = "INSERT INTO Anuncios (Usuarios_idUsuario, fotoAnuncio, tituloAnuncio, descricaoAnuncio, categoriaAnuncio, valorAnuncio, dataAnuncio, horaAnuncio, statusAnuncio)
                            VALUES ($idUsuario, '$fotoAnuncio', '$tituloAnuncio', '$descricaoAnuncio', '$categoriaAnuncio', '$valorAnuncio', '$dataAnuncio', '$horaAnuncio', 'disponivel')";

            //Inclui o arquivo de conexão com o Banco de Dados
            include "conexaoBD.php";

            //A função mysqli_connect() executa a QUERY no BD
            //Se conseguir executar a QUERY, exibe alerta de sucesso e a tabela com os dados cadastrados
            if(mysqli_query($conn, $inserirAnuncio)){

                echo "<div class='alert alert-success text-center'>Os dados do <strong>ANÚNCIO</strong> foram cadastrados com sucesso!</div>";
                echo "
                    <div class='container mt-3 mb-3'>
                        <div class='container mt-3 mb-3 text-center'>
                            <img src='$fotoAnuncio' title='Foto de $tituloAnuncio' style='width:150px' class='img-thumbnail'>
                        </div>
                        <table class='table'>
                            <tr>
                                <th>TÍTULO DO ANÚNCIO</th>
                                <td>$tituloAnuncio</td>
                            </tr>
                            <tr>
                                <th>DESCRIÇÃO DO ANÚNCIO</th>
                                <td>$descricaoAnuncio</td>
                            </tr>
                            <tr>
                                <th>CATEGORIA ANÚNCIO</th>
                                <td>$categoriaAnuncio</td>
                            </tr>
                            <tr>
                                <th>VALOR DO ANÚNCIO</th>
                                <td>$valorAnuncio</td>
                            </tr>
                            <tr>
                                <th>DATA DO ANÚNCIO</th>
                                <td>$dataAnuncio</td>
                            </tr>
                            <tr>
                                <th>HORA DO ANÚNCIO</th>
                                <td>$horaAnuncio</td>
                            </tr>
                            <tr>
                                <th>ID DO USUÁRIO ANUNCIANTE</th>
                                <td>$idUsuario</td>
                            </tr>
                        </table>
                    </div>
                ";
            }
            else{
                echo "<div class='alert alert-danger text-center'>Erro ao tentar cadastrar <strong>USUÁRIO</strong> no banco de dados $database!</div>";
            }
        }


    }
    else{
        //Usa a função header() para redirecionar o usuário para o formAnuncio.php
        header("location:formAnuncio.php");
    }

    //Função para filtrar entrada de dados
    function filtrar_entrada($dado){
        $dado = trim($dado); //Remove espaços desnecessários
        $dado = stripslashes($dado); //Remove barras invertidas
        $dado = htmlspecialchars($dado); //Converte caracteres especiais em entidades HTML

        //Após filtrado, o dado é retornado
        return($dado);
    }
?>

<?php include "footer.php" ?>