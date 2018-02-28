<?php function wcsvl_function_home() {

    // cabeçalho
    WC_Serveloja_Modulos::wcsvl_cabecalho(); ?>

    <h1>Woocommerce Serveloja</h1>
    
    <h2>
        Clique sobre um dos ícones para iniciar
    </h2>

    <div class="clear"></div>

    <div class="icon">
        <a href="admin.php?page=configuracoes">
            <img src="<?php echo plugins_url('assets/images/configuracoes.png', dirname(__FILE__)); ?>" alt="configuracoes" />
        </a>
        <br />
        <h2>Configurações</h2>
        <div class="subtitulo">
            Configure sua aplicação antes de começar a usar
        </div>
    </div>

    <div class="icon">
        <a href="admin.php?page=cartoes">
            <img src="<?php echo plugins_url('assets/images/cartoes.png', dirname(__FILE__)); ?>" alt="cartoes" />
        </a>
        <br />
        <h2>Cartões de Crédito</h2>
        <div class="subtitulo">
            Informe os cartões de crédito com os quais irá receber pagamentos
        </div>
    </div>

    <div class="icon">
        <a href="admin.php?page=wc-settings&tab=checkout&section=serveloja">
            <img src="<?php echo plugins_url('assets/images/woo.png', dirname(__FILE__)); ?>" alt="woocommerce" />
        </a>
        <br />
        <h2>WooCommerce</h2>
        <div class="subtitulo">
            Configurações da Serveloja no WooCommerce
        </div>
    </div>

<?php } ?>