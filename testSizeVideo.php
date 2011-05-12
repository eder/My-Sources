<?
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
?>