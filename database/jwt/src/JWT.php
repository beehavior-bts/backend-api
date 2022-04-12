<?php

namespace Firebase\JWT;

use ArrayAccess;
use DomainException;
use Exception;
use InvalidArgumentException;
use OpenSSLAsymmetricKey;
<<<<<<< HEAD
use UnexpectedValueException;
use DateTime;
=======
use OpenSSLCertificate;
use TypeError;
use UnexpectedValueException;
use DateTime;
use stdClass;
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb

/**
 * JSON Web Token implementation, based on this spec:
 * https://tools.ietf.org/html/rfc7519
 *
 * PHP version 5
 *
 * @category Authentication
 * @package  Authentication_JWT
 * @author   Neuman Vong <neuman@twilio.com>
 * @author   Anant Narayanan <anant@php.net>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */
class JWT
{
<<<<<<< HEAD
    const ASN1_INTEGER = 0x02;
    const ASN1_SEQUENCE = 0x10;
    const ASN1_BIT_STRING = 0x03;
=======
    private const ASN1_INTEGER = 0x02;
    private const ASN1_SEQUENCE = 0x10;
    private const ASN1_BIT_STRING = 0x03;
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb

    /**
     * When checking nbf, iat or expiration times,
     * we want to provide some extra leeway time to
     * account for clock skew.
<<<<<<< HEAD
=======
     *
     * @var int
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
     */
    public static $leeway = 0;

    /**
     * Allow the current timestamp to be specified.
     * Useful for fixing a value within unit testing.
<<<<<<< HEAD
     *
     * Will default to PHP time() value if null.
     */
    public static $timestamp = null;

    public static $supported_algs = array(
        'ES384' => array('openssl', 'SHA384'),
        'ES256' => array('openssl', 'SHA256'),
        'HS256' => array('hash_hmac', 'SHA256'),
        'HS384' => array('hash_hmac', 'SHA384'),
        'HS512' => array('hash_hmac', 'SHA512'),
        'RS256' => array('openssl', 'SHA256'),
        'RS384' => array('openssl', 'SHA384'),
        'RS512' => array('openssl', 'SHA512'),
        'EdDSA' => array('sodium_crypto', 'EdDSA'),
    );
=======
     * Will default to PHP time() value if null.
     *
     * @var ?int
     */
    public static $timestamp = null;

    /**
     * @var array<string, string[]>
     */
    public static $supported_algs = [
        'ES384' => ['openssl', 'SHA384'],
        'ES256' => ['openssl', 'SHA256'],
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
        'EdDSA' => ['sodium_crypto', 'EdDSA'],
    ];
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb

