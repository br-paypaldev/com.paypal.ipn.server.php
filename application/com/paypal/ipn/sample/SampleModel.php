<?php
namespace com\paypal\ipn\sample;

use com\paypal\ipn\google\auth\ClientLogin;

class SampleModel {
	public function getAuth() {
		$user = 'usuario@gmail.com';
		$pswd = 'senha';
		$type = 'GOOGLE';
		
		//TODO: verificar se existe uma autorização e definir regras de negócio
		//para atualizá-la.
		
		$cl = new ClientLogin();
		
		return $cl->getAuth( $type, $user, $pswd,'PayPalX-com.paypal.ipn-1.0',
							'ac2dm' );
	}
	
	public function getRegistrationId( $email ) {
		//TODO: adicionar obtenção do id de registro

		return null;
	}
}