<?php namespace MarlonFreire\Sitios\Console;


use Illuminate\Console\Command;
use MarlonFreire\Zureo\Classes\JobZureo;

class ZureoUpdate extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'zureo:update';

    /**
     * @var string The console command description.
     */
    protected $description = 'Actualizar catálogo desde Zureo';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $job = new JobZureo();

//        Obtener todas las marcas
        $job->getMarcas();

//        Obtener todas las categories
        $job->getTypes();

//        Obtener todos los productos
        $job->getProducts(0,1000);

        $this->output->writeln('Catálogo Actualizado!!!');
    }

}