<?php include "header.php" ?>
<?php include "conexaoBD.php" ?>

<?php
    //Recebe o ID como valor int. Se não for informado, utiliza zero.
    $idAnuncio = (int) ($_GET['idAnuncio'] ?? 0);

    //Query que busca o Anúncio e os dados do Anunciante
    mysqli_set_charset($conn, 'utf8mb4');

    $buscarAnuncio = "
                        SELECT Anuncios.*, Usuarios.nomeUsuario, Usuarios.cidadeUsuario
                        FROM Anuncios
                        INNER JOIN Usuarios
                        ON Anuncios.Usuarios_idUsuario = Usuarios.idUsuario
                        WHERE Anuncios.idAnuncio = $idAnuncio
    ";

    $resultadoAnuncio = mysqli_query($conn, $buscarAnuncio); //Executa a Query
    $anuncio          = mysqli_fetch_assoc($resultadoAnuncio); //Cria um array associativo

    if($anuncio) {
        //Prepara os dados para exibição segura no HTML
        $valorAnuncio = number_format($anuncio['valorAnuncio'], 2, ',', '.');

        foreach ($anuncio as $campo => $conteudo) {
            $anuncio[$campo] = htmlspecialchars($conteudo, ENT_QUOTES, 'UTF-8', false);
        }

        echo "
            <div class='row'>
                <div class='col-lg-6 mb-4'>
                    <img class='img-fluid rounded' src='{$anuncio['fotoAnuncio']}' alt='{$anuncio['tituloAnuncio']}'>
                </div>
                <div class='col-lg-6'>
                    <p class='text-muted'>{$anuncio['categoriaAnuncio']}</p>
                    <h2>{$anuncio['tituloAnuncio']}</h2>
                    <p class='fs-3 fw-bold'>R$ $valorAnuncio</p>
                    <p class='lead'>{$anuncio['descricaoAnuncio']}</p>
                    <p><strong>Anunciante:</strong> {$anuncio['nomeUsuario']}</p>
                    <p><strong>Cidade:</strong> {$anuncio['cidadeUsuario']}</p>
        ";

        //Exibe botão comprar com base no status e sessão

        echo "
                </div>
            </div>
        ";

    }
    else{
        echo "
            <div class='alert alert-warning text-center'>Anúncio não encontrado.</div>
            <a class='btn btn-dark' href='index.php'>Voltar</a>
        ";
    }

?>

<?php include "footer.php" ?>