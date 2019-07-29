<?php

namespace App\Console\Commands;

use function array_keys;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send {user?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mailjet e-mails to a user';

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
     * @return mixed
     */
    public function handle()
    {
        $users = \App\User::all()->toArray();
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();

        foreach ($users as $user) {
            $headers = array_keys($user);
            $bar->advance();
        }


        $this->table($headers, $users);
        $bar->finish();
    }
}