    /**
     * Decodes a JWT string into a PHP object.
     *
<<<<<<< HEAD
     * @param string                    $jwt            The JWT
     * @param Key|array<Key>|mixed      $keyOrKeyArray  The Key or array of Key objects.
     *                                                  If the algorithm used is asymmetric, this is the public key
     *                                                  Each Key object contains an algorithm and matching key.
     *                                                  Supported algorithms are 'ES384','ES256', 'HS256', 'HS384',
     *                                                  'HS512', 'RS256', 'RS384', and 'RS512'
     * @param array                     $allowed_algs   [DEPRECATED] List of supported verification algorithms. Only
     *                                                  should be used for backwards  compatibility.
     *
     * @return object The JWT's payload as a PHP object
     *
     * @throws InvalidArgumentException     Provided JWT was empty
=======
     * @param string                 $jwt            The JWT
     * @param Key|array<string, Key> $keyOrKeyArray  The Key or associative array of key IDs (kid) to Key objects.
     *                                               If the algorithm used is asymmetric, this is the public key
     *                                               Each Key object contains an algorithm and matching key.
     *                                               Supported algorithms are 'ES384','ES256', 'HS256', 'HS384',
     *                                               'HS512', 'RS256', 'RS384', and 'RS512'
     *
     * @return stdClass The JWT's payload as a PHP object
     *
     * @throws InvalidArgumentException     Provided key/key-array was empty
     * @throws DomainException              Provided JWT is malformed
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
     * @throws UnexpectedValueException     Provided JWT was invalid
     * @throws SignatureInvalidException    Provided JWT was invalid because the signature verification failed
     * @throws BeforeValidException         Provided JWT is trying to be used before it's eligible as defined by 'nbf'
     * @throws BeforeValidException         Provided JWT is trying to be used before it's been created as defined by 'iat'
     * @throws ExpiredException             Provided JWT has since expired, as defined by the 'exp' claim
     *
     * @uses jsonDecode
     * @uses urlsafeB64Decode
     */
<<<<<<< HEAD
    public static function decode($jwt, $keyOrKeyArray, array $allowed_algs = array())
    {
=======
    public static function decode(
        string $jwt,
        $keyOrKeyArray
    ): stdClass {
        // Validate JWT
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        $timestamp = \is_null(static::$timestamp) ? \time() : static::$timestamp;

        if (empty($keyOrKeyArray)) {
            throw new InvalidArgumentException('Key may not be empty');
        }
        $tks = \explode('.', $jwt);
        if (\count($tks) != 3) {
            throw new UnexpectedValueException('Wrong number of segments');
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
<<<<<<< HEAD
        if (null === ($header = static::jsonDecode(static::urlsafeB64Decode($headb64)))) {
            throw new UnexpectedValueException('Invalid header encoding');
        }
        if (null === $payload = static::jsonDecode(static::urlsafeB64Decode($bodyb64))) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }
        if (false === ($sig = static::urlsafeB64Decode($cryptob64))) {
            throw new UnexpectedValueException('Invalid signature encoding');
        }
=======
        $headerRaw = static::urlsafeB64Decode($headb64);
        if (null === ($header = static::jsonDecode($headerRaw))) {
            throw new UnexpectedValueException('Invalid header encoding');
        }
        $payloadRaw = static::urlsafeB64Decode($bodyb64);
        if (null === ($payload = static::jsonDecode($payloadRaw))) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }
        if (!$payload instanceof stdClass) {
            throw new UnexpectedValueException('Payload must be a JSON object');
        }
        $sig = static::urlsafeB64Decode($cryptob64);
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        if (empty($header->alg)) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        if (empty(static::$supported_algs[$header->alg])) {
            throw new UnexpectedValueException('Algorithm not supported');
        }

<<<<<<< HEAD
        list($keyMaterial, $algorithm) = self::getKeyMaterialAndAlgorithm(
            $keyOrKeyArray,
            empty($header->kid) ? null : $header->kid
        );

        if (empty($algorithm)) {
            // Use deprecated "allowed_algs" to determine if the algorithm is supported.
            // This opens up the possibility of an attack in some implementations.
            // @see https://github.com/firebase/php-jwt/issues/351
            if (!\in_array($header->alg, $allowed_algs)) {
                throw new UnexpectedValueException('Algorithm not allowed');
            }
        } else {
            // Check the algorithm
            if (!self::constantTimeEquals($algorithm, $header->alg)) {
                // See issue #351
                throw new UnexpectedValueException('Incorrect key for this algorithm');
            }
=======
        $key = self::getKey($keyOrKeyArray, property_exists($header, 'kid') ? $header->kid : null);

        // Check the algorithm
        if (!self::constantTimeEquals($key->getAlgorithm(), $header->alg)) {
            // See issue #351
            throw new UnexpectedValueException('Incorrect key for this algorithm');
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        }
        if ($header->alg === 'ES256' || $header->alg === 'ES384') {
            // OpenSSL expects an ASN.1 DER sequence for ES256/ES384 signatures
            $sig = self::signatureToDER($sig);
        }
<<<<<<< HEAD

        if (!static::verify("$headb64.$bodyb64", $sig, $keyMaterial, $header->alg)) {
=======
        if (!self::verify("$headb64.$bodyb64", $sig, $key->getKeyMaterial(), $header->alg)) {
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
            throw new SignatureInvalidException('Signature verification failed');
        }

        // Check the nbf if it is defined. This is the time that the
        // token can actually be used. If it's not yet that time, abort.
        if (isset($payload->nbf) && $payload->nbf > ($timestamp + static::$leeway)) {
            throw new BeforeValidException(
                'Cannot handle token prior to ' . \date(DateTime::ISO8601, $payload->nbf)
            );
        }

        // Check that this token has been created before 'now'. This prevents
        // using tokens that have been created for later use (and haven't
        // correctly used the nbf claim).
        if (isset($payload->iat) && $payload->iat > ($timestamp + static::$leeway)) {
            throw new BeforeValidException(
                'Cannot handle token prior to ' . \date(DateTime::ISO8601, $payload->iat)
            );
        }

        // Check if this token has expired.
        if (isset($payload->exp) && ($timestamp - static::$leeway) >= $payload->exp) {
            throw new ExpiredException('Expired token');
        }

        return $payload;
    }

    /**
     * Converts and signs a PHP object or array into a JWT string.
     *
<<<<<<< HEAD
     * @param object|array      $payload    PHP object or array
     * @param string|resource   $key        The secret key.
     *                                      If the algorithm used is asymmetric, this is the private key
     * @param string            $alg        The signing algorithm.
     *                                      Supported algorithms are 'ES384','ES256', 'HS256', 'HS384',
     *                                      'HS512', 'RS256', 'RS384', and 'RS512'
     * @param mixed             $keyId
     * @param array             $head       An array with header elements to attach
=======
     * @param array<mixed>          $payload PHP array
     * @param string|resource|OpenSSLAsymmetricKey|OpenSSLCertificate $key The secret key.
     * @param string                $keyId
     * @param array<string, string> $head    An array with header elements to attach
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
     *
     * @return string A signed JWT
     *
     * @uses jsonEncode
     * @uses urlsafeB64Encode
     */
<<<<<<< HEAD
    public static function encode($payload, $key, $alg = 'HS256', $keyId = null, $head = null)
    {
        $header = array('typ' => 'JWT', 'alg' => $alg);
=======
    public static function encode(
        array $payload,
        $key,
        string $alg,
        string $keyId = null,
        array $head = null
    ): string {
        $header = ['typ' => 'JWT', 'alg' => $alg];
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        if ($keyId !== null) {
            $header['kid'] = $keyId;
        }
        if (isset($head) && \is_array($head)) {
            $header = \array_merge($head, $header);
        }
<<<<<<< HEAD
        $segments = array();
        $segments[] = static::urlsafeB64Encode(static::jsonEncode($header));
        $segments[] = static::urlsafeB64Encode(static::jsonEncode($payload));
=======
        $segments = [];
        $segments[] = static::urlsafeB64Encode((string) static::jsonEncode($header));
        $segments[] = static::urlsafeB64Encode((string) static::jsonEncode($payload));
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        $signing_input = \implode('.', $segments);

        $signature = static::sign($signing_input, $key, $alg);
        $segments[] = static::urlsafeB64Encode($signature);

        return \implode('.', $segments);
    }

    /**
     * Sign a string with a given key and algorithm.
     *
<<<<<<< HEAD
     * @param string            $msg    The message to sign
     * @param string|resource   $key    The secret key
     * @param string            $alg    The signing algorithm.
     *                                  Supported algorithms are 'ES384','ES256', 'HS256', 'HS384',
     *                                  'HS512', 'RS256', 'RS384', and 'RS512'
=======
     * @param string $msg  The message to sign
     * @param string|resource|OpenSSLAsymmetricKey|OpenSSLCertificate  $key  The secret key.
     * @param string $alg  Supported algorithms are 'ES384','ES256', 'HS256', 'HS384',
     *                    'HS512', 'RS256', 'RS384', and 'RS512'
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
     *
     * @return string An encrypted message
     *
     * @throws DomainException Unsupported algorithm or bad key was specified
     */
<<<<<<< HEAD
    public static function sign($msg, $key, $alg = 'HS256')
    {
=======
    public static function sign(
        string $msg,
        $key,
        string $alg
    ): string {
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        if (empty(static::$supported_algs[$alg])) {
            throw new DomainException('Algorithm not supported');
        }
        list($function, $algorithm) = static::$supported_algs[$alg];
        switch ($function) {
            case 'hash_hmac':
<<<<<<< HEAD
                return \hash_hmac($algorithm, $msg, $key, true);
            case 'openssl':
                $signature = '';
                $success = \openssl_sign($msg, $signature, $key, $algorithm);
=======
                if (!is_string($key)) {
                    throw new InvalidArgumentException('key must be a string when using hmac');
                }
                return \hash_hmac($algorithm, $msg, $key, true);
            case 'openssl':
                $signature = '';
                $success = \openssl_sign($msg, $signature, $key, $algorithm); // @phpstan-ignore-line
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
                if (!$success) {
                    throw new DomainException("OpenSSL unable to sign data");
                }
                if ($alg === 'ES256') {
                    $signature = self::signatureFromDER($signature, 256);
                } elseif ($alg === 'ES384') {
                    $signature = self::signatureFromDER($signature, 384);
                }
                return $signature;
            case 'sodium_crypto':
                if (!function_exists('sodium_crypto_sign_detached')) {
                    throw new DomainException('libsodium is not available');
                }
<<<<<<< HEAD
                try {
                    // The last non-empty line is used as the key.
                    $lines = array_filter(explode("\n", $key));
                    $key = base64_decode(end($lines));
=======
                if (!is_string($key)) {
                    throw new InvalidArgumentException('key must be a string when using EdDSA');
                }
                try {
                    // The last non-empty line is used as the key.
                    $lines = array_filter(explode("\n", $key));
                    $key = base64_decode((string) end($lines));
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
                    return sodium_crypto_sign_detached($msg, $key);
                } catch (Exception $e) {
                    throw new DomainException($e->getMessage(), 0, $e);
                }
        }
<<<<<<< HEAD
=======

        throw new DomainException('Algorithm not supported');
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    }

    /**
     * Verify a signature with the message, key and method. Not all methods
     * are symmetric, so we must have a separate verify and sign method.
     *
<<<<<<< HEAD
     * @param string            $msg        The original message (header and body)
     * @param string            $signature  The original signature
     * @param string|resource   $key        For HS*, a string key works. for RS*, must be a resource of an openssl public key
     * @param string            $alg        The algorithm
=======
     * @param string $msg         The original message (header and body)
     * @param string $signature   The original signature
     * @param string|resource|OpenSSLAsymmetricKey|OpenSSLCertificate  $keyMaterial For HS*, a string key works. for RS*, must be an instance of OpenSSLAsymmetricKey
     * @param string $alg         The algorithm
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
     *
     * @return bool
     *
     * @throws DomainException Invalid Algorithm, bad key, or OpenSSL failure
     */
<<<<<<< HEAD
    private static function verify($msg, $signature, $key, $alg)
    {
=======
    private static function verify(
        string $msg,
        string $signature,
        $keyMaterial,
        string $alg
    ): bool {
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        if (empty(static::$supported_algs[$alg])) {
            throw new DomainException('Algorithm not supported');
        }

        list($function, $algorithm) = static::$supported_algs[$alg];
        switch ($function) {
            case 'openssl':
<<<<<<< HEAD
                $success = \openssl_verify($msg, $signature, $key, $algorithm);
=======
                $success = \openssl_verify($msg, $signature, $keyMaterial, $algorithm); // @phpstan-ignore-line
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
                if ($success === 1) {
                    return true;
                } elseif ($success === 0) {
                    return false;
                }
                // returns 1 on success, 0 on failure, -1 on error.
                throw new DomainException(
                    'OpenSSL error: ' . \openssl_error_string()
                );
            case 'sodium_crypto':
              if (!function_exists('sodium_crypto_sign_verify_detached')) {
                  throw new DomainException('libsodium is not available');
              }
<<<<<<< HEAD
              try {
                  // The last non-empty line is used as the key.
                  $lines = array_filter(explode("\n", $key));
                  $key = base64_decode(end($lines));
=======
              if (!is_string($keyMaterial)) {
                  throw new InvalidArgumentException('key must be a string when using EdDSA');
              }
              try {
                  // The last non-empty line is used as the key.
                  $lines = array_filter(explode("\n", $keyMaterial));
                  $key = base64_decode((string) end($lines));
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
                  return sodium_crypto_sign_verify_detached($signature, $msg, $key);
              } catch (Exception $e) {
                  throw new DomainException($e->getMessage(), 0, $e);
              }
            case 'hash_hmac':
            default:
<<<<<<< HEAD
                $hash = \hash_hmac($algorithm, $msg, $key, true);
                return self::constantTimeEquals($signature, $hash);
=======
                if (!is_string($keyMaterial)) {
                    throw new InvalidArgumentException('key must be a string when using hmac');
                }
                $hash = \hash_hmac($algorithm, $msg, $keyMaterial, true);
                return self::constantTimeEquals($hash, $signature);
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        }
    }

    /**
     * Decode a JSON string into a PHP object.
     *
     * @param string $input JSON string
     *
<<<<<<< HEAD
     * @return object Object representation of JSON string
     *
     * @throws DomainException Provided string was invalid JSON
     */
    public static function jsonDecode($input)
    {
        if (\version_compare(PHP_VERSION, '5.4.0', '>=') && !(\defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            /** In PHP >=5.4.0, json_decode() accepts an options parameter, that allows you
             * to specify that large ints (like Steam Transaction IDs) should be treated as
             * strings, rather than the PHP default behaviour of converting them to floats.
             */
            $obj = \json_decode($input, false, 512, JSON_BIGINT_AS_STRING);
        } else {
            /** Not all servers will support that, however, so for older versions we must
             * manually detect large ints in the JSON string and quote them (thus converting
             *them to strings) before decoding, hence the preg_replace() call.
             */
            $max_int_length = \strlen((string) PHP_INT_MAX) - 1;
            $json_without_bigints = \preg_replace('/:\s*(-?\d{'.$max_int_length.',})/', ': "$1"', $input);
            $obj = \json_decode($json_without_bigints);
        }

        if ($errno = \json_last_error()) {
            static::handleJsonError($errno);
=======
     * @return mixed The decoded JSON string
     *
     * @throws DomainException Provided string was invalid JSON
     */
    public static function jsonDecode(string $input)
    {
        $obj = \json_decode($input, false, 512, JSON_BIGINT_AS_STRING);

        if ($errno = \json_last_error()) {
            self::handleJsonError($errno);
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        } elseif ($obj === null && $input !== 'null') {
            throw new DomainException('Null result with non-null input');
        }
        return $obj;
    }

    /**
<<<<<<< HEAD
     * Encode a PHP object into a JSON string.
     *
     * @param object|array $input A PHP object or array
     *
     * @return string JSON representation of the PHP object or array
     *
     * @throws DomainException Provided object could not be encoded to valid JSON
     */
    public static function jsonEncode($input)
    {
        $json = \json_encode($input);
        if ($errno = \json_last_error()) {
            static::handleJsonError($errno);
        } elseif ($json === 'null' && $input !== null) {
            throw new DomainException('Null result with non-null input');
        }
=======
     * Encode a PHP array into a JSON string.
     *
     * @param array<mixed> $input A PHP array
     *
     * @return string JSON representation of the PHP array
     *
     * @throws DomainException Provided object could not be encoded to valid JSON
     */
    public static function jsonEncode(array $input): string
    {
        if (PHP_VERSION_ID >= 50400) {
            $json = \json_encode($input, \JSON_UNESCAPED_SLASHES);
        } else {
            // PHP 5.3 only
            $json = \json_encode($input);
        }
        if ($errno = \json_last_error()) {
            self::handleJsonError($errno);
        } elseif ($json === 'null' && $input !== null) {
            throw new DomainException('Null result with non-null input');
        }
        if ($json === false) {
            throw new DomainException('Provided object could not be encoded to valid JSON');
        }
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        return $json;
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
<<<<<<< HEAD
     */
    public static function urlsafeB64Decode($input)
=======
     *
     * @throws InvalidArgumentException invalid base64 characters
     */
    public static function urlsafeB64Decode(string $input): string
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \base64_decode(\strtr($input, '-_', '+/'));
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string The base64 encode of what you passed in
     */
<<<<<<< HEAD
    public static function urlsafeB64Encode($input)
=======
    public static function urlsafeB64Encode(string $input): string
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        return \str_replace('=', '', \strtr(\base64_encode($input), '+/', '-_'));
    }


    /**
     * Determine if an algorithm has been provided for each Key
     *
<<<<<<< HEAD
     * @param Key|array<Key>|mixed $keyOrKeyArray
     * @param string|null $kid
     *
     * @throws UnexpectedValueException
     *
     * @return array containing the keyMaterial and algorithm
     */
    private static function getKeyMaterialAndAlgorithm($keyOrKeyArray, $kid = null)
    {
        if (
            is_string($keyOrKeyArray)
            || is_resource($keyOrKeyArray)
            || $keyOrKeyArray instanceof OpenSSLAsymmetricKey
        ) {
            return array($keyOrKeyArray, null);
        }

        if ($keyOrKeyArray instanceof Key) {
            return array($keyOrKeyArray->getKeyMaterial(), $keyOrKeyArray->getAlgorithm());
        }

        if (is_array($keyOrKeyArray) || $keyOrKeyArray instanceof ArrayAccess) {
            if (!isset($kid)) {
                throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
            }
            if (!isset($keyOrKeyArray[$kid])) {
                throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
            }

            $key = $keyOrKeyArray[$kid];

            if ($key instanceof Key) {
                return array($key->getKeyMaterial(), $key->getAlgorithm());
            }

            return array($key, null);
        }

        throw new UnexpectedValueException(
            '$keyOrKeyArray must be a string|resource key, an array of string|resource keys, '
            . 'an instance of Firebase\JWT\Key key or an array of Firebase\JWT\Key keys'
        );
    }

    /**
     * @param string $left
     * @param string $right
     * @return bool
     */
    public static function constantTimeEquals($left, $right)
=======
     * @param Key|array<string, Key> $keyOrKeyArray
     * @param string|null            $kid
     *
     * @throws UnexpectedValueException
     *
     * @return Key
     */
    private static function getKey(
        $keyOrKeyArray,
        ?string $kid
    ): Key {
        if ($keyOrKeyArray instanceof Key) {
            return $keyOrKeyArray;
        }

        foreach ($keyOrKeyArray as $keyId => $key) {
            if (!$key instanceof Key) {
                throw new TypeError(
                    '$keyOrKeyArray must be an instance of Firebase\JWT\Key key or an '
                    . 'array of Firebase\JWT\Key keys'
                );
            }
        }
        if (!isset($kid)) {
            throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
        }
        if (!isset($keyOrKeyArray[$kid])) {
            throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
        }

        return $keyOrKeyArray[$kid];
    }

    /**
     * @param string $left  The string of known length to compare against
     * @param string $right The user-supplied string
     * @return bool
     */
    public static function constantTimeEquals(string $left, string $right): bool
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        if (\function_exists('hash_equals')) {
            return \hash_equals($left, $right);
        }
<<<<<<< HEAD
        $len = \min(static::safeStrlen($left), static::safeStrlen($right));
=======
        $len = \min(self::safeStrlen($left), self::safeStrlen($right));
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb

        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= (\ord($left[$i]) ^ \ord($right[$i]));
        }
<<<<<<< HEAD
        $status |= (static::safeStrlen($left) ^ static::safeStrlen($right));
=======
        $status |= (self::safeStrlen($left) ^ self::safeStrlen($right));
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb

        return ($status === 0);
    }

    /**
     * Helper method to create a JSON error.
     *
     * @param int $errno An error number from json_last_error()
     *
<<<<<<< HEAD
     * @return void
     */
    private static function handleJsonError($errno)
    {
        $messages = array(
=======
     * @throws DomainException
     *
     * @return void
     */
    private static function handleJsonError(int $errno): void
    {
        $messages = [
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters' //PHP >= 5.3.3
<<<<<<< HEAD
        );
=======
        ];
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        throw new DomainException(
            isset($messages[$errno])
            ? $messages[$errno]
            : 'Unknown JSON error: ' . $errno
        );
    }

    /**
     * Get the number of bytes in cryptographic strings.
     *
     * @param string $str
     *
     * @return int
     */
<<<<<<< HEAD
    private static function safeStrlen($str)
=======
    private static function safeStrlen(string $str): int
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        if (\function_exists('mb_strlen')) {
            return \mb_strlen($str, '8bit');
        }
        return \strlen($str);
    }

    /**
     * Convert an ECDSA signature to an ASN.1 DER sequence
     *
     * @param   string $sig The ECDSA signature to convert
     * @return  string The encoded DER object
     */
<<<<<<< HEAD
    private static function signatureToDER($sig)
    {
        // Separate the signature into r-value and s-value
        list($r, $s) = \str_split($sig, (int) (\strlen($sig) / 2));
=======
    private static function signatureToDER(string $sig): string
    {
        // Separate the signature into r-value and s-value
        $length = max(1, (int) (\strlen($sig) / 2));
        list($r, $s) = \str_split($sig, $length > 0 ? $length : 1);
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb

        // Trim leading zeros
        $r = \ltrim($r, "\x00");
        $s = \ltrim($s, "\x00");

        // Convert r-value and s-value from unsigned big-endian integers to
        // signed two's complement
        if (\ord($r[0]) > 0x7f) {
            $r = "\x00" . $r;
        }
        if (\ord($s[0]) > 0x7f) {
            $s = "\x00" . $s;
        }

        return self::encodeDER(
            self::ASN1_SEQUENCE,
            self::encodeDER(self::ASN1_INTEGER, $r) .
            self::encodeDER(self::ASN1_INTEGER, $s)
        );
    }

    /**
     * Encodes a value into a DER object.
     *
     * @param   int     $type DER tag
     * @param   string  $value the value to encode
<<<<<<< HEAD
     * @return  string  the encoded object
     */
    private static function encodeDER($type, $value)
=======
     *
     * @return  string  the encoded object
     */
    private static function encodeDER(int $type, string $value): string
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        $tag_header = 0;
        if ($type === self::ASN1_SEQUENCE) {
            $tag_header |= 0x20;
        }

        // Type
        $der = \chr($tag_header | $type);

        // Length
        $der .= \chr(\strlen($value));

        return $der . $value;
    }

    /**
     * Encodes signature from a DER object.
     *
     * @param   string  $der binary signature in DER format
     * @param   int     $keySize the number of bits in the key
<<<<<<< HEAD
     * @return  string  the signature
     */
    private static function signatureFromDER($der, $keySize)
=======
     *
     * @return  string  the signature
     */
    private static function signatureFromDER(string $der, int $keySize): string
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        // OpenSSL returns the ECDSA signatures as a binary ASN.1 DER SEQUENCE
        list($offset, $_) = self::readDER($der);
        list($offset, $r) = self::readDER($der, $offset);
        list($offset, $s) = self::readDER($der, $offset);

        // Convert r-value and s-value from signed two's compliment to unsigned
        // big-endian integers
        $r = \ltrim($r, "\x00");
        $s = \ltrim($s, "\x00");

        // Pad out r and s so that they are $keySize bits long
        $r = \str_pad($r, $keySize / 8, "\x00", STR_PAD_LEFT);
        $s = \str_pad($s, $keySize / 8, "\x00", STR_PAD_LEFT);

        return $r . $s;
    }

    /**
     * Reads binary DER-encoded data and decodes into a single object
     *
     * @param string $der the binary data in DER format
     * @param int $offset the offset of the data stream containing the object
     * to decode
<<<<<<< HEAD
     * @return array [$offset, $data] the new offset and the decoded object
     */
    private static function readDER($der, $offset = 0)
=======
     *
     * @return array{int, string|null} the new offset and the decoded object
     */
    private static function readDER(string $der, int $offset = 0): array
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        $pos = $offset;
        $size = \strlen($der);
        $constructed = (\ord($der[$pos]) >> 5) & 0x01;
        $type = \ord($der[$pos++]) & 0x1f;

        // Length
        $len = \ord($der[$pos++]);
        if ($len & 0x80) {
            $n = $len & 0x1f;
            $len = 0;
            while ($n-- && $pos < $size) {
                $len = ($len << 8) | \ord($der[$pos++]);
            }
        }

        // Value
        if ($type == self::ASN1_BIT_STRING) {
            $pos++; // Skip the first contents octet (padding indicator)
            $data = \substr($der, $pos, $len - 1);
            $pos += $len - 1;
        } elseif (!$constructed) {
            $data = \substr($der, $pos, $len);
            $pos += $len;
        } else {
            $data = null;
        }

<<<<<<< HEAD
        return array($pos, $data);
=======
        return [$pos, $data];
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    }
}
