<?php namespace Octoshop\Core\Util;

class Uuid
{
    /**
     * 00001111  Clears all bits of version byte with AND
     * @var int
     */
    const CLEAR_VER = 15;

    /**
     * 00111111  Clears all relevant bits of variant byte with AND
     * @var int
     */
    const CLEAR_VAR = 63;

    /**
     * 10000000  The RFC 4122 variant (this variant)
     * @var int
     */
    const VAR_RFC = 128;

    /**
     * 01000000
     * @var int
     */
    const VERSION_4 = 64;

    /**
     * Get a new Uuid instance from binary UUID date.
     * @param string $Uuid  Binary representation of the UUID
     */
    public function __construct($uuid)
    {
        $this->binary = $uuid;

        $this->string = implode('-', [
            bin2hex(substr($uuid, 0, 4)),
            bin2hex(substr($uuid, 4, 2)),
            bin2hex(substr($uuid, 6, 2)),
            bin2hex(substr($uuid, 8, 2)),
            bin2hex(substr($uuid, 10, 6))
        ]);
    }

    /**
     * Create a new Version 4 UUID
     * @return Uuid
     */
    public static function generate()
    {
        $uuid = static::randomBytes();

        // Set variant
        $uuid[8] = chr(ord($uuid[8]) & static::CLEAR_VAR | static::VAR_RFC);

        // Set version
        $uuid[6] = chr(ord($uuid[6]) & static::CLEAR_VER | static::VERSION_4);

        return new static($uuid);
    }

    /**
     * Import an existing UUID
     * @param mixed $uuid  UUID as either binary, string or a Uuid instance.
     * @return Uuid
     */
    public static function import($uuid)
    {
        if ($uuid instanceof self) {
            return $uuid;
        }

        if (strlen($uuid) === 36) {
            $uuid = hex2bin(str_replace('-', '', $uuid));
        }

        return new static($uuid);
    }

    /**
     * Generate random bytes for the UUID
     * @param $bytes
     * @return string
     */
    protected static function randomBytes($bytes = 16)
    {
        if (function_exists('random_bytes')) {
            return random_bytes($bytes);
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            return openssl_random_pseudo_bytes($bytes);
        }

        if (function_exists('mcrypt_encrypt')) {
            return mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM);
        }

        throw new SystemException("You must be running PHP 7, or an older version with Mcrypt or OpenSSL.");
    }

    /**
     * Get the UUID's string representation.
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }
}
