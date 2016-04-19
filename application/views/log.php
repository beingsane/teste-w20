<div class="row">
	<div class="container">

		<div id="retorno" class="alert alert-warning alert-dismissible" role="alert">
			<b>Os logs abaixo, são referente a ultima mimportação e acusam os casos dois quais não foram inseridos pois possuem alguma divergencia conforme enunciado ou já estavam inseridos no banco de clientes:</b><br>
			- O campo Código aceita números positivos de até 4 dígitos.<br>
			- O campo Nome tem que possuir até 200 caracteres.<br>
			- O campo Situação tem que possuir apenas um caractere em letra maiúscula.<br>
			- O campo Data deve somente importar os clientes acima de 2010.<br>
			- O campo Cidade deve conter até 250 caracteres.<br>
			- O campo UF deve conter somente 2 caracteres em letra maiúscula.<br>
		</div>

		<div class="panel panel-warning">

			<div class="panel-heading">
				Log de avisos na importação
				<a href="<?php echo base_url().'main/receberArquivo?limparLog=true'; ?>" class="pull-right">
					<span data-toggle="tooltip" data-placement="top" title="Limpar log" class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
				</a>
			</div> <!-- END .panel-heading -->

			<div class="panel-body" style="height: 400px; overflow-y:scroll">				
				<div class="table-responsive">
			    	<table class="table table-striped">
			    		<tr>
				    		<th>Linha do arquivo</th>
							<th>Código</th>
							<th>Situação</th>
							<th>Data</th>
							<th>Nome</th>
							<th>Cidade</th>
							<th>UF</th>
						</tr>
			    	<?php
			    	$contLogs = 0;

			    	// < Verifica se existe logs
			    	if(!empty($log)) {
			    		// < Percorre as linhas de alerta
						foreach($log as $key => $cliente) {
							$contLogs++;
							echo '
								<tr>
									<td>'.$key.'</td>
									<td>'.utf8_encode(implode('</td><td>', $cliente)).'</td>
								</tr>
							';
						}
						// > Percorre as linhas de alerta
					} else {
						echo '
						<tr>
							<td colspan="7">Não existem logs a serem listados.</td>
						</tr>
						';
					}
					// > Verifica se existe logs
			    	?>
			    		<tr>
							<td colspan="7"><b>Total de linhas: <?php echo $contLogs; ?></b></td>
						</tr>
			    	</table> 
				</div> <!-- END .table-responsive -->
			</div> <!-- END .panel-body -->

		</div> <!-- END .panel -->

	</div> <!-- END .container -->
</div> <!-- END .row -->