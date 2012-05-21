<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Model\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;

use JMS\SerializerBundle\Annotation as Serializer;

class HavingJobPendingException extends HttpException{
    /**
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @Serializer\Type("integer")
     */
    protected $statusCode;

    public function __construct() {
        parent::__construct(503, "Having already a UpdateJob pending");
    }
}