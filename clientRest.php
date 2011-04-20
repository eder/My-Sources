<?php
include 'RestClient.class.php';

#Cadastrar informativos
$url 		= "" ;
$titulo	= "Título do informativo" ;
$corpo 	= "Este conteúdo do corpo";
$token 	= ''

	$ex = RestClient::post($url,"{informativo:{titulo: '$titulo', corpo: '$corpo', entidade_id: '$token' }, commit: 'Create Informativos' }",$titulo,$corpo,"application/json");

print_r($ex) ;

foreach ($ex as $emails ){
	echo " $emails[status] ";
} ;


# Cadastar e-mails
$nome = "Eder Eduardo" ;
$email = "edereduardo@viaphp.joson";

#//content post

#
$ex = RestClient::post($url,"{email:{nome: '$nome', email: '$email', confirmado: '1', entidade_id: '$token' }, commit: 'Create Email' }",$nome,$email,"application/json");

print_r($ex) ;

foreach ($ex as $emails ){
  echo " $emails[status] ";
  } ;





?>





