<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;
use App\Models\Cursa;
use App\Models\Patrocini;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\File;
use Dompdf\Dompdf;

class SponsorsController extends BaseController
{

    public function index()
    {
        $query = Sponsor::query()->where('actiu', true);
        
        $sponsors = $query->get();

        $patrocinis = Patrocini::query()->get();
        
        return view('backend.sponsors.index', compact('sponsors'), compact('patrocinis'));

    }

    public function filtreAjax(Request $request){

        $query = Sponsor::query();

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

        $sponsors = $query->get();

        return response()->json($sponsors);
    }

    public function checkedAjax(Request $request){
        $cif = $request->input('cif');
        $actiu = $request->boolean('checked');
        Sponsor::where('cif', $cif)->update(['actiu' =>  $actiu]);
        return response()->json(['success' => true]);
    }

    public function checkedAjax2(Request $request){
        $cif = $request->input('cif');
        $actiu = $request->boolean('checked');
        Sponsor::where('cif', $cif)->update(['primera_plana' =>  $actiu]);
        return response()->json(['success' => true]);
    }

    public function formulariAfegir()
    {
        $route_form = route('sponsors.afegir');
        return view('backend.sponsors.formulari')->with('route_form', $route_form);
    }

    public function formulariEditar(Request $request)
    {
        $route_form = route('sponsors.editar');

        $query_arr = Sponsor::query()->where('cif', $request->query('cif'))->get();

        $return_val;

        if(count($query_arr) == 1){
            $query = $query_arr[0];

            $array = array('cif'=>$query->CIF,'nom'=>$query->nom,'adreca'=>$query->adreca, 'logo'=>$query->logo, 'primera_plana'=>$query->primera_plana);

            $request2 = new Request($array);

            session()->flashInput($request2->input());

            // dd($request2->input());

            $return_val = view('backend.sponsors.formulari')->with('route_form', $route_form);
        }

        else{
            $return_val = back();
        }

        return $return_val;
    }

    public function formulariPatrocini(Request $request)
    {
            $return_val;
            if($request->query('cif_sponsor') == null){
                $return_val = back();
            }
            
            else{
                $query = Cursa::query();
                
                $curses = $query->get();

                $patrocinis = Patrocini::query()->where('cif_sponsor',$request->query('cif_sponsor'))->get();

                $return_val = view('backend.sponsors.formulariPatrocini', compact('curses'), compact('patrocinis'));
            }
            
            return $return_val;

    }

    public function patrocinar(Request $request){
        $patrocini = new Patrocini();
        $patrocini->id_cursa = $request->input('id_cursa');
        $patrocini->cif_sponsor = $request->input('cif_sponsor');
        $patrocini->save();
        return response()->json(['success' => true]);
    }

    public function quitarPatrocini(Request $request){
        Patrocini::query()->where('cif_sponsor',$request->input('cif_sponsor'))->where('id_cursa',$request->input('id_cursa'))->delete();
        return response()->json(['success' => true]);
    }

