<?php 
if (!defined( 'ABSPATH' )) {
    exit;
}

require_once('class-wc-serveloja-api.php');
$api = new WC_Serveloja_Api;

class WC_Serveloja {
    
    // verifica se token informado por usuário é válido
    private function valida_token($url, $method, $param) {
        return $api->metodos_acesso_api($url, $method, $param);
    }

    // fecha div via javascript após alguns segundos
    private function script($div) {
        return '<script type="text/javascript">Fecha_mensagem("' . $div . '");</script>';
    }

    // exibe a mensagem e classe conforme setado
    private function div_resposta($id, $class, $mensagem) {
        return '<div id="' . $id . '" class="' . $class . '">' . $mensagem . '</div>' . $this->script($id);
    }

    // ações no banco para aplicação
    private function insert_aplicacao($apl_nome, $apl_token, $apl_prefixo, $apl_email, $apl_ambiente) {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . "aplicacao",
            array('apl_nome' => $apl_nome,
                    'apl_token' => $apl_token,
                    'apl_prefixo' => $apl_prefixo,
                    'apl_email' => $apl_email,
                    'apl_ambiente' => $apl_ambiente
            ),
            array('%s', '%s', '%s', '%s', '%s')
        );
        if ($wpdb->last_error) {
            return $this->div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            return $this->div_resposta("fecha_mensagem", "sucesso", "Os dados foram adicionados com sucesso");
        }
    }

    private function update_aplicacao($apl_nome, $apl_token, $apl_prefixo, $apl_email, $apl_ambiente, $apl_id) {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . "aplicacao",
            array('apl_nome' => $apl_nome,
                    'apl_token' => $apl_token,
                    'apl_prefixo' => $apl_prefixo,
                    'apl_email' => $apl_email,
                    'apl_ambiente' => $apl_ambiente
            ),
            array('apl_id' => $apl_id),
            array('%s', '%s', '%s', '%s', '%s'),
            array('%s')
        );
        if ($wpdb->last_error) {
            return $this->div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            return $this->div_resposta("fecha_mensagem", "sucesso", "Os dados foram modificados com sucesso");
        }
    }

    // validação de e-mail
    private function valida_email($email) {
        $conta = "^[a-zA-Z0-9\._-]+@";
        $domino = "[a-zA-Z0-9\._-]+.";
        $extensao = "([a-zA-Z]{2,4})$";
        $pattern = $conta.$domino.$extensao;
        if (ereg($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }

    // salva dados aplicação
    public function save_configuracoes($apl_nome, $apl_token, $apl_prefixo, $apl_email, $apl_ambiente, $apl_id) {
        if ($apl_nome == "" || $apl_token == "" || $apl_ambiente == "") {
            return $this->div_resposta("fecha_mensagem", "erro", "Os campos marcados com (*) devem ser preencidos");
        } else if ($this->valida_email($apl_email) == false) {
            return $this->div_resposta("fecha_mensagem", "erro", "Informe um e-mail válido para continuar");
        } else {
            if ($apl_id == "0") {
            return $this->insert_aplicacao($apl_nome, $apl_token, $apl_prefixo, $apl_email, $apl_ambiente);
            } else {
            return $this->update_aplicacao($apl_nome, $apl_token, $apl_prefixo, $apl_email, $apl_ambiente, $apl_id);
            }
        }
    }

    // lista dados da aplicação
    public function aplicacao() {
        global $wpdb;
        $rows = $wpdb->get_results("SELECT apl_id, apl_nome, apl_token, apl_prefixo, apl_email, apl_ambiente FROM " . $wpdb->prefix . "aplicacao ORDER BY apl_id DESC LIMIT 1");
        if ($wpdb->last_error) {
            return $this->div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            if (count($rows) == 0) {
            return "0";
            } else {
            return $rows;
            }
        }
    }

    // cartões
    public function lista_cartoes() {
        return array(
            array("cod" => "01", "nome" => "American Express", "bandeira" => "american", "parcelas" => 12),
            array("cod" => "02", "nome" => "Diners Club", "bandeira" => "diners", "parcelas" => 12),
            array("cod" => "03", "nome" => "Elo", "bandeira" => "elo", "parcelas" => 12),
            array("cod" => "04", "nome" => "Hiper", "bandeira" => "hiper", "parcelas" => 12),
            array("cod" => "06", "nome" => "Mastercard", "bandeira" => "mastercard", "parcelas" => 12),
            array("cod" => "07", "nome" => "Visa", "bandeira" => "visa", "parcelas" => 12)
        );
    }

    public function insert_cartoes($posicao, $car_cod, $car_bandeira, $car_parcelas) {
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
            return $this->div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            return $this->div_resposta("fecha_mensagem", "sucesso", "Os dados foram adicionados com sucesso");
        }
    }

    public function cartoes_salvos() {
        global $wpdb;
        $rows = $wpdb->get_results("SELECT car_cod, car_bandeira, car_parcelas FROM " . $wpdb->prefix . "cartoes");
        if ($wpdb->last_error) {
            return $this->div_resposta("fecha_mensagem", "erro", "Ocorreu um erro: " . $wpdb->last_error);
        } else {
            return $rows;
        }
    }

    // cartões de crédito
    public function lista_cartoes_api($url, $method, $param) {
        return $api->metodos_acesso_api($url, $method, $param);
    }
}

?>