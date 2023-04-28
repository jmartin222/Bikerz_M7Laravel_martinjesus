<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Models\Cursa;
use App\Models\Sponsor;
use App\Models\Asseguradora;
use Illuminate\Support\Facades\DB;
use App\Models\Patrocini;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Inscripcio;
use App\Models\Corredor;
use Illuminate\Routing\Controller as BaseController;

class MainController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $curses = Cursa::where('actiu', true)->where('data_cursa', '>=', now())->orderBy('data_cursa','asc')->get();
        $sponsors = Sponsor::where('actiu', true)->where('primera_plana', true)->get();

        return view('frontend.index', compact('curses'), compact('sponsors'));
    }

    public function inscripcions()
    {
        $curses = Cursa::where('actiu', true)->orderBy('data_cursa','desc')->get();

        return view('frontend.inscripcions', compact('curses'));
    }

    public function dadesCursa(Request $request){
        $return_val;
        $cursa = Cursa::where('id', $request->query('id'))->where('actiu', true)->get();

        if($request->query('id') == null || count($cursa)!=1){
            $return_val = back();
        }
        else{
            $sponsors = Sponsor::whereIn('CIF', Patrocini::where('id_cursa', $request->query('id'))->pluck('cif_sponsor')->toArray())->get();
            $asseguradores = Asseguradora::where('actiu', true)->get();
            $n_inscripcions = count(Inscripcio::where('id_cursa', $request->query('id'))->get());
            $return_val = view('frontend.cursa.dadesCursa', compact('sponsors'), compact('asseguradores'))->with('cursa', $cursa[0])->with('n_inscripcions', $n_inscripcions);
        }


        return $return_val;
    }

    public function fotografies(Request $request){
        $return_val;
        if($request->query('id') == null || !File::exists('images/curses/fotografies/'.$request->query('id')) || strtotime(Cursa::where('id', $request->query('id'))->get()[0]->data_cursa) > strtotime('now')){
            $return_val = redirect()->route('frontend.index');
        }

        else{
            $fotografies = [];
            $arxius = File::allFiles('images/curses/fotografies/'.$request->query('id'));     
            foreach($arxius as $arxiu) { 
                array_push($fotografies,$arxiu->getRelativePathname());
            }
            
            $return_val = view('frontend.cursa.fotografies', compact('fotografies'));
        }
        
        return $return_val;
    }

    public function classificacio(Request $request){
        $return_val;
        $cursa = Cursa::where('id', $request->query('id'))->where('actiu', true)->get();

        if($request->query('id') == null || count($cursa)!=1  || strtotime(Cursa::where('id', $request->query('id'))->get()[0]->data_cursa) > strtotime('now')){
            $return_val = back();
        }
        else{
            $filtre = $request->query('filtre');
            
            $participants = Corredor::select('corredors.*', DB::raw('inscripcions.temps AS temps'), DB::raw("@posicio:=@posicio+1 as posicio"))
            ->whereIn('DNI', Inscripcio::where('id_cursa', $request->query('id'))->pluck('dni_participant')->toArray())
            ->join('inscripcions', function ($join) use ($request) {
                $join->on('inscripcions.dni_participant', '=', 'corredors.DNI')
                     ->where('inscripcions.id_cursa', '=', $request->query('id'))
                     ->where('inscripcions.temps', '!=', null);
            })
            ->orderBy('temps')
            ->crossJoin(DB::raw("(SELECT @posicio:=0) p"));

            
            if($filtre == 'Home' || $filtre == 'Dona'){
                $participants = $participants->where('sexe', $filtre);
            }

            else if(in_array($filtre, [20,30,40,50,60])){
                $data_naixement_max = Carbon::now()->subYears($filtre+10)->format('Y-m-d');
                $data_naixement_min = Carbon::now()->subYears($filtre)->format('Y-m-d');
                $participants = $participants->whereDate('data_naixement', '<=', $data_naixement_min)->whereDate('data_naixement', '>=', $data_naixement_max);
            }

            else if($filtre == 'punts'){
                $participants = $participants->orderByDesc('punts');
            }

            else{
                $filtre = 'general';
            }
            $participants = $participants->get();


            $return_val = view('frontend.cursa.classificacio', compact('participants'))->with('id_cursa',$cursa[0]->id)->with('filtre', $filtre);
        }


        return $return_val;
    }
}
