require 'net/http'
require 'json'

    http = Net::HTTP.new('raw.github.com', 443); http.use_ssl = true
    puts JSON.parse http.get('/celsodantas/br_populate/master/states.json').body

