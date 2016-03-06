<?php namespace App\Elastic;

class User extends ElasticModel {

    protected $table = "users";

    protected $fillable = ['name', 'email', 'password'];

    public function schema()
    {
        return [
            "email" => array(
                "type" => "string",
                "index" => "not_analyzed"
            ),
            'name' => array(
                "type" => "string",
                "analyzer" => "autocomplete",
                "search_analyzer" => "standard"
            ),
        ];
    }
}
