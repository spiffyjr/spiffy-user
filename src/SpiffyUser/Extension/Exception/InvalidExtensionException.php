<?php

namespace SpiffyUser\Extension\Exception;

use SpiffyUser\Exception;

class InvalidExtensionException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}