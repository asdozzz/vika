<?php

namespace Asdozzz\{{$module|ucfirst}}\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class {{$essence|ucfirst}} extends \Asdozzz\Universal\Model\Universal
{
	protected $table = '{{$config->table}}';
  	protected $essenceName = '{{$essence|ucfirst}}';
  	protected $datasourceName = '\Asdozzz\{{$module|ucfirst}}\Datasource\{{$essence|ucfirst}}';
  	protected $softDeletes = false;
}