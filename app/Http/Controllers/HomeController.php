<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class HomeController extends Controller
{
    // Хотелось сделать клиент по круче но не хватило времени )
    // Если будет возможность, то расскажу как делал
    public function index() {
        $url = 'http://balance.loc/api/balance';
        $data = [
            [
                'jsonrpc' => '2.0',
                'method'  => 'balance_userBalance',
                'params'  => [
                    'user_id' => 1
                ],
                'id'      => 1
            ],
            [
                'jsonrpc' => '2.0',
                'method'  => 'balance_history',
                'params'  => [
                    'limit' => 50
                ],
                'id'      => 2
            ]
        ];
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        $response = $client->post($url,
            [
                'body' => json_encode($data)
            ]
        )->getBody()->getContents();
        $response = json_decode($response, 1);
        if ( isset($response[0]['result']) && isset($response[1]['result']) ) {
            // тут можно проверить успешно ли запрошен баланс пользователя или его не в БД
            // if ( $response[0]['result']['success'] )
            // но мы просто выведем результат
            $data['user_balance'] = $response[0]['result']['result'];
            // странно показывать пользователю историю всех транзакций, но делаю по тз
            $data['balance_history'] = $response[1]['result'];
        } else {
            $data['user_balance'] = 'Что-то пошло не так...';
            $data['balance_history'] = [];
        }
        return view('welcome', $data);
    }
}
