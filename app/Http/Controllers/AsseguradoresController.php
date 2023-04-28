<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asseguradora;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\File;

class AsseguradoresController extends BaseController
{

    public function index()
    {
        $query = Asseguradora::query()->where('actiu', true);
        
        $asseguradores = $query->get();
        
        return view('backend.asseguradores.index', compact('asseguradores'));

    }

    public function filtreAjax(Request $request){

        $query = Asseguradora::query();

        // Comprova que el camp de busqueda per text estigui omplert
        $search_cif = $request->input('search_cif');
        if (!empty($search_cif)) {
            $query->where('cif', 'LIKE', '%' . $search_cif . '%');
        }

        $search_adreca = $request->input('search_adreca');
        if (!empty($search_adreca)) {
            $query->where('adreca', 'LIKE', '%' . $search_adreca . '%');
        }

        $search_nom = $request->input('search_nom');
        if (!empty($search_nom)) {
            $query->where('nom', 'LIKE', '%' . $search_nom . '%');
        }
        
        $actiu = $request->boolean('actiu');
        $query->where('actiu', $actiu);

        $asseguradores = $query->get();

        return response()->json($asseguradores);
    }

    public function checkedAjax(Request $request){
        $cif = $request->input('cif');
        $actiu = $request->boolean('checked');
        Asseguradora::where('cif', $cif)->update(['actiu' =>  $actiu]);
        return response()->json(['success' => true]);
    }

    public function formulariAfegir()
    {
        $route_form = route('asseguradores.afegir');
        return view('backend.asseguradores.formulari')->with('route_form', $route_form);
    }

    public function formulariEditar(Request $request)
    {
        $route_form = route('asseguradores.editar');

        $query_arr = Asseguradora::query()->where('cif', $request->query('cif'))->get();

        $return_val;

        if(count($query_arr) == 1){
            $query = $query_arr[0];

            $array = array('cif'=>$query->CIF,'nom'=>$query->nom,'adreca'=>$query->adreca, 'preu_per_cursa' => $query->preu_per_cursa);

            $request2 = new Request($array);

            session()->flashInput($request2->input());

            // dd($request2->input());

            $return_val = view('backend.asseguradores.formulari')->with('route_form', $route_form);
        }

        else{
            $return_val = back();
        }

        return $return_val;
    }

    public function afegir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cif' => 'required|unique:asseguradores|regex:/^[A-Z]{2}[0-9]{8}$/',
            'nom' => 'required|max:50',
            'adreca' => 'required',
            'preu_per_cursa' => 'required|numeric|min:0',
        ]);
        $validator->setAttributeNames([
            'cif' => 'CIF',
            'preu_per_cursa' => 'preu per cursa',
            'nom' => 'nom',
            'adreca' => 'adreça',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $asseguradora = new Asseguradora();
        $asseguradora->CIF = $request->input()['cif'];
        $asseguradora->nom = $request->input()['nom'];
        $asseguradora->adreca = $request->input()['adreca'];
        $asseguradora->preu_per_cursa = $request->input()['preu_per_cursa'];
        $asseguradora->actiu = true;

        $asseguradora->save();

        return redirect()->route('asseguradores.index')->with('success', 'asseguradora creada');
    }
    public static function editar(Request $request) {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|max:50',
            'adreca' => 'required',
            'preu_per_cursa' => 'required|numeric|min:0',
        ]);
        $validator->setAttributeNames([
            'nom' => 'nom',
            'adreca' => 'adreça',
            'preu_per_cursa' => 'preu per cursa'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $asseguradora = new Asseguradora();
        $asseguradora->nom = $request->input()['nom'];
        $asseguradora->adreca = $request->input()['adreca'];
        $asseguradora->preu_per_cursa = $request->input()['preu_per_cursa'];
        $asseguradora->actiu = true;

        Asseguradora::where('CIF', $request->cif)->update($asseguradora->toArray());

        return redirect()->route('asseguradores.index')->with('success', 'asseguradora editatada');
    }
}
