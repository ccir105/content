<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/5/16
 * Time: 4:36 PM
 */

namespace App\Elastic;
use Elastica\Query\BoolQuery;
use Elastica\Query\Ids;
use Elastica\Query\QueryString;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;

class ElasticQuery
{
    public $elastic;

    public $query;

    public $filter;

    public function __construct( ElasticConnection $elastic )
    {
        $this->elastic = $elastic;
        $this->query = new BoolQuery();
        $this->refresh();
    }

    function refresh()
    {
        $this->query = new BoolQuery();
    }

    function get($count = null)
    {
        if(is_null($count))
        {
            $count = $this->elastic->count($this->query);
        }

        $results = $this->elastic->search( $this->query, $count)->getResults();

        $return = [];


        foreach($results as $result)

        {
            $return[] = $result->getData();
        }

        return $return;
    }
//
//    function all()
//    {
//        return $this->get();
//    }
//
//    public function operator()
//    {
//      return [
//            '=' => function($key, $value)
//            {
//                $term = new Term([$key => $value]);
//
//                $this->query->addFilter($term);
//            },
//            '<' => function($key, $value)
//            {
//                $query = new Range($key,[ 'lt' =>$value ]);
//                $this->query = $query;
//            },
//            ">" => function($key, $value)
//            {
//                $query = new Range($key,[ 'gt'=> $value ]);
//                $this->query->addFilter($query);
//            },
//            "<=" => function($key, $value)
//            {
//                $query = new Range($key,[ 'gte'=> $value ]);
//                $this->query->addFilter($query);
//            },
//            "<>" => function($key, $value)
//            {
//                $query = new Term([ $key => $value]);
//                $this->query->addMustNot($query);
//            },
//            ">=" => function($key, $value)
//            {
//                $query = new Range($key,[ 'gte' => $value ]);
//                $this->query->addFilter($query);
//            }
//        ];
//    }
//
//    public function find( $ids )
//    {
//        $query = new Ids();
//        $query->setIds($ids);
//        $this->query->addMust($query);
//        $results = $this->get();
//        return $results;
//    }
//
//    public function whereIn($column, $values = [])
//    {
//        $query = new Terms();
//        $query->setTerms($column,$values);
//        $this->query->addFilter($query);
//    }
//
//    public function whereLike($columns, $value)
//    {
//        $query = new QueryString( $value);
//
//        $query->setFields( $columns );
//
//        $this->query->addMust($query);
//    }
}