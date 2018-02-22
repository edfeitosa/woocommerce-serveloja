<?php function function_cartoes() { ?>

    <!-- scripts e estilos -->
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/style.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/forms.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/tabelas.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo PASTA_PLUGIN; ?>assets/scripts/scripts.js"></script>

    <!-- cabeçalho -->
    <?php WC_Serveloja_Modulos::cabecalho(); ?>

    <?php $funcoes = new WC_Serveloja_Funcoes;
    if (isset($_POST["salvar_cartoes"])) {
        echo $funcoes::insert_cartoes($_POST["posicao"], $_POST["car_cod"], $_POST["car_bandeira"], $_POST["car_parcelas"]);
    } ?>

    <h1>Cartões de Crédito</h1>

    <!-- barra de ferramentas -->
    <?php WC_Serveloja_Modulos::ferramentas(); ?>

    <div class="clear"></div>

    <h2>Selecione as bandeiras com as quais você irá receber pagamentos</h2>

    <div class="clear"></div>

    <p><i>Selecione os cartões que você utilizará para receber pagamentos em sua loja virtual. Após concluir, clique no botão <b>"Salvar"</b>.</i></p>

    <form method="post" action="" name="cartoes">
        <?php echo $funcoes::tabela_cartoes(); ?>
        <div class="clear"></div>
        <input type="submit" class="submit" name="salvar_cartoes" value="Salvar" name="salvar" />
    </form>

    <div class="clear"></div>

<?php } ?>