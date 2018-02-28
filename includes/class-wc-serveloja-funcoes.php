<?php
/**
 * WooCommerce Serveloja Gateway class.
 *
 * Funções utilizadas em Woocommerce Serveloja.
 *
 * @class   WC_Serveloja_Funcoes
 * @version 1.0.0
 * @author  Eduardo Feitosa
 */

if (!defined( 'ABSPATH' )) {
    exit;
}

class WC_Serveloja_Funcoes {

    // verifica se token informado por usuário é válido
    private function wcsvl_valida_token($url, $method, $param) {
        return WC_Serveloja_Api::wcsvl_metodos_acesso_api($url, $method, $param);
    }

    // fecha div via javascript após alguns segundos
    private function wcsvl_script($div) {
        return '<script type="text/javascript">Fecha_mensagem("' . $div . '");</script>';
    }

    // exibe a mensagem e classe conforme setado
    private function wcsvl_div_resposta($id, $class, $mensagem) {
        return '<div id="' . $id . '" class="' . $class . '">' . $mensagem . '</div>' . WC_Serveloja_Funcoes::wcsvl_script($id);
    }

    // ações no banco para aplicação
    private function wcsvl_insert_aplicacao($apl_nome, $apl_token_teste, $apl_token, $apl_prefixo, $apl_email) {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . "aplicacao",
            array('apl_nome' => $apl_nome,
                    'apl_token_teste' => $apl_token_teste,
                    'apl_token' => $apl_token,
                    'apl_prefixo' => $apl_prefixo,
                    'apl_email' => $apl_email
            ),
            array('%s', '%s', '%s', '%s', '%s')
        );
        if ($wpdb->last_error) {
            return WC_Serveloja_Funcoes::wcsvl_div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            return WC_Serveloja_Funcoes::wcsvl_div_resposta("fecha_mensagem", "sucesso", "Os dados foram adicionados com sucesso");
        }
    }

    private function wcsvl_update_aplicacao($apl_nome, $apl_token_teste, $apl_token, $apl_prefixo, $apl_email, $apl_id) {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . "aplicacao",
            array('apl_nome' => $apl_nome,
                    'apl_token_teste' => $apl_token_teste,
                    'apl_token' => $apl_token,
                    'apl_prefixo' => $apl_prefixo,
                    'apl_email' => $apl_email
            ),
            array('apl_id' => $apl_id),
            array('%s', '%s', '%s', '%s', '%s'),
            array('%s')
        );
        if ($wpdb->last_error) {
            return WC_Serveloja_Funcoes::wcsvl_div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            return WC_Serveloja_Funcoes::wcsvl_div_resposta("fecha_mensagem", "sucesso", "Os dados foram modificados com sucesso");
        }
    }

    // validação de e-mail
    private function wcsvl_valida_email($email) {
        if ($email == '') {
            return true;
        } else {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else {
                return false; 
            }
        }
    }

    // salva dados aplicação
    public function wcsvl_save_configuracoes($apl_nome, $apl_token_teste, $apl_token, $apl_prefixo, $apl_email, $apl_id) {
        if ($apl_nome == "" || $apl_token == "") {
            return WC_Serveloja_Funcoes::wcsvl_div_resposta("fecha_mensagem", "erro", "Os campos marcados com (*) devem ser preencidos");
        } else if (WC_Serveloja_Funcoes::wcsvl_valida_email($apl_email) == false) {
            return WC_Serveloja_Funcoes::wcsvl_div_resposta("fecha_mensagem", "erro", "Informe um e-mail válido para continuar");
        } else {
            if ($apl_id == "0") {
                return WC_Serveloja_Funcoes::wcsvl_insert_aplicacao($apl_nome, $apl_token_teste, $apl_token, $apl_prefixo, $apl_email);
            } else {
                return WC_Serveloja_Funcoes::wcsvl_update_aplicacao($apl_nome, $apl_token_teste, $apl_token, $apl_prefixo, $apl_email, $apl_id);
            }
        }
    }

    // lista dados da aplicação
    public function wcsvl_aplicacao() {
        global $wpdb;
        $rows = $wpdb->get_results("SELECT apl_id, apl_nome, apl_token_teste, apl_token, apl_prefixo, apl_email FROM " . $wpdb->prefix . "aplicacao ORDER BY apl_id DESC LIMIT 1");
        if ($wpdb->last_error) {
            return WC_Serveloja_Funcoes::wcsvl_div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            if (count($rows) == 0) {
                return "0";
            } else {
                return $rows;
            }
        }
    }

    // cartões
    public function wcsvl_lista_cartoes() {
        $authorization = (WC_Serveloja_Funcoes::wcsvl_aplicacao() == "0") ? "" : WC_Serveloja_Funcoes::wcsvl_aplicacao()[0]->apl_token;
        $applicatioId = (WC_Serveloja_Funcoes::wcsvl_aplicacao() == "0") ? "" : WC_Serveloja_Funcoes::wcsvl_aplicacao()[0]->apl_nome;
        return WC_Serveloja_API::wcsvl_metodos_get('Cartao/ObterBandeirasValidas', "", $authorization, $applicatioId);
    }

    public function wcsvl_insert_cartoes($posicao, $car_cod, $car_bandeira, $car_parcelas) {
        global $wpdb;
        $wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "cartoes");
        for ($i = 0; $i < count($posicao); $i++) {
            $pos = $posicao[$i];
            $wpdb->insert(
                $wpdb->prefix . "cartoes",
                array('car_cod' => $car_cod[$pos],
                    'car_bandeira' => $car_bandeira[$pos],
                    'car_parcelas' => $car_parcelas[$pos]
                ),
                array('%s', '%s', '%s')
            );
        }
        if ($wpdb->last_error) {
            return WC_Serveloja_Funcoes::wcsvl_div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            header("location: " . esc_url(admin_url('admin.php?page=cartoes')));
        }
    }

    public function wcsvl_cartoes_salvos() {
        global $wpdb;
        $rows = $wpdb->get_results("SELECT car_cod, car_bandeira, car_parcelas FROM " . $wpdb->prefix . "cartoes");
        if ($wpdb->last_error) {
            return WC_Serveloja_Funcoes::div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            return $rows;
        }
    }

    // cartões de crédito
    public function wcsvl_lista_cartoes_api($url, $method, $param) {
        return WC_Serveloja_Funcoes::wcsvl_metodos_acesso_api($url, $method, $param);
    }

    // verifica se existem configurações salvas
    public function wcsvl_configuracoes() {
        global $wpdb;
        $rows = $wpdb->get_results("SELECT COUNT(apl_id) AS total FROM " . $wpdb->prefix . "aplicacao");
        if ($wpdb->last_error) {
            return WC_Serveloja_Funcoes::div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            foreach ($rows as $row) {
                return (int)$row->total;
            }
        }
    }

    // tabela
    private function wcsvl_parcelas($quant, $bandeira, $parcelas) {
        $retorno = '<select name="car_parcelas[]" class="select_menor" style="margin-top: 0px;">';
        for ($i = 1; $i <= intval($quant); $i++) {
            $selected = '';
            if (in_array($i . '-' . $bandeira, $parcelas)) {
                $selected = 'selected';
            }
            if ($i == 1) {
                $retorno .= '<option value="' . $i . '-' . $bandeira . '" ' . $selected . '>Apenas uma vez</option>"';
            } else {
                $retorno .= '<option value="' . $i . '-' . $bandeira . '" ' . $selected . '>Em ' . $i . ' vezes</option>"';
            }
        }
        $retorno .= '</select>';
        return $retorno;
    }

    public function wcsvl_tabela_cartoes() {
        $retorno = '';
        if (WC_Serveloja_Funcoes::wcsvl_configuracoes() == 0) {
            $retorno .= '<div class="alerta">Antes de selecionar os cartões, você precisar informar um Nome e Token da aplicação em Configurações.</div>';
        } else {
            $lista_cartoes = WC_Serveloja_Funcoes::wcsvl_lista_cartoes();
            $cartoes = json_decode($lista_cartoes["body"], true);
            $cartoes_banco = WC_Serveloja_Funcoes::wcsvl_cartoes_salvos();
            $quant_parcelas = 12;
            $array_cod = array();
            $array_parcelas = array();
            foreach ($cartoes_banco as $row) {
                array_push($array_cod, $row->car_cod);
                array_push($array_parcelas, $row->car_parcelas);
            }
            $retorno .= '<table cellspacing="0" cellpadding="0" class="tabela">' .
            '<thead>' .
            '<tr>' .
            '<td class="celulathead" style="width: 60%;">Cartão</td>' .
            '<td class="celulathead celulacentralizar" style="width: 10%;">Receber?</td>' .
            '<td class="celulathead celulacentralizar" style="width: 30%;">Em quantas parcelas?</td>' .
            '</tr>' .
            '</thead>';
            for ($i = 0; $i < count($cartoes["Container"]); $i++) {
                if ($i % 2 == 0) { $css = ''; } else { $css = 'impar'; }
                // verifica se existe item em array
                if (in_array($cartoes["Container"][$i]['CodigoBandeira'], $array_cod)) {
                    $css = 'no_banco';
                    $checado = 'checked';
                } else {
                    $css = $css;
                    $checado = '';
                }
                $retorno .= '<tr>' .
                '<td class="celulabody ' . $css . '">' . 
                '<img class="img_tabela" src="' . plugins_url('assets/images/' . strtolower($cartoes["Container"][$i]["NomeBandeira"]) . '.png', dirname(__FILE__)) . '" title="' . $cartoes["Container"][$i]["NomeBandeira"] .'" alt="' . strtolower($cartoes["Container"][$i]["NomeBandeira"]) . '" />' .
                $cartoes["Container"][$i]["NomeBandeira"] . 
                '</td>' .
                '<td class="celulabody celulacentralizar ' . $css . '"><input ' . $checado . ' type="checkbox" name="posicao[]" value="' . $i .'" /> Sim' .
                '<input type="hidden" name="car_bandeira[]" value="' . $cartoes["Container"][$i]["NomeBandeira"] . '" />' .
                '<input type="hidden" name="car_cod[]" value="' . $cartoes["Container"][$i]["CodigoBandeira"] . '" />' .
                '</td>' .
                '<td class="celulabody celulacentralizar ' . $css . '">' .
                WC_Serveloja_Funcoes::wcsvl_parcelas($quant_parcelas, strtolower($cartoes["Container"][$i]["NomeBandeira"]), $array_parcelas) .
                '</td>' .
                '</tr>';
            }
            $retorno .= '</table>' .
            '<div class="clear"></div>' .
            '<input type="submit" class="submit" name="salvar_cartoes" value="Salvar" name="salvar" />';
        }
        return $retorno;
    }

}

?>