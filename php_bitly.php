<?
*
	* Criar URLs reduzidas das 
	*
	* @author 	Eder Eduardo <eder.esilva@gmail.com>
	* @version  0.0.1 
	* @param 	string $url parametro com a URL original
	* @return 	string  retorna a url reduzida pelo bit.ly


	function BitUrl($url)
{

	//  Variaveis de acesso a Bit.ly 
	$user 	= 'ederesilva';							  // Usuário do bit.ly		
	$key  	= 'R_15280c78606271f4da96eef8523e63ef';   // Code key 
	$format	= 'json';							  // Técnilogia usada 	
	$version = '2.0.1';							  // Versão do fonte	

	//Criando uma url	
	$api = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$user.'&apiKey='.$key.'&format='.$format;

	// Verifica se existe o cURL habilitado no servidor
	$curl = (bool) function_exists('curl_inite');

	// Verifica se a biblioteca cURL existe e se está habilitada no php.ini
	if ($curl  ) {
		// Caso exista, usa o cURL para fazer a requisição na variável $api
		$ch = curl_init($api);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);


		//Converte a string para minusculas
		if( strtolower($format) == 'json'){   

			// Decodifica o resultado Json 
			$json 		= @json_decode($response,true);
			// Verifica se a conexão foi bem sucedida 
			$status 	= $json['statusCode'];
			if($status =='OK'){
				return $json['results'][$url]['shortUrl'];
				// Caso ocorra algum erro mostra a messagem de erro	
			} else{
				echo "Não foi possivel abrir o bit.ly verifique os dados de acesso.";
			}

		}

		// Se a biblioteca cURL Falhou usa file_get_contents
	} else if  (!$curl) {

		//faz a requisição na variável $api usando file_get_content
		$response = file_get_contents($api);
		//Converte a string para minusculas 
		if( strtolower($format) == 'json'){
			$json 		= @json_decode($response,true);
			// Verifica se a conexão foi bem sucedida 
			$status 	= $json['statusCode'];
			if($status =='OK'){
				return print $api ; $json['results'][$url]['shortUrl'];

				// Caso ocorra algum erro mostra a messagem de erro	
			} else{
				echo "Não foi possivel abrir o bit.ly verifique os dados de acesso.";
			}
		}
	}	
}

/* Exemplo de como usar a função. */
$link = "http://edereduardo.wordpress.com/2010/10/16/funcao-para-encutar-url-usando-o-bit-ly-e-php/" 	;
$shorturl = BitUrl($link);
echo $shorturl ;
?>
