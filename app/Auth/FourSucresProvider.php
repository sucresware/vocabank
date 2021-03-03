<?php

namespace App\Auth;

use Illuminate\Support\Facades\Http;
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
        return \Arr::add(
            parent::getTokenFields($code),
            'grant_type',
            'authorization_code'
        );
    }

    public function getAccessTokenResponse($code)
    {
        $response = Http::post($this->getTokenUrl(), $this->getTokenFields($code));

        return $response->json();
    }

    protected function getUserByToken($token)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(config('services.foursucres.server_url') . '/api/v1/@me');

        return $response->json();
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'     => $user['id'],
            'name'   => $user['name'],
            'avatar' => $user['avatar_link'],
            'email'  => isset($user['email']) ? $user['email'] : null,
        ]);
    }
}
