<?php
/**
 * WooCommerce Serveloja módulos.
 *
 * Barras de ferramentas da aplicação na área administrativa
 *
 * @class   WC_Serveloja_Modulos
 * @extends WC_Serveloja_Modulos
 * @version 1.0.0
 * @author  Eduardo Feitosa
 */

if (!defined( 'ABSPATH' )) {
    exit;
}

class WC_Serveloja_Modulos {

    public function wcsvl_cabecalho() {
        $html = '<div id="headerPlugin">' .
            '<div id="logo">' .
                '<a href="admin.php?page=home">' .
                    '<img src="' . WP_PLUGIN_URL . '/woocommerce-serveloja/assets/images/serveloja.png" alt="servloja" border="0" />' .
                '</a>' .
            '</div>' .
        '</div>';
        echo $html;
    }

    public function wcsvl_ferramentas() {
        $html = '<div class="barraFerramentas">' .
            '<div class="botao">' .
                '<a href="admin.php?page=home">' .
                    '<img src="' . PASTA_PLUGIN . 'assets/images/home.png" alt="serveloja" border="0" />' .
                '</a>' .
                '<br />' .
                'Página Inicial' .
                '<div class="clear"></div>' .
            '</div>' .

            '<div class="botao">' .
                '<a href="admin.php?page=configuracoes">' .
                    '<img src="' . PASTA_PLUGIN . 'assets/images/configuracoes.png" alt="configuracoes" title="Configurações" border="0" />' .
                '</a>' .
                '<br />' .
                'Configurações' .
                '<div class="clear"></div>' .
            '</div>' .

            '<div class="botao">' .
                '<a href="admin.php?page=cartoes">' .
                    '<img src="' . PASTA_PLUGIN . 'assets/images/cartoes.png" alt="cartoes" title="Cartões" border="0" />' .
                '</a>' .
                '<br />' .
                'Cartões' .
                '<div class="clear"></div>' .
            '</div>' .

            '<div class="botao">' .
                '<a href="admin.php?page=wc-settings&tab=checkout&section=serveloja">' .
                    '<img src="' . PASTA_PLUGIN . 'assets/images/woo.png" alt="woocommerce" title="Woocommerce" border="0" />' .
                '</a>' .
                '<br />' .
                'Wocommerce' .
                '<div class="clear"></div>' .
            '</div>' .
        '</div>';
        echo $html;
    }

} ?>