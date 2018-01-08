<?php
/**
 * WooCommerce Serveloja Gateway class.
 *
 * Extende as funções de pagamento, utilizando os serviços da Serveloja.
 *
 * @class   WC_Serveloja_Gateway
 * @extends WC_Payment_Gateway
 * @version 1.0.0
 * @author  Eduardo Feitosa
 */

if (!defined( 'ABSPATH' )) {
    exit;
}

class WC_Serveloja_Gateway extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'serveloja';
        $this->icon               = apply_filters('woocommerce_serveloja_icon', plugins_url( 'assets/images/serveloja-verde.png', plugin_dir_path( __FILE__ )));
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

        // actions principais
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_receipt_' . $this->id, array( $this, 'receipt_page'));
    }

    // form na administração do woocommerce
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

    private function modal() {
        wc_enqueue_js('
            $("#bgModal").hide();
            function HtmlModal(tipo, titulo, mensagem, url) {
                var reply = "";
                reply += "<div id=\'modal\' class=\'modal sombra\'>" +
                    "<div id=\'cabecalho\'>" + titulo + "</div>" +
                    "<div id=\'icone\'><img src=\'' . PASTA_PLUGIN .'assets/images/" + tipo + ".png\' alt=\'icone\' /></div>" +
                    "<div class=\'resposta\'>" + mensagem + "</div>";
                if (tipo == "duvida") {
                    reply += "<div class=\'ok\' id=\'cancela\'>Não</div>" +
                        "<div class=\'ok\' id=\'okConf\' style=\'margin-right: 120px;\'>Sim</div>";
                } else {
                    reply += "<div class=\'ok\' id=\'ok\'>Ok</div>";
                }
                reply += "</div>";
                return reply;
            }
            
            function Modal(tipo, titulo, mensagem, url) {
                $("#bgModal").fadeIn();
                setTimeout(function () {
                    $("#bgModal").html(HtmlModal(tipo, titulo, mensagem, url));
                }, 300);
                $("#ok, #okConf, #cancela").live("click", function () {
                    console.log("Funciona aqui!");
                    $("#bgModal").html("");
                    $("#bgModal").fadeOut();
                });
            }
        ');
    }

    private function cpf_cnpj($id) {
        wc_enqueue_js('
            $(document).ready(function () {
                $("#' . $id . '").mask("999.999.999-99?99999");
                $("#' . $id . '").live("keyup", function (e) {
                    var query = $(this).val().replace(/[^a-zA-Z 0-9]+/g,"");
                    if (query.length == 11) {
                        $("#' . $id . '").mask("999.999.999-99?99999");
                    }
                    if (query.length == 14) {
                        $("#' . $id . '").mask("99.999.999/9999-99");
                    }
                });
            });
        ');
    }

    private function mascaraValor() {
        wc_enqueue_js('
            function mascaraValor(value, str_cifrao) {
                return str_cifrao + " " + value.formatMoney(2, ",", ".");
            }

            Number.prototype.formatMoney = function (c, d, t) {
                var n = this,
                    c = isNaN(c = Math.abs(c)) ? 2 : c,
                    d = d == undefined ? "." : d,
                    t = t == undefined ? "," : t,
                    s = n < 0 ? "-" : "",
                    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
                    j = (j = i.length) > 3 ? j % 3 : 0;
                return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            };
        ');
    }

    private function detalhes_cartao($total) {
        echo $this->mascaraValor();
        wc_enqueue_js('
            $(document).ready(function () {
                $("#exibeCartao").hide();
                $("input[name=bandeira_cartao]:checked").live("click", function() {
                    var isChecked = $(this).val();
                    if (isChecked) {
                        $("#exibeCartao").show();
                        var valor = ' . $total . ';
                        var qtd = isChecked.split("-");
                        var parcela = valor / qtd[0];
                        var imagem = "<img class=\'img_detalhes\' src=\'' . PASTA_PLUGIN .'assets/images/" + qtd[1].toLowerCase() + ".png\' alt=\'" + qtd[1].toLowerCase() + "\' />";
                        var select = "<div class=\'dir_detalhes\'>";
                            select += "<div class=\'tituloInput\' style=\'margin-top:0px;\'>Selecione a quantidade de parcelas</div>";
                            select += "<select class=\'select input_maior select_sborda\'>";
                                for(var i = 1; i <= qtd[0]; i++) {
                                    select += "<option value=\'" + i + "\'>" + i + "x - " + mascaraValor((valor / i), "R$") + "</option>";
                                }
                            select += "</select>";
                        select += "</div>";
                        $("#exibeCartao").html(imagem + select);
                        return true;
                    }
                });
            });
        ');
    }

    private function lista_cartoes() {
        $cartoes_banco = WC_Serveloja_Funcoes::cartoes_salvos();
        $lista = "<table cellspacing='0' cellpadding='0' class='tabela'>";
            foreach ($cartoes_banco as $row) {
                $lista .= "<tr>" .
                    "<td class='celulabody' style='width: 20%;'><img class='img_tabela_client' src='" . PASTA_PLUGIN . "assets/images/" . strtolower($row->car_bandeira) . ".png' title='" . ucfirst(strtolower($row->car_bandeira)) . "' alt='" . strtolower($row->car_bandeira) . "' /></td>" .
                    "<td class='celulabody' style='width: 60%;'>" . ucfirst(strtolower($row->car_bandeira)) . "</td>" .
                    "<td class='celulabody celulacentralizar' style='width: 20%;'><input id='" . strtolower($row->car_bandeira) . "' type='radio' name='bandeira_cartao' value='" . strtolower($row->car_parcelas) . "' /></td>" .
                "</tr>";
            }
        $lista .= "</table>";
        return $lista;
    }

    private function modal_payment($order_id) {
        $order = wc_get_order($order_id);
        echo $this->cpf_cnpj('cpfcnpjcomprador');
        echo $this->detalhes_cartao($order->get_total());
        wc_enqueue_js('
            $("#bgModal").fadeIn();
            var reply = "";
            reply += "<div id=\'formPagamento\' class=\'sombra\'>" +
                "<div id=\'cabecalho\'>" +
                    "<div id=\'logo\'><img src=\'' . PASTA_PLUGIN .'assets/images/serveloja.png\' alt=\'serveloja\' /></div>" +
                    "<div id=\'cancelar\' title=\'Cancelar e voltar para o carrinho\'>" +
                        "<a href=\'' . esc_url($order->get_cancel_order_url()) . '\'>" +
                            "<img src=\'' . PASTA_PLUGIN .'assets/images/fechar.png\' alt=\'serveloja\' style=\'width:100%;\' />" +
                        "</a>" +
                    "</div>" +
                "</div>" +
                "<div class=\'clear\'></div>" +
                "<p>Todos os campos marcados com <b>(*)</b>, são de preenchimento obrigatório</p>" +
                "<div id=\'colunaEsq\'>" +
                    "<div class=\'tituloInput\' style=\'margin-top: -5px;\'>Selecione um cartão (*)</div>" +
                    "' . $this->lista_cartoes() . '" +
                "</div>" +
                "<div id=\'colunaDir\'>" +
                    "<div id=\'exibeCartao\'></div>" +
                    "<div class=\'tituloInput\'>Titular do cartão - Como se encontra no mesmo (*)</div>" +
                    "<input type=\'text\' name=\'bandeira\' class=\'input input_maior input_sborda caixa_alta\' id=\'bandeira\' value=\'\' />" +
                    "<div class=\'tituloInput margin_top\'>CPF ou CNPJ do comprador (*)</div>" +
                    "<input type=\'text\' name=\'cpfcnpjcomprador\' class=\'input input_maior input_sborda\' id=\'cpfcnpjcomprador\' value=\'\' />" +
                    "<div class=\'tituloInput margin_top\'>Número do cartão (*)</div>" +
                    "<div class=\'coluna25\'>" +
                        "<input type=\'text\' name=\'numcartao1\' class=\'input input_maior input_sborda\' id=\'numcartao1\' maxlength=\'4\' value=\'\' />" +
                    "</div>" +
                    "<div class=\'coluna25\'>" +
                        "<input type=\'text\' name=\'numcartao2\' class=\'input input_maior input_sborda\' id=\'numcartao2\' maxlength=\'4\' value=\'\' />" +
                    "</div>" +
                    "<div class=\'coluna25\'>" +
                        "<input type=\'text\' name=\'numcartao3\' class=\'input input_maior input_sborda\' id=\'numcartao3\' maxlength=\'4\' value=\'\' />" +
                    "</div>" +
                    "<div class=\'coluna25\' style=\'width:24.9%;\'>" +
                        "<input type=\'text\' name=\'numcartao4\' class=\'input input_maior input_sborda\' id=\'numcartao4\' maxlength=\'4\' value=\'\' />" +
                    "</div>" +
                    "" +
                    "" +
                    "<br />" +
                    "<input class=\'float_right input_verde\' type=\'submit\' name=\'finalizar\' id=\'submit-serveloja-payment-form\' value=\'Finalizar\' />" +
                    "<a class=\'botao input_vermelho float_right\' href=\'' . esc_url( $order->get_cancel_order_url() ) . '\' title=\'Cancelar e voltar para o carrinho\'>' . __( 'Cancelar', 'woocommerce-serveloja' ) . '</a>";
                "</div>" +
            "</div>";
            $("#bgModal").html(reply);
        ');
    }

    public function generate_serveloja_form($order_id) {
        $order = wc_get_order($order_id);
        echo $this->modal_payment($order_id);
        return '<div id="bgModal"></div>';
    }
    
    // processa pagamento
    public function process_payment($order_id) {
        $order = wc_get_order($order_id);
        return array(
            'result'    => 'success',
            'redirect'	=> $order->get_checkout_payment_url(true)
        );
    }

    // página de recebimento
    public function receipt_page($order_id) {
        echo '<link type="text/css" href="' . PASTA_PLUGIN . 'assets/css/client.css" rel="stylesheet" />';
        echo '<link type="text/css" href="' . PASTA_PLUGIN . 'assets/css/forms.css" rel="stylesheet" />';
        echo '<link type="text/css" href="' . PASTA_PLUGIN . 'assets/css/tabela.css" rel="stylesheet" />';
        echo '<script type="text/javascript" src="' . PASTA_PLUGIN . 'assets/scripts/maskedinput.js"></script>';
        $order = wc_get_order( $order_id );
        echo $this->generate_serveloja_form($order_id);
    }
    
} ?>