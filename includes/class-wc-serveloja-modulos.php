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
                    '<img src="' . plugins_url('assets/images/serveloja.png', dirname(__FILE__)) . '" alt="servloja" border="0" />' .
                '</a>' .
            '</div>' .
        '</div>';
        echo $html;
    }

    public function wcsvl_ferramentas() {
        $html = '<div class="barraFerramentas">' .
            '<div class="botao">' .
                '<a href="admin.php?page=home">' .
                    '<img src="' . plugins_url('assets/images/home.png', dirname(__FILE__)) . '" alt="serveloja" border="0" />' .
                '</a>' .
                '<br />' .
                'Página Inicial' .
                '<div class="clear"></div>' .
            '</div>' .

            '<div class="botao">' .
                '<a href="admin.php?page=configuracoes">' .
                    '<img src="' . plugins_url('assets/images/configuracoes.png', dirname(__FILE__)) . '" alt="serveloja" border="0" />' .
                '</a>' .
                '<br />' .
                'Configurações' .
                '<div class="clear"></div>' .
            '</div>' .

            '<div class="botao">' .
                '<a href="admin.php?page=cartoes">' .
                    '<img src="' . plugins_url('assets/images/cartoes.png', dirname(__FILE__)) . '" alt="serveloja" border="0" />' .
                '</a>' .
                '<br />' .
                'Cartões' .
                '<div class="clear"></div>' .
            '</div>' .

            '<div class="botao">' .
                '<a href="admin.php?page=wc-settings&tab=checkout&section=serveloja">' .
                    '<img src="' . plugins_url('assets/images/woo.png', dirname(__FILE__)) . '" alt="serveloja" border="0" />' .
                '</a>' .
                '<br />' .
                'Wocommerce' .
                '<div class="clear"></div>' .
            '</div>' .
        '</div>';
        echo $html;
    }

} ?>