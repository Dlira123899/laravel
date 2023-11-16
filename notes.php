<?php

// This will return a value of 	the column directly
$email = DB::table('users')->where('name', 'John')->value('email');



This method retrieves a small chunk of results at a time and feeds each chunk into a closure for processing
DB::table('users')->orderBy('id')->chunk(100, function (Collection $users) {
    foreach ($users as $user) {
        // ...
    }
});



// Chunking result
DB::table('users')->where('active', false)
    ->chunkById(100, function (Collection $users) {
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['active' => true]);
        }
    });


// Lazy method
use Illuminate\Support\Facades\DB;
DB::table('users')->orderBy('id')->lazy()->each(function (object $user) {
    // ...
});
