<div class="row">
	<div class="container">

		<div id="retorno" class="alert alert-info alert-dismissible" role="alert">
			Clique no botão "Selecione", para realizar o envio do arquivo de importação de clientes. <br><b>Atenção:</b> O formato do arquivo deverá ser <b>CSV</b>.
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				Importar arquivo
			</div> <!-- END .panel-heading -->
			<div class="panel-body">

				<div id="aguarde"><h3>Aguarde...</h3></div>

				<div class="form-group">
					<label>Layoult de importação</label>
					
					<div class="checkbox">
						<label data-toggle="tooltip" data-placement="top" title="Cod;Nome;Sit.;Data;Cidade;UF">
							Teste-Programador-PHP.pdf <input type="radio" name="tipoDeArquivo" value="teste-programador">
						</label>
						<label data-toggle="tooltip" data-placement="top" title="Cod;Sit.;Data;Nome;Cidade;UF">
							teste.csv <input type="radio" name="tipoDeArquivo" value="teste" checked>
						</label>
					</div>

					<hr>

				</div>

				<a href="#" id="btn-enviar" class="btn btn-lg btn-primary btn-block" onclick="$('#arquivo').trigger('click');">
					Selecione <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span>
				</a>

				<input type="file" name="arquivo" id="arquivo" style="display:none;">

				<div style="display:none; margin-bottom:0px !important" class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div> <!-- END .progress -->

			</div> <!-- END .panel-body -->
		</div> <!-- END .panel -->

	</div> <!-- END .container -->
</div> <!-- END .row -->

<script type="text/javascript">
$(function() {

	// < Variaveis
	botaoEnviar	= $("#btn-enviar");
	arquivo 	= $("input[name=arquivo]");
	progress 	= $(".progress");
	retorno     = $("#retorno");
	checkbox    = $(".form-group");
	aguarde     = $("#aguarde");
	// > Variaveis

	// Detecta ao ter mudanca no evento do botao de envio de arquivo
	arquivo.bind('change', function(event) {
		fData = new FormData();
		fData.append('file', $('#arquivo')[0].files[0]); // Campo do arquivo
		fData.append('layout', $('input[name=tipoDeArquivo]:checked').val()); // Tipo de arquivo de importacao

		$.ajax({
			url 		: '<?php echo base_url().'main/receberArquivo'; ?>',
			data 		: fData,
			processData : false,
			contentType : false,
			method 		: 'POST',
			success: function(data){
				dataJson = JSON.parse(data); // Converte retorno  para JSON

				// Verifica se houve retorno
				if(dataJson.feedback.tipo != "")
					retorno.removeClass('alert-danger').removeClass('alert-warning').removeClass('alert-info').removeClass('alert-success').addClass('alert-'+dataJson.feedback.tipo).html(dataJson.feedback.mensagem); // Seta a mensagem de retorno

				// < Redirecinamento ao finalizar o processamento
				if(dataJson.feedback.href != "" && dataJson.feedback.href != undefined) {
					if(dataJson.feedback.href == 'reload')
						setTimeout(function(){ location.reload(); }, 5000); // Atualiza
					else 
						setTimeout(function(){ location.href = dataJson.feedback.href; }, 5000); // Redirecionamento

					aguarde.closest('.panel').fadeOut('slow');
				}
				// > Redirecinamento ao finalizar o processamento

				// < Volta ao normal para novo envio
				aguarde.css('display', 'none');
				checkbox.css('display', 'block');
				botaoEnviar.css('display','block'); // Exibe botao de envio
					progress.css('display','none').find('.progress-bar').attr('aria-valuenow', "0").css("width", "0").text(''); // zera progressbar
				// > Volta ao normal para novo envio
			},
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				try {
					xhr.upload.addEventListener("progress", function(evt) {
						if(evt.lengthComputable) {
							valueProgress = Math.round(evt.loaded/evt.total*100); // valor do progressbar

							// < Verifica se o envio ja foi finalizado
							if(valueProgress == 100) {
								botaoEnviar.css('display','none'); // Oculta o botao de envio
								progress.css('display','none')
										.find('.progress-bar')
											.attr('aria-valuenow', "0")
											.css("width", "0")
											.text('');

								aguarde.css('display','block');
								checkbox.css('display','none');

							} else {
								aguarde.css('display','none');
								checkbox.css('display','none');
								botaoEnviar.css('display','none'); // Oculta o botao de envio

								progress.css('display','block')
									.find('.progress-bar')
										.attr('aria-valuenow', valueProgress)
										.css("width", valueProgress+"%")
										.text(valueProgress+"%");

							}
							// > Verifica se o envio ja foi finalizado
						}
					},false);

					return xhr;
				} catch(err){
					// < Exibe e oculta elementos
					botaoEnviar.css('display','block'); 
					aguarde.css('display','none');
					checkbox.css('display','block'); 
					progress.css('display','none').find('.progress-bar').attr('aria-valuenow', "0").css("width", "0").text(''); // zera progressbar
					// > Exibe e oculta elementos
				}
			}
		});

	});

});	
</script>