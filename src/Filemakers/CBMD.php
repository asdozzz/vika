<?php

namespace Asdozzz\Vika\Filemakers;

class CBMD implements \Asdozzz\Filemakers\Interfaces\iFilemakers
{
	private function validationArguments($data)
	{
		if (empty($data))
		{
			throw new \Exception(\Lang::get('vika.filemakers.data_not_found'));
		}

		if (empty($data['essence']))
		{
			throw new \Exception(\Lang::get('vika.filemakers.essence_name_is_empty'));
		}

		if (empty($data['module']))
		{
			throw new \Exception(\Lang::get('vika.filemakers.module_name_is_empty'));
		}

		return true;
	}

	public function generateModulePath($module)
	{
		return base_path('packages/Asdozzz/'.$module.'/src/');
	}

	public function generateDirs($module_path)
	{
		$dirs = [
			'Controller' => $module_path.'/Controller/',
			'Business' => $module_path.'/Business/',
			'Model' => $module_path.'/Model/',
			'Datasource' => $module_path.'/Datasource/',
			'migrations' => $module_path.'/migrations/',
            //'routes' => $module_path.'/routes/',
            //'config' => $module_path.'/config/'
		];


        foreach ($dirs as $key => $path)
        {
            if (!is_dir($path))	mkdir($path, 0775, true);
        }

		return $dirs;
	}

	public function generateTemplatePath($essence)
	{
		$templates_path = [
			'Controller' => 'vika_filemakers::controller',
			'Business' => 'vika_filemakers::business',
			'Model' => 'vika_filemakers::model',
			'Datasource' => 'vika_filemakers::datasource',
			//'migrations' => 'vika_filemakers::migrations',
            //'routes' => 'vika_filemakers::routes',
            //'config' => 'vika_filemakers::config'
		];

		return $templates_path;
	}

	public function build(\Illuminate\Console\Command $Command)
	{
		$this->console = $Command;

		$data = $this->console->argument();
        $options = $this->console->options();

		$this->validationArguments($data);

		$data['config'] = \Asdozzz\Essence\Essence::factory($data['essence']);

		$module_path = $this->generateModulePath(ucfirst($data['module']));

		$dirs = $this->generateDirs($module_path);

        if (empty($options['nosp']))
        {
            $this->generateServiceProvider($data, $module_path);
        }

        $this->generateRelatations($data, $dirs);
        $this->generateMigrations($data, $dirs);
        $this->generateCBMD($data, $dirs);

		$this->console->info(\Lang::get('vika.filemakers.success'));
	}

    /**
     * @param $data
     * @param $dirs
     */
    private function generateCBMD($data, $dirs)
    {
        $templates_path = $this->generateTemplatePath($data);
        foreach ($templates_path as $key => $viewpath)
        {
            $path = $dirs[$key];
            $filename = $path . ucfirst($data['essence']);
            if ($key == 'Controller')
            {
                $filename .= 'Controller';
            }
            $filename .= '.php';
            $content = view($viewpath, $data);

            if (file_exists($filename))
            {
                throw new \Exception('File '.$filename.' already exists');
            }

            file_put_contents($filename, $content);
        }
    }

    /**
     * @param $data
     * @param $dirs
     */
    private function generateMigrations($data, $dirs)
    {
        $input = $data;
        $input['drop_sql'] = 'DROP TABLE '.$data['config']->table;

        $columns = array();
        foreach ($data['config']->columns as $col)
        {
            $item = '';
            $default = '';
            if (isset($col['default']) && $col['default']!== false)
            {
                $default = 'DEFAULT '.$col['default'];
            }

            if ($col['data'] == $data['config']->primary_key)
            {
                $default = 'UNSIGNED NOT NULL AUTO_INCREMENT';
            }

            switch ($col['type'])
            {
                case 'string':
                    $item .= 'VARCHAR('.$col['length'].')';
                break;

                case 'text':
                    $item .= 'TEXT('.$col['length'].')';
                break;

                case 'integer':
                    $item .= 'INT('.$col['length'].')';
                break;

                case 'float':
                    $item .= 'FLOAT('.$col['length'].')';
                break;

                case 'boolean':
                    $item .= 'TINYINT(1)';
                break;

                case 'date':
                    $item .= 'DATE';
                break;

                case 'datetime':
                    $item .= 'DATETIME';
                break;
            }

            if (!empty($default))
            {
                $item .= ' '.$default;
            }

            $columns[] = $col['data'].' '.$item;
        }

        $columns[] = 'PRIMARY KEY (`'.$data['config']->primary_key.'`)';

        $input['create_sql'] = 'CREATE TABLE '.$data['config']->table.' ('.join(',', $columns).') COLLATE=\'utf8_general_ci\' ENGINE=InnoDB' ;

        $content = view('vika_filemakers::migrations', $input);
        $filename = date('Y_m_d_His') . '_create_' . $input['essence'] . '_table.php';
        $filepath = $path = $dirs['migrations'] . '/' . $filename;

        if (file_exists($filepath))
        {
            throw new \Exception('File '.$filepath.' already exists');
        }

        return file_put_contents($filepath, $content);
    }

    private function generateServiceProvider($data, $module_path)
    {
        $content = view('vika_filemakers::ServiceProvider', $data);
        $filename = ucfirst($data['essence']). 'ServiceProvider.php';
        $filepath = $module_path . '/' . $filename;

        if (file_exists($filepath))
        {
            throw new \Exception('File '.$filepath.' already exists');
        }

        return file_put_contents($filepath, $content);
    }

    private function generateRelatations($data, $module_path)
    {
        $arr = $data['config']->getRelationships();

        foreach ($arr as $item)
        {
            $exitCode = $this->console->call('fm:build', [
                'alias' => 'vModule', 'module' => $data['module'],'essence' => $item->essence, '--nosp' => 1
            ]);
        }

        return true;
    }
}