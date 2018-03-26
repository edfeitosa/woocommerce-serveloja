<?php function wcsvl_function_cartoes() {

    // cabeçalho
    WC_Serveloja_Modulos::wcsvl_cabecalho();

    $funcoes = new WC_Serveloja_Funcoes;
    if (isset($_POST["salvar_cartoes"])) {
        // campos 'sanatizados' antes da inserção no banco
        $salvar = $funcoes::wcsvl_insert_cartoes(
            $funcoes::sanitize_text_or_array($_POST["posicao"]),
            $funcoes::sanitize_text_or_array($_POST["car_cod"]),
            $funcoes::sanitize_text_or_array($_POST["car_bandeira"]),
            $funcoes::sanitize_text_or_array($_POST["car_parcelas"]),
            $_POST["_nonce_cartoes"]
        );

        if (!is_null($salvar["class"])) {
            echo "<div class='" . $salvar["class"] . "'>" .
                "<h3>" . $salvar["titulo"] . "</h3>" . $salvar["mensagem"] .
            "</div>";
        } else {
            echo $salvar;
        }
    } ?>

    <h1>Cartões de Crédito</h1>

    <!-- barra de ferramentas -->
    <?php WC_Serveloja_Modulos::wcsvl_ferramentas(); ?>

    <div class="clear"></div>

    <h2>Selecione as bandeiras com as quais você irá receber pagamentos</h2>

    <div class="clear"></div>

    <p><i>Selecione os cartões que você utilizará para receber pagamentos em sua loja virtual. Após concluir, clique no botão <b>"Salvar"</b>.</i></p>

    <form method="post" action="" name="cartoes">
        <input type="hidden" name="_nonce_cartoes" value="<?php echo wp_create_nonce('cartoes_user'); ?>" />
        <?php echo $funcoes::wcsvl_tabela_cartoes(); ?>
    </form>

    <div class="clear"></div>

<?php } ?>
