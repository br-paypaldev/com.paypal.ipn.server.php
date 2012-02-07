<?php
namespace com\paypal\ipn\google\ac2dm;

/**
 * A classe AndroidCloud2DeviceMessaging faz integração com o serviço
 * Android Cloud to Device Messaging (C2DM) para enviar dados para dispositivos
 * Android.
 * 
 * @author	João Batista Neto
 */
class AndroidCloud2DeviceMessaging {
	/**
	 * URL do serviço C2DM.
	 * @var	string
	 */
	const URL = 'https://android.apis.google.com/c2dm/send';
	
	/**
	 * Conjunto de pares key=value que serão enviados para o dispositivo.
	 * @var	array
	 */
	private $data = array();

	/**
	 * Adiciona um par key=value que será enviado para o dispositivo android.
	 * 
	 * @param	string $key A chave que será enviada para o dispositivo.
	 * @param	string $value O valor da chave.
	 * @param	string $collapseKey Chave de agrupamento que será utilizado pelo
	 * 			Google para evitar que várias mensagens do mesmo tipo sejam
	 * 			enviadas para o usuário de uma vez quando o dispositivo fique
	 * 			online.
	 */
	public function addData( $key , $value , $collapseKey ) {
		if ( !isset( $this->data[ $collapseKey ] ) ) {
			$this->data[ $collapseKey ] = array();
		}
		
		$this->data[ $collapseKey ][ 'data.' . $key ] = $value;
	}
	
	/**
	 * Remove todas os pares key=value.
	 */
	public function clean() {
		$this->data = array();
	}
	
	/**
	 * Envia a mensagem para o servidor C2DM.
	 * 
	 * @param	string $registrationId ID de registro do dispositivo android.
	 * @param	string $auth Token de autorização
	 * @see		com\google\auth\ClientLogin::getAuth()
	 */
	public function send( $registrationId , $auth ) {
		$curl = curl_init();

		curl_setopt( $curl , CURLOPT_URL , AndroidCloud2DeviceMessaging::URL );
		curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false );
		curl_setopt( $curl , CURLOPT_RETURNTRANSFER , 1 );
		curl_setopt( $curl , CURLOPT_POST , 1 );
		curl_setopt( $curl , CURLOPT_HTTPHEADER , array(
			'Authorization: GoogleLogin auth=' . $auth
		) );
		
		foreach ( $this->data as $collapseKey => $data ) {
			$data[ 'registration_id' ] = $registrationId;
			$data[ 'collapse_key' ] = $collapseKey;
			
			curl_setopt( $curl , CURLOPT_POSTFIELDS , http_build_query($data));
			
			var_dump( curl_exec( $curl ) );
		}
		
		curl_close( $curl );
	}
}