<?php
require_once('rCSS.php');
try {
	$rCSS = new rCSS('style.css');
	$rCSS->Init();
}catch(Exception $e)
{
	echo $e->getMessage();
}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
	<head>
		<title>Demonstração de Página</title>
	</head>
	<body>
		<ul>
			<li><a href=""></a></li>
		<ul>
		<div class="1">
			<h1>Exemplo de página</h1>
			<div id="2">
				<h2>Olá mundo</h2>
				<p>Testando <strong>página<strong></p>
			</div>
		</div>
	</body>
</html>
<?php
try {
	$rCSS->End();
}catch(Exception $e)
{
	echo $e->getMessage();
}
?>