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

    protected function getUserByToken($token)
    {
        $userInfoUrl = config('services.foursucres.server_url') . '/api/v1/@me';
        $response = $this->getHttpClient()->get($userInfoUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        $userData = json_decode($response->getBody(), true);
        dd($userData);

        // $rawUser = reset($userData['response']);
        // $rawUser['email'] = $token['email'];

        // return $rawUser;
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['id'],
            'nickname' => $user['nickname'],
            'name'     => $user['first_name'] . ' ' . $user['last_name'],
            'email'    => isset($user['email']) ? $user['email'] : null,
            'avatar'   => $user['photo_100'],
        ]);
    }

    // public function user()
    // {
    //     if ($this->hasInvalidState()) {
    //         throw new InvalidStateException();
    //     }

    //     $token = $this->getAccessToken($this->getCode());
    //     $user = $this->mapUserToObject($this->getUserByToken($token));

    //     return $user->setToken($token['access_token']);
    // }

    // /**
    //  * Return all decoded data in order to retrieve additional params like 'email'.
    //  *
    //  * {@inheritdoc}
    //  */
    // protected function parseAccessToken($body)
    // {
    //     return json_decode($body, true);
    // }

    protected function getTokenFields($code)
    {
        return array_add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
    }
}
