<?php function function_cartoes() { ?>

    <!-- scripts e estilos -->
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/style.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/forms.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/tabelas.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo PASTA_PLUGIN; ?>assets/scripts/scripts.js"></script>

    <!-- cabeçalho -->
    <div id="headerPlugin">
        <div id="logo">
            <a href="admin.php?page=index">
                <img src='<?php echo PASTA_PLUGIN; ?>assets/images/serveloja.png' alt='servloja' border='0' />
            </a>
        </div>
    </div>

    <?php $funcoes = new WC_Serveloja_Funcoes;
    $cartoes_salvos = $funcoes::cartoes_salvos();
    if (isset($_POST["salvar_cartoes"])) {
        echo $funcoes::insert_cartoes($_POST["posicao"], $_POST["car_cod"], $_POST["car_bandeira"], $_POST["car_parcelas"]);
    } ?>

    <h1>Cartões de Crédito</h1>
    <h2>
        Selecione as bandeiras com as quais você irá receber pagamentos
    </h2>

    <div class="clear"></div>

    <p><i>Selecione os cartões que você utilizará para receber pagamentos em sua loja virtual. Após concluir, clique no botão <b>"Salvar"</b>.</i></p>

    <?php $cartoes = $funcoes::lista_cartoes(); ?>
    
    <form method="post" action="" name="cartoes">
        <?php echo $funcoes::tabela_cartoes($cartoes, $cartoes_salvos); ?>
        <div class="clear"></div>
        <input type="submit" class="submit" name="salvar_cartoes" value="Salvar" name="salvar" />
    </form>

    <div class="clear"></div>

<?php } ?>