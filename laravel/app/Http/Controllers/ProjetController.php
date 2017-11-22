<?php

namespace App\Http\Controllers;

use DB;
use App\Projet;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $projet = DB::table('projet')->select("id as projet_id", "nom as projet_nom"
                                            , "description as projet_description"
                                            , "date_du as projet_date_du")
        ->whereNull("date_complete")
        ->get();


        return view("projetRapide")->with("dataProjet", $projet->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          return $request->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $projet = new Projet;
        $projet->increments('id');
        $projet->creer_par_acteur_id = 2;
        $projet->nom = request('nom_projet');
        $projet->description = request('description_projet');
        $projet->date_du = $request->input('date_du_projet');
        //$projet->date_complete = $request->input('date_complete');
        $projet->save();

/*
        $sprint_activite = new SprintActivite;
        $sprint_activite->projet_id = request("projet_id");
        $sprint_activite->sprint_id = NULL;
        $sprint_activite->actif = 1;
        $sprint_activite->creer_par_acteur_id = 2;
        $sprint_activite->assigne_acteur_id = 2;
        $sprint_activite->save();*/

        $data = array(
          /*'last_inserted_id' => $projet->id,
          'nom' => request('nom_projet'),
          'description' => request('description_projet')*/
        );
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function show(Projet $projet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\id  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $projet = Projet::find($id);
        $data = array(
            'projet_id' => $projet->id,
            'projet_nom' => $projet->nom,
            'projet_description' => $projet->description,
            'projet_date_du' => $prjet->date_du,
            'projet_date_complete' => $projet->date_complete
       );
       return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\id  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $projet = Tache::find($id);
        $projet->nom = $request->modifier_nom_projet;
        $projet->description = $request->modifier_description_projet;
        $projet->projet_date_du = $request->input('modifier_date_du');
        $projet->projet_date_complete = $request->input('modifier_date_complete');
        $projet->update();

        $data = array(
           'message' => 'Le projet a été modifié avec succès.'
       );
       return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\id  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $data = array(

          'message' => 'Le projet a été supprimé.'
      );
      return $data;
    }
}
