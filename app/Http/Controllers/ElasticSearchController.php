<?php

namespace App\Http\Controllers;

use App\Models\User;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Http\JsonResponse;
use JetBrains\PhpStorm\NoReturn;

class ElasticSearchController extends Controller
{
    public Client $client;
    public const ELASTIC_HOST = 'elasticsearch_docker_diploma:9200';

    /**
     * @throws AuthenticationException
     */
    #[NoReturn] public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts([self::ELASTIC_HOST])->build();
    }

    //TODO Когда проверишь работоспособность, приведи в человеческий вид.

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function import(): JsonResponse
    {
        $arData = User::all();
        $index = 0;
        $status = 0;
        $params = ['body' => []];

        foreach ($arData as $ar) {
            $index++;

            $params['body'][] = [
                'index' => [
                    '_index' => 'users', // указываем в какой индекс добавляем
                    '_id' => $ar->id // присваиваем документу id как в БД
                ]
            ];

            $params['body'][] = [
                'name' => $ar->name,
                'email' => $ar->email,
                'phone' => $ar->phone,
                'birth_date' => $ar->birth_date,
            ];

            if ($index === 10) {
                $index = 0;
                $status = 1;
                $responses = $this->client->bulk($params);
                $params = ['body' => []];
                unset($responses);
            }
        }

        return $status ? response()->json(['status' => 'imported!']) : response()->json(['status' => 'Not imported!']);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function search($query): array
    {
        $params = [
            'index' => 'users', // по какому индексу ищем
            'size' => 100 // количество результатов выборки
        ];

        $params['body'] = [
            'query' => [
                'bool' => [
                    'should' => [ // should - логическое OR, must - логическое AND
                        // полное совпадение
                        [
                            'match' => [
                                'name' => $query
                            ]
                        ],
                        // частичное совпадение, аналог LIKE в MySQL
                        [
                            'wildcard' => [
                                'name' => [
                                    'value' => '*' . $query . '*',
                                    'boost' => '1.0',
                                    'rewrite' => 'constant_score',
                                ]
                            ]
                        ],
                        // поиск по похожим фразам (box → fox, black → lack, act → cat)
                        [
                            'fuzzy' => [
                                'name' => $query
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->search($params)->asArray();
    }
}
