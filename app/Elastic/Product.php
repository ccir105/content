<?php namespace App\Elastic;

class Product extends Model
{
    protected static $_index = 'test';
    protected static $_type = 'test';

    public $name;
    public $price = 0;
}