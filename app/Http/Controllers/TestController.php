<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsors;
use Illuminate\Routing\Controller as BaseController;

class TestController extends BaseController
{
    public function index()
    {
        // for ($i = 0; $i < 5; $i++) {
        //     Sponsors::create([
        //         'name' => 'User '.$i,
        //         'email' => 'user'.$i.'@example.com',
        //         'password' => bcrypt('password'),
        //     ]);
        // }

        // for ($i = 0; $i < 5; $i++) {
        //     Sponsors::create([
        //         'CIF' => 'CIF_' . $i,
        //         'nom' => 'nom_' . $i,
        //         'logo' => 'logo_' . $i,
        //         'adreca' => 'adreca_' . $i,
        //         'curses' => json_encode([['curs_' . $i . '_1', 'curs_' . $i . '_2'], ['curs_' . $i+1 . '_1', 'curs_' . $i+1 . '_2']]),
        //         'primera_plana' => ($i % 2 == 0)
        //     ]);
        // }

        $sponsors = Sponsors::latest()->take(3)->get();

        return view('welcome', compact('sponsors'));
    }
}
