<?php namespace App\Elastic;

use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Illuminate\Database\Eloquent\Model;

abstract class ElasticModel extends Model{

    protected $table;

    protected $dsl;

    public $elasticConnection;

    protected $fillable = null;

    public function __construct()
    {
        parent::__construct([]);

        $this->elasticConnection = new ElasticConnection([
            'name' => $this->table,
            'schema' => $this->schema()
        ]);

        $this->dsl = new ElasticQuery( $this->elasticConnection );
    }

    public function schema()
    {
        return false;
    }

    public function save( array $options=[] )
    {
        $status = parent::save($options);
        $this->elasticConnection->addItem( $this->toArray());
        return $status;
    }

    public function get( $count = null )
    {
        return $this->makeInstance( $this->dsl->get( $count ) );
    }

    public function makeInstance($items = array())
    {
        $instance = new static;

        if(count($items) == 1)
        {
            return $this->createInstance($instance,$items[0]);

        }
        else if(count($items) == 0)
        {
            $items = [];
        }
        $instance = new static;

        foreach($items as $key => $item)
        {
            $items[$key] = $this->createInstance($instance,$item);
        }

        return $instance->newCollection($items);
    }

    public function createInstance($static, $item)
    {
        return $static->newFromBuilder($item);
    }
}