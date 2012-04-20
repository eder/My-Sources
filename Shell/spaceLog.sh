######## O que esse programa faz 
#Monta um histórico no arquivo chamado "espaco_home.log", 
#dentro do diretório "/var/log/regs", contendo o espaço ocupado do diretório /home  
#armazenando a data, a hora e o espaço livre do HD.

#!/bin/bash
#Autor:		Eder Eduardo
#E-mail: 	eder.esilva@gmail.com
#Twitter: @edereduardo
#github: http://github.com/eder


########SpaceLog############ 

#Version 0.0.1
#date 18.04.11

## Como Executar esse programa
# Crie um arquivo spacelog.sh e adicione essas linhas feche e salve
# De permissão de execução com chmod +x spacelog.sh 
# Esse programa precisar ser iniciado com o Crontab 
# Exemplo de configuração do contrab
# */5 * * * *  /sbin/SpaceLog.sh
# Isso faz que o programa adicione as informaçõe a casa 5 minutos no arquivo de log


path="/var/log/regs/" 	 	# Diretorio de logs
directory="/home/"  	# Diretorio a ser analizado (Pode ser alterado)
fileLog="space_home.log" 	# Arquivo de log
Time=$(date +%k:%M)      	# Pega Hora e minuto do sistema
Day=`/bin/date +%Y/%m/%d` # Pega Ano mes e dia do sistema
Available=`df -h | grep /dev/sda1 | cut -c 29-33` # Pega o espaço disponivel em disco (altere para a sua unidade de disco)

#Função que cria pega o tamnho do diretori e cria o log no arquivo indicado
getSizeLog () {    
size=`du -sh ${1} | cut -c 1-4`  # Verifica tamanho do diretorio 
echo  "[${Day}-${Time}] - [Space occupied in directory]- ${size}  [Space Free] - ${Available}
">>${path}${2} # Adiciona dados no arquivo de log
}

if [ -d $path ] # Verifica existe o diretório  /va/log/regs/ na variavel $path 
	then
	getSizeLog $directory $fileLog
else   # Se o diretorio não existir cria o diretorio indicado na variavel
	mkdir -p $path
	getSizeLog $directory $fileLog
fi