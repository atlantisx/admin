<?php

namespace Atlantis\Admin\Commands;

use Illuminate\Foundation\Application;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class AdminCommandsUpdate extends Command{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Admin package post update task';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(File $file)
    {
        $this->file = $file;

        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $dirs = explode('/',__DIR__);
        $package_flag = 'package';

        if( in_array('workbench',$dirs) ){
            $package_flag = '--bench';
        }

        $this->call('asset:publish',[$package_flag => 'atlantis/admin']);
        $this->call('cache:clear');
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }

}