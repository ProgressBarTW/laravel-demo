<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PrintLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:print {message : is a message string} 
                                        {ary?* : is an array } 
                                        {--S|sleep=0 : sleep time} 
                                        {--id=* : group of id }
                                        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Print log';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $message = $this->argument('message');
        $ary = $this->argument('ary');
        $sleep = $this->option('sleep');
        $id = $this->option('id');
        $name = $this->ask('What is your name?');
        $password = $this->secret('What is the password?');

        echo $name . "\n";
        echo $password . "\n";

        if (!is_string($message)){
            $message = 'Message is not string';
        }

        var_dump($ary);
        var_dump($id);

        $sleep = min(max(intval($sleep), 0), 3);
        $this->info("sleep: $sleep \n");
        
        echo "message: $message\n";
        sleep($sleep);
       
        return 0;
    }
}
