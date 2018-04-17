<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meniam\OauthBundle\Oauth\Response;

use Meniam\OauthBundle\Oauth\ResponseInterface;
use Meniam\OauthBundle\Oauth\OauthToken;

interface UserResponseInterface extends ResponseInterface
{
    /**
     * Get the unique user identifier.
     *
     * Note that this is not always common known "username" because of implementation
     * in Symfony framework. For more details follow link below.
     *
     * @see https://github.com/symfony/symfony/blob/2.7/src/Symfony/Component/Security/Core/User/UserProviderInterface.php#L20-L28
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get the username to display.
     *
     * @return string
     */
    public function getNickname();

    /**
     * Get the first name of user.
     *
     * @return null|string
     */
    public function getFirstName();

    /**
     * Get the last name of user.
     *
     * @return null|string
     */
    public function getLastName();

    /**
     * Get the real name of user.
     *
     * @return null|string
     */
    public function getRealName();

    /**
     * Get the email address.
     *
     * @return null|string
     */
    public function getEmail();

    /**
     * Get the url to the profile picture.
     *
     * @return null|string
     */
    public function getProfilePicture();

    /**
     * Get the access token used for the request.
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * Get the access token used for the request.
     *
     * @return null|string
     */
    public function getRefreshToken();

    /**
     * Get oauth token secret used for the request.
     *
     * @return null|string
     */
    public function getTokenSecret();

    /**
     * Get the info when token will expire.
     *
     * @return null|string
     */
    public function getExpiresIn();

    /**
     * Set the raw token data from the request.
     *
     * @param OauthToken $token
     */
    public function setOauthToken(OauthToken $token);

    /**
     * Get the raw token data from the request.
     * @return OauthToken
     */
    public function getOauthToken();
}
