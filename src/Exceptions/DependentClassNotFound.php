<?php

namespace ParityBit\DependencyResolver\Exceptions;

/**
 * A class which *has dependencies* which this component is trying to resolve
 * could not be found.
 */
class DependentClassNotFound extends \LogicException
{

}
