<?php

namespace App\Auth\Social;

class GitHub extends Service
{
    public function getAuthorizeUrl()
    {
        return 'https://github.com/login/oauth/authorize?client_id=CLIENT_ID&redirect_uri=http://REDIRECT_URI&scope=user,user:email&state=RENDOM_STR';
    }

    public function getUserByCode($code)
    {
        $token = $this->getAccessTokenFromCode($code);

        return $this->normalizeUser($this->getUserByToken($token));
    }

    protected function getUserByToken($token)
    {
        $response = $this->client->request('GET', 'https://api.github.com/user', [
            'query' => [
                'access_token' => $token
            ]
        ])->getBody();

        return json_decode($response);
    }

    protected function getAccessTokenFromCode($code)
    {
        $response = $this->client->request('GET', 'https://github.com/login/oauth/access_token', [
                'query' => [
                    'client_id' => 'CLIENT_ID',
                    'client_secret' => 'CLIENT_SECRET',
                    'redirect_uri' => 'REDIRECT_URI',
                    'code' => $code,
                    'state' => 'RENDOM_STR',
                ],
                'headers' => [
                    'accept' => 'application/json',
                ]
            ])->getBody();

        return json_decode($response)->access_token;
    }

    protected function normalizeUser($user)
    {
        return (object) [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'photo' => $user->avatar_url,
        ];
    }
}
