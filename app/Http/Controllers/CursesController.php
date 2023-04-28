<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cursa;
use App\Models\Inscripcio;
use App\Models\Corredor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Dompdf\Dompdf;
use ZipArchive;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class CursesController extends BaseController
{

    public function index()
    {
        $query = Cursa::query()->where('actiu', true);
        
        $curses = $query->get();
        
        return view('backend.curses.index', compact('curses'));

    }

    public function filtreAjax(Request $request){

        $query = Cursa::query();

        // Comprova que el camp de busqueda per data estigui omplert
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
    
        if (!empty($date_from)) {
            $query ->whereDate('data_cursa', '>=', $date_from);
        }
        
        if (!empty($date_to)) {
            $query ->whereDate('data_cursa', '<=', $date_to);
        }

        // Comprova que el camp de busqueda per text estigui omplert
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where('punt_sortida', 'LIKE', '%' . $search . '%');
        }
        
        $actiu = $request->boolean('actiu');
        $query->where('actiu', $actiu);

        $curses = $query->get();

        return response()->json($curses);
    }

    public function checkedAjax(Request $request){
        $id = $request->input('id');
        $actiu = $request->boolean('checked');
        Cursa::where('id', $id)->update(['actiu' =>  $actiu]);
        return response()->json(['success' => true]);
    }

    public function formulariAfegir()
    {
        $route_form = route('curses.afegir');
        return view('backend.curses.formulari')->with('route_form', $route_form);
    }

    public function formulariEditar(Request $request)
    {
        $route_form = route('curses.editar');

        $query_arr = Cursa::query()->where('id', $request->query('id'))->get();

        $return_val;

        if(count($query_arr) == 1){
            $query = $query_arr[0];

            $array = array('descripcio'=>$query->descripcio,'max_participants'=>$query->max_participants,'punt_sortida'=>$query->punt_sortida,'cost_patrocini'=>$query->cost_patrocini, 'desnivell'=>explode(preg_replace('/[0-9]+/', '', $query->desnivell), $query->desnivell)[0], 'desnivell_type'=>preg_replace('/[0-9]+/', '', $query->desnivell),'longitud'=>explode(preg_replace('/[0-9]+/', '', $query->longitud), $query->longitud)[0], 'longitud_type'=>preg_replace('/[0-9]+/', '', $query->longitud), 'data_cursa'=>join("T",[$query->data_cursa, $query->hora_cursa]), 'img_mapa'=>$query->img_mapa,'cartell_promocio'=>$query->cartell_promocio);

            $request2 = new Request($array);

            session()->flashInput($request2->input());

            // dd($request2->input());

            $return_val = view('backend.curses.formulari')->with('route_form', $route_form);
        }

        else{
            $return_val = back();
        }

        return $return_val;
    }

    public function participants(Request $request){
        $return_val;
        $cursa = Cursa::where('id', $request->query('id'))->where('actiu', true)->get();

        if($request->query('id') == null || count($cursa)!=1){
            $return_val = back();
        }
        else{
            $participants = Corredor::whereIn('DNI', Inscripcio::where('id_cursa', $request->query('id'))->pluck('dni_participant')->toArray())->get();
            $return_val = view('backend.curses.participants', compact('participants'))->with('id_cursa',$cursa[0]->id);
        }


        return $return_val;
    }

    public function afegir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcio' => 'required',
            'desnivell' => 'required|numeric|min:0',
            'desnivell_type' => ['required', Rule::in(['m', 'km'])],
            'cartell_promocio' => 'required|image|mimes:jpeg,png,jpg,svg',
            'img_mapa' => 'required|image|mimes:jpeg,png,jpg,svg',
            'max_participants' => 'required|integer|min:1',
            'longitud' => 'required|numeric|min:0.001',
            'longitud_type' => ['required', Rule::in(['m', 'km'])],
            'data_cursa' => 'required|date',
            'punt_sortida' => ['required',
                Rule::unique('curses')->where(fn (Builder $query) => $query->where('punt_sortida', 'LIKE', '%'. $request->input()['punt_sortida'])->where('data_cursa', date(explode("T",$request->input()['data_cursa'])[0])))
            ],
            'cost_patrocini' => 'required|numeric|min:0.01'
        ]);
        $validator->setAttributeNames([
            'descripcio' => 'descripció',
            'desnivell' => 'desnivell',
            'desnivell_type' => 'unitat de longitud del desnivell',
            'img_mapa' => 'imatge de mapa',
            'max_participants' => 'participants maxíms',
            'longitud' => 'longitud de cursa',
            'longitud_type' => 'unitat de longitud de la longitud',
            'data_cursa' => 'data de la cursa',
            'punt_sortida' => 'punt de sortida',
            'cartell_promocio' => 'cartell de promoció'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $cursa = new Cursa();
        $cursa->descripcio = $request->input()['descripcio'];
        $cursa->desnivell = $request->input()['desnivell'].$request->input()['desnivell_type'];
        $cursa->max_participants = $request->input()['max_participants'];
        $cursa->longitud = $request->input()['longitud'].$request->input()['longitud_type'];
        $cursa->data_cursa = date(explode("T",$request->input()['data_cursa'])[0]);
        $cursa->hora_cursa = date(explode("T",$request->input()['data_cursa'])[1]);
        $cursa->punt_sortida = $request->input()['punt_sortida'];
        $cursa->cost_patrocini = $request->input()['cost_patrocini'];
        $cursa->actiu = true;

        if ($request->hasFile('img_mapa')) {
            $imageName = time().rand(1000, 9999).'.'.$request->img_mapa->extension();
            $request->img_mapa->move(public_path('images/curses/img_mapa'), $imageName);
            // $img_mapa_path = $img_mapa->store('public/images/curses/img_mapa');
            $cursa->img_mapa = 'images/curses/img_mapa/'.$imageName;
        }

        if ($request->hasFile('cartell_promocio')) {
            $imageName = time().rand(1000, 9999).'.'.$request->cartell_promocio->extension();
            $request->cartell_promocio->move(public_path('images/curses/cartell_promocio'), $imageName);
            // $cartell_promocio_path = $cartell_promocio->store('public/images/curses/cartell_promocio');
            $cursa->cartell_promocio = 'images/curses/cartell_promocio/'.$imageName;
        }

        $cursa->save();

        File::makeDirectory('images/curses/fotografies/'.$cursa->id);

        return redirect()->route('curses.index')->with('success', 'Cursa creada');
    }

    public function descarregarQR(Request $request){
        $return_val;
        $cursa = Cursa::where('id', $request->query('id_cursa'))->where('actiu', true)->get();
        $participant = Corredor::where('DNI', $request->query('dni_participant'))->get();

        if($request->query('id_cursa') == null || count($cursa)!=1 || $request->query('dni_participant') == null || count($participant)!=1){
            $return_val = back();
        }
        else{
            // Generate QR code image
            // $qrCode = QrCode::format('png')->size(200)->generate('http://example.com/scan-qr?qr_data=abc123');
            $qrCode = QrCode::format('png')->size(2000)->generate(route('corredors.establirTemps', ['id' => $cursa[0]->id,'dni'=>$participant[0]->DNI]));

            // Create PDF document
            $dompdf = new Dompdf();
            $dompdf->loadHtml('<html><head></head><body><img src="data:image/png;base64,' . base64_encode($qrCode) . '" style="max-width:100%;height:auto;"/><table style="width:100%"><thead></thead><tbody><tr><th style="font-size:22px">'.$participant[0]->nom. '-' . str_replace('-','/', $participant[0]->data_naixement) .'</th></tr></tbody></table></body></html>');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Output PDF document to browser or save to file
            $dompdf->stream('BikerzQR_'.$participant[0]->DNI . '_' . $cursa[0]->punt_sortida . '_' . $cursa[0]->data_cursa . '_' . time() . '.pdf');
        }


        return $return_val;
    }

    public function descarregarZipQR(Request $request){
        $return_val;
        $cursa = Cursa::where('id', $request->query('id'))->where('actiu', true)->get();
        $participants = Corredor::whereIn('DNI', Inscripcio::where('id_cursa', $request->query('id'))->pluck('dni_participant')->toArray())->get();

        if($request->query('id') == null || count($cursa)!=1 || count($participants)<0){
            $return_val = back();
        }
        else{
            // Create a new ZipArchive instance
            $zip = new ZipArchive();
            $zipFileName = 'BikerzQRZip_'.$cursa[0]->punt_sortida.'_'.$cursa[0]->data_cursa . '_' . time().'.zip';
            $zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            foreach($participants as $participant){
                // Generate QR code image
                // $qrCode = QrCode::format('png')->size(200)->generate('http://example.com/scan-qr?qr_data=abc123');
                $qrCode = QrCode::format('png')->size(2000)->generate(route('corredors.establirTemps', ['id' => $cursa[0]->id,'dni'=>$participant->DNI]));

                // Create PDF document
                $dompdf = new Dompdf();
                $dompdf->loadHtml('<html><head></head><body><img src="data:image/png;base64,' . base64_encode($qrCode) . '" style="max-width:100%;height:auto;"/><table style="width:100%"><thead></thead><tbody><tr><th style="font-size:22px">'.$participant->nom. '-' . str_replace('-','/', $participant->data_naixement) .'</th></tr></tbody></table></body></html>');
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                $pdfContent = $dompdf->output();

                $zip->addFromString('BikerzQR_'.$participant->DNI . '_' . $cursa[0]->punt_sortida . '_' . $cursa[0]->data_cursa . '_' . time() . '.pdf', $pdfContent);
            }
            $zip->close();

            $return_val = response()->download($zipFileName)->deleteFileAfterSend(true);
        }


        return $return_val;  
    }

    public static function editar(Request $request) {
        $validator = Validator::make($request->all(), [
            'descripcio' => 'required',
            'desnivell' => 'required|numeric|min:0',
            'desnivell_type' => ['required', Rule::in(['m', 'km'])],
            'cartell_promocio' => 'required|image|mimes:jpeg,png,jpg,svg',
            'img_mapa' => 'required|image|mimes:jpeg,png,jpg,svg',
            'max_participants' => 'required|integer|min:1',
            'longitud' => 'required|numeric|min:0.001',
            'longitud_type' => ['required', Rule::in(['m', 'km'])],
            'data_cursa' => 'required|date',
            'punt_sortida' => 'required',
            'cost_patrocini' => 'required|numeric|min:0.01'
        ]);
        $validator->setAttributeNames([
            'descripcio' => 'descripció',
            'desnivell' => 'desnivell',
            'desnivell_type' => 'unitat de longitud del desnivell',
            'img_mapa' => 'imatge de mapa',
            'max_participants' => 'participants maxíms',
            'longitud' => 'longitud de cursa',
            'longitud_type' => 'unitat de longitud de la longitud',
            'data_cursa' => 'data de la cursa',
            'punt_sortida' => 'punt de sortida',
            'cartell_promocio' => 'cartell de promoció'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cursa = new Cursa();
        $cursa->descripcio = $request->input()['descripcio'];
        $cursa->desnivell = $request->input()['desnivell'].$request->input()['desnivell_type'];
        $cursa->max_participants = $request->input()['max_participants'];
        $cursa->longitud = $request->input()['longitud'].$request->input()['longitud_type'];
        $cursa->data_cursa = date(explode("T",$request->input()['data_cursa'])[0]);
        $cursa->hora_cursa = date(explode("T",$request->input()['data_cursa'])[1]);
        $cursa->punt_sortida = $request->input()['punt_sortida'];
        $cursa->cost_patrocini = $request->input()['cost_patrocini'];
        $id = $request->id_cursa;

        if ($request->hasFile('img_mapa')) {
            $old_img = $request->old_img_mapa;
            $old_img_name = explode('/',$old_img)[count(explode('/',$old_img))-1];
            if($request->img_mapa->getClientOriginalName() != $old_img_name){
                File::delete($old_img);
                $imageName = time().rand(1000, 9999).'.'.$request->img_mapa->extension();
                $request->img_mapa->move(public_path('images/curses/img_mapa'), $imageName);
                // $img_mapa_path = $img_mapa->store('public/images/curses/img_mapa');
                $cursa->img_mapa = 'images/curses/img_mapa/'.$imageName;
            }
        }

        if ($request->hasFile('cartell_promocio')) {
            $old_img = $request->old_cartell_promocio;
            $old_img_name = explode('/',$old_img)[count(explode('/',$old_img))-1];
            if($request->cartell_promocio->getClientOriginalName() != $old_img_name){
                if (File::exists($old_img)) {
                    File::delete($old_img);
                }
                $imageName = time().rand(1000, 9999).'.'.$request->cartell_promocio->extension();
                $request->cartell_promocio->move(public_path('images/curses/cartell_promocio'), $imageName);
                // $cartell_promocio_path = $cartell_promocio->store('public/images/curses/cartell_promocio');
                $cursa->cartell_promocio = 'images/curses/cartell_promocio/'.$imageName;
            }
        }

        Cursa::where('id', $id)->update($cursa->toArray());

        return redirect()->route('curses.index')->with('success', 'Cursa editatada');
    }

    public function fotografies(Request $request){
        $return_val;
        if($request->query('id') == null || !File::exists('images/curses/fotografies/'.$request->query('id'))){
            $return_val = back();
        }

        else{
            $fotografies = [];
            $arxius = File::allFiles('images/curses/fotografies/'.$request->query('id'));     
            foreach($arxius as $arxiu) { 
                array_push($fotografies,$arxiu->getRelativePathname());
            }
            
            $return_val = view('backend.curses.fotografies', compact('fotografies'));
        }
        
        return $return_val;
    }

    public function afegirFotos(Request $request){
        $images = $request->file('images');
        foreach ($images as $image) {
            $route = 'images/curses/fotografies/' . $request->input('id_cursa');
            $imageName = time().rand(1000, 9999).'.'.$image->extension();
            $image->move(public_path($route), $imageName);
        }

        return response()->json(['success']);
    }

    public function deletePhoto(Request $request){
        File::delete($request->img);
        return response()->json(['success']);
    }
}
