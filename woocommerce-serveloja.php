<?php
/**
 * Plugin Name: Woocommerce Serveloja
 * Plugin URI: http://www.serveloja.com.br
 * Description: Plugin para realização de pagamentos via lojas virtuais com Woocommerce, utilizando soluções fornecidas pela Serveloja.
 * Version: 1.0
 * Author: TI Serveloja
 * Author URI: http://www.serveloja.com.br
**/

if (!defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WC_Serveloja')) {

    class WC_Serveloja {

        protected static $instance = null;

        private function __construct() {
            if (class_exists('WC_Payment_Gateway')) {
                $this->includes();
                add_filter('woocommerce_payment_gateways', array($this, 'add_gateway'));
                // add_filter('woocommerce_cancel_unpaid_order', array( $this, 'stop_cancel_unpaid_orders' ), 10, 2);
                add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links'));
            
                // define diretório do plugin
                define('PASTA_PLUGIN', WP_PLUGIN_URL.'/woocommerce-serveloja/');

                // define o arquivo de desisntalação e executa funções
                define('WP_UNINSTALL_PLUGIN', PASTA_PLUGIN.'uninstall.php');

                // cria tabelas no banco
                $this->create_db_table();
                register_activation_hook(__FILE__, 'create_db_table');

                // truncate nas tabelas quando desativa plugin
                $this->truncate_db_table();
                register_deactivation_hook(__FILE__, 'delete_db_table');
            } else {
				add_action('admin_notices', array( $this, 'woocommerce_missing_notice'));
			}
        }

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
        }
        
        // adiciona link na página de edição de plugins
		public function plugin_action_links( $links ) {
			$plugin_links   = array();
			$plugin_links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=serveloja' ) ) . '">' . __( 'Configurações', 'woocommerce-serveloja' ) . '</a>';
			return array_merge( $plugin_links, $links );
        }

		private function includes() {
			include_once dirname( __FILE__ ) . '/includes/class-wc-serveloja-gateway.php';
			include_once dirname( __FILE__ ) . '/includes/class-wc-serveloja-gateway.php';
        }
        
		public function add_gateway($methods) {
			$methods[] = 'WC_Serveloja_Gateway';
			return $methods;
		}

        private function create_db_table() {
            global $wpdb;
            $tabela_aplicacao = $wpdb->prefix . 'aplicacao';
            $tabela_cartoes = $wpdb->prefix . 'cartoes';
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $tabela_aplicacao (
                `apl_id` int(11) NOT NULL AUTO_INCREMENT,
                `apl_nome` varchar(32) NOT NULL,
                `apl_token` varchar(32) NOT NULL,
                `apl_prefixo` varchar(50),
                `apl_email` varchar(100),
                `apl_ambiente` varchar(1) NOT NULL,
                PRIMARY KEY (`apl_id`)
              ) $charset_collate;
              CREATE TABLE $tabela_cartoes (
                `car_id` int(11) NOT NULL AUTO_INCREMENT,
                `car_cod` varchar(32) NOT NULL,
                `car_bandeira` varchar(50) NOT NULL,
                `car_parcelas` varchar(3) NOT NULL,
                PRIMARY KEY (`car_id`)
              ) $charset_collate;
            ";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        private function truncate_db_table() {
            global $wpdb;
            $tabela_aplicacao = $wpdb->prefix . 'aplicacao';
            $tabela_cartoes = $wpdb->prefix . 'cartoes';
            $wpdb->query("TRUNCATE TABLE $tabela_aplicacao");
            $wpdb->query("TRUNCATE TABLE $tabela_cartoes");
            delete_option("serveloja");
            delete_site_option('serveloja');
        }
        
    }
    add_action('plugins_loaded', array('WC_Serveloja', 'get_instance'));
} ?>