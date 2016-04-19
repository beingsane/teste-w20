<div class="row">

	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo base_url(); ?>">Sistema B</a>
			</div> <!-- END .navbar-header -->

			<div class="collapse navbar-collapse" id="navbar">
				<ul class="nav navbar-nav">
					<li <?php if(strpos(uri_string(), 'listarClientes')) echo 'class="active"'; ?>>
						<a href="<?php echo base_url().'main/listarClientes'; ?>">Listar clientes</a>
					</li>
					<li <?php if(strpos(uri_string(), 'listarPorEstado')) echo 'class="active"'; ?>>
						<a href="<?php echo base_url().'main/listarPorEstado'; ?>">Total de clientes por estado</a>
					</li>
					<li <?php if(strpos(uri_string(), 'receberArquivo')) echo 'class="active"'; ?>>
						<a href="<?php echo base_url().'main/receberArquivo'; ?>">Receber arquivo</a>
					</li>
				</ul>			
			</div> <!-- END .navbar-collapse -->
		</div> <!-- END .container-fluid -->

	</div> <!-- END .navbar -->

</div> <!-- END .row -->