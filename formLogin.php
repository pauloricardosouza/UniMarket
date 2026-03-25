<?php include "header.php" ?>

    <div class="d-flex justify-content-center mb-3">
        <h2>Acessar o Sistema:</h2>
    </div>

    <div class="d-flex justify-content-center mb-3">
        <form action="#actionLogin.php" method="POST" class="was-validated">

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

            <button type="submit" class="btn btn-outline-dark">Login</button>

        </form>

    </div>

    <div class="d-flex justify-content-center mb-3">
        <p>Ainda não é cadastrado? <a href="formUsuario.php" title="Cadastrar-se">Clique aqui!</a>&nbsp<i class="bi bi-emoji-smile"></i></p>
    </div>

<?php include "footer.php" ?>