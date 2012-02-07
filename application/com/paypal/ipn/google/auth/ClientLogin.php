<?php
namespace com\paypal\ipn\google\auth;

/**
 * Faz a requisição POST ao ClientLogin do Google para obter a autorização
 * de acesso para contas Google.
 * 
 * @author João Batista Neto
 */
class ClientLogin {
	/**
	 * URL do serviço de autorização ClientLogin do Google.
	 * @var string
	 */
	const URL = 'https://www.google.com/accounts/ClientLogin';

	/**
	 * Token de autorização.
	 * @var	string
	 */
	private $auth;

	/**
	 * Obtém o token de autorização do Google utilizando ClientLogin
	 * 
	 * @param	string $accountType Tipo da conta que está solicitando a
	 * 			autorização, os valores possíveis são:
	 * 			<ul>
	 * 			<li>GOOGLE</li>
	 * 			<li>HOSTED</li>
	 * 			<li>HOSTED_OR_GOOGLE</li>
	 * 			</ul>
	 * @param	string $Email Email completo do usuário, incluindo o domínio.
	 * @param	string $Passwd Senha do usuário.
	 * @param	string $source Uma string identificando a aplicação.
	 * @param	string $service Nome do serviço que será solicitada a
	 * 			autorização.
	 * @return	string O Token de autorização.
	 */
	public function getAuth(
							$accountType,
							$Email,
							$Passwd,
							$source,
							$service ) {

		if ( $this->auth === null ) {
			$curl = curl_init();

			curl_setopt( $curl , CURLOPT_URL , ClientLogin::URL );
			curl_setopt( $curl , CURLOPT_RETURNTRANSFER , 1 );
			curl_setopt( $curl , CURLOPT_POST , 1 );
			curl_setopt( $curl , CURLOPT_POSTFIELDS , http_build_query(
				array(
					'accountType' => $accountType,
					'Email' => $Email,
					'Passwd' => $Passwd,
					'source' => $source,
					'service' => $service
				)
			) );

			$responseStr = curl_exec( $curl );
			$responseArr = array();
			$matches = array();
			
			curl_close( $curl );

			if ( preg_match_all(
								"/\n?(?<field>\\w+)\\=(?<value>[^\n]+)/",
								$responseStr,
								$matches ) ) {

				foreach ( $matches[ 'field' ] as $offset => $field ) {
					$responseArr[ $field ] = $matches[ 'value' ][ $offset ];
				}

				if ( isset( $responseArr[ 'Auth' ] ) ) {
					$this->auth = $responseArr[ 'Auth' ];
				}
			}
		}

		return $this->auth;
	}
}