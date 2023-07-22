<?php
namespace Tests\Mockers;

class EventMocker
{
    public static function getEventFake(string $status = 'Aberto'): object
    {
        return (object) [
            'id' => 1,
            'title' => 'evento titulo',
            'description'=> 'evento descrição',
            'start_date' => '2023-07-24',
            'due_date' => '2023-07-26',
            'status' => $status,
            'type_id' => 1,
            'user_id' => 1,
            'finalization_at' => null,
            'created_at' => '2023-07-21T04:55:19.000000Z',
            'updated_at' => '2023-07-21T04:55:19.000000Z'
        ];
    }

    /**
     * @return array
     */
    public static function eventsPaginate(): array
    {
        return [
            'current_page' => 1,
            'data' => [
                [
                    'id' => 2,
                    'title' => 'evento titulo',
                    'description'=> 'evento descrição',
                    'start_date' => '2023-07-24',
                    'due_date' => '2023-07-26',
                    'status' => 'Aberto',
                    'type_id' => 1,
                    'user_id' => 1,
                    'finalization_at' => null,
                    'created_at' => '2023-07-21T04:55:19.000000Z',
                    'updated_at' => '2023-07-21T04:55:19.000000Z'
                ],
                [
                    'id' => 1,
                    'title' => 'evento titulo',
                    'description'=> 'evento descrição',
                    'start_date' => '2023-07-20',
                    'due_date' => '2023-07-25',
                    'status' => 'Concluido',
                    'type_id' => 1,
                    'user_id' => 1,
                    'finalization_at' => null,
                    'created_at' => '2023-07-21T02:05:09.000000Z',
                    'updated_at' => '2023-07-21T02:05:09.000000Z'
                ]
            ],
            'to' => 2,
            'total' => 2
        ];
    }
}
