<?php
/**
 * WooCommerce Serveloja Gateway class.
 *
 * Extende as funções de pagamento, utilizando os serviços da Serveloja.
 *
 * @class   WCSVL_Serveloja_Gateway
 * @extends WCSVL_Payment_Gateway
 * @version 1.0.0
 * @author  Eduardo Feitosa
 */

if (!defined( 'ABSPATH' )) {
    exit;
}

class WCSVL_Serveloja_Gateway extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'serveloja';
        $this->icon               = apply_filters('woocommerce_serveloja_icon', plugins_url( 'assets/images/serveloja-verde.png', plugin_dir_path( __FILE__ )));
        $this->method_title       = __('Serveloja', 'woocommerce-serveloja');
        $this->method_description = __('Aceite pagamentos com cartões de crédito através da Serveloja em sua loja virtual.', 'woocommerce-serveloja');
        $this->title              = 'Serveloja';
        $this->description        = 'Realize pagamentos com cartões de crédito através da Serveloja.';
        $this->order_button_text  = __('Pagar agora', 'woocommerce-serveloja');

        // forms
        $this->WCSVL_init_form_fields();

        // settings
        $this->init_settings();

        // veriaveis do form
        $this->checkbox = $this->get_option('checkbox');

        // actions principais
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_receipt_' . $this->id, array( $this, 'receipt_page'));
    }

    // form na administração do woocommerce
    function WCSVL_init_form_fields() {
        $this->form_fields = array(
            'integration' => array(
				'title'       => __('Configuração da aplicação', 'woocommerce-serveloja'),
				'type'        => 'title',
				'description' => '',
			),
            'enabled' => array(
                'title'       => __('Habilitar/Desabilitar', 'woocommerce-serveloja'),
                'type'        => 'checkbox',
                'label'       => __('Utilizar <b>WooCommerce Serveloja</b> para receber pagamentos', 'woocommerce-serveloja'),
                'default'     => 'yes'
            )
        );
    }

    private function WCSVL_modal() {
        wc_enqueue_js('
            $("#bgModal").hide();
            function HtmlModal(tipo, titulo, mensagem, url) {
                var reply = "";
                reply += "<div id=\'modal\' class=\'modal modal_" + tipo +  " sombra\'>" +
                    "<div class=\'cabecalho cabecalho_" + tipo +  "\'>" + titulo + "</div>" +
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
            
            function Modal(tipo, titulo, mensagem, url, bg) {
                $("#" + bg).fadeIn();
                setTimeout(function () {
                    $("#" + bg).html(HtmlModal(tipo, titulo, mensagem, url));
                }, 300);
                $("#ok, #okConf, #cancela").live("click", function () {
                    $("#" + bg).html("");
                    $("#" + bg).fadeOut();
                    if (url != "") {
                        jQuery(window.document.location).attr("href", url);
                    }
                });
            }
        ');
    }

    private function WCSVL_cpf_cnpj() {
        wc_enqueue_js('
            function cpf_cnpj (id) {
                $(document).ready(function() {
                    $("#" + id).mask("999.999.999-99?99999");
                    $("#" + id).live("keyup", function (e) {
                        var query = $(this).val().replace(/[^a-zA-Z 0-9]+/g,"");
                        if (query.length == 11) {
                            $("#" + id).mask("999.999.999-99?99999");
                        }
                        if (query.length == 14) {
                            $("#" + id).mask("99.999.999/9999-99");
                        }
                    });
                });
            }
        ');
    }

    private function WCSVL_mascaras() {
        wc_enqueue_js('
            function mascaras(campo, mascara) {
                $(document).ready(function () {
                    $("#" + campo).mask(mascara);
                });
            }
        ');
    }

    private function WCSVL_mascaraValor() {
        wc_enqueue_js('
            function mascaraValor(value) {
                return value.formatMoney(2, ",", ".");
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

    private function WCSVL_detalhes_cartao($total) {
        echo $this->WCSVL_mascaraValor();
        wc_enqueue_js('
            $(document).ready(function () {
                $("#bgModal_interno").hide();
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
                            select += "<select class=\'select input_maior select_sborda coluna100\' id=\'QtParcela\' name=\'QtParcela\'>";
                                for (var i = 1; i <= qtd[0]; i++) {
                                    select += "<option value=\'" + i + "\'>" + i + "x - R$ " + mascaraValor(valor / i) + "</option>";
                                }
                            select += "</select>";
                        select += "</div>";
                        select += "<input type=\'hidden\' id=\'Bandeira\' name=\'Bandeira\' value=\'" + qtd[1] + "\' />";
                        select += "<input type=\'hidden\' id=\'Valor\' name=\'Valor\' value=\'" + mascaraValor(valor) + "\' />";
                        $("#exibeCartao").html(imagem + select);

                        var input_senha = "";
                        if (qtd[1] == "assomise") {
                            input_senha = "<div class=\'tituloInput margin_top\'>Senha do cartão (*)</div>";
                            input_senha += "<input type=\'password\' name=\'SenhaCartao\' class=\'input input_maior input_sborda caixa_alta\' id=\'SenhaCartao\' value=\'\' />";
                            $("#senha").html(input_senha);
                        } else {
                            input_senha += "<input type=\'hidden\' name=\'SenhaCartao\' value=\'\' />";
                            $("#senha").html(input_senha);
                        }

                        return true;
                    }
                });
            });
        ');
    }

    private function WCSVL_lista_cartoes() {
        $cartoes_banco = WCSVL_Serveloja_Funcoes::WCSVL_cartoes_salvos();
        $lista = "";
            if (count($cartoes_banco) > 0) {
                $lista .= "<table cellspacing='0' cellpadding='0' class='tabela'>";
                    foreach ($cartoes_banco as $row) {
                        $lista .= "<tr>" .
                            "<td class='celulabody' style='width: 20%;'><img class='img_tabela_client' src='" . PASTA_PLUGIN . "assets/images/" . strtolower($row->car_bandeira) . ".png' title='" . ucfirst(strtolower($row->car_bandeira)) . "' alt='" . strtolower($row->car_bandeira) . "' /></td>" .
                            "<td class='celulabody' style='width: 60%;'>" . ucfirst(strtolower($row->car_bandeira)) . "</td>" .
                            "<td class='celulabody celulacentralizar' style='width: 20%;'><input id='" . strtolower($row->car_bandeira) . "' type='radio' name='bandeira_cartao' value='" . strtolower($row->car_parcelas) . "-" . strtolower($row->car_cod) . "' /></td>" .
                        "</tr>";
                    }
                $lista .= "</table>";
            } else {
                $lista .= "<div class='alerta'>Nenhum cartão está disponível para uso. Entre em contato com o administrador da loja.</div>";
            }
        return $lista;
    }

    private function WCSVL_validacoes() {
        wc_enqueue_js('
            function validarCPF (cpf) {
                var Soma;
                var Resto;
                Soma = 0;

                cpf = cpf.replace(/[^\d]+/g,\'\');
            
                if (cpf == \'\') { return false; }

                if (cpf == "00000000000" || 
                    cpf == "11111111111" || 
                    cpf == "22222222222" || 
                    cpf == "33333333333" || 
                    cpf == "44444444444" || 
                    cpf == "55555555555" || 
                    cpf == "66666666666" || 
                    cpf == "77777777777" || 
                    cpf == "88888888888" || 
                    cpf == "99999999999") {
                    return false;
                }
                
                for (i=1; i<=9; i++) Soma = Soma + parseInt(cpf.substring(i-1, i)) * (11 - i);
                Resto = (Soma * 10) % 11;
                
                if ((Resto == 10) || (Resto == 11))  Resto = 0;
                if (Resto != parseInt(cpf.substring(9, 10)) ) return false;
                
                Soma = 0;
                for (i = 1; i <= 10; i++) Soma = Soma + parseInt(cpf.substring(i-1, i)) * (12 - i);
                Resto = (Soma * 10) % 11;
                
                if ((Resto == 10) || (Resto == 11))  Resto = 0;
                if (Resto != parseInt(cpf.substring(10, 11) ) ) return false;
                return true;
            }

            function validarCNPJ (cnpj) {
    
                cnpj = cnpj.replace(/[^\d]+/g,\'\');
            
                if (cnpj == \'\') { return false; }
                
                if (cnpj.length != 14) { return false; }

                if (cnpj == "00000000000000" || 
                    cnpj == "11111111111111" || 
                    cnpj == "22222222222222" || 
                    cnpj == "33333333333333" || 
                    cnpj == "44444444444444" || 
                    cnpj == "55555555555555" || 
                    cnpj == "66666666666666" || 
                    cnpj == "77777777777777" || 
                    cnpj == "88888888888888" || 
                    cnpj == "99999999999999") {
                    return false;
                }
                    
                // Valida DVs
                tamanho = cnpj.length - 2
                numeros = cnpj.substring(0, tamanho);
                digitos = cnpj.substring(tamanho);
                soma = 0;
                pos = tamanho - 7;
                for (i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) { pos = 9; }
                }
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(0)) { return false; }
                tamanho = tamanho + 1;
                numeros = cnpj.substring(0,tamanho);
                soma = 0;
                pos = tamanho - 7;
                for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) { pos = 9; }
                }
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(1)) { return false; }
                    
                return true;
            }

            function compareDatas(valCartao) { 
                var hoje = new Date();
                var dadosData = valCartao.split("/");
                var validade = new Date(dadosData[0] + "/01/" + dadosData[1]);
                if (validade > hoje) {
                    return true;
                } else {
                    $("#submit-serveloja-payment-form").hide();
                    return false;
                }
            }

            $(document).ready(function() {
                $("#nmTitular, #NrCartao, #DataValidade, #CodSeguranca, #DDDCelular, #NrCelular").live("click", function() {
                    var cpfCnpj = $("#CpfCnpjComprador").val();
                    if (cpfCnpj != "") {
                        var cpfCnpj = cpfCnpj.replace(/[^\d]+/g,\'\');
                        if (cpfCnpj.length == 11) {
                            if (validarCPF(cpfCnpj) == false) {
                                Modal("erro", "Algo está errado...", "Informe um número de CPF válido", "", "bgModal_interno");
                                $("#submit-serveloja-payment-form").hide();
                            }
                        } else if (cpfCnpj.length == 14) {
                            if (validarCNPJ(cpfCnpj) == false) {
                                Modal("erro", "Algo está errado...", "Informe um número de CNPJ válido", "", "bgModal_interno");
                                $("#submit-serveloja-payment-form").hide();
                            }
                        } else if (cpfCnpj.length != 11 || cpfCnpj.length != 14) {
                            Modal("erro", "Algo está errado...", "Informe um número de CPF ou CNPJ válido", "", "bgModal_interno");
                            $("#submit-serveloja-payment-form").hide();
                        }
                    }
                });

                $(document).ready(function() {
                    $("#nmTitular, #NrCartao, #CpfCnpjComprador, #CodSeguranca, #DDDCelular, #NrCelular").live("click", function() {
                        var DataValidade = $("#DataValidade").val();
                        if (DataValidade != "" && DataValidade != "__/____") {
                            if (compareDatas(DataValidade) == false) {
                                Modal("erro", "Algo está errado...", "A data de validade do cartão <b>(" + DataValidade + ")</b>, aparentemente já experiou", "", "bgModal_interno");
                            }
                        }
                    });
                });

                $("#submit-serveloja-payment-form").hide();
                $("#nmTitular, #NrCartao, #DataValidade, #CodSeguranca, #colunaEsq, #DDDCelular, #NrCelular, #CpfCnpjComprador").live("mousemove", function() {
                    var isChecked = $("input[name=bandeira_cartao]:checked").val();
                    if (!isChecked ||
                        $("#nmTitular").val() == "" ||
                        $("#NrCartao").val() == "" ||
                        $("#DataValidade").val() == "" ||
                        $("#CodSeguranca").val() == "" ||
                        $("#DDDCelular").val() == "" ||
                        $("#NrCelular").val() == "" ||
                        $("#CpfCnpjComprador").val() == ""
                    ) {
                        $("#submit-serveloja-payment-form").hide();
                    } else {
                        $("#submit-serveloja-payment-form").show();
                    }
                });

            });
        ');
    }

    private function WCSVL_alert_inicio_validacao() {
        wc_enqueue_js('
            $(document).ready(function() {
                $("#submit-serveloja-payment-form").live("click", function () {
                    Modal("alerta", "Validando...", "Estamos validando suas informações, aguarde alguns instantes", "", "bgModal_interno");
                });
            });
        ');
    }

    public function WCSVL_apl_authorization() {
        $authorization = (WCSVL_Serveloja_Funcoes::WCSVL_aplicacao() == "0") ? "" : WCSVL_Serveloja_Funcoes::WCSVL_aplicacao()[0]->apl_token;
        return $authorization;
    }

    public function WCSVL_apl_applicatioId() {
        $applicatioId = (WCSVL_Serveloja_Funcoes::WCSVL_aplicacao() == "0") ? "" : WCSVL_Serveloja_Funcoes::WCSVL_aplicacao()[0]->apl_nome;
        return $applicatioId;
    }

    private function WCSVL_form_payment($order_id) {
        $order = wc_get_order($order_id);
        if ($this->WCSVL_apl_authorization() != "") {
            // verifica se existe post
            $nmTitular = isset($_POST['nmTitular']) ? $_POST['nmTitular'] : '';
            $NrCartao = isset($_POST['NrCartao']) ? $_POST['NrCartao'] : '';
            $DataValidade = isset($_POST['DataValidade']) ? $_POST['DataValidade'] : '';
            $CpfCnpjComprador = isset($_POST['CpfCnpjComprador']) ? $_POST['CpfCnpjComprador'] : '';
            $CodSeguranca = isset($_POST['CodSeguranca']) ? $_POST['CodSeguranca'] : '';
            $DDDCelular = isset($_POST['DDDCelular']) ? $_POST['DDDCelular'] : '';
            $NrCelular = isset($_POST['NrCelular']) ? $_POST['NrCelular'] : '';
            // form
            $retorno = '"<p>Todos os campos marcados com <b>(*)</b>, são de preenchimento obrigatório</p>" +
            "<div id=\'colunaEsq\'>" +
                "<div class=\'tituloInput\' style=\'margin-top: -5px;\'>Selecione um cartão (*)</div>" +
                "' . $this->lista_cartoes() . '" +
            "</div>" +
            "<div id=\'colunaDir\'>" +
                "<form method=\'POST\' action=\'\' name=\'dados_pagamento\'>" +
                    "<div id=\'exibeCartao\'></div>" +
                    "<div class=\'clear\'></div>" +
                    "<div class=\'tituloInput\' style=\'margin-top: -6px;\'>Titular do cartão - Como se encontra no mesmo (*)</div>" +
                    "<input type=\'text\' name=\'nmTitular\' class=\'input input_maior input_sborda caixa_alta\' id=\'nmTitular\' value=\'' . $nmTitular . '\' />" +
                    "<div class=\'coluna50\'>" +
                        "<div class=\'tituloInput margin_top\'>Número do cartão (*)</div>" +
                        "<input type=\'text\' name=\'NrCartao\' class=\'input input_maior input_sborda coluna96\' id=\'NrCartao\' value=\'' . $NrCartao . '\' />" +
                    "</div>" +
                    "<div class=\'coluna25_left\'>" +
                        "<div class=\'tituloInput margin_top\'>Validade (*)</div>" +
                        "<input type=\'text\' name=\'DataValidade\' class=\'input input_maior input_sborda\' id=\'DataValidade\' value=\'' . $DataValidade . '\' />" +
                    "</div>" +
                    "<div class=\'coluna25_right\'>" +
                        "<div class=\'tituloInput margin_top\'>CCV (*)</div>" +
                        "<input type=\'text\' name=\'CodSeguranca\' class=\'input input_maior input_sborda\' id=\'CodSeguranca\' maxlength=\'4\' value=\'' . $CodSeguranca . '\' />" +
                    "</div>" +
                    "<div class=\'clear\'></div>" +
                    "<div id=\'senha\'></div>" +
                    "<div class=\'coluna50\'>" +
                        "<div class=\'tituloInput margin_top\'>CPF ou CNPJ do comprador (*)</div>" +
                        "<input type=\'text\' name=\'CpfCnpjComprador\' class=\'input input_maior input_sborda coluna96\' id=\'CpfCnpjComprador\' value=\'' . $CpfCnpjComprador . '\' />" +
                    "</div>" +
                    "<div class=\'coluna25_left\'>" +
                        "<div class=\'tituloInput margin_top\'>DDD (*)</div>" +
                        "<input type=\'text\' name=\'DDDCelular\' class=\'input input_maior input_sborda\' id=\'DDDCelular\' maxlength=\'2\' value=\'' . $DDDCelular . '\' />" +
                    "</div>" +
                    "<div class=\'coluna25_right\'>" +
                        "<div class=\'tituloInput margin_top\'>Celular (*)</div>" +
                        "<input type=\'text\' name=\'NrCelular\' class=\'input input_maior input_sborda\' id=\'NrCelular\' value=\'' . $NrCelular . '\' />" +
                    "</div>" +
                    "" +
                    "" +
                    "<br />" +
                    "<input class=\'float_right input_verde\' type=\'submit\' name=\'finalizar\' id=\'submit-serveloja-payment-form\' value=\'Finalizar\' />" +
                    "<a class=\'botao input_vermelho float_right\' href=\'' . esc_url( $order->get_cancel_order_url() ) . '\' title=\'Cancelar e voltar para o carrinho\'>' . __( 'Cancelar', 'woocommerce-serveloja' ) . '</a>";
                "</form>" +
            "</div>"';
        } else {
            $retorno = '"<div class=\'alerta\'>O pagamento via Serveloja ainda não está liberado. Entre em contato com o proprietário da loja.</div>"';
        }
        return $retorno;
    }

    private function WCSVL_modal_payment($order_id) {
        $order = wc_get_order($order_id);
        echo $this->cpf_cnpj();
        echo $this->detalhes_cartao($order->get_total());
        echo $this->mascaras();
        echo $this->modal();
        echo $this->validacoes();
        echo $this->alert_inicio_validacao();
        wc_enqueue_js('
            $("#bgModal").fadeIn();
            cpf_cnpj("CpfCnpjComprador");
            mascaras("DataValidade", "99/9999");
            mascaras("NrCelular", "99999-9999");
            mascaras("NrCartao", "9999-9999-9999-9999");
            var reply = "";
            reply += "<div id=\'formPagamento\' class=\'sombra\'>" +
            "<div id=\'bgModal_interno\'></div>" +
                "<div id=\'cabecalho_pagamento\'>" +
                    "<div id=\'logo\'><img src=\'' . PASTA_PLUGIN .'assets/images/serveloja.png\' alt=\'serveloja\' /></div>" +
                    "<div id=\'valor_total\'>R$ " + mascaraValor(' . $order->get_total() . ') + "</div>" +
                    "<div id=\'cancelar\' title=\'Cancelar e voltar para o carrinho\'>" +
                        "<a href=\'' . esc_url($order->get_cancel_order_url()) . '\'>" +
                            "<img src=\'' . PASTA_PLUGIN .'assets/images/fechar.png\' alt=\'serveloja\' style=\'width:100%;\' />" +
                        "</a>" +
                    "</div>" +
                "</div>" +
                "<div class=\'clear\'></div>" +
                ' . $this->form_payment($order_id) . ' +
            "</div>";
            $("#bgModal").html(reply);
        ');
    }

    public function WCSVL_generate_serveloja_form($order_id) {
        $order = wc_get_order($order_id);
        echo $this->modal_payment($order_id);
        return '<div id="bgModal"></div>';
    }
    
    // processa pagamento
    public function WCSVL_process_payment($order_id) {
        $order = wc_get_order($order_id);
        return array(
            'result'   => 'success',
            'redirect' => $order->get_checkout_payment_url(true)
        );
    }

    private function WCSVL_UrlAtual () {
        $dominio= $_SERVER['HTTP_HOST'];
        $url = "http://" . $dominio. $_SERVER['REQUEST_URI'];
        return $url;
    }

    // página de pagamento com modal
    public function WCSVL_receipt_page($order_id) {
        global $woocommerce;
        echo '<link type="text/css" href="' . PASTA_PLUGIN . 'assets/css/client.css" rel="stylesheet" />';
        echo '<link type="text/css" href="' . PASTA_PLUGIN . 'assets/css/forms.css" rel="stylesheet" />';
        echo '<link type="text/css" href="' . PASTA_PLUGIN . 'assets/css/tabela.css" rel="stylesheet" />';
        echo '<script type="text/javascript" src="' . PASTA_PLUGIN . 'assets/scripts/maskedinput.js"></script>';
        $order = wc_get_order( $order_id );

        echo $this->WCSVL_generate_serveloja_form($order_id);
        
        if (isset($_POST['finalizar'])) {
            $dados = array(
                "Bandeira"         => strtoupper($_POST['Bandeira']),
                "CpfCnpjComprador" => preg_replace("/[^0-9]/", "", $_POST['CpfCnpjComprador']),
                "nmTitular"        => strtoupper($_POST['nmTitular']), 
                "NrCartao"         => preg_replace("/[^0-9]/", "", $_POST['NrCartao']),
                "CodSeguranca"     => $_POST['CodSeguranca'],
                "DataValidade"     => $_POST['DataValidade'],
                "Valor"            => $_POST['Valor'],
                "QtParcela"        => $_POST['QtParcela'],
                "SenhaCartao"      => $_POST['SenhaCartao'],
                "DDDCelular"       => $_POST['DDDCelular'],
                "NrCelular"        => preg_replace("/[^0-9]/", "", $_POST['NrCelular']),
                "DsObservacao"     => "Venda de produtos na loja utilizando Woocommerce Serveloja. Número do pedido: #" . $order->get_order_number()
            );

            // envia dados via API
            $resposta = WCSVL_Serveloja_API::WCSVL_metodos_get('Vendas/EfetuarVendaCredito', $dados, $this->WCSVL_apl_authorization(), $this->WCSVL_apl_applicatioId());
            $resultado = json_decode($resposta["body"], true);

            if ($resultado['HttpStatusCode'] == 200) {
                // adiciona status na loja
                $order->update_status('completed', __('Pagamento realizado com cartão ' . strtoupper($_POST['Bandeira']) . ' através do Woocommerce Serveloja. Código da transação: ' . $resultado['Container'] . '.', 'woocommerce-serveloja' ));
                // reduz estoque, se houver
                $order->reduce_order_stock();
                // limpa carrinho
                $woocommerce->cart->empty_cart();
                wc_enqueue_js('
                    $(document).ready(function () {
                        $("#formPagamento").hide();
                        Modal("sucesso", "Sucesso", "Sua compra foi realizada com sucesso. Muito obrigado por utilizar os serviços da <b>Serveloja</b>", "' . get_option('home') . esc_url('/loja') . '", "bgModal");
                    });
                ');
            } else {
                wc_enqueue_js('
                    $(document).ready(function () {
                        Modal("erro", "Algo está errado...", "' . trim(preg_replace('/\s\s+/', ' ', $resultado['Mensagem'])) . '", "", "bgModal_interno");
                    });
                ');
            }
        }
    }
    
} ?>