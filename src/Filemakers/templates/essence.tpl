<?php

namespace Asdozzz\{{$module|ucfirst}}\Essences;

use DB;

class {{$essence|ucfirst}} extends \Asdozzz\Essence\Essences\Essence implements \Asdozzz\Essence\Interfaces\iEssence
{
    public $moduleName = '{{$module|ucfirst}}';

    public $businessName   = 'Asdozzz\\{{$module|ucfirst}}\\Business\\{{$essence|ucfirst}}';

    public $modelName      = 'Asdozzz\\{{$module|ucfirst}}\\Model\\{{$essence|ucfirst}}';

    public $datasourceName = 'Asdozzz\\{{$module|ucfirst}}\\Datasource\\{{$essence|ucfirst}}';

    public $primary_key   = 'id';
    public $table         = '{{$essence|lower}}';
    public $label         = '{{$essence|ucfirst}}';
    public $softDeletes   = true;
    public $deleted_field = 'deleted_at';

    public function getPermissions()
    {
        return array(
            'listing' => 'listing.'.$this->table,
            'read'    => 'read.'.$this->table,
            'create'  => 'create.'.$this->table,
            'update'  => 'update.'.$this->table,
            'delete'  => 'delete.'.$this->table
        );
    }

    public function getColumns()
    {
        return array(
            'id' => \Columns::factory('PrimaryKey')->get(),
            /* Columns */
        );
    }

    public function getForms()
    {
        $columns = $this->getColumns();

        return [
            'create' =>
            [
                'label'   => 'Add',
                'columns' => $columns
            ],
            'edit' =>
            [
                'label'   => 'Edit',
                'columns' => $columns
            ],
        ];
    }

    public function getDatatables()
    {
        $columns = $this->getColumns();

        return
        [
            'default' => [
                'label'       => $this->label,
                'table' => $this->table,
                'primary_key' => $this->primary_key,
                'columns'     => $columns,
                'order' =>
                [
                    [ 'column' => 'id', 'direction' => 'desc' ]
                ]
            ]
        ];
    }
}