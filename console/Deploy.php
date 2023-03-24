<?php namespace MarlonFreire\Sitios\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;

class Deploy extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'assets:deploy';

    /**
     * @var string The console command description.
     */
    protected $description = 'Despliegue de assets de la compañía';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
//        copy('./bootstrap/backups/cms/controllers/themeoptions/update.htm', './modules/cms/controllers/themeoptions/update.htm');
//        copy('./bootstrap/backups/cms/models/ThemeData.php', './modules/cms/models/ThemeData.php');
//        copy('./bootstrap/backups/backend/assets/css/october.css', './modules/backend/assets/css/october.css');
//        copy('./bootstrap/backups/backend/layouts/_head.htm', './modules/backend/layouts/_head.htm');
//        copy('./bootstrap/backups/backend/layouts/_mainmenu.htm', './modules/backend/layouts/_mainmenu.htm');
//        (new Filesystem())->copyDirectory('./bootstrap/backups/backend/assets/images', './modules/backend/assets/images');
//        (new Filesystem())->copyDirectory('./bootstrap/backups/backend/assets/vendor/toastr', './modules/backend/assets/vendor/toastr');
//        copy('./bootstrap/backups/backend/database/seeds/SeedSetupAdmin.php', './modules/backend/database/seeds/SeedSetupAdmin.php');
//        (new Filesystem())->copyDirectory('./bootstrap/backups/system/assets/css/lightbox', './modules/system/assets/css/lightbox');
//        (new Filesystem())->copyDirectory('./bootstrap/backups/system/assets/js/lightbox', './modules/system/assets/js/lightbox');
        $this->output->writeln('Succesfull!!!');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }


}
