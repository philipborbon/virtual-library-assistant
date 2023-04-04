<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating a new user');

        $username = $this->ask('Username');
        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password');
        $password = bcrypt($password);

        $auth = config('backpack.base.user_model_fqn', 'App\User');
        $user = new $auth();
        $user->username = $username;
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;

        if ($user->save()) {
            $this->info('Successfully created new user');
        } else {
            $this->error('Something went wrong trying to save your user');
        }
    }
}
