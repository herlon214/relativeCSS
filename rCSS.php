<?php
/*
* Classe Relative CSS
* @author Herlon Aguiar <herlon214@gmail.com>
*/
class rCSS
{
	public $Out; # Variável de conteúdo capturado da página
	public $cssFile; # Arquivo alvo CSS
	public $Selectors; # Variável com os css específicos
	public $UseSelectors = array(); # Variáveis com os seletores que serão usados na página
	
	/*
	* Inicia a classe
	*/
	function __construct($cssFile)
	{
		# Salva a variável do css
		$this->cssFile = $cssFile;
		$this->parseCss();
	}
	/*
	* Função que começa a pegar o conteúdo
	*/
	function Init()
	{
		ob_start();
	}
	/*
	* Função para pegar as propriedades css
	*/
	function parseCss()
	{
		if(empty($this->cssFile))
		{
			throw new Exception('Você precisa definir um arquivo css ao iniciar a classe');
		}else
		{
			# Lê o arquivo CSS
			$lines = file($this->cssFile);
			$style = '';
			foreach ($lines as $line_num => $line) {

			  $style .= trim($line);
			}
			$tok = strtok($style, "{}");
			# Cria uma array que ficará os valores
			$sarray = array();
			# Contador
			$spos = 0;
			# Loop separando os identificadores dos atributos
			while ($tok !== false) { 
			  $sarray[$spos] = $tok;
			  $spos++; 
			  $tok = strtok("{}");
			}
			# Pega o tamanho da array
			$size = count($sarray);
			# Cria os seletores e os atributos
			$selectors = array();
			$sstyles = array();
			# Cria Contadores
			$npos = 0;
			$sstl = 0;
			# Loop para juntar seletores dos atributos
			for($i = 0; $i<$size; $i++){
			   if ($i % 2 == 0) { 
				 $selectors[$npos] = $sarray[$i];
				 $npos++;    
			   }else{
				$sstyles[$sstl] = $sarray[$i];
				$sstl++;
			   } 
			}
			# Variável do selector
			#$this->Selectors = array_combine($selectors,$sstyles);
			$this->Selectors = $selectors;
			$this->Styles = $sstyles;
			var_dump($this->Selectors);
		}
		
	}
	function parseContent()
	{
		if(empty($this->Out))
		{
			throw new Exception('Primeiro é necessário executar o Start() no começo da página e o End() no final.');
		}else
		{
			$Out = $this->Out;
			# Remove as quebras de linha
			$Out = preg_replace("/\s+/", " ", $Out);
			$Out = preg_match_all("'<(.*?)>'si", $Out, $match);
			$Out = $match[0];
			$Out = implode('',$Out);
			$Out = str_replace(array(' id=',' class=','"','div'),array('#','.','',''),$Out);
			$Out = str_replace('"', ' .',$Out);
			$Out = str_replace('a .href=','',$Out);
			
			$tok = strtok($Out, "<>");
			while ($tok !== false) {
				if(strlen($tok) > 1 and substr_count($tok,'/') <= 0)
				{
					$htmlTags[] = $tok;
				}
				$tok = strtok("<>");
			}
			
			$this->htmlTags = $htmlTags;
			var_dump($this->htmlTags);
		}
	}
	/*
	* Função que termina a captura de conteúdo
	*/
	function End()
	{
		$this->Out = ob_get_contents();
		ob_end_clean();
		$this->parseContent();
		$this->mergeAll();
	}
	/*
	* Função que verifica as tags usadas no html e cria o css
	*/
	function mergeAll()
	{
		foreach($this->htmlTags as $hTag)
		{
			#if(array_key_exists($hTag,$this->Selectors))
			#{
			#	echo $hTag . ' { ' . $this->Selectors[$hTag] . ' } <br />';
			#}
			$Key = array_search($hTag,$this->Selectors);
			if($Key != false)
			{
				if(!array_key_exists($Key,$this->UseSelectors))
				{
					$this->UseSelectors[$this->Selectors[$Key]] = $this->Styles[$Key];
				}
			}
			
		}
		
		foreach($this->UseSelectors as $K => $V)
		{
			echo $K . '{ ' . $V . ' } <br />';
		}
	}
}
?>