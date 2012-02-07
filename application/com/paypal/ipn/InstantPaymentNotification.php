<?php
namespace com\paypal\ipn;

use \BadMethodCallException;

/**
 * Observador de Notificações de Pagamento Instantâneo.
 * 
 * @author	João Batista Neto
 */
class InstantPaymentNotification {
	/**
	 * Endpoint de produção.
	 * @var	string
	 */
	const HOST = 'https://www.paypal.com';
	
	/**
	 * Endpoint de produção.
	 * @var	string
	 */
	const SANDBOX_HOST = 'https://www.sandbox.paypal.com';
	
	/**
	 * @var string
	 */
	private $endpoint = InstantPaymentNotification::HOST;
	
	/**
	 * @var com\paypal\ipn\IPNHandler
	 */
	private $ipnHandler;
	
	/**
	 * Constroi o observador no notificação instantânea de pagamento informando
	 * o ambiente que será utilizado para validação.
	 * 
	 * @param	boolean $sandbox Define se será utilizado o Sandbox
	 * @throws	InvalidArgumentException
	 */
	public function __construct( $sandbox = false ) {
		if ( !!$sandbox ) {
			$this->endpoint = InstantPaymentNotification::SANDBOX_HOST;
		}
		
		$this->endpoint .= '/cgi-bin/webscr?cmd=_notify-validate';
	}
	
	/**
	 * Aguarda por notificações de pagamento instantânea; Caso uma nova
	 * notificação seja recebida, faz a verificação e notifica um manipulador
	 * com o status (verificada ou não) e a mensagem recebida.
	 * 
	 * @param	array $post Dados postatos pelo PayPal.
	 * @see     InstantPaymentNotification::setIPNHandler()
	 * @throws  BadMethodCallException Caso o método seja chamado antes de um
	 * 			manipulador ter sido definido ou nenhum email de recebedor
	 * 			tenha sido informado.
	 */
	public function listen( array $post ) {
		if ( $this->ipnHandler !== null && isset( $post[ 'receiver_email' ] ) ) {
			$curl = curl_init ();
			
			curl_setopt( $curl, CURLOPT_URL, $this->endpoint );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_POST, 1 );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query(
				$post
			) );
			
			$response = curl_exec( $curl );
			$error = curl_error( $curl );
			$errno = curl_errno( $curl );
			
			curl_close ( $curl );
			
			if ( empty( $error ) && $errno == 0 ) {
				$this->ipnHandler->handle(
					$response == 'VERIFIED', $post
				);
			}
		}
	}
	
	/**
	 * Define o objeto que irá manipular as notificações de pagamento
	 * instantâneas enviadas pelo PayPal.
	 * 
	 * @param	com\paypal\ipn\IPNHandler $ipnHandler
	 */
	public function setIPNHandler( IPNHandler $ipnHandler ) {
		$this->ipnHandler = $ipnHandler;
	}
}