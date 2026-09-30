<?php
    include "header.php";
    include "conexaoBD.php";

    // 1. Define quantos anúncios serão exibidos em cada página.
    $anunciosPorPagina = 8;

    // 2. Lê a página da URL. Exemplo: index.php?pagina=2
    // Aceita apenas números inteiros positivos. Sem uma página válida, usa a primeira.
    $pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);
    if (!$pagina || $pagina < 1) {
        $pagina = 1;
    }

    // 3. Lê o filtro escolhido no formulário.
    $filtro = $_GET['filtrarAnuncios'] ?? 'todos';
    $where = "";

    // A condição SQL usa valores fixos, nunca o texto recebido diretamente da URL.
    if ($filtro === 'disponivel') {
        $where = "WHERE statusAnuncio = 'Disponível'";
    } elseif ($filtro === 'finalizado') {
        $where = "WHERE statusAnuncio = 'Finalizado'";
    } else {
        $filtro = 'todos';
    }

    // 4. Conta os anúncios que atendem ao filtro.
    mysqli_set_charset($conn, 'utf8mb4');
    $sqlTotal = "SELECT COUNT(*) AS total FROM anuncios $where";
    $resultadoTotal = mysqli_query($conn, $sqlTotal);
    $totalAnuncios = (int) mysqli_fetch_assoc($resultadoTotal)['total'];

    // ceil() arredonda para cima: 17 anúncios / 8 = 3 páginas.
    // max() mantém a página 1 mesmo quando não existem anúncios.
    $totalPaginas = max(1, (int) ceil($totalAnuncios / $anunciosPorPagina));

    // Se alguém informar uma página maior que o total, exibe a última página.
    if ($pagina > $totalPaginas) {
        $pagina = $totalPaginas;
    }

    // 5. Calcula quantos registros devem ser pulados.
    // Página 1: pula 0; página 2: pula 8; página 3: pula 16.
    $inicio = ($pagina - 1) * $anunciosPorPagina;

    // LIMIT limita a quantidade; OFFSET indica quantos registros serão pulados.
    // Exibe os mais recentes primeiro. O ID desempata anúncios com a mesma data/hora.
    // Os números inseridos na consulta são inteiros calculados pelo PHP.
    $sqlAnuncios = "SELECT idAnuncio, fotoAnuncio, tituloAnuncio, valorAnuncio, statusAnuncio
                    FROM anuncios $where
                    ORDER BY dataAnuncio DESC, horaAnuncio DESC, idAnuncio DESC
                    LIMIT $anunciosPorPagina OFFSET $inicio";
    $resultadoAnuncios = mysqli_query($conn, $sqlAnuncios);
?>

<!-- Ao aplicar outro filtro, o formulário volta à primeira página. -->
<div class="row justify-content-center mb-4">
    <div class="col-md-6">
        <form action="index.php" method="get">
            <label for="filtrarAnuncios" class="form-label">Filtrar anúncios</label>
            <select class="form-select" id="filtrarAnuncios" name="filtrarAnuncios">
                <option value="todos" <?= $filtro === 'todos' ? 'selected' : '' ?>>Exibir todos os Anúncios</option>
                <option value="disponivel" <?= $filtro === 'disponivel' ? 'selected' : '' ?>>Exibir apenas Anúncios Disponíveis</option>
                <option value="finalizado" <?= $filtro === 'finalizado' ? 'selected' : '' ?>>Exibir apenas Anúncios Finalizados</option>
            </select>
            <button type="submit" class="btn btn-outline-dark mt-2">Filtrar</button>
        </form>
    </div>
</div>

<?php if ($totalAnuncios === 0): ?>
    <div class="alert alert-info text-center">Nenhum anúncio encontrado.</div>
<?php else: ?>
    <p class="text-center text-muted">Página <?= $pagina ?> de <?= $totalPaginas ?> — <?= $totalAnuncios ?> anúncio(s)</p>
<?php endif; ?>

<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
    <?php
    // 6. O while percorre os resultados. Cada registro gera um cartão.
    while ($anuncio = mysqli_fetch_assoc($resultadoAnuncios)):
        // htmlspecialchars() impede que os dados sejam interpretados como HTML.
        // double_encode=false preserva textos que o cadastro já salvou como entidades.
        $titulo = htmlspecialchars($anuncio['tituloAnuncio'], ENT_QUOTES, 'UTF-8', false);
        $foto = htmlspecialchars($anuncio['fotoAnuncio'], ENT_QUOTES, 'UTF-8', false);
    ?>
        <div class="col mb-5">
            <div class="card h-100">
                <?php if ($anuncio['statusAnuncio'] === 'Finalizado'): ?>
                    <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Anúncio Finalizado</div>
                <?php endif; ?>

                <img class="card-img-top" src="<?= $foto ?>" alt="<?= $titulo ?>" style="height: 200px; object-fit: cover;" />
                <div class="card-body p-4">
                    <div class="text-center">
                        <h5 class="fw-bolder"><?= $titulo ?></h5>
                        <!-- Formata o preço no padrão brasileiro. -->
                        R$ <?= number_format((float) $anuncio['valorAnuncio'], 2, ',', '.') ?>
                    </div>
                </div>
                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                    <div class="text-center">
                        <!-- A página de detalhes ainda não existe no projeto.
                             Quando for criada, use: visualizarAnuncio.php?idAnuncio=<?= (int) $anuncio['idAnuncio'] ?> -->
                        <button class="btn btn-outline-dark mt-auto" type="button" disabled title="Página de detalhes ainda não implementada">
                            <i class="bi bi-eye"></i> Visualizar Anúncio
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php if ($totalPaginas > 1): ?>
    <!-- 7. Os links mantêm o filtro escolhido ao trocar de página. -->
    <nav aria-label="Paginação dos anúncios">
        <ul class="pagination justify-content-center flex-wrap">
            <?php if ($pagina > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?pagina=<?= $pagina - 1 ?>&amp;filtrarAnuncios=<?= $filtro ?>">Anterior</a>
                </li>
            <?php endif; ?>

            <?php for ($numero = 1; $numero <= $totalPaginas; $numero++): ?>
                <li class="page-item <?= $numero === $pagina ? 'active' : '' ?>">
                    <?php if ($numero === $pagina): ?>
                        <span class="page-link" aria-current="page"><?= $numero ?></span>
                    <?php else: ?>
                        <a class="page-link" href="index.php?pagina=<?= $numero ?>&amp;filtrarAnuncios=<?= $filtro ?>"><?= $numero ?></a>
                    <?php endif; ?>
                </li>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?pagina=<?= $pagina + 1 ?>&amp;filtrarAnuncios=<?= $filtro ?>">Próxima</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php
mysqli_close($conn);
include "footer.php";
?>
