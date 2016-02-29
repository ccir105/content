<?php namespace Modules\Project\FieldType;

interface FieldType{
    public function validates();
    public function get();
    public function set();
}