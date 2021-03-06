<?php

namespace Meniam\OauthBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class OauthErrorHandler
{
    /**
     * "translated" OAuth errors to human readable format.
     *
     * @var array
     */
    private static $translatedOauthErrors = [
        'access_denied' => 'You have refused access for this site.',
        'authorization_expired' => 'Authorization expired.',
        'bad_verification_code' => 'Bad verification code.',
        'consumer_key_rejected' => 'You have refused access for this site.',
        'incorrect_client_credentials' => 'Incorrect client credentials.',
        'invalid_assertion' => 'Invalid assertion.',
        'redirect_uri_mismatch' => 'Redirect URI mismatches configured one.',
        'unauthorized_client' => 'Unauthorized client.',
        'unknown_format' => 'Unknown format.',
    ];

    /**
     * @param Request $request
     *
     * @throws AuthenticationException
     */
    public static function handleOauthError(Request $request)
    {
        $error = null;

        // Try to parse content if error was not in request query
        if ($request->query->has('error')) {
            $content = json_decode($request->getContent(), true);
            if (isset($content['error']) && JSON_ERROR_NONE === json_last_error()) {
                if (isset($content['error']['message'])) {
                    throw new AuthenticationException($content['error']['message']);
                }

                if (isset($content['error']['code'])) {
                    $error = $content['error']['code'];
                } elseif (isset($content['error']['error-code'])) {
                    $error = $content['error']['error-code'];
                } else {
                    $error = $request->query->get('error');
                }
            }
        } elseif ($request->query->has('oauth_problem')) {
            $error = $request->query->get('oauth_problem');
        }

        if (null !== $error) {
            if (isset(static::$translatedOauthErrors[$error])) {
                $error = static::$translatedOauthErrors[$error];
            } else {
                $error = sprintf('Unknown Oauth error: "%s".', $error);
            }

            throw new AuthenticationException($error);
        }
    }
}
