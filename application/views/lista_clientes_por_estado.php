<div class="row">
	<div class="container">

		<div class="panel panel-default">

			<div class="panel-heading">
				Listagem de clientes agrupados por estado
			</div> <!-- END .panel-heading -->

			<div class="panel-body">				
				<div class="table-responsive">
			    	<table class="table table-striped">
			    		<tr>
							<th>Estado</th>
							<th>Total de clientes</th>
						</tr>
			    	<?php 
			    	$totalSoma = 0;

			    	// < Verifica se existe estados
			    	if(!empty($estados)) {

				    	// < Percorre os estados
						foreach($estados as $key => $estado) {

							echo '
								<tr>
									<td>'.$estado['ESTSigla'].'</td>
									<td>'.$estado['total'].'</td>
								</tr>
							';

							$totalSoma += $estado['total'];
						}
						// > Percorre os estados
					} else {
						echo '
						<tr>
							<td colspan="2">Não há clientes cadastrados</td>
						</tr>
						'	;
					}
					// > Verifica se existe clientes
			    	?>
			    		<tr>
							<td colspan="2"><b>Total de clientes: <?php echo $totalSoma; ?></b></td>
						</tr>
			    	</table> 
				</div> <!-- END .table-responsive -->
			</div> <!-- END .panel-body -->

		</div> <!-- END .panel -->

	</div> <!-- END .container -->
</div> <!-- END .row -->