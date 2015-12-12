<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Illuminate\Console\Application\Artisan;

class SetupController extends Controller {
    protected function parseExitCode($exitCode) {
        if ($exitCode == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function setupAlreadyRan() {
        return view('error', [
            'message' => 'Sorry, but you have already ran the set up script previously.'
        ]);
    }

    private function resetDatabase() {
        $exitCode = Artisan::call('migrate:refresh', [
            '--force' => true,
        ]);
        return $this->parseExitCode($exitCode);
    }

    private function createDatabase() {
        $exitCode = Artisan::call('migrate');
        return $this->parseExitCode($exitCode);
    }

    public static function displaySetupPage(Request $request) {
        if (env('POLR_SETUP_RAN')) {
            return $this->setupAlreadyRan();
        }

        return view('setup');
    }

    public static function performSetup(Request $request) {
        if (env('POLR_SETUP_RAN')) {
            return $this->setupAlreadyRan();
        }

    }
}
