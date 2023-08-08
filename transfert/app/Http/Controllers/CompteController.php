<?php

namespace App\Http\Controllers;

use App\Models\compte;
use App\Http\Requests\StorecompteRequest;
use App\Http\Requests\UpdatecompteRequest;
use App\Models\client;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compte=compte::all();
        if(!$compte){
            return response()->json([
"message"=>'pas de compte disponible'
            ]);
        }
        return response()->json($compte);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecompteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'opperateur' => 'required|in:OM,WV,WR,CB',
            'solde' => 'required|numeric',
        ]);
    
        if ($request->opperateur == 'WR') {
            return response()->json([
                "message" => 'Le compte Wari ne peut pas être créé'
            ]);
        }
    
        $client = Client::where('numero', $request->numero)->first();
    
        if (!$client) {
            return response()->json([
                "message" => 'Aucun client n\'a ce numéro'
            ]);
        }
        $compteExiste = Compte::where('client_id', $client->id)
        ->where('opperateur', $request->opperateur)->first();
                if ($compteExiste) {
            return response()->json([
                "message" => 'Un compte existe déjà pour ce client'
            ]);
        }
    
        $validation['client_id'] = $client->id;
        
        Compte::create($validation);
    
        return response()->json([
            "message" => 'Compte créé avec succè'
        ]);
    }
    
    
    public function bloque($compte_id){
        $compte=compte::where('id',$compte_id)->first();
        if(!$compte){
            return response()->json([
                "message" => "ce compte n\'esxiste pas",
            ]);

        }
        if($compte->etat==1){
            $compte->etat=2;
            $compte->save();
            return response()->json([
                "message"=>'compte bloquée',
                'detail'=>$compte

            ]);
        }
        return response()->json([
            "message"=>'ce compte est deja en mode bloquée'
        ]);

    }
    public function debloque($compte_id){
        $compte=compte::where('id',$compte_id)->first();
        if(!$compte){
            return response()->json([
                "message" => "ce compte nesxiste pas",
            ]);

        }
        if($compte->etat==2){
            $compte->etat=1;
            $compte->save();
            return response()->json([
                "message"=>'compte debloquée',
                'detail'=>$compte

            ]);
        }
        return response()->json([
            "message"=>'ce compte est deja en mode debloquée'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function show(compte $compte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function edit(compte $compte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecompteRequest  $request
     * @param  \App\Models\compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecompteRequest $request, compte $compte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\compte  $compte
     * @return \Illuminate\Http\Response
     */
    

public function destroy($id)
{
    // Cherchez le compte par son ID
    $compte = compte::find($id);

    // Vérifiez si le compte existe
    if (!$compte) {
        return response()->json([
            "message" => "Aucun compte ne correspond à cet ID."
        ], 404);
    }

    // Supprimez le compte en utilisant Eloquent
    $compte->delete();
     return response()->json([
        "message" => "Compte supprimé avec succès."
    ], 200);
}

}
