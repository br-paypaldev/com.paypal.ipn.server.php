<?php
namespace com\paypal\ipn\sample;

use com\paypal\ipn\google\ac2dm\AndroidCloud2DeviceMessaging;
use com\paypal\ipn\IPNHandler;

/**
 * Manipulador de notificação instantânea de pagamento que envia a
 * mensagem para dispositivos Android.
 * 
 * @author	João Batista Neto
 */
class IPNToAC2DMHandler implements IPNHandler {
	/**
	 * @var	com\paypal\ipn\sample\SampleModel
	 */
	private $model;
	
	public function __construct( SampleModel $model ) {
		$this->model = $model;
	}
	
	/* (non-PHPdoc)
	 * @see com\paypal\ipn\IPNHandler#handle()
	 */
	public function handle( $isVerified, array $message ) {
		if ( $isVerified && isset( $message[ 'receiver_email' ] ) ) {
			$ac2dm = new AndroidCloud2DeviceMessaging();
			
			foreach ( $message as $field => $value ) {
				$ac2dm->addData( $field , $value, 'ipn' );
			}
			
			$ac2dm->send(
				$this->model->getRegistrationId( $message['receiver_email' ] ),
				$this->model->getAuth() );
		}
	}
}