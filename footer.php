            </div>
        </section>
        <!-- Rodapé -->
        <footer class="py-2 bg-dark fixed-bottom">
            <div class="container"><p class="m-0 text-center text-white">Sistema Web desenvolvido no curso Técnico em Informática para Internet do IFPR campus Telêmaco Borba &copy <?php echo date("Y"); ?> </p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>

        <script>
            const campoValor = document.getElementById('valorAnuncio');
            const campoNumerico = document.getElementById('valorAnuncioNumerico');

            // Executa a máscara apenas nas páginas que possuem os dois campos.
            if (campoValor && campoNumerico) {
                campoValor.addEventListener('input', function () {
                    // Remove tudo que não for número.
                    const numeros = campoValor.value.replace(/\D/g, '');

                    if (numeros === '') {
                        campoValor.value = '';
                        campoNumerico.value = '';
                        return;
                    }

                    // Os dois últimos dígitos representam os centavos.
                    const valor = Number(numeros) / 100;

                    campoValor.value = valor.toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });

                    // Envia ao PHP o número no formato esperado pelo banco.
                    campoNumerico.value = valor.toFixed(2);
                });
            }
        </script>
    </body>
</html>
