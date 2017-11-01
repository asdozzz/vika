<?php
/**
 * Created by PhpStorm.
 * User: asd
 * Date: 30.10.2017
 * Time: 11:05
 */
namespace Asdozzz\Vika\Filemakers;

class Essence implements \Asdozzz\Filemakers\Interfaces\iFilemakers
{
    public function build(\Illuminate\Console\Command $Command)
    {
        $this->console = $Command;

        $data = $this->console->argument();

        $this->validationArguments($data);

        $module_path = $this->generateModulePath(ucfirst($data['module']));

        $dirs = $this->generateDirs($module_path);

        $this->generateServiceProvider($data, $module_path);

        $this->console->info(\Lang::get('vika.filemakers.success'));
    }

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
            'Essences' => $module_path.'/Essences/',
        ];

        foreach ($dirs as $key => $path)
        {
            if (!is_dir($path))	mkdir($path, 0775, true);
        }

        return $dirs;
    }

    private function generateServiceProvider($data, $module_path)
    {
        $content = view('vika_filemakers::essence', $data);
        $filename = ucfirst($data['essence']). '.php';
        $filepath = $module_path . '/Essences/' . $filename;

        if (file_exists($filepath))
        {
            throw new \Exception('File '.$filepath.' already exists');
        }

        return file_put_contents($filepath, $content);
    }
}