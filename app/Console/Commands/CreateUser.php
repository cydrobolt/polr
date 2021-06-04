<?php

namespace App\Console\Commands;

use App\Factories\UserFactory;
use App\Helpers\UserHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUser extends Command {

    /**
     * @inheritdoc
     */
    protected $signature = 'create:user {username} {email} {--password=} {--active=1} {--ip=127.0.0.1} {--api_key=} {--api_active=0} {--role=default}';

    /**
     * @inheritdoc
     */
    protected $description = 'Create a new user.';

    /**
     * Run the command.
     *
     * @return int
     */
    public function handle() {
        $username = $this->argument('username');
        $email = $this->argument('email');
        $password = $this->option('password');
        $active = (int)$this->option('active');
        $ip = $this->option('ip');
        $api_key = $this->option('api_key');
        $api_active = (int)$this->option('api_active');
        $role = $this->option('role');

        // Username and email should not be empty.
        if(empty($username) || empty($email)) {
            $this->output->error('Username and email should not be empty.');
            return 1;
        }

        // Exit if the user already exists.
        if(UserHelper::userExists($username)) {
            $this->output->error(sprintf('User "%s" already exists.', $username));
            return 1;
        }

        // If password arg is empty, generate a random password.
        if(empty($password)) {
            $password = Str::random(8);
            $this->output->writeln(sprintf('Using generated password: %s', $password));
        }

        // Set the user role.
        if(array_key_exists($role, UserHelper::$USER_ROLES)) {
            $role = UserHelper::$USER_ROLES[$role];
        }
        else {
            // Exit if the given role is invalid.
            $this->output->error('User role is invalid.');
            return 1;
        }

        // Create the user.
        UserFactory::createUser($username, $email, $password, $active, $ip, $api_key, $api_active, $role);

        $this->output->writeln(sprintf('User %s created!', $username));
    }

}