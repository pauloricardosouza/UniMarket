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

    // 5. Verifica se a consulta não retornou anúncios.
    if(mysqli_num_rows($resultadoAnuncios) === 0){
        echo "<div class='alert alert-info text-center'>Nenhum anúncio encontrado.</div>";
    }

    //Abre uma linha que organizará os cards.
    echo "<div class='row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center'>";

        // 6. Algoritmo que percorre os resultados. Cada registro gera um card.
        while($anuncio = mysqli_fetch_assoc($resultadoAnuncios)) {
            //Converte caracteres especiais para exibí-los como texto no HTML.
            $idAnuncio     = (int) $anuncio['idAnuncio'];
            $fotoAnuncio   = htmlspecialchars($anuncio['fotoAnuncio'], ENT_QUOTES, 'UTF-8', false);
            $tituloAnuncio = htmlspecialchars($anuncio['tituloAnuncio'], ENT_QUOTES, 'UTF-8', false);
            //Formata o preço no padrão brasileiro. Exemplo: R$ 1.250,00
            $valorAnuncio  = number_format($anuncio['valorAnuncio'], 2, ',', '.');

            echo "
                <div class='col mb-5'>
                    <div class='card h-100'>
            ";

            //Exibe o aviso apenas em anúncios finalizados.
            if($anuncio['statusAnuncio'] === 'finalizado'){
                echo "<div class='badge bg-dark text-white position-absolute' style='top: 0.5rem; right: 0.5rem'>Anúncio Finalizado</div>";
            }

            //Exibe o card com foto, título, valor e botão "Visualizar Anúncio".
            echo "
                <img class='card-img-top' src='$fotoAnuncio' title='Foto de $tituloAnuncio' style='height: 200px; object-fit: cover;'>
                <div class='card-body p-4'>
                    <div class='text-center'>
                        <h5 class='fw-bolder'>$tituloAnuncio</h5>
                        R$ $valorAnuncio
                    </div>
                </div>
                <div class='card-footer p-4 pt-0 border-top-0 bg-transparent'>
                    <div class='text-center'>
                        <a class='btn btn-outline-dark mt-auto' href='visualizarAnuncio.php?idAnuncio=$idAnuncio' title='Ver mais detalhes do anúncio'>
                            <i class='bi bi-eye'></i> Visualizar Anúncio
                        </a>
                    </div>
                </div>
            ";

            echo "
                    </div>
                </div>
            ";

        }

    //Fecha a linha dos cards.
    echo "</div>";

    //Encerra a conexão com o Banco de Dados
    mysqli_close($conn);

?>
            
<?php include "footer.php" ?>
 