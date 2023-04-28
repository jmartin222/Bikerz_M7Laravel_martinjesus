<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Corredor;
use App\Models\Asseguradora;
use App\Models\Cursa;
use Dompdf\Dompdf;
use App\Models\Inscripcio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Phone;
use PayPal\Api\Address;
use PayPal\Api\MerchantInfo;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;

class CorredorsController extends BaseController
{

    public function formulariAfegir()
    {
        $route_form = route('corredors.afegir');
        return view('frontend.corredors.formulari')->with('route_form', $route_form);
    }

    public function afegir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|unique:corredors|regex:/^[0-9]{8}[A-Z]{1}$/',
            'nom' => 'required|max:50',
            'adreca' => 'required',
            'sexe' => ['required', Rule::in(['Home', 'Dona'])],
            'data_naixement' => 'required|date|before:-20 years',
        ]);
        $validator->setAttributeNames([
            'dni' => 'DNI',
            'data_naixement' => 'data de naixement',
            'nom' => 'nom',
            'adreca' => 'adreça',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $corredor = new Corredor();
        $corredor->DNI = $request->input()['dni'];
        $corredor->nom = $request->input()['nom'];
        $corredor->adreca = $request->input()['adreca'];
        $corredor->sexe = $request->input()['sexe'];
        $corredor->data_naixement = $request->input()['data_naixement'];

        // dd($corredor);

        $corredor->save();

        return redirect()->route('frontend.index')->with('success', 'corredor creat');
    }

    public function inscripcio(Request $request){

        $validator;
        // Set up items
        $item = new Item();
        $item->setName('Entrada Bikerz')
            ->setCurrency('EUR')
            ->setQuantity(1)
            ->setPrice(21.00);  
        $items = [$item];


        if($request->input('option') == 'pro'){
            $validator = Validator::make($request->all(), [
                'dni_participant' => ['required','exists:corredors,dni','regex:/^[0-9]{8}[A-Z]{1}$/', 
                    function ($attribute, $value, $fail) use ($request) {
                        $exists = \DB::table('inscripcions')
                            ->where('dni_participant', $value)
                            ->where('id_cursa', $request->cursa_id)
                            ->exists();
                        if ($exists) {
                            $fail("El DNI ja está enregistrad per la carrera.");
                        }
                    }],
                'proOptionInput' => 'required|regex:/^[A-Z]{3}-[0-9]{3}$/',
            ]);
            $validator->setAttributeNames([
                'dni_participant' => 'DNI',
                'proOptionInput' => 'numero federat',
            ]);
        }

        elseif($request->input('option') == 'open'){
            $validator = Validator::make($request->all(), [
                'dni_participant' => ['required','exists:corredors,dni','regex:/^[0-9]{8}[A-Z]{1}$/', 
                                        function ($attribute, $value, $fail) use ($request) {
                                            $exists = \DB::table('inscripcions')
                                                ->where('dni_participant', $value)
                                                ->where('id_cursa', $request->cursa_id)
                                                ->exists();
                                            if ($exists) {
                                                $fail("El DNI ja está enregistrad per la carrera.");
                                            }
                                        }],
            ]);
            $validator->setAttributeNames([
                'dni_participant' => 'DNI',
            ]);

            $asseguradora = Asseguradora::where('CIF', $request->input('openOptionSelect'))->get()[0];

            $item2 = new Item();
            $item2->setName('Assegurança de ' . $asseguradora->nom)
                ->setCurrency('EUR')
                ->setQuantity(1)
                ->setPrice(floatval($asseguradora->preu_per_cursa));
            array_push($items, $item2); 
        }

        else{
            redirect()->back();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Set up PayPal API context using sandbox environment and API credentials
        $paypal = new ApiContext(new OAuthTokenCredential(
            'AekGlr038q-8wLTU9MhovKNTZUoqc-Ve-uO_2skl74GDDfbQzILGjIgyf_8OVh-6pjXhmDSjptweQhR8',     // Replace with your own Client ID
            'EI0wryv8wrKs67oRv3H4SgjT9BQfjtxL-hyhSFfT7fsy9aDW8bRYy41pHVp3-uzhDj1m5bI2cSCsBvWc'  // Replace with your own Secret
        ));
        $paypal->setConfig([
            'mode' => 'sandbox',
        ]);

        // $merchantInfo = new MerchantInfo();
        // $merchantInfo->setEmail('sb-vynmk25773319@business.example.com')
        // ->setFirstName('Jane')
        // ->setLastName('Doe')
        // ->setBusinessName('Bikerz')
        // ->setPhone(new Phone())
        // ->setAddress(new Address());;

        // Set up payer
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $itemList = new ItemList();
        $itemList->setItems($items);

        // Set up payment details
        $subtotal = $preu = array_sum(array_column($items, 'price'));;
        $tax = $preu * 0.21;
        $total = $preu + $tax;
        $details = new Details();
        $details->setSubtotal($subtotal)
                ->setTax($tax);

        $amount = new Amount();
        $amount->setCurrency('EUR')
            ->setTotal($total)  
            ->setDetails($details);

        // Set up transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription('Entrada para carrera de ciclismo de Bikerz');

        // Set up redirect URLs
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('corredors.guardarInscripcio', ['dni_participant'=>$request->input('dni_participant'),'cursa_id'=>$request->input('cursa_id'),'tipus'=>$request->input('option'), 'assegurança' => $request->input('option') == 'pro'?$request->input('proOptionInput'):$request->input('openOptionSelect')]))
                    ->setCancelUrl(route('frontend.index'));

        // Set up payment
        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));
        // Create payment
        try {
            $payment->create($paypal);

            return redirect()->away($payment->getApprovalLink());
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            echo $e->getData();
        }
    }

    public function guardarInscripcio(Request $request)
    {
        $return_val;
        $htmlContent = null;
        $htmlContentTitle = null;
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');

        // Set up PayPal API context using sandbox environment and API credentials
        $paypal = new ApiContext(new OAuthTokenCredential(
            'AekGlr038q-8wLTU9MhovKNTZUoqc-Ve-uO_2skl74GDDfbQzILGjIgyf_8OVh-6pjXhmDSjptweQhR8',     // Replace with your own Client ID
            'EI0wryv8wrKs67oRv3H4SgjT9BQfjtxL-hyhSFfT7fsy9aDW8bRYy41pHVp3-uzhDj1m5bI2cSCsBvWc'  // Replace with your own Secret
        ));
        $paypal->setConfig([
            'mode' => 'sandbox',
        ]);

        // Get payment object by ID
        $payment = Payment::get($paymentId, $paypal);

        $items = $payment->getTransactions()[0]->getItemList()->getItems();
        $total = $payment->getTransactions()[0]->getAmount()->total;
        $subtotal = $payment->getTransactions()[0]->getAmount()->details->subtotal;
        $tax = $payment->getTransactions()[0]->getAmount()->details->tax;
        $corredor = Corredor::where('DNI', $request->query('dni_participant'))->first();
        $currency = $payment->getTransactions()[0]->getAmount()->currency;

        // Set up execution details
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        // Execute payment
        try {
            $result = $payment->execute($execution, $paypal);
            $inscripcio = new Inscripcio();
            $inscripcio->dni_participant = $request->query('dni_participant');
            $inscripcio->id_cursa = $request->cursa_id;
            $inscripcio->save();
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
                                                                CIF: '.$corredor->DNI.'<br />
                                                                Nom: '.$corredor->nom.'<br />
                                                                Adreça: '.$corredor->adreca.'
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="heading">
                                                <td>Articles</td>
                                                <td>Preu</td>
                                            </tr>';
                            foreach($items as $item){
                                $htmlContent .= '<tr class="item">
                                                    <td>'.$item->name.'</td>
                                                    <td>'.$item->price. ' ' . $currency .'</td>
                                                </tr>';
                            }
                            $htmlContent .= '<tr class="item">
                                        <td></td>
                                        <td style="font-weight: bold;">Subtotal: '.$subtotal. ' ' . $currency .'</td>
                                    </tr>
                                    <tr class="item">
                                        <td></td>
                                        <td style="font-weight: bold;">IVA 21%: '.$tax. ' ' . $currency .'</td>
                                    </tr>
                                    <tr class="total">
                                        <td></td>
                                        <td>Total: '.$total. ' ' . $currency .'</td>
                                    </tr>
                                </table>
                                </div>
                                </body>
                                </html>';
                            $htmlContentTitle = 'BikerzFacturaInscripcio_'.$corredor->DNI . '_' .  time() . '.pdf';
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            echo dd($e);
        }

        // Redirect user to a thank-you page or some other page to confirm the payment
        return redirect()->route('frontend.descarregarPdfPage', ['htmlContent'=>$htmlContent, 'htmlContentTitle'=>$htmlContentTitle]);
    }

    public function establirTemps(Request $request){
        $cursa = Cursa::where('id', $request->query('id'))->first();
        if(strtotime('now')>=strtotime(\DateTime::createFromFormat('Y-m-d H:i:s', $cursa->data_cursa . ' ' . $cursa->hora_cursa)->format('Y-m-d H:i:s')) && count(Inscripcio::where('id_cursa', $request->query('id'))->where('dni_participant', $request->query('dni'))->get()) == 1 && Inscripcio::where('id_cursa', $request->query('id'))->where('dni_participant', $request->query('dni'))->first()->temps == null){
            $temps = number_format((float)((strtotime('now')-strtotime(\DateTime::createFromFormat('Y-m-d H:i:s', $cursa->data_cursa . ' ' . $cursa->hora_cursa)->format('Y-m-d H:i:s')))/60), 2, '.', '');
            Inscripcio::where('id_cursa', $request->query('id'))->where('dni_participant', $request->query('dni'))->update(['temps'=>$temps]);

            $corredor = Corredor::select('corredors.*', DB::raw('inscripcions.temps AS temps'), DB::raw("@posicio:=@posicio+1 as posicio"))
            ->join('inscripcions', 'inscripcions.dni_participant', '=', 'corredors.DNI')
            ->whereIn('DNI', Inscripcio::where('id_cursa', $request->query('id'))->pluck('dni_participant')->toArray())
            ->orderBy('temps')
            ->crossJoin(DB::raw("(SELECT @posicio:=0) p"))
            ->get()
            ->filter(function($value, $key) use ($request){
                return $value->DNI == $request->query('dni');
            })->first();
            if($corredor->posicio < 11){
                Corredor::where('DNI', $request->query('dni'))->increment('punts', (1100-(100*$corredor->posicio)));
            }
        }

        return redirect()->route('frontend.index');
    }

    public function descarregarPdfPage(Request $request){
        $htmlContent = $request->query('htmlContent');
        $htmlContentTitle = $request->query('htmlContentTitle');
        $return_val;

        if($htmlContent != null && $htmlContentTitle != null){
            $return_val =  view('frontend.descarregarPdfPage')->with('htmlContent', $htmlContent)->with('htmlContentTitle', $htmlContentTitle);
        }

        else{
            $return_val = back();
        }

        return $return_val;
    }

    public function descarregarPdf(Request $request){
        $htmlContent = $request->query('htmlContent');
        $htmlContentTitle = $request->query('htmlContentTitle');
        $return_val;

        if($htmlContent != null && $htmlContentTitle != null){
            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);

            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $response = response($dompdf->output());
            $response->header('Content-Type', 'application/pdf');
            $return_val =  $response->header('Content-Disposition', 'attachment;filename="'.$htmlContentTitle.'"');
        }

        else{
            $return_val = back();
        }
        
        return $return_val;
    }
}
