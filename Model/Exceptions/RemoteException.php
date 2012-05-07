<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Model\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RemoteException extends \Exception{
	public function __construct($message, $code, \Exception $previous = null){
		parent::__construct($message, $code);
	}
}