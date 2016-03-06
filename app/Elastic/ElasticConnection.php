<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/5/16
 * Time: 3:17 PM
 */

namespace App\Elastic;
use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Config;
use Elastica\Type\Mapping;
use Elastica\Exception\ResponseException;
use Elastica\Search;

class ElasticConnection
{
    public $index;

    public $type;

    public $schema;

    public $client;

    public function __construct($type = array())
    {
        $this->client = new Client();

        $this->index = new Index($this->client, 'ccir');

        $this->schema = (isset($type['schema'])) ? $type['schema'] : false;

        $this->type = $type['name'];

        if( !$this->index->exists() ){
            $this->setUp();
        }else{
            $this->type = $this->index->getType( $this->type );
        }
    }

    public function setUp(){

        try{
            $mappingSettings = array (

            'number_of_shards'   => 4,
            'number_of_replicas' => 1,
            'analysis'           => array (
                "filter"=> array (
                    "autocomplete_filter" => array (
                        "type" => "nGram",
                        "min_gram" => 2,
                        "max_gram" => 20,
                        "token_chars" => [
                            "letter",
                            "digit",
                            "punctuation",
                            "symbol"
                        ]
                    )
                ),
                "analyzer" => array(
                    "autocomplete" => array(
                        "type" => "custom",
                        "tokenizer" => "standard",
                        "filter" => [
                            "lowercase",
                            "asciifolding",
                            "autocomplete_filter"
                        ],
                        "stopwords" => [ "and", "the","is","are","was" ]
                    ),
                    "whitespace_analyzer" => array(
                        "type" => "custom",
                        "tokenizer" => "whitespace",
                        "filter" => [
                            "lowercase",
                            "asciifolding"
                        ]
                    )
                )
            )
        );

            $this->index->create( $mappingSettings );

            $this->type = $this->index->getType( $this->type );

            echo get_class($this->type);

            if($this->schema)
            {
                $mapping = new Mapping();
                $mapping->setType($this->type);
                $mapping->setProperties($this->schema);
                $this->type->setMapping($mapping);
            }
        }
        catch(Exception $e){
            return false;
        }
    }

    public function addItem( $data ){
        $doc = new Document( $data['id'], $data );
        $this->type->addDocument( $doc );
        $this->index->refresh();
    }

    public function removeItem($id){
        return $this->type->deleteById($id);
    }

    public function search($query,$total = 10){
        try{
            return $this->index->search($query,[
                Search::OPTION_SIZE => $total,
            ]);
        }
        catch(ResponseException $e){
            die($e);
        }
    }

    public function count($query){
        return $this->index->count($query);
    }

}