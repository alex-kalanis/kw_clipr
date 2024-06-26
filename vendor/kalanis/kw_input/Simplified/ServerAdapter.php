<?php

namespace kalanis\kw_input\Simplified;


use ArrayIterator;
use kalanis\kw_input\InputException;
use kalanis\kw_input\Interfaces;
use kalanis\kw_input\Parsers\FixServer;
use kalanis\kw_input\Traits;
use Traversable;


/**
 * Class ServerAdapter
 * @package kalanis\kw_input\Extras
 * Accessing _SERVER via ArrayAccess
 * @property string $PHP_SELF
 * @property string $argv
 * @property string $argc
 * @property string $GATEWAY_INTERFACE
 * @property string $SERVER_ADDR
 * @property string $SERVER_NAME
 * @property string $SERVER_SOFTWARE
 * @property string $SERVER_PROTOCOL
 * @property string $REQUEST_METHOD
 * @property string $REQUEST_TIME
 * @property string $REQUEST_TIME_FLOAT
 * @property string $QUERY_STRING
 * @property string $DOCUMENT_ROOT
 * @property string $HTTP_ACCEPT
 * @property string $HTTP_ACCEPT_CHARSET
 * @property string $HTTP_ACCEPT_ENCODING
 * @property string $HTTP_ACCEPT_LANGUAGE
 * @property string $HTTP_CONNECTION
 * @property string $HTTP_HOST
 * @property string $HTTP_REFERER
 * @property string $HTTP_USER_AGENT
 * @property string $HTTPS
 * @property string $REMOTE_ADDR
 * @property string $REMOTE_HOST
 * @property string $REMOTE_PORT
 * @property string $SCRIPT_FILENAME
 * @property string $SERVER_ADMIN
 * @property string $SERVER_PORT
 * @property string $SERVER_SIGNATURE
 * @property string $PATH_TRANSLATED
 * @property string $SCRIPT_NAME
 * @property string $REQUEST_URI
 * @property string $PHP_AUTH_DIGEST
 * @property string $PHP_AUTH_USER
 * @property string $PHP_AUTH_PW
 * @property string $AUTH_TYPE
 * @property string $PATH_INFO
 * @property string $ORIG_PATH_INFO
 */
class ServerAdapter implements Interfaces\IFilteredInputs
{
    use Traits\TFill;
    use Traits\TInputEntries;
    use Traits\TKV;

    public final function __construct()
    {
        $this->input = $this->keysValues(
            $this->fillFromEntries(
                Interfaces\IEntry::SOURCE_SERVER,
                FixServer::updateAuth(
                    FixServer::updateVars($_SERVER)
                )
            )
        );
    }

    /**
     * @param string|int $offset
     * @return mixed
     */
    public final function __get($offset)
    {
        return $this->offsetGet($offset);
    }

    /**
     * @param string $offset
     * @param mixed|null $value
     * @throws InputException
     */
    public final function __set($offset, $value): void
    {
        $this->offsetSet($offset, $value);
        // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * @param string $offset
     * @return bool
     */
    public final function __isset($offset): bool
    {
        return $this->offsetExists($offset);
    }

    /**
     * @param string $offset
     * @throws InputException
     */
    public final function __unset($offset): void
    {
        $this->offsetUnset($offset);
        // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws InputException
     */
    public final function offsetSet($offset, $value): void
    {
        throw new InputException('Cannot write into _SERVER variable');
    }

    /**
     * @param mixed $offset
     * @throws InputException
     */
    public final function offsetUnset($offset): void
    {
        throw new InputException('Cannot write into _SERVER variable');
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator(
            $this->input,
            ArrayIterator::STD_PROP_LIST | ArrayIterator::ARRAY_AS_PROPS
        );
    }

    /**
     * @return string
     * @codeCoverageIgnore sets are disabled
     */
    protected function defaultSource(): string
    {
        return Interfaces\IEntry::SOURCE_SERVER;
    }
}
