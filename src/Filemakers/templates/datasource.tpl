<?php

namespace Asdozzz\{{$module|ucfirst}}\Datasource;

class {{$essence|ucfirst}} extends \Asdozzz\Universal\Datasource\Universal
{
	public $primary_key = 'id';
	public $table = '{{$config->table}}';
}