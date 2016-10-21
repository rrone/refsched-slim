<?php
namespace App\Action;

use Slim\App;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Tuupola\Base62;

class TokenManager
{
    protected $tokenName;
    protected $c;

    public function __construct(Container $container, $tokenName = null)
    {
        $this->c = $container;

        if(is_null($tokenName)){
            $this->tokenName = 'token';
        }
        else {
            $this->tokenName = $tokenName;
        }
    }
    public function jwt($appData)
    {
        $user = isset($appData['user']) ? $appData['user'] : null;
        $event = isset($appData['event']) ? $appData['event'] : null;
        $target_id = isset($appData['target_id']) ? $appData['target_id'] : null;

        $tokenId    = Base62::encode(random_bytes(16));

        $issuedAt   = time();
        $notBefore  = $issuedAt;                    //Subtracting 10 seconds
        $expire     = $notBefore + 1200;              // Adding 20 minutes
        $serverName = $_SERVER['SERVER_NAME'];      // Retrieve the server name from config file

        /*
         * Create the token as an array
         */
        $data = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'data' => [                  // Data related to the signer user
                'user' => $user->name,   // User name
                'event' => $event,      // event object
                'target_id' => $target_id //id of object to edit in editrefs
            ],
            'status' => "ok"
        ];

        $secret = getenv("JWT_SECRET");
        $jwt = JWT::encode($data, $secret);

        return $jwt;
    }
    public function isValid(Request $request)
    {
        $result = $this->getAuth($request);

        return $result['valid'];
    }
    public function getData(Request $request)
    {
        $result = $this->getAuth($request);

        return $result['data'];
    }
    public function getHeader(Request $request)
    {
        $result = $this->getAuth($request);

        return $result['hdr'];
    }
    protected function getAuth(Request $request)
    {
        $token = array(
            'valid' => false,
            'hdr' => null,
            'data' => null
        );

        /*
         * Look for the cookie
         */
        $authHeader = $request->getHeader('Authorization');
//        var_dump($authHeader);
        if ($authHeader) {
            /*
             * Extract the jwt from the cookie value
             */
            list($jwt) = sscanf( $authHeader->toString(), 'Authorization: Bearer %s');

            if ($jwt) {
                try {
                    /*
                     * decode the jwt using the key from config
                     */
                    $secret = getenv("JWT_SECRET");
                    $data = JWT::decode($jwt, $secret, array('HS256'));

                    $token = array(
                        'valid' => true,
                        'data' => $data
                    );

                } catch (\Exception $e) {
                    /*
                     * the token was not able to be decoded.
                     * this is likely because the signature was not able to be verified (tampered token)
                     */
                    $token['hdr'] = 'HTTP/1.0 401 Unauthorized';
                }
            } else {
                /*
                 * No token was able to be extracted from the authorization header
                 */
                $token['hdr'] = 'HTTP/1.0 400 Bad Request';
            }
        } else {
            /*
             * The request lacks the authorization token
             */
            $token['hdr'] = 'HTTP/1.0 400 Bad Request';
        }

        return $token;
    }
}