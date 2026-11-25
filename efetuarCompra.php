<?php include "header.php" ?>
<?php include "conexaoBD.php" ?>

<?php
    // Verifica o login e se a compra foi enviada por formulário.
    if (empty($_SESSION['logado'])) {
        echo "<div class='alert alert-warning text-center'>Faça login para comprar.</div>";
    }
    elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "<div class='alert alert-warning text-center'>Selecione um anúncio para comprar.</div>";
    }
    else {
        // Converte os identificadores para números inteiros.
        $idUsuario = (int) $_SESSION['idUsuario'];
        $idAnuncio = (int) ($_POST['idAnuncio'] ?? 0);
        // A transação garante que a compra e a finalização ocorram juntas.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        mysqli_begin_transaction($conn);

        try {
            // Bloqueia o anúncio durante a compra, evitando compras simultâneas.
            $buscarAnuncio = "SELECT * FROM Anuncios WHERE idAnuncio = $idAnuncio FOR UPDATE";
            $resultado = mysqli_query($conn, $buscarAnuncio);
            $anuncio = mysqli_fetch_assoc($resultado);

            if (!$anuncio || $anuncio['statusAnuncio'] !== 'disponivel') {
                echo "<div class='alert alert-warning text-center'>Anúncio indisponível.</div>";
                mysqli_rollback($conn);
            }
            elseif ((int) $anuncio['Usuarios_idUsuario'] === $idUsuario) {
                echo "<div class='alert alert-warning text-center'>Você não pode comprar seu próprio anúncio.</div>";
                mysqli_rollback($conn);
            }
            else {
                // Recupera o preço do banco, sem aceitar valores do formulário.
                $valorCompra = (float) $anuncio['valorAnuncio'];

                $registrarCompra = "INSERT INTO Compras (Usuarios_idUsuario, Anuncios_idAnuncio, dataCompra, horaCompra, valorCompra)
                                    VALUES ($idUsuario, $idAnuncio, CURDATE(), CURTIME(), $valorCompra)";

                mysqli_query($conn, $registrarCompra);
                $finalizarAnuncio = "UPDATE Anuncios SET statusAnuncio = 'finalizado' WHERE idAnuncio = $idAnuncio";

                mysqli_query($conn, $finalizarAnuncio);

                // Confirma as duas alterações no banco.
                mysqli_commit($conn);

                echo "<div class='alert alert-success text-center'>Compra realizada com sucesso!</div>";
            }
        }
        catch (mysqli_sql_exception $erro) {
            // Desfaz as alterações se alguma consulta falhar.
            mysqli_rollback($conn);
            echo "<div class='alert alert-danger text-center'>Não foi possível realizar a compra.</div>";
        }
    }
    echo "<a class='btn btn-dark' href='index.php'>Voltar para os anúncios</a>";
    mysqli_close($conn);
?>

<?php include "footer.php" ?>