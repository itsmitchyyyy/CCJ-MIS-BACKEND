<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $directoryExists = Storage::exists('document_files');

        if (!$directoryExists) {
            Storage::makeDirectory('document_files');

            $directories = [
                'letter', 
                'waver', 
                'student_data', 
                'graduatin_student_data', 
                'student_research', 
                'indiana_jones', 
                'approval_to_print_form',
                'other_documents'
            ];

            foreach($directories as $directory) {
                $directoryExists = Storage::exists('document_files/' . $directory);

                if (!$directoryExists) {
                    Storage::makeDirectory('document_files/' . $directory);
                    echo "{$directory} created\n";
                }
            }
        }
    }
}
