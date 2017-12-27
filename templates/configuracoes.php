<?php function function_configuracoes() { ?>

<!-- scripts e estilos -->
<link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/style.css" rel="stylesheet" />
<link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/forms.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo PASTA_PLUGIN; ?>assets/scripts/scripts.js"></script>

<?php $funcoes = new WC_Serveloja_Funcoes ?>

<?php // verifica se já existem informações sobre a aplicação
  $dados = $funcoes::aplicacao();
  $apl_id = ($dados == "0") ? "0" : $dados[0]->apl_id;
  $apl_nome = ($dados == "0") ? "" : $dados[0]->apl_nome;
  $apl_token = ($dados == "0") ? "" : $dados[0]->apl_token;
  $apl_prefixo = ($dados == "0") ? "" : $dados[0]->apl_prefixo;
  $apl_email = ($dados == "0") ? "" : $dados[0]->apl_email;
  $apl_ambiente = ($dados == "0") ? "" : $dados[0]->apl_ambiente;
?>

<!-- cabeçalho -->
<div id="headerPlugin">
    <div id="logo">
      <a href="admin.php?page=index">
        <img src='<?php echo PASTA_PLUGIN; ?>assets/images/serveloja.png' alt='servloja' border='0' />
      </a>
    </div>
</div>

<?php // post configurações principais
  if (isset($_POST["salvar_config"])) {
    // atribui os valores do post às variaveis quando houver
    $apl_nome = $_POST["apl_nome"];
    $apl_token = $_POST["apl_token"];
    $apl_prefixo = $_POST["apl_prefixo"];
    $apl_email = $_POST["apl_email"];
    $apl_ambiente = $_POST["apl_ambiente"];
    // executa
    echo $funcoes::save_configuracoes($_POST["apl_nome"], $_POST["apl_token"], $_POST["apl_prefixo"], $_POST["apl_email"], $_POST["apl_ambiente"], $_POST["apl_id"]);
    $dados = $funcoes::aplicacao();
  } ?>

  <?php // post cartões
  if (isset($_POST["salvar_cartoes"])) {
    // executa
    echo $funcoes::insert_cartoes($_POST["posicao"], $_POST["car_cod"], $_POST["car_bandeira"], $_POST["car_parcelas"]);
  } ?>

<h1>Configurações</h1>
<h2>
    Caso você ainda não seja cliente Serveloja, entre em contato com um de nossos consultores
</h2>

<div class="clear"></div>

<p><i>Todos os campos marcados com <b>(*)</b> são de preenchimento obrigatório.</i></p>
  
<form name="configuracoes" method="post" action="">
  <div class="tituloInput">Nome da Aplicação (*)</div>
  <input type="text" class="input" name="apl_nome" value="<?php echo $apl_nome; ?>" maxlength="30" />
  <br />
  <div class="tituloInput">Token da Aplicação (*)</div>
  <input type="text" class="input" name="apl_token" value="<?php echo $apl_token; ?>" maxlength="30" />
  <br />
  <div class="tituloInput">Prefixo das transações</div>
  <input type="text" class="input" name="apl_prefixo" value="<?php echo $apl_prefixo; ?>" />
  <br />
  <div class="tituloInput">Informe um e-mail para receber notificações sobre compras realizadas em seu site/loja</div>
  <input type="text" class="input" name="apl_email" value="<?php echo $apl_email; ?>" />
  <br />
  <div class="tituloInput">O que você pretende fazer com esta aplicação? (*)</div>
  <select class="select" name="apl_ambiente">
      <option value="0" <?php if ($apl_ambiente == "0") { echo 'selected="selected"'; } ?>>Apenas um teste, estou verificando o funcionamento</option>
      <option value="1" <?php if ($apl_ambiente == "1") { echo 'selected="selected"'; } ?>>Vou utilizar em minha loja/site para receber pagamentos</option>
  </select>
  <br />
  <input type="hidden" name="apl_id" value="<?php echo $apl_id; ?>" />
  <input type="submit" class="submit" name="salvar_config" value="Salvar" name="salvar" />
</form>

<?php } ?>