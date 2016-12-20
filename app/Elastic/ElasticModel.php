<?php namespace App\Elastic;

use Elasticsearch\Client;
use Isswp101\Persimmon\DAL\ElasticsearchDAL;
use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\Event\EventEmitter;
use Isswp101\Persimmon\QueryBuilder\QueryBuilder;
use Isswp101\Persimmon\Collection\ElasticsearchCollection;
use Illuminate\Pagination\LengthAwarePaginator; 
use Illuminate\Pagination\Paginator;

class ElasticModel extends ElasticsearchModel
{

    protected static $query;

    private $client;

    public function __construct(array $attributes = [])
    {
        $this->client = app(Client::class);

        $dal = new ElasticsearchDAL($this, $this->client, app(EventEmitter::class));

        parent::__construct($dal, $attributes);

        $this->schemaDefination();
    }

    private function createIndex($index)
    {
        $dbConfig = config('elasticsearch.schema');

        $dbConfig['index'] = $index;

        $this->client->indices()->create( $dbConfig  );
    }

    private function indexExists($index)
    {
        if( !$this->client->indices()->exists( ['index' => $index ] ) )
        {
            $this->createIndex( $index );
        }
    }

    public function schemaDefination()
    {
        $tables = config('elasticsearch.tables');

        $tableName = $this::$_type;

        $dbName = $this::$_index;

        $this->indexExists( $dbName );

        foreach( $tables as $name => $settings)
        {
            if( $tableName === $name )
            {   
                if( !$this->typeExists($dbName, $tableName) )
                {
                    $this->createType( $dbName, $tableName, $settings );
                }

                return;
            }
        }
    }

    private function createType($dbName, $tableName, $mapping = [])
    {
        $params = [
            'index' => $dbName,
            'type' => $tableName,
            'body' => [
                $tableName => $mapping
            ]
        ];

        $this->client->indices()->putMapping($params);
    }

    private function typeExists($dbName, $tableName)
    {
        $currentMapping = $this->client->indices()->getMapping([
            'index' => $dbName,
            'type' => $tableName
        ]);

        return count( $currentMapping ) === 0 ? false : true;
    }

    public static function createInstance()
    {
        return new static();
    }

    public static function getClient()
    {
        return app(Client::class);
    }

    public static function buildQuery($query = [])
    {
        if(self::$query)
        {
            $query = self::$query;
        }

        if($query instanceof QueryBuilder)
        {
            $query = $query->build();
        }

        if (empty($query['body']['query']) && empty($query['body']['filter'])) {
            $query['body']['query'] = [
                'match_all' => []
            ];
        }

        return $query;
    } 

    public static function count($query = [])
    {
        $query = self::buildQuery($query);

        $params = [
            'index' => self::getIndex(),
            'type' => self::getType(),
            'body' => $query['body']
        ]; 

        return self::getClient()->count($params)['count'];
    }


    public static function paginate($perPage, $currentPage = 0)
    {
        $query = self::makeQueryInstance()->build();

        $query['size'] = $perPage;

        $query['from'] = ($currentPage) * $perPage;

        $total = self::count($query);

        $items = self::get($query);

        $paginated = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]);

        return $paginated;
    }

    public static function limit($limit)
    {
        self::$query = self::makeQueryInstance();

        self::$query->size($limit);

        return self::createInstance();
    }

    public static function __callStatic($name, $args)
    {
        self::$query = self::makeQueryInstance();
        
        call_user_func_array([self::$query, $name], $args);

        return self::createInstance();
    }

    public function __call($name, $args)
    {
        self::$query = self::makeQueryInstance();

        call_user_func_array([ self::$query, $name ], $args);

        return $this;
    }

    private static function makeQueryInstance()
    {
        if(!self::$query)
        {
            self::$query = new QueryBuilder;
        }

        return self::$query;
    }

    public static function toDsl($isArray = true)
    {
        $debugResults =  ($isArray) ? self::buildQuery() : self::$query->toJson();
        self::clear();
        return $debugResults;
    }

    private static function clear()
    {
        self::$query = null;
    }

    public static function build()
    {
        $query = self::makeQueryInstance();
        self::clear();
        return $query->build();
    }

    public static function union($queries = [])
    {
        $bodies = [];
        foreach ($queries as $value) {
            array_push($bodies, []);
            $bodies[] = array_merge($value['body'],['size'=>$value['size']]);
        }
        array_push($bodies, []);
        return self::msSearch($bodies);
    }

    private static function msSearch($bodies)
    {
        $client = app(Client::class);

        $params = [
            'index' => self::getIndex(),
            'type' => self::getType(),
            'body' => $bodies
        ];

        $response = $client->msearch($params);

        return self::finalBuild($response);
    }

    private static function finalBuild($responses)
    {
        $allCollection = new ElasticsearchCollection();

        foreach ($responses['responses'] as $response)
        {
            foreach ($response['hits']['hits'] as $hit) {
                $model = self::createInstance();
                $model->_score = $hit['_score'];
                $model->_exist = true;
                $model->fillByResponse($hit);
                $model->fillByInnerHits($hit);
                $allCollection->put($model->getId(), $model);
            }
        }

        return array_values($allCollection->toArray());
    }

    public static function get($query = null)
    {
        $query = !is_null($query) ? $query : clone(self::$query);
        
        $returnData = [];

        self::clear();
        
        $items = self::search($query);

        foreach($items as $item)
        {
            $returnData[] = $item;
        }

        return $returnData;
    }
}