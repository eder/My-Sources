<?php
/**
* Função para criar arquivos menores em flv
*
* @author    Eder Eduardo <eder.esilva@gmail.com>
// taxa legal 280
*
* @param string $InFile e $ID 
* @return string  O  novo Arquivo reduzido (usando FFmpeg)
* @version 0.0.4 
Exemplo de uso:
$encoder		= new Encoder();

* @changelog  
1. Criada a Class Encoder para dar mais escalabilidade ao código 
2. Foi criado escapadores de () e espaços para \(\)  e \  usando str_replace e preg_replace  para o bash
3. A função encoderFile  foi modificada anteriormente a função recebia um argumento de entrada e saida agora a função recebe somente um argumento de entrada @InFile e outro de @ID  paramentros passado via consulta SQL.
4. GetSize nova função criada na pega o tamanho do arquivo e verifica se ele realmete foi criado o comparando com original.
5. Verficação de condição if verificando se o arquivo gerado é realmente menor que o arquivo original.

7. Criada a função ScreenShot para gerar imagems dos vídeos particionados por tempo. 
8. Criar funcão para cortar trechos do vídeos em novos arquivos.
*/


 $ffmpegPath = "/usr/share/ffmpeg/ffmpeg"; // Caminho do Executável

global $flvPath   ;
global $ffmpegPath;


class  Video {

	public function replace($InFile){

		$Fnd    = array('/ /');
		$Rpl    = array('\ ');
		$InFile = preg_replace($Fnd, $Rpl, $InFile); #Procura por espaços no nome do aquivo e troca por /
		$Fnd    = array('(', ')');
		$Rpl    = array('\(', '\)');
		$InFile = str_replace($Fnd, $Rpl, $InFile); // Procura por () e troca por \(\) .

		return 	$InFile;

	}

	public function ScreenShot($InFile, $StartTime, $imagem ){

		if(is_file($imagem)){
			return false ;
		}
		else { exec("$ffmpegPath -y -ss $StartTime -t 1 -i $InFile -f mjpeg $imagem" ) ; }
	}



	function GetSize($SizeFile){                // Pega o Tamanho do Arquivo
		if(is_file($SizeFile)){
			filesize($SizeFile);
			return  $SizeFile;
		}
	}

	public function encoderFile($InFile , $ID )  // Recebe parametros de entrada e ID
	{

		// Indexa video ..
	}

}

$encoder		= new Video();
$encoder->ScreenShot($flv,$time) ;

?>
