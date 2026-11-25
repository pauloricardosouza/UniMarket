<?php include "header.php" ?>
<?php include "conexaoBD.php" ?>

<?php
    // Recebe o ID como inteiro. Se não for informado, utiliza zero.
    $idAnuncio = (int) ($_GET['idAnuncio'] ?? 0);

    // Busca o anúncio e os dados de seu anunciante.
    mysqli_set_charset($conn, 'utf8mb4');

    $buscarAnuncio = "
                        SELECT Anuncios.*, Usuarios.nomeUsuario, Usuarios.cidadeUsuario
                        FROM Anuncios
                        INNER JOIN Usuarios
                        ON Anuncios.Usuarios_idUsuario = Usuarios.idUsuario
                        WHERE Anuncios.idAnuncio = $idAnuncio
                     ";

    $resultadoAnuncio = mysqli_query($conn, $buscarAnuncio);
    $anuncio = mysqli_fetch_assoc($resultadoAnuncio);

    if ($anuncio) {
        // Prepara os dados para exibição segura no HTML.
        $valor = number_format($anuncio['valorAnuncio'], 2, ',', '.');

        foreach ($anuncio as $campo => $conteudo) {
            $anuncio[$campo] = htmlspecialchars($conteudo, ENT_QUOTES, 'UTF-8', false);
        }

        echo "
            <div class='row'>
                <div class='col-lg-6 mb-4'>
                    <img class='img-fluid rounded'
                         src='{$anuncio['fotoAnuncio']}'
                         alt='{$anuncio['tituloAnuncio']}'>
                </div>

                <div class='col-lg-6'>
                    <p class='text-muted'>{$anuncio['categoriaAnuncio']}</p>
                    <h2>{$anuncio['tituloAnuncio']}</h2>
                    <p class='fs-3 fw-bold'>R$ $valor</p>
                    <p class='lead'>{$anuncio['descricaoAnuncio']}</p>
                    <hr>
                    <p><strong>Anunciante:</strong> {$anuncio['nomeUsuario']}</p>
                    <p><strong>Cidade:</strong> {$anuncio['cidadeUsuario']}</p>
        ";

        // Exibe o botão de acordo com o status do anúncio e a sessão.
        if ($anuncio['statusAnuncio'] === 'finalizado') {
            echo "<button class='btn btn-secondary' disabled>Anúncio Finalizado</button>";
        }
        elseif (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
            // A funcionalidade de compra será desenvolvida posteriormente.
            echo "
                <form action='efetuarCompra.php' method='post' class='d-inline'>
                    <input type='hidden' name='idAnuncio' value='$idAnuncio'>
                    <button type='submit' class='btn btn-outline-dark'>
                        <i class='bi bi-bag-check'></i> Comprar
                    </button>
                </form>
            ";
        }
        else {
            echo "<a class='btn btn-outline-dark' href='formLogin.php'>Faça login para comprar</a>";
        }

        echo "
                    <a class='btn btn-outline-secondary' href='index.php'>Voltar</a>
                </div>
            </div>
        ";
    }
    else {
        echo "
            <div class='alert alert-warning text-center'>Anúncio não encontrado.</div>
            <a class='btn btn-dark' href='index.php'>Voltar</a>
        ";
    }

    mysqli_close($conn);
?>

<?php include "footer.php" ?>