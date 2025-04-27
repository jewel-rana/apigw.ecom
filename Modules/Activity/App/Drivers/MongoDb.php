<?php

namespace Modules\Activity\App\Drivers;

use Illuminate\Database\Connection;
use MongoDB\Client;
use MongoDB\Collection;

class MongoDb extends Connection
{
    protected Client $client;

    public function __construct(array $config)
    {
        $this->client = new Client($config['dsn']);
        $this->database = $this->client->selectDatabase($config['database']);
    }

    public function getCollection($collection): Collection
    {
        return $this->database->selectCollection($collection);
    }
}
