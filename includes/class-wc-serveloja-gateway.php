<?php 
if (!defined( 'ABSPATH' )) {
    exit;
}

class WC_Serveloja_Gateway extends WC_Payment_Gateway {

    public function __construct() {

        $this->id = 'serveloja';
        $this->icon = apply_filters( 'woocommerce_serveloja_icon', plugins_url( 'assets/images/serveloja.png', plugin_dir_path( __FILE__ )));
        $this->method_title = __('Serveloja', 'woocommerce-serveloja');
        $this->method_description = __('Aceite pagamentos com cartões de crédito através da Serveloja em sua loja virtual.', 'woocommerce-serveloja');
        $this->order_button_text = __('Pagar', 'woocommerce-serveloja');
        
        // forms
        $this->init_form_fields();

        // settings
        $this->init_settings();

        // veriaveis do form
        $this->title = $this->get_option('title');
        $this->token_teste = $this->get_option('token_teste');
        $this->token_producao = $this->get_option('token_producao');
        $this->prefixo = $this->get_option('prefixo');
        $this->email = $this->get_option('email');
        $this->mastercard = $this->get_option('mastercard');
        $this->cartoes = $this->get_option('cartoes');

    }

    function init_form_fields() {
        $this->form_fields = array(
            'integration' => array(
				'title'       => __('Configuração da aplicação', 'woocommerce-serveloja'),
				'type'        => 'title',
				'description' => '',
			),
            'enabled' => array(
                'title' => __('Habilitar/Desabilitar', 'woocommerce-serveloja'),
                'type' => 'checkbox',
                'label' => __('Utilizar <b>Serveloja Woocommerce</b> para receber pagamentos', 'woocommerce-serveloja'),
                'default' => 'yes'
            ),
            'title' => array(
                'title'       => __('Nome da aplicação', 'woocommerce-serveloja'),
                'type'        => 'text',
                'description' => __('Informe o nome da aplicação onde será utilizada o recebimento com Serveloja Woocommerce.', 'woocommerce-serveloja'),
                'desc_tip'    => true,
                'placeholder' => 'Nome da aplicação',
                'default'     => __('', 'woocommerce-serveloja'),
            ),
            'token_teste' => array(
                'title'       => __('Token para teste', 'woocommerce-serveloja'),
                'type'        => 'text',
                'description' => __('Este token será usado para testes, não realizará transações em sua loja.', 'woocommerce-serveloja'),
                'desc_tip'    => true,
                'placeholder' => 'Informe aqui o token para teste',
                'default'     => __('', 'woocommerce-serveloja'),
            ),
            'token_producao' => array(
                'title'       => __('Token principal', 'woocommerce-serveloja'),
                'type'        => 'text',
                'description' => __('Este token será utilizado em transações em sua loja.', 'woocommerce-serveloja'),
                'desc_tip'    => true,
                'placeholder' => 'Informe aqui o token principal',
                'default'     => __('', 'woocommerce-serveloja'),
            ),
            'prefixo' => array(
                'title'       => __('Prefixo das transações', 'woocommerce-serveloja'),
                'type'        => 'text',
                'description' => __('Este prefixo servirá para você identificar suas transações.', 'woocommerce-serveloja'),
                'desc_tip'    => true,
                'placeholder' => 'Informe aqui o prefixo das transações',
                'default'     => __('', 'woocommerce-serveloja'),
            ),
            'email' => array(
                'title'       => __('E-mail', 'woocommerce-serveloja'),
                'type'        => 'text',
                'description' => __('Este token será utilizado para avisos de transações via e-mail.', 'woocommerce-serveloja'),
                'desc_tip'    => true,
                'placeholder' => 'Informe aqui o e-mail que será utilizado para comunicação',
                'default'     => __('', 'woocommerce-serveloja'),
            ),
            'cartoes' => array(
                'title'       => __('Cartões', 'woocommerce-serveloja'),
                'type'        => 'multiselect',
                'description' => __('Selecione as bandeiras dos cartões usadas para receber pagamentos.', 'woocommerce-serveloja'),
                'desc_tip'    => true,
                'options' => array(
                    '2'  => 'Amex',
                    '18' => 'Assomise',
                    '4'  => 'Diners',
                    '16' => 'Elo',
                    '10' => 'Fortbrasil',
                    '7'  => 'Hiper',
                    '3'  => 'Mastercard',
                    '6'  => 'Sorocred',
                    '1'  => 'Visa'
                ),
                'default'     => '6'
            )
        );
    }

    public function admin_options() { ?>

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
    }
    
} ?>