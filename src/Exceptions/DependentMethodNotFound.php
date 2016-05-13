<?php

namespace ParityBit\DependencyResolver\Exceptions;

/**
 * A method which *has dependencies* which this component is trying to resolve
 * could not be found.
 */
class DependentMethodNotFound extends \LogicException
{

}
