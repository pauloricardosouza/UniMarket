<?php include "header.php" ?>
<?php include "conexaoBD.php" ?>

<?php
    // 1. Lê o filtro escolhido no formulário. Se nenhum filtro for enviado, exibe todos os anúncios.
    $filtro = $_GET['filtrarAnuncios'] ?? 'todos';
    $where  = "";

    // Define a condição da consulta usando valores fixos.
    if ($filtro === 'disponivel') {
        $where = "WHERE statusAnuncio = 'disponivel' ";
    }
    elseif ($filtro === 'finalizado') {
        $where = "WHERE statusAnuncio = 'finalizado' ";
    }
    else {
        $filtro = 'todos';
    }

    // 2. Consulta os anúncios, do mais recente para o mais antigo.
    mysqli_set_charset($conn, 'utf8mb4');

    $listarAnuncios = "SELECT idAnuncio, fotoAnuncio, tituloAnuncio, valorAnuncio, statusAnuncio
                       FROM Anuncios
                       $where
                       ORDER BY dataAnuncio DESC, horaAnuncio DESC, idAnuncio DESC
                      ";

    $resultadoAnuncios = mysqli_query($conn, $listarAnuncios);

    // 3. Define qual opção aparecerá selecionada no formulário.
    $selecionadoTodos      = "";
    $selecionadoDisponivel = "";
    $selecionadoFinalizado = "";

    if ($filtro === 'disponivel') {
        $selecionadoDisponivel = "selected";
    }
    elseif ($filtro === 'finalizado') {
        $selecionadoFinalizado = "selected";
    }
    else {
        $selecionadoTodos = "selected";
    }

    // 4. Exibe o formulário de filtro.
    echo "
        <div class='row justify-content-center mb-4'>
            <div class='col-md-6'>
                <form action='index.php' method='get'>
                    <select class='form-select' id='filtrarAnuncios' name='filtrarAnuncios'>
                        <option value='todos' $selecionadoTodos>Exibir todos os Anúncios</option>
                        <option value='disponivel' $selecionadoDisponivel>Exibir apenas Anúncios Disponíveis</option>
                        <option value='finalizado' $selecionadoFinalizado>Exibir apenas Anúncios Finalizados</option>
                    </select>

                    <button type='submit' class='btn btn-outline-dark mt-2'><i class='bi bi-filter'></i> Filtrar</button>
                </form>
            </div>
        </div>
    ";

?>
            
<?php include "footer.php" ?>
