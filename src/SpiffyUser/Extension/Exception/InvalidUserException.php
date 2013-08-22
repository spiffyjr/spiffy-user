<?php

namespace SpiffyUser\Extension\Exception;

use SpiffyUser\Exception;

class InvalidUserException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}