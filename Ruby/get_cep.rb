require 'rubygems'
require 'net/http'
require 'uri'

def cep
 zipcode = ''
 key = ""
 api = "http://www.buscarcep.com.br/?cep=#{zipcode}&formato=xml&chave=#{key}"
 response  = Net::HTTP.get_response(URI.parse(api))
 result = response.body
 puts result
end
