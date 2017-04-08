<?php

namespace App\Auth\Social;

class Facebook extends Service
{
    public function getAuthorizeUrl()
    {
        return 'https://www.facebook.com/dialog/oauth?client_id=CLIENT_ID&redirect_uri=http://REDIRECT_URI&scope=email,public_profile'
    }

    public function getUserByCode()
    {
        $token = $this->getAccessTokenFromCode($code);

        return $this->normalizeUser($this->getUserByToken($token));
    }

    protected function getAccessTokenFromCode($code)
    {
        $response = $this->client->request('GET', 'https://graph.facebook.com/v2.3/oauth/access_token', [
            'query' => [
                'client_id'     => 'CLIENT_ID',
                'client_secret' => 'CLIENT_SECRET',
                'redirect_uri'  => 'REDIRECT_URI',
                'code'          => $code,
            ]
        ])->getBody();

        return json_decode($response)->access_token;
    }

    protected function getUserByToken($token)
    {
        $response = $this->client->request('GET', 'https://graph.facebook.com/me', [
            'query' => [
                'access_token'  => $token,
                'fields'        => 'id,name,email,picture'
            ],
        ])->getBody();

        return json_decode($response);
    }

    protected function normalizeUser($user)
    {
        return (object) [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'photo' => $user->picture->data->url,
        ];
    }
}
