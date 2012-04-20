# Criar URLs reduzidas
# @author  Eder Eduardo <eder.esilva@gmail.com>
# @version  0.0.2 
# @param   string url parametro com a URL original
# @return  string  retorna a url reduzida pelo bit.ly
require 'rubygems'
require 'json'
require 'net/http'
require 'uri'

def BitUrl url

  user  = 'ederesilva'                # Usuário do bit.ly   
  key   = 'R_adcb49b3fdffb4b3d64c100652d070c8'   # Code key 
  format  = 'json'                # Técnilogia usada  
  version = '2.0.1'               # Versão do fonte
  api = "http://api.bit.ly/shorten?version=#{version}&longUrl=#{URI.encode(url)}&login=#{user}&apiKey=#{key}&format=#{format}"
  #Faz a requisição na variável api  
  response  = Net::HTTP.get_response(URI.parse(api))
  data      = response.body
  result    = JSON.parse(data)
  # Verifica se a conexão foi bem sucedida 
  status    = result['statusCode'] 
  result['results'][url]['shortUrl'] if status == "OK"
  # else
  #  puts "Não foi possivel abrir o bit.ly verifique os dados de acesso."

end  
# Exemplo de como usar a função. #
link = "http://edereduardo.wordpress.com/2010/10/17/ruby-e-bit-ly-funcao-para-encutar-url/"
urlshort = BitUrl(link)
puts urlshort

