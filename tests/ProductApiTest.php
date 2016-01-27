<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductApiTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test_index_gives_all_suppliers()
    {
        $this->get('supplier')
        ->seeJson(['name' => "Narendra"]);

    }

    function test_id_returns_particular_supplier_data () {

        $this->get('supplier/1')->seeJson(["name" => "Suman"]);
    }

    function test_post_data_can_save_supplier() {


    }

}