    public function afegir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cif' => 'required|unique:sponsors|regex:/^[A-Z]{2}[0-9]{8}$/',
            'nom' => 'required|max:50',
            'adreca' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg',
        ]);
        $validator->setAttributeNames([
            'cif' => 'CIF',
            'nom' => 'nom',
            'adreca' => 'adreça',
            'logo' => 'logo'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $sponsor = new Sponsor();
        $sponsor->CIF = $request->input()['cif'];
        $sponsor->nom = $request->input()['nom'];
        $sponsor->adreca = $request->input()['adreca'];
        $sponsor->actiu = true;

        if ($request->hasFile('logo')) {
            $imageName = time().rand(1000, 9999).'.'.$request->logo->extension();
            $request->logo->move(public_path('images/sponsors/logo'), $imageName);
            // $logo_path = $logo->store('public/images/sponsors/logo');
            $sponsor->logo = 'images/sponsors/logo/'.$imageName;
        }

        if($request->has('primera_plana')){
            $sponsor->primera_plana = true;
        }

        else{
            $sponsor->primera_plana = false;
        }

        $sponsor->save();

        return redirect()->route('sponsors.index')->with('success', 'sponsor creada');
    }
    public static function editar(Request $request) {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|max:50',
            'adreca' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg',
        ]);
        $validator->setAttributeNames([
            'nom' => 'nom',
            'adreca' => 'adreça',
            'logo' => 'logo'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sponsor = new Sponsor();
        $sponsor->nom = $request->input()['nom'];
        $sponsor->adreca = $request->input()['adreca'];
        $sponsor->actiu = true;

        if($request->has('primera_plana')){
            $sponsor->primera_plana = true;
        }

        else{
            $sponsor->primera_plana = false;
        }

        if ($request->hasFile('logo')) {
            $old_img = $request->old_logo;
            $old_img_name = explode('/',$old_img)[count(explode('/',$old_img))-1];
            if($request->logo->getClientOriginalName() != $old_img_name){
                File::delete($old_img);
                $imageName = time().rand(1000, 9999).'.'.$request->logo->extension();
                $request->logo->move(public_path('images/sponsors/logo'), $imageName);
                // $logo_path = $logo->store('public/images/sponsors/logo');
                $sponsor->logo = 'images/sponsors/logo/'.$imageName;
            }
        }

        Sponsor::where('CIF', $request->cif)->update($sponsor->toArray());

        return redirect()->route('sponsors.index')->with('success', 'sponsor editatada');
    }

    public function descarregarFactura(Request $request){
        $return_val;
        $sponsor = Sponsor::query()->where('CIF',$request->query('cif_sponsor'))->first();
        $curses = Cursa::whereIn('id',Patrocini::query()->where('cif_sponsor',$request->query('cif_sponsor'))->pluck('id_cursa')->toArray())->get();
        $total = 0;

        if(count($curses) <= 0){
            $return_val = back();
        }
        else{
            $htmlContent = '<!DOCTYPE html>
            <html>
            <head>
            <style>
                        .invoice-box {
                            max-width: 800px;
                            margin: auto;
                            padding: 30px;
                            border: 1px solid #eee;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                            font-size: 16px;
                            line-height: 24px;
                            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                            color: #555;
                        }
                        .invoice-box table {
                            width: 100%;
                            line-height: inherit;
                            text-align: left;
                        }
                        .invoice-box table td {
                            padding: 5px;
                            vertical-align: top;
                        }
                        .invoice-box table tr td:nth-child(2) {
                            text-align: right;
                        }
                        .invoice-box table tr.top table td {
                            padding-bottom: 20px;
                        }
                        .invoice-box table tr.top table td.title {
                            font-size: 45px;
                            line-height: 45px;
                            color: #333;
                        }
                        .invoice-box table tr.information table td {
                            padding-bottom: 40px;
                        }
                        .invoice-box table tr.heading td {
                            background: #eee;
                            border-bottom: 1px solid #ddd;
                            font-weight: bold;
                        }
                        .invoice-box table tr.details td {
                            padding-bottom: 20px;
                        }
                        .invoice-box table tr.item td {
                            border-bottom: 1px solid #eee;
                        }
                        .invoice-box table tr.item.last td {
                            border-bottom: none;
                        }
                        .invoice-box table tr.total td:nth-child(2) {
                            border-top: 2px solid #eee;
                            font-weight: bold;
                        }
                        @media only screen and (max-width: 600px) {
                            .invoice-box table tr.top table td {
                                width: 100%;
                                display: block;
                                text-align: center;
                            }
                            .invoice-box table tr.information table td {
                                width: 100%;
                                display: block;
                                text-align: center;
                            }
                        }
                        /** RTL **/
                        .invoice-box.rtl {
                            direction: rtl;
                            font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                        }
                        .invoice-box.rtl table {
                            text-align: right;
                        }
                        .invoice-box.rtl table tr td:nth-child(2) {
                            text-align: left;
                        }

                        img{
                            width: 20%;
                            max-width: 150px !important;
                        }

                    </style>
                </head>
                <body>
                    <div class="invoice-box">
                        <table cellpadding="0" cellspacing="0">
                            <tr class="top">
                                <td colspan="2">
                                    <table>
                                        <tr>
                                            <td class="title" style="justify-content:center; display: flex">
                                                <h1>BIKERZ</h1>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr class="information">
                                <td colspan="2">
                                    <table>
                                        <tr>
                                            <td>
                                                Bikerz<br />
                                                12345 Badalona<br />
                                                Barcelona, CA 12345
                                            </td>
                                            <td>
                                                CIF: '.$sponsor->CIF.'<br />
                                                Nom: '.$sponsor->nom.'<br />
                                                Adreça: '.$sponsor->adreca.'
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr class="heading">
                                <td>Articles</td>
                                <td>Preu</td>
                            </tr>';
            foreach($curses as $cursa){
                $htmlContent .= '<tr class="item">
                                    <td> Patrocini de cursa '.$cursa->punt_sortida.' - '.$cursa->data_cursa.'</td>
                                    <td>'.$cursa->cost_patrocini.'€</td>
                                </tr>';
                $total += $cursa->cost_patrocini;
            }
            if($sponsor->primera_plana){
                $htmlContent .= '<tr class="item">
                                    <td>Logo a la primera plana</td>
                                    <td>200€</td>
                                </tr>';
                $total += 200;
            }
            $htmlContent .= '<tr class="total">
                        <td></td>
                        <td>Total: '.$total.'€</td>
                    </tr>
                </table>
                </div>
                </body>
                </html>';
            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);

            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $response = response($dompdf->output());
            $response->header('Content-Type', 'application/pdf');
            $response->header('Content-Disposition', 'attachment;filename="BikerzFacturaPatrocini_'.$sponsor->CIF . '_' .  time() . '.pdf"');
            $return_val = $response;

            // $dompdf->stream('BikerzFacturaPatrocini_'.$sponsor->CIF . '_' .  time() . '.pdf');
        }
        return $return_val;
    }
}
