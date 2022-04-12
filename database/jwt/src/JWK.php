<?php

namespace Firebase\JWT;

use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * JSON Web Key implementation, based on this spec:
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-key-41
 *
 * PHP version 5
 *
 * @category Authentication
 * @package  Authentication_JWT
 * @author   Bui Sy Nguyen <nguyenbs@gmail.com>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */
class JWK
{
    /**
     * Parse a set of JWK keys
     *
<<<<<<< HEAD
     * @param array $jwks The JSON Web Key Set as an associative array
     *
     * @return array An associative array that represents the set of keys
=======
     * @param array<mixed> $jwks The JSON Web Key Set as an associative array
     *
     * @return array<string, Key> An associative array of key IDs (kid) to Key objects
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
     *
     * @throws InvalidArgumentException     Provided JWK Set is empty
     * @throws UnexpectedValueException     Provided JWK Set was invalid
     * @throws DomainException              OpenSSL failure
     *
     * @uses parseKey
     */
<<<<<<< HEAD
    public static function parseKeySet(array $jwks)
    {
        $keys = array();
=======
    public static function parseKeySet(array $jwks): array
    {
        $keys = [];
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb

        if (!isset($jwks['keys'])) {
            throw new UnexpectedValueException('"keys" member must exist in the JWK Set');
        }
<<<<<<< HEAD
=======

>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        if (empty($jwks['keys'])) {
            throw new InvalidArgumentException('JWK Set did not contain any keys');
        }

        foreach ($jwks['keys'] as $k => $v) {
            $kid = isset($v['kid']) ? $v['kid'] : $k;
            if ($key = self::parseKey($v)) {
<<<<<<< HEAD
                $keys[$kid] = $key;
=======
                $keys[(string) $kid] = $key;
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
            }
        }

        if (0 === \count($keys)) {
            throw new UnexpectedValueException('No supported algorithms found in JWK Set');
        }

        return $keys;
    }

    /**
     * Parse a JWK key
     *
<<<<<<< HEAD
     * @param array $jwk An individual JWK
     *
     * @return resource|array An associative array that represents the key
=======
     * @param array<mixed> $jwk An individual JWK
     *
     * @return Key The key object for the JWK
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
     *
     * @throws InvalidArgumentException     Provided JWK is empty
     * @throws UnexpectedValueException     Provided JWK was invalid
     * @throws DomainException              OpenSSL failure
     *
     * @uses createPemFromModulusAndExponent
     */
<<<<<<< HEAD
    public static function parseKey(array $jwk)
=======
    public static function parseKey(array $jwk): ?Key
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        if (empty($jwk)) {
            throw new InvalidArgumentException('JWK must not be empty');
        }
<<<<<<< HEAD
=======

>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        if (!isset($jwk['kty'])) {
            throw new UnexpectedValueException('JWK must contain a "kty" parameter');
        }

<<<<<<< HEAD
=======
        if (!isset($jwk['alg'])) {
            // The "alg" parameter is optional in a KTY, but is required for parsing in
            // this library. Add it manually to your JWK array if it doesn't already exist.
            // @see https://datatracker.ietf.org/doc/html/rfc7517#section-4.4
            throw new UnexpectedValueException('JWK must contain an "alg" parameter');
        }

>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        switch ($jwk['kty']) {
            case 'RSA':
                if (!empty($jwk['d'])) {
                    throw new UnexpectedValueException('RSA private keys are not supported');
                }
                if (!isset($jwk['n']) || !isset($jwk['e'])) {
                    throw new UnexpectedValueException('RSA keys must contain values for both "n" and "e"');
                }

                $pem = self::createPemFromModulusAndExponent($jwk['n'], $jwk['e']);
                $publicKey = \openssl_pkey_get_public($pem);
                if (false === $publicKey) {
                    throw new DomainException(
                        'OpenSSL error: ' . \openssl_error_string()
                    );
                }
<<<<<<< HEAD
                return $publicKey;
=======
                return new Key($publicKey, $jwk['alg']);
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
            default:
                // Currently only RSA is supported
                break;
        }
<<<<<<< HEAD
=======

        return null;
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    }

    /**
     * Create a public key represented in PEM format from RSA modulus and exponent information
     *
     * @param string $n The RSA modulus encoded in Base64
     * @param string $e The RSA exponent encoded in Base64
     *
     * @return string The RSA public key represented in PEM format
     *
     * @uses encodeLength
     */
<<<<<<< HEAD
    private static function createPemFromModulusAndExponent($n, $e)
    {
        $modulus = JWT::urlsafeB64Decode($n);
        $publicExponent = JWT::urlsafeB64Decode($e);

        $components = array(
            'modulus' => \pack('Ca*a*', 2, self::encodeLength(\strlen($modulus)), $modulus),
            'publicExponent' => \pack('Ca*a*', 2, self::encodeLength(\strlen($publicExponent)), $publicExponent)
        );
=======
    private static function createPemFromModulusAndExponent(
        string $n,
        string $e
    ): string {
        $mod = JWT::urlsafeB64Decode($n);
        $exp = JWT::urlsafeB64Decode($e);

        $modulus = \pack('Ca*a*', 2, self::encodeLength(\strlen($mod)), $mod);
        $publicExponent = \pack('Ca*a*', 2, self::encodeLength(\strlen($exp)), $exp);
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb

        $rsaPublicKey = \pack(
            'Ca*a*a*',
            48,
<<<<<<< HEAD
            self::encodeLength(\strlen($components['modulus']) + \strlen($components['publicExponent'])),
            $components['modulus'],
            $components['publicExponent']
=======
            self::encodeLength(\strlen($modulus) + \strlen($publicExponent)),
            $modulus,
            $publicExponent
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
        );

        // sequence(oid(1.2.840.113549.1.1.1), null)) = rsaEncryption.
        $rsaOID = \pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
        $rsaPublicKey = \chr(0) . $rsaPublicKey;
        $rsaPublicKey = \chr(3) . self::encodeLength(\strlen($rsaPublicKey)) . $rsaPublicKey;

        $rsaPublicKey = \pack(
            'Ca*a*',
            48,
            self::encodeLength(\strlen($rsaOID . $rsaPublicKey)),
            $rsaOID . $rsaPublicKey
        );

        $rsaPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" .
            \chunk_split(\base64_encode($rsaPublicKey), 64) .
            '-----END PUBLIC KEY-----';

        return $rsaPublicKey;
    }

    /**
     * DER-encode the length
     *
     * DER supports lengths up to (2**8)**127, however, we'll only support lengths up to (2**8)**4.  See
     * {@link http://itu.int/ITU-T/studygroups/com17/languages/X.690-0207.pdf#p=13 X.690 paragraph 8.1.3} for more information.
     *
     * @param int $length
     * @return string
     */
<<<<<<< HEAD
    private static function encodeLength($length)
=======
    private static function encodeLength(int $length): string
>>>>>>> 62696121aa1911b51f2f13d99b67b780a82637fb
    {
        if ($length <= 0x7F) {
            return \chr($length);
        }

        $temp = \ltrim(\pack('N', $length), \chr(0));

        return \pack('Ca*', 0x80 | \strlen($temp), $temp);
    }
}
