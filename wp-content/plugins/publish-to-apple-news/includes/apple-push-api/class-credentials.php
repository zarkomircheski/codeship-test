<?php
namespace Apple_Push_API;

class Credentials {

	/**
	 * The key used in the authentication process, this is provided as part of
	 * the API credentials and should be safely stored in the server, do not
	 * hard-code it in the source code.
	 *
	 * @var string
	 * @since 0.2.0
	 * @access private
	 */
	private $key;

	/**
	 * The secret used in the authentication process, this is provided as part of
	 * the API credentials and should be safely stored in the server, do not
	 * hard-code it in the source code.
	 *
	 * @var string
	 * @since 0.2.0
	 * @access private
	 */
	private $secret;


	/**
	 * Constructor.
	 *
	 * @param string $key
	 * @param string $secret
	 */
	function __construct( $key, $secret ) {
		$this->secret = $secret;
		$this->key    = $key;
	}

	/**
	 * Get the key.
	 *
	 * @return string
	 * @access public
	 */
	public function key() {
		return $this->key;
	}

	/**
	 * Get the secret.
	 *
	 * @return string
	 * @access public
	 */
	public function secret() {
		return $this->secret;
	}

}
