require 'rubygems'
require 'rack'


# HTTP Auth For Resque Console
AUTH_PASSWORD = "password" || ENV['AUTH']
if AUTH_PASSWORD
  Resque::Server.use Rack::Auth::Basic do |username, password|
    password == AUTH_PASSWORD
  end
end

use Rails::Rack::Static
run Rack::URLMap.new(
  "/" => ActionController::Dispatcher.new,
  "/resque" => Resque::Server.new
)