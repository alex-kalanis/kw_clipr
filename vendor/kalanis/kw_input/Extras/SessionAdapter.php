<?php

namespace kalanis\kw_input\Extras;


use ArrayAccess;


/**
 * Class SessionAdapter
 * @package kalanis\kw_input\Extras
 * Connect session via ArrayAccess
 */
class SessionAdapter implements ArrayAccess
{
    public final function __get($offset)
    {
        return $this->offsetGet($offset);
    }

    public final function __set($offset, $value)
    {
        $this->offsetSet($offset, $value);
    }

    public final function __isset($offset)
    {
        return $this->offsetExists($offset);
    }

    public final function __unset($offset)
    {
        $this->offsetUnset($offset);
    }

    public final function offsetExists($offset)
    {
        return isset($_SESSION[$this->removeNullBytes($offset)]);
    }

    public final function offsetGet($offset)
    {
        return $_SESSION[$this->removeNullBytes($offset)];
    }

    public final function offsetSet($offset, $value)
    {
        $_SESSION[$this->removeNullBytes($offset)] = $value;
    }

    public final function offsetUnset($offset)
    {
        unset($_SESSION[$this->removeNullBytes($offset)]);
    }

    protected function removeNullBytes($string)
    {
        return str_replace(chr(0), '', $string);
    }
}
