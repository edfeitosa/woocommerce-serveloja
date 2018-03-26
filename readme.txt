=== WooCommerce Serveloja ===
Contributors: TiServeloja
Donate link: 
Tags: woocommerce, serveloja, payment
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds Serveloja gateway to the WooCommerce plugin

== Description ==

O Woocommerce Serveloja, fornece a proprietários de e-commerces (lojas virtuais), uma forma rápida de finalizar suas vendas, utilizando cartões de créditos.

== Installation ==

Envie os arquivos do plugin para a pasta wp-content/plugins, ou instale usando o instalador de plugins do WordPress. Após realizar alguma detas ações, ative o plugin.

É necessário ter conta na [Serveloja](http://www.serveloja.com.br) e ter instalado o [WooCommerce](http://wordpress.org/plugins/woocommerce/).

Com o plugin instalado acesse o admin do WordPress e entre em "WooCommerce" > "Configurações" > "Finalizar compra" > "Serveloja". Habilite a opção "Utilizar WooCommerce Serveloja para receber pagamentos".

Ainda no admin do WordPress, acesse no menu "Serveloja" > "Configurações" e informe o Nome da Aplicação (ID da Aplicação) e o Token gerados através da sua conta na Serveloja.

Após, acesse "Serveloja" > "Cartões" e informe as bandeiras de cartões com as quais irá receber pagamentos, bem como, a quantidade máxima de parcelas que irá receber.

== Frequently Asked Questions ==

= Qualquer pessoa pode utilizar o WooCommerce Serveloja? =

Sim, qualquer pessoa pode utilizar, desde que tenha uma conta habilitada e ativa na Serveloja, e tenha WooCommerce instalado em seu e-commerce (loja virtual).

= Como conseguir o Nome da Aplicação (ID da Aplicação) e o Token para usar em minha loja? =

Você precisa entrar em contato com a Serveloja, realizar seu cadastro e acessar o sistema. A partir daí, você poderá gerar o Nome da Aplicação e Token para que seja possível a utilização da sua loja.

= Meus clientes precisarão realizar algum cadastro no ambiente da Serveloja? =

Não. Os usuários, clientes da sua loja não precisarão realizar nenhum cadastro na Serveloja. Os dados informados no momento do pagamento, são usados para validar a operação e não são gravados no sistema da Serveloja.

= A Serveloja aceita outras formas de pagamento além do cartão de crédito? =

No plugin WooCommerce Serveloja, não. Aqui são aceitos apenas cartões de crédito como forma de pagamento.

= Se não for informado o Nome da Aplicação e o Token, as transações serão realizadas? =

Não. Você poderá realizar a instalação, mas para uso efetivo, só será concretizado após a informação de Nome da Aplicação e Token.

= Ao ir para página de fianlização de pedido, não aparece o botão "Finalizar", é correto? =

Somente após serem fornecidos as informações obrigatórias, o botão estará disponível para finalização do processo.

= Verifiquei que não existem alguns cartões na lista de seleção no pagamento. O que fazer? =

Verifique no admin do WordPress em "Serveloja" > "Cartões" e verifique se a bandeira desejada está marcada como "Sim" e a quantidade de parcelas está correta com a quantidade que você trabalha.

= Após o usuário da loja clicar no botão "Finalizar", ocorre um erro dizendo que a transação não foi liberada. O que aconteceu? =

Se isto ocorrer, será informada uma mensagem de retorno com a descrição do problema. Se o erro foi causado por algum dado informado incorretamente pelo usuário, este deve ser corrigido e o processo deve ser refeito. Caso haja problemas quanto a liberação da operação, o usuário deve entrar em contato com a operadora do cartão para resolvê-los.

= Como entro em contato com a Serveloja? =

Acesse o site www.serveloja.com.br, e você terá acesso a todos os nosso canais de comunicação.

= Como criar uma conta Serveloja? =

Acesse o site www.serveloja.com.br, e você terá todas as informações de como criar sua conta na Serveloja, além de ter a possibilidade de falar com um de nossos representantes.

== Screenshot ==

1. Plugin após a instalação sendo listado e aparecendo no menu do WordPress.
2. Plugin já habilitado no WooCommerce.
3. Tela de Configuração em Serveloja.
4. Formulário onde serão adicionados informações como Nome da Aplicação e Token para uso em transações.
5. Lista de possíveis cartões para uso em transações.
6. Botão disponível na tela de finalização de pagamento.
7. Modal para preenchimento dos dados e finalização do processo.
8. Aspecto da tela com todos os dados preenchidos e botão Finalizar já visível.
9. Validação automática retornará erros durante o preenchimento, caso haja informações incompátiveis.
10. Alerta exibido enquanto a transação está sendo validada.
11. Retorno em caso de erro da transação.
12. Retorno em caso de sucesso da transação.