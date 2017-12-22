<?php function function_cartoes() { ?>

    <!-- scripts e estilos -->
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/style.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/forms.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo PASTA_PLUGIN; ?>assets/scripts/scripts.js"></script>

    <?php require_once (PASTA_PLUGIN.'includes/class-wc-serveloja.php');
    $funcoes = new WC_Serveloja; ?>

    <!-- cabeçalho -->
    <div id="headerPlugin">
        <div id="logo">
            <a href="admin.php?page=index">
                <img src='<?php echo PASTA_PLUGIN; ?>assets/images/serveloja.png' alt='servloja' border='0' />
            </a>
        </div>
    </div>

    <h1>Cartões de Crédito</h1>
    <h2>
        Selecione as bandeiras com as quais você irá receber pagamentos
    </h2>

    <div class="clear"></div>

    <div class="painel">

        <?php $cartoes = $funcoes::lista_cartoes_api("Cartao/ObterBandeirasValidas", "get", ""); ?>

        <div class="clear"></div>

    </div>

<?php } ?>