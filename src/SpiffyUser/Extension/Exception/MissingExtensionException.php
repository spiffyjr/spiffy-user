<?php

namespace SpiffyUser\Extension\Exception;

use SpiffyUser\Exception;

class MissingExtensionException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}