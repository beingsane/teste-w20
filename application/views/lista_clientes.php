<div class="row">
	<div class="container">

		<div class="panel panel-default">

			<div class="panel-heading">
				Listagem de clientes
			</div> <!-- END .panel-heading -->

			<div class="panel-body">				
				<div class="table-responsive">
			    	<table class="table table-striped">
			    		<tr>
							<th>Código</th>
							<th>Nome</th>
							<th>Data</th>
							<th>Situação</th>
							<th>Cidade</th>
							<th>UF</th>
						</tr>
			    	<?php 
			    	$contClientes = 0;
			    	// < Verifica se existe clientes
			    	if(!empty($clientes)) {

				    	// < Percorre os clientes
						foreach($clientes as $key => $cliente) {
							$contClientes++;
							($cliente['CLISituacao'] == 'B') ? $class = 'danger' : $class = null;

							echo '
								<tr class="'.$class.'">
									<td>'.$cliente['CLICodigo'].'</td>
									<td>'.utf8_encode($cliente['CLINome']).'</td>
									<td>'.$this->functions->formataData($cliente['CLIData']).'</td>
									<td>'.$cliente['CLISituacao'].'</td>
									<td>'.$cliente['CIDNome'].'</td>
									<td>'.$cliente['ESTSigla'].'</td>
								</tr>
							';
						}
						// > Percorre os clientes
					} else {
						echo '
						<tr>
							<td colspan="7">Não há clientes cadastrados</td>
						</tr>
						'	;
					}
					// > Verifica se existe clientes
			    	?>
			    		<tr>
							<td colspan="7"><b>Total de clientes: <?php echo $contClientes; ?></b></td>
						</tr>
			    	</table> 
				</div> <!-- END .table-responsive -->
			</div> <!-- END .panel-body -->

		</div> <!-- END .panel -->

	</div> <!-- END .container -->
</div> <!-- END .row -->