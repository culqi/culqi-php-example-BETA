<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Culqi Test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="waitMe.min.css"/>
  </head>
  <body>
    <div class="container">
      <h1>Culqi PHP Example</h1>
      <a id="miBoton" class="btn btn-primary" href="#" >Pay</a>
      <br/><br/><br/>
      <div class="panel panel-default" id="response-panel">
        <div class="panel-heading">Response</div>
        <div class="panel-body" id="response">
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://checkout.culqi.com/plugins/v2/"></script>
    <script src="waitMe.min.js"></script>
    <script>
      $("#response-panel").hide();
      Culqi.publicKey = 'pk_test_vzMuTHoueOMlgUPj';
      Culqi.settings({
        title: 'Culqi Store',
        currency: 'PEN',
        description: 'Polo/remera Culqi lover',
        amount: 3500
       });
       $('#miBoton').on('click', function (e) {
            // Abre el formulario con las opciones de Culqi.configurar
            Culqi.open();
            e.preventDefault();
        });
        // Recibimos Token del Culqi.js
        function culqi() {
          if (Culqi.token) {
              $(document).ajaxStart(function(){
                run_waitMe();
              });
              // Imprimir Token
              $.ajax({
                 type: 'POST',
                 url: '/server.php',
                 data: { token: Culqi.token.id, installments: Culqi.token.metadata.installments },
                 datatype: 'json',
                 success: function(data) {
                   var result = "";
                   if(data.constructor == String){
                       result = JSON.parse(data);
                   }
                   if(data.constructor == Object){
                       result = JSON.parse(JSON.stringify(data));
                   }
                   if(result.object === 'charge'){
                     resultdiv(result.outcome.user_message);
                   }
                   if(result.object === 'error'){
                       resultdiv(result.user_message);
                   }
                 },
                 error: function(error) {
                   resultdiv(error)
                 }
              });
          } else {
            // Hubo un problema...
            // Mostramos JSON de objeto error en consola
            $('#response-panel').show();
            $('#response').html(Culqi.error.merchant_message);
            $('body').waitMe('hide');
          }
        };
        function run_waitMe(){
          $('body').waitMe({
            effect: 'orbit',
            text: 'Procesando pago...',
            bg: 'rgba(255,255,255,0.7)',
            color:'#28d2c8'
          });
        }
        function resultdiv(message){
          $('#response-panel').show();
          $('#response').html(message);
          $('body').waitMe('hide');
        }
    </script>
  </body>
</html>
