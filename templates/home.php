<?php function function_index() { ?>

    <!-- scripts e estilos -->
    <link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/style.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo PASTA_PLUGIN; ?>assets/scripts/scripts.js"></script>

    <!-- cabeçalho -->
    <div id="headerPlugin">
        <div id="logo">
            <a href="admin.php?page=index">
                <img src='<?php echo PASTA_PLUGIN; ?>assets/images/serveloja.png' alt='servloja' border='0' />
            </a>
        </div>
    </div>

    <h1>Woocommerce Serveloja</h1>
    <h2>
        Clique sobre um dos ícones para iniciar
    </h2>

    <div class="clear"></div>

    <div class="icon">
        <a href="admin.php?page=configuracoes">
            <img src="<?php echo PASTA_PLUGIN; ?>assets/images/configuracoes.png" alt="configuracoes" />
        </a>
        <br />
        <h2>Configurações</h2>
        <div class="subtitulo">
            Configure sua aplicação antes de começar a usar
        </div>
    </div>

    <div class="icon">
        <a href="admin.php?page=cartoes">
            <img src="<?php echo PASTA_PLUGIN; ?>assets/images/cartoes.png" alt="cartoes" />
        </a>
        <br />
        <h2>Cartões de Crédito</h2>
        <div class="subtitulo">
            Informe os cartões de crédito com os quais irá receber pagamentos
        </div>
    </div>

    <div class="icon">
        <a href="admin.php?page=wc-settings&tab=checkout&section=serveloja">
            <img src="<?php echo PASTA_PLUGIN; ?>assets/images/woo.png" alt="woocommerce" />
        </a>
        <br />
        <h2>WooCommerce</h2>
        <div class="subtitulo">
            Configurações da Serveloja no WooCommerce
        </div>
    </div>

<?php } ?>