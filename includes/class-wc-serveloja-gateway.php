<?php 
if (!defined( 'ABSPATH' )) {
    exit;
}

class WC_Serveloja_Gateway extends WC_Payment_Gateway {

    public function __construct() {

        $this->id                 = 'serveloja';
        $this->icon               = apply_filters('woocommerce_serveloja_icon', plugins_url( 'assets/images/serveloja.png', plugin_dir_path( __FILE__ )));
        $this->method_title       = __('Serveloja', 'woocommerce-serveloja');
        $this->method_description = __('Aceite pagamentos com cartões de crédito através da Serveloja em sua loja virtual.', 'woocommerce-serveloja');
        $this->order_button_text  = __('Pagar agora', 'woocommerce-serveloja');
        
        // forms
        $this->init_form_fields();

        // settings
        $this->init_settings();

        // veriaveis do form
        $this->title = $this->get_option('title');

    }

    function init_form_fields() {
        $this->form_fields = array(
            'integration' => array(
				'title'       => __('Configuração da aplicação', 'woocommerce-serveloja'),
				'type'        => 'title',
				'description' => '',
			),
            'enabled' => array(
                'title'       => __('Habilitar/Desabilitar', 'woocommerce-serveloja'),
                'type'        => 'checkbox',
                'label'       => __('Utilizar <b>Serveloja Woocommerce</b> para receber pagamentos', 'woocommerce-serveloja'),
                'default'     => 'yes'
            )
        );
    }

    /* public function admin_options() { ?>

        <?php $this->save_info(); ?>

        <h2><?php _e('Serveloja', 'woocommerce-serveloja'); ?></h2>
        <p><?php _e('Aceite pagamentos com cartões de crédito através da Serveloja em sua loja virtual.', 'woocommerce-serveloja'); ?></p>
        
        <table class="form-table">
            <?php $this->generate_settings_html(); ?>
        </table>
        
        <?php
    }

    private function save_info() {
        if(isset($_POST['woocommerce_serveloja_cartoes'])) {
            print_r($_POST['woocommerce_serveloja_cartoes']);
        }
    } */
    
} ?>