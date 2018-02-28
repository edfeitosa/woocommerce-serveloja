<?php function wcsvl_function_configuracoes() {

  // verifica se já existem informações sobre a aplicação
  $dados = WC_Serveloja_Funcoes::wcsvl_aplicacao();
  $apl_id = ($dados == "0") ? "0" : $dados[0]->apl_id;
  $apl_nome = ($dados == "0") ? "" : $dados[0]->apl_nome;
  $apl_token_teste = ($dados == "0") ? "" : $dados[0]->apl_token_teste;
  $apl_token = ($dados == "0") ? "" : $dados[0]->apl_token;
  $apl_prefixo = ($dados == "0") ? "" : $dados[0]->apl_prefixo;
  $apl_email = ($dados == "0") ? "" : $dados[0]->apl_email;

  // cabeçalho
  WC_Serveloja_Modulos::wcsvl_cabecalho();

  // post
  if (isset($_POST["salvar_config"])) {
    $apl_nome = $_POST["apl_nome"];
    $apl_token_teste = $_POST["apl_token_teste"];
    $apl_token = $_POST["apl_token"];
    $apl_prefixo = $_POST["apl_prefixo"];
    $apl_email = $_POST["apl_email"];
    echo WC_Serveloja_Funcoes::wcsvl_save_configuracoes($_POST["apl_nome"], $_POST["apl_token_teste"], $_POST["apl_token"], $_POST["apl_prefixo"], $_POST["apl_email"], $_POST["apl_id"]);
    $dados = WC_Serveloja_Funcoes::wcsvl_aplicacao();
  } ?>

  <h1>Configurações</h1>

  <!-- barra de ferramentas -->
  <?php WC_Serveloja_Modulos::wcsvl_ferramentas(); ?>

  <div class="clear"></div>


  <h2>
      Caso você ainda não seja cliente Serveloja, entre em contato com um de nossos consultores
  </h2>

  <div class="clear"></div>

  <p><i>Todos os campos marcados com <b>(*)</b> são de preenchimento obrigatório.</i></p>
    
  <form name="configuracoes" method="post" action="">
    <div class="tituloInput">Nome da Aplicação (*)</div>
    <input type="text" class="input" name="apl_nome" value="<?php echo $apl_nome; ?>" maxlength="30" />
    <br />
    <div class="tituloInput">Token para Testes</div>
    <input type="text" class="input" name="apl_token_teste" value="<?php echo $apl_token_teste; ?>" maxlength="60" />
    <br />
    <div class="tituloInput">Token da Aplicação (*)</div>
    <input type="text" class="input" name="apl_token" value="<?php echo $apl_token; ?>" maxlength="60" />
    <br />
    <div class="tituloInput">Prefixo das transações</div>
    <input type="text" class="input" name="apl_prefixo" value="<?php echo $apl_prefixo; ?>" />
    <br />
    <div class="tituloInput">Informe um e-mail para receber notificações sobre compras realizadas em seu site/loja</div>
    <input type="text" class="input" name="apl_email" value="<?php echo $apl_email; ?>" />
    <br />
    <input type="hidden" name="apl_id" value="<?php echo $apl_id; ?>" />
    <input type="submit" class="submit" name="salvar_config" value="Salvar" name="salvar" />
  </form>

<?php } ?>