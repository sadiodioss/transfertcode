<?php

namespace App\Http\Controllers;
use App\Http\Controllers\genererCodeAleatoire;
use App\Models\transaction;
use App\Http\Requests\StoretransactionRequest;
use App\Http\Requests\UpdatetransactionRequest;
use App\Models\client;
use App\Models\compte;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




     


public function depot(Request $request){
 $this->validate($request,[
    'opperateur' =>'required|in:OM,WV,CB,WR',
    'montant'=>'required|numeric|min:500',
    'numero'=>'required|exists:clients,numero',
    'type'=>'required|in:depot',
 ]);
  
    $receveur=client::where('numero',$request->numero)->first();
    if(!$receveur){
        return response()->json([
            "message"=>'ce numero na pas de client assigner'
        ]);
    }
    $compe=compte::where('client_id',$receveur->id)
    ->where('opperateur',$request->opperateur)->first();
    if(!$compe){
        return response()->json([
            "message" => "ce client na pas de compte"
        ]);
        if($compe->out==2){
            return response()->json([
                "message"=>'ce compte est ferme elle ne peut pas faire de transaction '
            ]);
        }
    }
    $compe->solde+=$request->montant;
    $compe->save();
$transaction= new transaction();
$transaction->compte_id=$compe->id;
$transaction->opperateur=$request->opperateur;
$transaction->montant=$request->montant;
$transaction->type=$request->type;
$transaction->save();
return response()->json([
    "message"=>'depot effectuer avec succe',
    'detail'=>$transaction
]);

    
  }
  public function transfert(Request $request){

 $this->validate($request,[
 'numexpr'=>'required|exists:clients,numero',
 'numerdest'=>'required|exists:clients,numero',
 'montant'=>'required|numeric|min:500',
 'code'=>'',
 'opperateur'=>'required|in:OM,WV,WR,CB',
 'type'=>'required|in:transfert',
]);
$expediteur=client::where('numero',$request->numexpr)->first();
if(!$expediteur){
    return response()->json([
        "message"=>'expedieteur non vue'
    ]);
}  

$receveur=client::where('numero',$request->numerdest)->first();
      if(!$receveur){
        return response()->json([
            "message"=>'ce expediteur existe pas'
        ]);
      }
    
if ($request->opperateur == 'WR') {
    $caracteres = '0123456789';
    $receiverCode = '';
    $nombreDeCaracteres = strlen($caracteres);

    for ($i = 0; $i < 15; $i++) {
        $receiverCode .= $caracteres[rand(0, $nombreDeCaracteres - 1)];
    }
   $transaction=new transaction();
   $transaction->compte_id=$expediteur->id;
   $transaction->client_id=$receveur->id;
   $transaction->montant=$request->montant;
   $transaction->type=$request->type;
   $transaction->opperateur=$request->opperateur;
  $transaction->code=$receiverCode;
  $transaction->save();
  return response()->json([ "message"=>'effectuer avec succe',
  "detail"=>$transaction]);
 
}

    $comptex=compte::where('client_id',$expediteur->id)
    ->where('opperateur',$request->opperateur)->first();

    if(!$comptex){
        return response()->json([
            "message"=>'ce client na pas de compte'
        ]);
      }
      if($comptex->out==2){
        return response()->json([
            "message"=>'ce pas est fermee actuellement'
        ]);
      }
      if($comptex->etat=="2"){
        return response()->json([
            "message"=>'ce compte est acctuemllement bloque elle peu pas faire de transfert',
        ]);
      }
      
      
      
      $compterec=compte::where('client_id',$receveur->id)
      ->where('opperateur',$request->opperateur)->first();
    
      if(!$compterec){
        return response()->json([
            "message"=>"compte innexistante"
        ]);
      }
      if($compterec->out==2){
        return response()->json([
            "message"=>"ce compte ne peu pas faire de transaction acctuellement"
        ]);
      }
      $comptex->solde-=$request->montant;
      $comptex->save();
      $compterec->solde+=$request->montant;
      $compterec->save();

      $transaction=new transaction();
      $transaction->compte_id=$comptex->id;
      $transaction->client_id=$compterec->id;
      $transaction->montant=$request->montant;
      $transaction->type=$request->type;
      $transaction->opperateur=$request->opperateur;
      $transaction->save();
      return response()->json([
    "message"=>'transaction reussi',
    "detail"=>$transaction]);
}
public function deletTransaction(){
    $transaction=transaction::latest()->first();
    if(!$transaction){
        return response()->json([
            "message"=>'il ny a pas de transaction pour le momment',
        ]);
    }
    if($transaction->etat==2){
        return response()->json([
            "message"=>"cette transaction est deja annulée",
        ]);
    }
    if($transaction->type=='depot'){
        $comptdepot=compte::where('id',$transaction->compte_id)
       ->where('opperateur',$transaction->opperateur)->first();
       $comptdepot->solde-=$transaction->montant;
       $comptdepot->save();
       $transaction->etat=2;
       $transaction->save();
       return response()->json([
"message"=>'la transaction a ete annuleé',
'detail'=>$comptdepot
       ]);

       }
       if($transaction->type =='transfert'){
         $comptex=compte::where('id',$transaction->compte_id)
         ->where('opperateur',$transaction->opperateur)->first();
         $comptdes=compte::where('id',$transaction->client_id)
         ->where('opperateur',$transaction->opperateur)->first();
         $comptex->solde+=$transaction->montant;
         $comptex->save();
         $comptdes->solde-=$transaction->montant;
         $comptdes->save();
         $transaction->etat=2;
         $transaction->save();
         return response()->json([
            "message"=>'la transaction a ete annullee avec succes'
         ]);
       }
    
}
 





    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoretransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatetransactionRequest  $request
     * @param  \App\Models\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetransactionRequest $request, transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(transaction $transaction)
    {
        //
    }
    public function getAllTransacFilter($compe_id){
        $filtre=[];
        $all=transaction::where('compte_id',$compe_id)->get();
        if(!$all){
            return response()->json([
            "messade"=>'aucune transaction pour ce compte'
            ]);
        }
        return response()->json([
        "transaction"=>$all

        ]); 
  //-----------------------par date-------------//////////////////////

    }

   
}
