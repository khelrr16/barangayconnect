<?php

namespace App\Providers;

use App\Models\Committee;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (! Schema::hasTable('committees')) {
            return;
        }

        $committees = Committee::all();

        foreach($committees as $committee){

            Gate::define('view ' . $committee->slug, function ($user) use ($committee) {
                if ($user->hasRole('admin')) {
                    return true;
                }
                
                return $user->official && 
                    $user->official->committee_id === $committee->id;
            });
        }
    }
}
