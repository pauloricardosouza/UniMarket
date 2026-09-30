<?php
    include "header.php";
    include "conexaoBD.php";

    // 1. Lê o filtro escolhido no formulário.
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

// 2. Consulta todos os anúncios que atendem ao filtro, sem paginação.
// ORDER BY organiza os anúncios do mais recente para o mais antigo.
mysqli_set_charset($conn, 'utf8mb4');
$sqlAnuncios = "SELECT idAnuncio, fotoAnuncio, tituloAnuncio, valorAnuncio, statusAnuncio
                FROM anuncios $where
                ORDER BY dataAnuncio DESC, horaAnuncio DESC, idAnuncio DESC";
$resultadoAnuncios = mysqli_query($conn, $sqlAnuncios);
?>

<!-- O formulário envia o filtro para o próprio index.php usando GET. -->
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

<?php if (mysqli_num_rows($resultadoAnuncios) === 0): ?>
    <div class="alert alert-info text-center">Nenhum anúncio encontrado.</div>
<?php endif; ?>

<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
    <?php
    // 3. O while percorre os resultados. Cada registro gera um cartão.
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

<?php
mysqli_close($conn);
include "footer.php";
?>
