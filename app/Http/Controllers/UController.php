<?php

namespace App\Http\Controllers;

/* use stdCLass */
use stdClass;

use Inertia\Inertia;

class UController extends Controller
{
    public function show()
    {
       
        $user = new stdClass();
        $user->name = 'Arhur';
        return Inertia::render('Test', [
          'user' => $user
        ]);
    }
}