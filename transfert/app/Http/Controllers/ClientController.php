<?php

namespace App\Http\Controllers;

use App\Models\client;
use App\Http\Requests\StoreclientRequest;
use App\Http\Requests\UpdateclientRequest;
use App\Models\compte;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
     * @param  \App\Http\Requests\StoreclientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
         "nom"=>'required',
         "numero"=>'required|unique:clients,numero',
        ]);
        $client=client::where('nom',$request->nom)
        ->where('numero',$request->numero)->first();
        if($client){
            return response()->json([
"message"=>'ce client existe deja'
            ]);
        }
        client::create($validation);
        return response()->json([
            "message"=>'client creer avec succee',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateclientRequest  $request
     * @param  \App\Models\client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateclientRequest $request, client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(client $client)
    {
        //
    }
    public function getCompte(string $chaine){
        $data=explode("_",$chaine);
        if(count($data)==1 && strlen($data[0])==9){
            $numero=client::ClientByTel($data[0])->first();
            if(!$numero){
              return response()->json(["message"=>'ce numero existe pas',
              "data"=>""]);
            }
            return response()->json([
                "data"=>$numero
            ]);
            

        }
        if(count($data)==2 && strlen($data[1])==9 && strlen($data[0])==2){
$client=client::ClientByTel($data[1])->first();
if($client){
    $fournisseur=compte::where('client_id',$client->id)
    ->where('opperateur',$data[0])->first();
    if($fournisseur){
        return response()->json([
            'data' => $fournisseur
        ]);
    }
        }


    }return response()->json([
    "message" => "le client existe pas",
]);
}

 }