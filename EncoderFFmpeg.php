d<?php
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
$rpl = $encoder->replace("/tv/Portal$item[alta]");  //  
$encoder->encoderFile($rpl, "$item[ID]") ;         //
$encoder->ScreenShot()
    
* @changelog  
     1. Criada a Class Encoder para dar mais escalabilidade ao código 
     2. Foi criado escapadores de () e espaços para \(\)  e \  usando str_replace e preg_replace  para o bash
	 3. A função encoderFile  foi modificada anteriormente a função recebia um argumento de entrada e saida agora a função recebe somente um argumento de entrada @InFile e outro de @ID  paramentros passado via consulta SQL.
	 4. GetSize nova função criada na pega o tamanho do arquivo e verifica se ele realmete foi criado o comparando com original.
	 5. Verficação de condição if verificando se o arquivo gerado é realmente menor que o arquivo original.
	 6. Criada a função FlvTool para indexar keyframe dentro do vídeo. 23/10/2010
	 7. Criada a função ScreenShot para gerar imagems dos vídeos particionados por tempo. 
*/

class  Encoder{
  

   
      public function replace($InFile){
      
        $Fnd = array('/ /');
        $Rpl	=	array('\ ');
        $InFile= preg_replace($Fnd, $Rpl, $InFile);  // Procura por espaços no nome do aquivo e troca por \  .	
        $Fnd= array('(', ')');
		$Rpl = array('\(', '\)');
		$InFile 	= str_replace($Fnd, $Rpl, $InFile); // Procura por () e troca por \(\) .
		   
		return 	$InFile;
		
              }

   
        
		public function ScreenShot($InFile, $StartTime, $imagem ){
		
		 	  // Caminho do Executavel 
		$ffmpegPath = "/usr/share/ffmpeg/ffmpeg";     // Caminho do Executavel	
			
				if(is_file($imagem)){
				
					return false ;
								
				}
			   
			   else {	
			 
		      exec("$ffmpegPath -y -ss $StartTime -t 1 -i $InFile -f mjpeg $imagem" ) ;
              $y= "$ffmpegPath -y -ss 00:$StartTime -t 1 -i $InFile -f mjpeg $imagem \n";
	    	}
			 
			 return	 print "$y \n" ;
		}
     
    			
	
	    public function encoderFile($InFile , $ID )  // Recebe parametros de entrada e ID
	   		{
	   			$ffmpegPath = "/usr/bin/ffmpeg";  
	             			
	   			// Indexa video ..
	   		      function FlvTool($InFile){
	   			
	              $flvPath 	= "/usr/bin/flvtool2";	
	   				
	   			  exec("$flvPath -UP $InFile")  ;
	            //  print "$flvPath -UP $InFile \n" ;	   
	   			  return  $InFile ;
	   
	   			 }
	      
	   			// chama a Função para indexar o video em Alta	
	   			FlvTool("$InFile") ;
	   											
	   			
	   			
	   					
	   		    list($n,$e,$p) = nomeExtensao("$InFile" );  // Separa em caminho , nome e extenção 
	   			$OutFile = "".$p."" .$n.  "_baixa" . $e . "" ;   // Gera um novo nome de aquivo adicionando _baixa.flv ao final
	   			exec(" $ffmpegPath  -y -i  $InFile -ab 50 -ar 11025  -b 300kb  -mbd 2 -flags +4mv  -s 470x320  $OutFile"  ) ; // Gera um novo arquivo
	   			$x = "$ffmpegPath  -y -i  $InFile -ab 50 -ar 11025  -b 412kb  -mbd 2 -flags +4mv  -s 470x320  $OutFile" ;
				//print "$x \n" ;
				
				// chama a Função para indexar o video	
	   			
	            FlvTool("$OutFile") ;   		
	   			       
	   					function GetSize($SizeFile){                // Pega o Tamanho do Arquivo
	   							if(is_file($SizeFile)){
								$SizeFile= filesize($SizeFile);
								//echo "$SizeFile  : Tamanho. \n" ;
								return  $SizeFile;
				
							}
 				 } 
					
					$urlFile = str_replace("/tv/Portal", "", "$OutFile" );	 // Remove o caminho absluto usado pelo fonte
 					$SInFile		= GetSize($InFile);      // Pega o tamanho do aquivo original
	  				$SOutFile 		= GetSize($OutFile);  //  Pega o tamanho do arquivo gerado

	  					if ($SInFile <=   $SOutFile){  // Verficar se o arquivo orginal é menor que o arquivo gerado.
	 						exec(" $ffmpegPath  -y -i  $InFile -ab 50 -ar 11025  -b 280kb  -mbd 2 -flags +4mv  -s 470x320   $OutFile"  ) ;
	   						
							// Chama a Função para indexar o video em baixa	
	   						FlvTool("$OutFile") ;	
	   												
	   			   	      $UPDATE ="UPDATE `tv_video` SET baixa = '$urlFile' , keyframe='1' WHERE `ID`='$ID'" ;
	   			
	   										
	   			   	      select ($UPDATE) ;
	   			         
	   						}
	   					else {
	   							$UPDATE ="UPDATE `tv_video` SET baixa = '$urlFile' , keyframe='1' WHERE `ID`='$ID'" ;			// O Arquivo já foi criado só da um update no banco.
	   			   	    		select ($UPDATE) ;
	   								}  ;
	   			
	   				    return  $OutFile ;
	   		         }
	   		    
	   			
	   }


//   $encoder		= new Encoder();
//   $encoder->ScreenShot($flv,$time) ;
//	  $encoder->replace()   
	

?>
