<?php namespace App\Elastic;

use Elasticsearch\Client;
use Isswp101\Persimmon\DAL\ElasticsearchDAL;
use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\Event\EventEmitter;
use Isswp101\Persimmon\QueryBuilder\QueryBuilder;
use Input;
use Isswp101\Persimmon\Collection\ElasticsearchCollection;
use Illuminate\Pagination\LengthAwarePaginator; 
use Illuminate\Pagination\Paginator;

class Model extends ElasticsearchModel
{

    protected static $query;

    public function __construct(array $attributes = [])
    {
        $dal = new ElasticsearchDAL($this, app(Client::class), app(EventEmitter::class));

        parent::__construct($dal, $attributes);

        $this->schemaDefination();
    }


    public function schemaDefination()
    {
        $client = app(Client::class);
        
        $dbs = config('elasticsearch.databases');

        foreach($dbs as $db)
        {
            $params = [ 'index' => $db['index'] ];

            if( $client->indices()->exists($params) )
            {
                continue;
            }
            else
            {
                $client->indices()->create($db);
            }
        }
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


    public static function paginate($perPage)
    {
        $currentPage = Paginator::resolveCurrentPage();

        $query = self::makeQueryInstance()->build();

        $query['size'] = $perPage;

        $query['from'] = ($currentPage - 1) * $perPage;

        $total = self::count($query);

        $items = self::get();

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