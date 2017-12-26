<?php function function_configuracoes() { ?>

<!-- scripts e estilos -->
<link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/style.css" rel="stylesheet" />
<link type="text/css" href="<?php echo PASTA_PLUGIN; ?>assets/css/forms.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo PASTA_PLUGIN; ?>assets/scripts/scripts.js"></script>

<!-- cabeçalho -->
<div id="headerPlugin">
    <div id="logo">
      <a href="admin.php?page=index">
        <img src='<?php echo PASTA_PLUGIN; ?>assets/images/serveloja.png' alt='servloja' border='0' />
      </a>
    </div>
</div>

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