<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Security\Listener;
use Buzz\Listener\ListenerInterface;
use Buzz\Message\Request;
use Buzz\Message\Response;


class WsseAuthenticationBuzzListener implements ListenerInterface{

	protected $username;
	protected $password;

	public function setCredentials($username, $password)
	{
		$this->username = $username;
		$this->password = $password;

		return $this;
	}

	public function preSend(Request $request)
	{
		if($this->username === null){
			throw new \RuntimeException("You have to setCredentials before using WsseListener with Buzz");
		}
		$username = $this->username;
		$created  = date('c');
		$nonce = substr(md5(uniqid('nonce_', true)),0,16);
		$nonce64 = base64_encode($nonce);
		$passwordDigest = base64_encode(sha1($nonce . $created . $this->password, true));
		$request->addHeader('Authorization: WSSE profile="UsernameToken"');
		$header = "X-WSSE: UsernameToken Username=\"{$username}\", PasswordDigest=\"{$passwordDigest}\", Nonce=\"{$nonce64}\", Created=\"{$created}\"";
		$request->addHeader($header);
	}

	public function postSend(Request $request, Response $response)
	{
	}
}