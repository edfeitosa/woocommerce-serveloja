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
        $this->title              = 'Serveloja';
        $this->description        = 'Realize pagamentos com cartões de crédito através da Serveloja.';
        $this->order_button_text  = __('Pagar agora', 'woocommerce-serveloja');

        // forms
        $this->init_form_fields();

        // settings
        $this->init_settings();

        // veriaveis do form
        $this->checkbox = $this->get_option('checkbox');

        // $this->has_fields = true;

        // actions principais
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        //add_action( 'wp_enqueue_scripts', array( $this, 'receipt_page' ) );
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

    /* public function payment_fields() {
        wc_get_template( 'pagina-recebimento.php', array(), 'woocommerce/serveloja/', WC_Serveloja::get_templates_path() );
    } */

    function process_payment($order_id) {
        global $woocommerce;
        $order = new WC_Order($order_id);
    
        /* grava status
        $order->update_status('on-hold', __('Pagamento realizado através da Serveloja, com cartão crédito', 'woocommerce-serveloja'));
    
        // reduz estoque, se houver
        $order->reduce_order_stock();
    
        // remove produtos do carrinho após conclusão
        $woocommerce->cart->empty_cart(); */
    
        // redirecionamento após conclusão

        if (isset($_POST['finalizar'])) {

        } else {
            return array(
                'result' => 'success',
                'execute' => $this->receipt_page()
            );
        }


        /* return array(
            'result' => 'success',
            'redirect' => add_query_arg(
                'order', $order->id, 
                add_query_arg(
                    'key',
                    $order->order_key,
                    get_permalink(get_option())
                )
            )
        ); */
    }

    public function receipt_page($order_id) {
        global $woocommerce;
        $order = new WC_Order($order_id);
        wc_get_template('pagina-recebimento.php', array(
            'cancel_order_url' => $order->get_cancel_order_url()
        ), 'woocommerce/serveloja/', WC_Serveloja::get_templates_path());
    }

    
} ?>