<?php
  /**
   * Funcao para criar arquivos menores em flv
   *
   * @author    Eder Eduardo <eder.esilva@gmail.com>
   // taxa legal 280
   *
   * @param string $InFile e $ID
   * @return string  O  novo Arquivo reduzido (usando FFmpeg)
   * @version 0.0.4
   Exemplo de uso:
   $encoder    = new Encoder();
   $rpl = $encoder->replace("/tv/Portal$item[alta]");  //
   $encoder->encoderFile($rpl, "$item[ID]") ;         //
   $encoder->ScreenShot()
   
   * @changelog
   1. Criada a Class Encoder para dar mais escalabilidade ao codigo
   2. Foi criado escapadores de () e espaÃ§os para \(\)  e \  usando str_replace e preg_replace  para o bash
   3. A funcao encoderFile  foi modificada, anteriormente a função recebia um argumento de entrada e saida agora a funcao recebe somente um argumento de entrada @InFile e outro de @ID  paramentros passado via resultado SQL.
   4. GetSize nova função criada pega o tamanho do arquivo e verifica se foi criado eo compara com original.
   5. Condicao if verifica se o arquivo gerado é menor que o arquivo original.
   6. Criada a funcao FlvTool para indexar keyframe dentro do vídeo. 23/10/2010
   7. Criada a função ScreenShot para gerar imagems dos vídeos particionados por tempo.
   */
  class Encoder
  {
      public function replace($InFile)
      {
          $Fnd = array('/ /');
          $Rpl = array('\ ');
          // Procura por espaÃ§os no nome do aquivo e troca por \  . 
          $InFile = preg_replace($Fnd, $Rpl, $InFile);
          $Fnd = array('(', ')');
          $Rpl = array('\(', '\)');
          // Procura por () e troca por \(\) .
          $InFile = str_replace($Fnd, $Rpl, $InFile);
          return $InFile;
      }
      public function ScreenShot($InFile, $StartTime, $imagem)
      {
          // Caminho do Executavel  
          $ffmpegPath = "/usr/share/ffmpeg/ffmpeg";
          if (is_file($imagem)) {
              return false;
          } else {
              exec("$ffmpegPath -y -ss $StartTime -t 1 -i $InFile -f mjpeg $imagem");
          }
          return print "$y \n";
      }
      public function encoderFile($InFile, $ID)// Recebe parametros de entrada e ID
      {
          $ffmpegPath = "/usr/bin/ffmpeg";
          // Indexa video ..
          function FlvTool($InFile)
          {
              $flvPath = "/usr/bin/flvtool2";
              exec("$flvPath -UP $InFile");
              return $InFile;
          }
          // chama a Função para indexar o video em Alta  
          FlvTool("$InFile");
          // Separa em caminho , nome e extenção 
          list($n, $e, $p) = nomeExtensao("$InFile");
          // Gera um novo nome de aquivo adicionando _baixa.flv ao final
          $OutFile = "" . $p . "" . $n . "_baixa" . $e . "";
          // Gera um novo arquivo
          exec(" $ffmpegPath  -y -i  $InFile -ab 50 -ar 11025  -b 300kb  -mbd 2 -flags +4mv  -s 470x320  $OutFile");
          // chama a Função para indexar o video 
          FlvTool("$OutFile");
          function GetSize($SizeFile)
          {
              // Pega o Tamanho do Arquivo
              if (is_file($SizeFile)) {
                  $SizeFile = filesize($SizeFile);
                  return $SizeFile;
              }
          }
          // Remove o caminho absluto usado pelo fonte
          $urlFile = str_replace("/tv/Portal", "", "$OutFile");
          // Pega o tamanho do aquivo original
          $SInFile = GetSize($InFile);
          //  Pega o tamanho do arquivo gerado
          $SOutFile = GetSize($OutFile);
          if ($SInFile <= $SOutFile) {
              // Verficar se o arquivo orginal Ã© menor que o arquivo gerado.
              exec(" $ffmpegPath  -y -i  $InFile -ab 50 -ar 11025  -b 280kb  -mbd 2 -flags +4mv  -s 470x320   $OutFile");
              // Chama a FunÃ§Ã£o para indexar o video em baixa 
              FlvTool("$OutFile");
              $UPDATE = "UPDATE `tv_video` SET baixa = '$urlFile' , keyframe='1' WHERE `ID`='$ID'";
              select($UPDATE);
          } else {
              // O Arquivo já foi criado/ upa no banco.
              $UPDATE = "UPDATE `tv_video` SET baixa = '$urlFile' , keyframe='1' WHERE `ID`='$ID'";
              select($UPDATE);
          }
          return $OutFile;
      }
  }
  //   $encoder    = new Encoder();
  //   $encoder->ScreenShot($flv,$time) ;
  //   $encoder->replace()   
?>
