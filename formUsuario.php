<?php include "header.php" ?>

    <div class="d-flex justify-content-center mb-3">
        <h2>Formulário para Cadastro de Usuário</h2>
    </div>

    <div class="d-flex justify-content-center mb-3">
        <form action="#actionUsuario.php" method="POST" class="was-validated">

            <div class="form-floating mt-3 mb-3">
                <input type="text" name="nomeUsuario" id="nomeUsuario" placeholder="Nome Completo" class="form-control" required>
                <label for="nomeUsuario">Nome</label>
                <div class="valid-feedback"></div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-floating mt-3 mb-3">
                <input type="email" name="emailUsuario" id="emailUsuario" placeholder="Email" class="form-control" required>
                <label for="emailUsuario">Email</label>
                <div class="valid-feedback"></div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-floating mt-3 mb-3">
                <input type="password" name="senhaUsuario" id="senhaUsuario" placeholder="Senha" class="form-control" minlength="6" maxlength="8" required>
                <label for="senhaUsuario">Senha</label>
                <div class="valid-feedback"></div>
                <div class="invalid-feedback"></div>
            </div>

            <button type="submit" class="btn btn-outline-dark">Cadastrar</button>

        </form>

    </div>

<?php include "footer.php" ?>