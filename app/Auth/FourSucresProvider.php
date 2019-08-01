<?php

namespace App\Auth;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class FourSucresProvider extends AbstractProvider implements ProviderInterface
{
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(config('services.foursucres.server_url') . '/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return config('services.foursucres.server_url') . '/oauth/token';
    }

    protected function getTokenFields($code)
    {
        return array_add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(config('services.foursucres.server_url') . '/api/v1/@me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => isset($user['email']) ? $user['email'] : null,
            // 'avatar_link' => $user['avatar_link'],
        ]);
    }
}
