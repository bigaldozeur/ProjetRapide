<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <header>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ Session::token() }}">
        <title>Projet Rapide</title>

        <link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">



        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href={{ asset('css/projetrapide.css') }}>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('js/jquery.blockUI.js') }}"></script>

  <script>

    <?php ini_set('display_errors', 'On'); ?>


    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
    });

  </script>

        <script type="text/javascript">

            var g_selected_projet_id  = 1;

            // var sprint_id  = 1;

         // sur double-click d'une tache
            $("body").delegate('li','dblclick',function() {


                var tache_id =  $(this).attr('id');
                //on s'assure que le <li> cliqu� est une t�che sinon exit
                //il faut que le id de <a> commence par btn_ajouter_tache_Liste + _ + le id de la liste dans la bd
                if(!(typeof tache_id != 'undefined' && tache_id.indexOf("li_tache") >= 0)){
                  return;
                }
                $tache_no = tache_id.replace("li_tache_", "");
                $("body").data("modif_tache_no", $tache_no);


                 $url = "taches/" + $tache_no + "/edit";
                 $.ajax({ statusCode: {
                    500: function(xhr) {
                    alert(500);
                    }},
                    //the route pointing to the post function
                    url: $url,
                    type: 'GET',
                    dataType: 'text',

                success: function (result,status,xhr) {
                    var la_tache = JSON.parse(result);
                    $('#modifier_nom_tache').val(la_tache.tache_nom);
                    $('#modifier_description_tache').val(la_tache.tache_description);
                   // $('#tache_message_modifier').hide();

                },error(xhr,status,error){
                    alert("error 1 " + status);
                    alert("error 2 " + error);
                },

                    complete: function (xhr,status) {
                    // Handle the complete event
                    //alert("complete " + status);
                    }
                });

                // Partie ajax pour éditer le formulaire

                $.blockUI({
                     message: $('#tache_modifier_form'),
                     css: { top:'20%'}
                });
            }); //  $("body").delegate('li','dblclick',function

              // Modifier un tâche
            $("body").delegate('#btn_tache_modifier_annuler','click',function(){
             // $('#btn_tache_modifier_annuler').click(function() {
                  //$('#form_modifier_tache')[0].reset();

                  $.unblockUI();
                  return false;
            }); // $("body").delegate('#btn_tache_modifier_annuler','click',function



            $("body").delegate('#btn_tache_modifier','click',function(){
                $tache_no = $("body").data("modif_tache_no");
                var input_name = "modifier_nom_tache";
                var nom_tache = $("#form_modifier_tache :input[name='"+input_name+"']").val();

                if(nom_tache == ""){
                     //confirm("Attention, vous devez entrer une tâche!");

                    nom_tache = "Non défini";
                }
                if(description_tache == ""){
                    description_tache = "Non défini"
                }

                $url = "taches/" + $tache_no;

                $.ajax({ statusCode: {
                    500: function(xhr) {
                    alert(500);
                    }},
                    //the route pointing to the post function
                    url: $url,
                    type: 'PUT',
                    // send the csrf-token and the input to the controller
                    data: $('#form_modifier_tache').serialize(),
                    dataType: 'text',
                    // remind that 'data' is the response of the AjaxController
                success: function (result,status,xhr) {
                    var tache_no = $("body").data("modif_tache_no");
                    var spanAModifier = "tache_titre_" + $tache_no;
                    $('#'+spanAModifier).text(nom_tache);
                },error(xhr,status,error){
                    alert("error 1 " + status);
                    alert("error 2 " + error + " "+ xhr.responseText);
                },

                    complete: function (xhr,status) {
                    // Handle the complete event
                    //alert("complete PUT " + status);
                    }
                });
                $.unblockUI();

            });//#btn_tache_modifier
            // Fin modifier une tâche

            function ajouter_tache(liste_no){
                $.blockUI({
                     message: $('.div_tache_form'),
                     css: { top:'20%'}
                });
            } //ajouter_tache


            function get_all_liste_tache(){
                var json;
                var json_ordre_tache;
                var ordre_tache;
                json_ordre_tache = '{'
                $( '.container-list .sortable-list' ).each(function(){

                    ordre_tache = String($(this).sortable("toArray"));
                    json_ordre_tache += '"' + $(this).attr("id") + '":';
                    //if(ordre_tache != ""){

                        json_ordre_tache += '{"ordre_tache":[' +  ordre_tache + ']}';

                    //}
                    json_ordre_tache += ",";
                });
                json_ordre_tache = json_ordre_tache.substr(0,json_ordre_tache.length-1);
                json_ordre_tache =  json_ordre_tache.replace(/li_tache_/g,"").replace(/ul_liste_/g,"");
                json_ordre_tache += '}';

               // alert(json_ordre_tache);
                //var sortedIDs = $( '.container-list .sortable-list' ).sortable( "toArray" );
                //alert(sortedIDs);
                return json_ordre_tache;

            }

             function creer_liste(sprint_id_name,id, nom, description){

               // var sprint_id_name = $('#tabs .ui-state-active').attr('aria-controls');
                  //  alert(sprint_id_name);
                //    var sprint_id = sprint_id_name.replace("sprint_", "");
                  //  alert(sprint_id);

                $liste = '<div class="container-list">'
                $liste +='    <div class="panel panel-default column left"  id="liste_' + id + '">'
                $liste +='        <div class="panel-heading" id="liste_panel_' + id + '" rel="tooltip" title="' + description + '">'
                $liste +='            <span id="liste_titre_' + id + '">' + nom + '</span>'
                $liste +='        </div>  <!-- panel-heading -->'
                $liste +='        <div class="panel-body">'
                $liste +='            <ul class="sortable-list" id="ul_liste_' + id + '">'
                $liste +='            </ul>'
                $liste +='        </div> <!-- panel-body -->'
                $liste +='        <div class="panel-footer">'
                $liste +='            <a href="#" id="btn_ajouter_tache_Liste_' + id + '" class="btn btn-link right">ajouter une tache</a>'
                $liste +='        </div> <!-- panel-footer -->'
                $liste +='    </div>  <!-- panel-default -->'
                $liste +='</div>  <!-- #container-liste -->'

                $("#" + sprint_id_name).append($liste);

                $("#btn_ajouter_tache_Liste_" + id ).bind('click', function(e)
                {
                    e.preventDefault();
                    ajouter_tache();
                });



                $('.container-list .sortable-list').sortable({
                    connectWith: '.container-list .sortable-list',
                    placeholder: 'placeholder',


                    stop: function( event, ui ){

                        var json_liste_tache = get_all_liste_tache();




                    /*    //on récupere le numéro de la liste
                        var liste_id_name = $(this).attr("id");
                        var liste_no = liste_id_name.replace("ul_liste_", "");

                        var tache_id_name = $(ui.item).attr("id");
                        var tache_no = tache_id_name.replace("li_tache_", "");
                      */

                        //var sprint_id_name = $("#tabs .ui-tabs-panel:visible").attr("id");
                        var sprint_id = sprint_id_name.replace("sprint_", "");

                        var data =  "projet_id=" + g_selected_projet_id+ "&sprint_id=" + sprint_id + "&liste_tache=" + json_liste_tache;




                        $.ajax({ statusCode: {
                            500: function(xhr) {
                             alert(500);
                            }},
                            //the route pointing to the post function
                            url: "/sprintactivite" ,
                            type: 'POST',
                            // send the csrf-token and the input to the controller
                            data: data,
                            dataType: 'text',

                        success: function (result,status,xhr) {

                               //alert("drag drop successs");

                        },error(xhr,status,error){
                            alert("error 1 " + status);
                            alert("error 2 " + error);
                        },
                            complete: function (xhr,status) {
                                // Handle the complete event
                             //alert("complete " + status);
                            }
                        });  //ajax






                    } // stop function


                }); //$('.container-list .sortable-list').sortable

            } //creer_liste



            function creer_tache(liste_id, tache_id, tache_nom, tache_description){
                 $("#ul_liste_" + liste_id).append( '<li id="li_tache_' + tache_id + '" class="sortable-item"><a href="#" class="x-remove"><span class="glyphicon glyphicon-remove pull-right"></span></a><a href="#"  class="c-comment"><span class="glyphicon glyphicon-comment pull-left"></a><a href="#" class="i-info"><span class="glyphicon glyphicon-info-sign pull-left"></a><span id="tache_titre_'+ tache_id + '">' + tache_nom + '</span></li>' );

            }


            function sur_double_clique_liste(liste_id){

              //var liste_id =  $(this).attr('id');
              //on s'assure qu'on a bien cliqué sur la partie liste sinon exit
              if(!(typeof liste_id != 'undefined' && liste_id.indexOf("liste_panel_") >= 0)){
                      return;
              }
              var liste_no = liste_id.replace("liste_panel_", "");
              $("body").data("modif_liste_no", liste_no);

              $url = "listes/" + liste_no + "/edit";

              $.ajax({ statusCode: {
                  500: function(xhr) {
                  alert(500);
                  }},
                  //the route pointing to the post function
                  url: $url,
                  type: 'GET',
                  dataType: 'text',

              success: function (result,status,xhr) {
                  var la_liste = JSON.parse(result);
                  $('#modifier_nom_liste').val(la_liste.nom_liste);
                  $('#modifier_description_liste').val(la_liste.description_liste);
                  $('#liste_message_modifier').hide();

                  },error(xhr,status,error){
                      alert("error 1 " + status);
                      alert("error 2 " + error);
                  },

                  complete: function (xhr,status) {
                  // Handle the complete event
                  //alert("complete " + status);
                  }
              });

              $.blockUI({
                    message: $('#liste_modifier_form'),
                    css: { top:'20%'}
              });
            }

           // sur double-clique d'un div aller chercher la liste et l'afficher pour modification, sinon ne rien faire
           $("body").delegate('div','dblclick',function(){return sur_double_clique_liste($(this).attr('id'))});

            // Annuler la modification d'une liste
            $("body").delegate('#btn_liste_formmodifier_annuler','click',function(){
                //$('#form_modifier_liste')[0].reset();
                $.unblockUI();
                return false;
            });

            // Permet de valider les champs d'une liste Ne sert plus à rien car maintenant, on fait les validations en backend.
            function valider_champs_liste(nom_liste, description_liste){

              if( nom_liste.replace(/\s/g, '') == ""){
                $("#liste_message_modifier").html("Le nom de la liste ne doit pas être vide ou contenir seulement des espaces.").removeClass().addClass("alert alert-warning").show();
                return false;
              }

              if( description_liste.replace(/\s/g, '') == ""){
                $("#liste_message_modifier").html("La description de la liste ne doit pas être vide ou contenir seulement des espaces.").removeClass().addClass("alert alert-warning").show();
                return false;
              }
              return true;

            }// valider_champs_liste

            // Permet d'afficher la modification du nom de la liste
            function afficher_liste_modifiee(id_liste, nom_liste, description_liste){

              var tagAModifier = "liste_titre_"+id_liste;
              $('#'+tagAModifier).text(nom_liste);

              tagAModifier = "liste_panel_"+id_liste;
              $('#'+tagAModifier).attr('title', description_liste);
            }

            // Permet de modifier la liste dans la bd
            function modifier_liste_bd(id_liste, nom_liste, description_liste){
              $url = "listes/" + id_liste;

              $.ajax({ statusCode: {
                  500: function(xhr) {
                  alert(500);
                  //TODO : mettre un log ici faire ajax dans une table de logs créer table de log, creer ajax.. Faire une fonction
                }},
                  //the route pointing to the post function
                  url: $url,
                  type: 'PUT',
                  // send the csrf-token and the input to the controller
                  data: $('#form_modifier_liste').serialize(),
                  dataType: 'text',
                  // remind that 'data' is the response of the AjaxController
              success: function (result,status,xhr) {

                  //alert("result, status, xhr"+ result + ','+status+','+xhr);
                   //xhr{"success":"false","errors":"Controller : Les valeurs entr\u00e9es ne sont pas conformes aux valeurs attentues."},success,[object Object]
                  var json_rep = JSON.parse(xhr.responseText);

                  if(json_rep.success != null && json_rep.success == "false"){
                    $erreur = json_rep.errors == null? "Une valeur entrée n'est pas conforme." : json_rep.errors;
                    $("#liste_message_modifier").html($erreur).removeClass().addClass("alert alert-warning").show();
                    return false;
                  }
                  else {
                    afficher_liste_modifiee(id_liste, nom_liste, description_liste);
                    $("#liste_message_modifier").hide();
                    $("#sprint_message").html("Modification de la liste réussie avec succès.").removeClass().addClass("alert alert-success").show().fadeOut(8000);
                  }

                  $.unblockUI();
                  return true;

              },error(xhr,status,error){
                    $("#sprint_message").html("Une erreur est survenue lors de la modification de la liste.").removeClass().addClass("alert alert-danger").show().fadeOut(8000);
                    return true;
                    //$.unblockUI();
              },
                  complete: function (xhr,status) {
                  // Handle the complete event
                  //alert("complete " + status);
                  }
              });
            }

            // Sur appuie du bouton modifier de la liste
            $("body").delegate('#btn_liste_modifier','click',function(){

                var id_liste = $("body").data("modif_liste_no");
                var input_name = "modifier_nom_liste";
                var nom_liste = $("#form_modifier_liste :input[name='"+input_name+"']").val();
                var input_name = "modifier_description_liste";
                var description_liste = $("#form_modifier_liste :input[name='"+input_name+"']").val();

                var champs_valides = valider_champs_liste(nom_liste, description_liste);
                // Si les champs ne sont pas valides, on ne continue pas le processus de modification.
                if(!champs_valides)
                  return;

                if(!modifier_liste_bd(id_liste, nom_liste, description_liste))
                  return;

                $.unblockUI();

            });//#btn_liste_modifier
            // Fin modifier une liste
/*
            function afficherTache(data) {
                var tache = JSON.stringify(data);
                var tachef = JSON.parse(tache);

                $.each( JSON.parse(tachef), function( nom, value ) {
                    var nomTache = value.nom;
                    var idTache = value.id;
                    $("#ul_liste_" + 1).append( '<li id="li_da" class="sortable-item"><a href="#" class="x-remove"><span class="glyphicon glyphicon-remove pull-right"></span></a><span id="tache_titre_' + idTache + '">' + nomTache + '</span></li>');
                });

            }

*/

$(document).ready(function() {


            $("body").delegate('#btn_projet_charger','click', function() {


                var p_id = $(this).attr("projet_id");
                g_selected_projet_id = p_id;
                var titre = $(this).attr("projet_nom");
                $("#titre_projet").html(titre);
                $("#projet_wrapper").hide();
                $("#center-wrapper").show();



                $.ajax({

                            url: "/" + p_id,
                            type: 'GET',
                            dataType: 'text',

                        success: function (result,status,xhr) {

                          //   alert(result);
                             var json_obj = JSON.parse(result);
                             var prev_sprint;
                             var prev_liste;
                           //alert(j[0].projet_nom);
                          for (var i in json_obj)
                        {

                            if(prev_sprint != json_obj[i].sprint_id){
                                sprint_add_tab(json_obj[i].sprint_id, json_obj[i].sprint_numero);

                                $( "#tabs" ).tabs({ active: i });
                                $( "#tabs" ).tabs( "refresh" );
                            }

                            if(json_obj[i].liste_id != null && prev_liste != json_obj[i].liste_id){
                                creer_liste("sprint_" + json_obj[i].sprint_id, json_obj[i].liste_id, json_obj[i].liste_nom, json_obj[i].liste_description);
                            }

                            if(json_obj[i].tache_nom != null){
                                creer_tache( json_obj[i].liste_id,  json_obj[i].tache_id,  json_obj[i].tache_nom,  json_obj[i].tache_description);
                            }
                            prev_liste = json_obj[i].liste_id
                            prev_sprint = json_obj[i].sprint_id;

                        }


                        if(! jQuery.isEmptyObject(json_obj) ){
                             $( "#tabs" ).tabs({ active: 0 })
                        }
             },
                        error(xhr,status,error){
                            alert("error 1 " + status);
                            alert("error 2 " + error);
                        }

                });


            });

            $("body").delegate('#btn_projet_modifier','click', function() {

                var p_id = $(this).attr("projet_id");
                g_selected_projet_id = p_id;

                var titre = $(this).attr("projet_nom");
                //$("#titre_projet").html(titre);
                //$("#projet_wrapper").hide();
                //$("#center-wrapper").show();

                $("body").data("modif_projet_no", p_id);
                $url = "projets/" + p_id + "/edit";

                $.ajax({ statusCode: {
                    500: function(xhr) {
                    alert(500);
                    }},
                    //the route pointing to the post function
                    url: $url,
                    type: 'GET',
                    dataType: 'text',

                success: function (result,status,xhr) {
                    var le_projet = JSON.parse(result);
                    //alert("resultat : "+ result);
                    $('#modifier_nom_projet').val(le_projet.projet_nom);
                    $('#modifier_description_projet').val(le_projet.projet_description);
                    $('#modifier_date_du_projet').val(le_projet.projet_date_du);
                    if(le_projet.projet_date_complete != null)
                      $('#modifier_date_complete_projet').val(le_projet.projet_date_complete);
                },error(xhr,status,error){
                    alert("error 1 " + status);
                    alert("error 2 " + error);
                },

                    complete: function (xhr,status) {
                    // Handle the complete event
                    //alert("complete " + status);
                    }
                });

                $.blockUI({
                     message: $('#projet_modifier_form'),
                     css: { top:'20%'}
                });


            }); // btn_projet_modifier click

            $("body").delegate('#btn_projet_formmodifier','click',function(){

                $projet_no = $("body").data("modif_projet_no");

                var input_name = "modifier_nom_projet";
                var nom_projet = $("#form_modifier_projet :input[name='"+input_name+"']").val();
                var input_name = "modifier_description_projet";
                var description_liste = $("#form_modifier_projet :input[name='"+input_name+"']").val();
                var input_name = "modifier_date_du_projet";
                var description_liste = $("#form_modifier_projet :input[name='"+input_name+"']").val();
                var input_name = "modifier_date_complete_projet";
                var description_liste = $("#form_modifier_projet :input[name='"+input_name+"']").val();


                /*var lblMessageListeModifier = "liste_message_modifier"; // Permet d'afficher un message d'erreur dans le formulaire.

                var ExpNom = /^[0-9a-zA-Z\s\.àÀâÂîÎïÏéÉèÈêÊëËôÔöÖÙùÛûÜüŸÿç  Ç_]{2,40}$/;
                var ExpDesc = /^[0-9a-zA-Z\s\r\n\.àÀâÂîÎïÏéÉèÈêÊëËôÔöÖÙùÛûÜüŸÿçÇ_]{2,150}$/;
                var ExpDesc = /^[^;]{2,150}$/;

                var res_test_nom = nom_liste.match(ExpNom);
                var res_test_desc = description_liste.match(ExpDesc);

                if( nom_projet.replace(/\s/g, '') == "" ||
                    description_projet.replace(/\s/g, '') == "" || !res_test_nom || !res_test_desc){

                    $('#'+lblMessageListeModifier).html("<tr><td width=\"20%\" style=\"vertical-align : middle; font-size: 35px;text-align:center;\"><span>&#9888</span></td><td width=\"80%\" style=\"vertical-align : middle;\">" +
                    "<span><strong>Nom de la liste :</strong><br/>(2 à 40 caractères maximum acceptant les caratères : 0 à 9, a à z, A à Z, espace, point, àÀâÂîÎïÏéÉèÈêÊëËôÔöÖÙùÛûÜüŸÿçÇ_)<br/><strong>Description de la liste :</strong><br/>(2 à 150 caractères).</span></td></tr>");
                    $('#'+lblMessageListeModifier).show();
                    message: $('#liste_modifier_form')
                    return;
                }*/

                $url = "projets/" + $projet_no;

                $.ajax({ statusCode: {
                    500: function(xhr) {
                    alert(500);
                    }},
                    //the route pointing to the post function
                    url: $url,
                    type: 'PUT',
                    // send the csrf-token and the input to the controller
                    data: $('#form_modifier_projet').serialize(),
                    dataType: 'text',
                    // remind that 'data' is the response of the AjaxController
                    success: function (result,status,xhr) {
                      // on retourne au home pour voir les projets... en attendant todo a continuer
                      url = "http://localhost:8000";
                      $( location ).attr("href", url);

                    },error(xhr,status,error){
                        alert("error 1 " + status);
                        alert("error 2 " + error);
                    },
                    complete: function (xhr,status) {
                    // Handle the complete event
                    //alert("complete " + status);
                    }
                });
                $.unblockUI();

            });//#btn_projet_formmodifier click

            // Fin modifier une tâche
            // Modifier un projet
            $("body").delegate('#btn_projet_formmodifier_annuler','click',function(){
                //alert("annuler");
                $.unblockUI();
                //$("#center-wrapper").show();
                return false;
            });  // $("body").delegate('#btn_projet_formmodifier_annuler','click',function


//Permet d'afficher des tooltips de types Bootstrap
            $("[rel=tooltip]").tooltip({ placement: 'top'});


                //$('#getTaches').on('click',function(){
            /*        $.get("{{URL::to('/getTaches')}}", function(data){
                        $('#getTachesData').append(data);
                        afficherTache(data);
                    });
            */
               // })



                $('#btn_tache_fermer').click(function() {
                    $("body").removeData("ajout_liste_no");
                    //permet d'effacer les valeurs du form et recommencer à neuf
                    $('#form_tache')[0].reset();
                    $.unblockUI();
                    return false;
                }); //$('#btn_tache_fermer').click(function()

                 $('#btn_liste_fermer').click(function() {
                    //permet d'effacer les valeurs du form et recommencer à neuf
                    $('#form_liste')[0].reset();

                    $.unblockUI();
                    return false;
                }); //$('#btn_liste_fermer').click(function()
                 $('#btn_sprint_fermer').click(function() {
                    //permet d'effacer les valeurs du form et recommencer à neuf
                    $('#form_sprint')[0].reset();
                    $.unblockUI();
                    return false;
                }); //$('#btn_liste_fermer').click(function()
                 $('#btn_projet_fermer').click(function() {
                    //permet d'effacer les valeurs du form et recommencer à neuf
                    $('#form_projet')[0].reset();
                    $.unblockUI();
                    return false;
                }); //$('#btn_projet_fermer').click(function()


               $('#btn_tache_ajouter').click(function() {

                    var liste_no = $("body").data("ajout_liste_no");
                    var nom_tache = $("#nom_tache").val();

                    var sprint_id_name = $("#tabs .ui-state-active").attr("aria-controls");
      //  alert(sprint_id_name);
                    var sprint_id = sprint_id_name.replace("sprint_", "");

                    if(nom_tache == ""){
                        nom_tache = "Non Défini";

                    }

                    var data =  $('#form_tache').serialize() + "&projet_id=" + g_selected_projet_id+ "&sprint_id=" + sprint_id + "&liste_id=" + liste_no;

                    $.ajax({ statusCode: {
                        500: function(xhr) {
                         alert(500);
                        }},
                        //the route pointing to the post function
                        url: "{{ URL::to('taches') }}",
                        type: 'POST',
                        // send the csrf-token and the input to the controller
                        data: data,
                        dataType: 'text',
                        // remind that 'data' is the response of the AjaxController
                    success: function (result,status,xhr) {

                            var liste_no = $("body").data("ajout_liste_no");

                             creer_tache(liste_no, JSON.parse(result).last_inserted_id, JSON.parse(result).nom , JSON.parse(result).description);
                            /*
                            $("#ul_liste_" + liste_no).append( '<li id="li_tache_' + JSON.parse(result).last_inserted_id + '" class="sortable-item"><a href="#" class="x-remove"><span class="glyphicon glyphicon-remove pull-right"></span></a><span id="tache_titre_'+ JSON.parse(result).last_inserted_id + '">' + JSON.parse(result).nom + '</span></li>' );
                            */

                    },error(xhr,status,error){
                        alert("error 1 " + status);
                        alert("error 2 " + error);
                    },
                        complete: function (xhr,status) {
                            // Handle the complete event
                         //alert("complete " + status);
                        }
                    });


                 }); // $('#btn_tache_ajouter').click(function()

                $('#btn_liste_ajouter').click(function() {



                    var sprint_id_name = $('#tabs .ui-state-active').attr('aria-controls');

                    var sprint_id = sprint_id_name.replace("sprint_", "");
                   // alert(sprint_id);

                    var data =  $('#form_liste').serialize() + "&projet_id=" + g_selected_projet_id+ "&sprint_id=" + sprint_id

                    $.ajax({ statusCode: {
                        500: function(xhr) {
                         alert(500);
                        }},
                        //the route pointing to the post function
                        url: "{{ URL::to('listes') }}",
                        type: 'POST',
                        // send the csrf-token and the input to the controller
                        data: data,
                        dataType: 'text',
                        // remind that 'data' is the response of the AjaxController
                    success: function (result,status,xhr) {

                            var id = JSON.parse(result).last_inserted_id;
                            var nom = JSON.parse(result).nom;
                            var description = JSON.parse(result).description;
                            creer_liste(sprint_id_name, id, nom, description);

                    },
                    error(xhr,status,error){
                        alert("error 1 " + status);
                        alert("error 2 " + error);
                    },
                    complete: function (xhr,status) {
                            // Handle the complete event

                         //alert("complete " + status);

                    }
                    });

                }); // $('#btn_liste_ajouter').click(function()

                $('#btn_projet_ajouter').click(function() {

                    var nom_projet = $("#nom_projet").val();
                    var description_projet = $("#description_projet").val();
                    var data =  $('#form_projet').serialize();

                    $.ajax({ statusCode: {
                        500: function(xhr) {
                         alert(500);
                        }},
                        //the route pointing to the post function
                        url: "{{ URL::to('projets') }}",
                        type: 'POST',
                        // send the csrf-token and the input to the controller
                        data: data,
                        dataType: 'text',
                        // remind that 'data' is the response of the AjaxController
                    success: function (result,status,xhr) {

                            // on retourne au home pour voir les projets... en attendant todo a continuer
                            url = "http://localhost:8000";
                            $( location ).attr("href", url);
                    },
                    error(xhr,status,error){
                        alert("error 1 " + status);
                        alert("error 2 " + error);
                    },
                    complete: function (xhr,status) {
                            // Handle the complete event

                        //alert("complete " + status);

                    }
                    });

                }); // $('#btn_projet_ajouter').click(function()



                $("body").delegate('a.btn','click', function() {
                    var list_id_from_a =  $(this).attr('id');


                    //on s'assure que le <a> cliquer est un bouton pour ajouter une tache sinon exit
                    //il faut que le id de <a> commence par btn_ajouter_tache_Liste + _ + le id de la liste dans la bd
                    if(!(typeof list_id_from_a != 'undefined' && list_id_from_a.indexOf("btn_ajouter_tache_Liste") >= 0)){

                        return;
                    }

                    var liste_no = list_id_from_a.replace("btn_ajouter_tache_Liste_", "");
                    $("body").data("ajout_liste_no", liste_no);
                    ajouter_tache(liste_no );
                    //permet d'effacer les valeurs du form et recommencer à neuf
                    $('#form_tache')[0].reset();


                }); //$("body").delegate('a.btn','click', function()

  
    $("body").delegate('a.c-comment','click',function(e) {
        /**********************************************************
            C'est le click de l'icone commentaire sur les taches...
            on load les commentaires associés a une taches
        ***********************************************************/
        e.preventDefault();
        
        var tache_nom = $(this).parent().attr("id");
        var tache_id = tache_nom.replace("li_tache_", "");
        var obj_commentaire;
        var json_commentaires = get_commentaire(g_selected_projet_id, tache_id);



        if(json_commentaires != ""){

            obj_commentaire = JSON.parse(json_commentaires);    
        }
        
        afficher_commentaire(obj_commentaire, g_selected_projet_id, tache_id);


        $.blockUI({
            theme:false,
            message: $('#tache_commentaire'),
            css: { textAlign:'left',overflow:'auto',top:'10%',left:'10%',width:'80%',height:'90%', cursor: 'default' }
        });
        


    }); //$("body").delegate('a.c-comment','click',function()
    $("body").delegate('a.i-info','click',function(e) {

        e.preventDefault();

        var t_no = $(this).parent().attr("id").replace("li_tache_", "");
        var url = "/taches/" + t_no;
        $.ajax({ statusCode: {
                500: function(xhr) {
                 alert(500);
                }},
                //the route pointing to the post function
                url: url,
                type: 'get',
                dataType: 'text',
                // remind that 'data' is the response of the AjaxController
            success: function (result,status,xhr) {
                var res =  JSON.parse(result)[0];
                var t = "<span class='glyphicon glyphicon-remove pull-right' style='color:#BBB;'></span>";
                t += "<table class='table table_info'>";
                t += "<caption>" + res.tache_nom + "</caption>";
                t += "<tr><td>Creer par</td><td>" + res.creer_par + "</td></tr>";
                t += "<tr><td>Telephone</td><td>" + res.telephone + "</td></tr>";
                t += "<tr><td>Courriel</td><td>" + res.courriel + "</td></tr>";
                t += "<tr><td>Date Création</td><td>" + res.tache_creer_date + "</td></tr>";
                t += "<tr><td>Date Modification</td><td>" + res.tache_maj_date + "</td></tr>";
                t += "<tr><td>Description</td><td>" +  res.tache_description + "</td></tr>";
                t += "</table>";


                $('#tache_info').html(t);
                

                 // alert( result);
            },
            error(xhr,status,error){
                alert("error 1 " + status);
                alert("error 2 " + error);
            },
            complete: function (xhr,status) {
                    // Handle the complete event

                //alert("complete " + status);

            }
        });

      
       $.blockUI({

            message: $('#tache_info'),
            css: { top:'20%',  cursor: 'default'},
            textAlign: 'left'
        });

    }); //$("body").delegate('a.i-info','click',function()



    $(".btn_fermer_ui").click(function(){
        $.unblockUI();
    });


                //utilisation de delegate au lieu de juste click car la fonctionalité est
                //ajouté dynamiquement... sinon, ca ne marche pas
                $("body").delegate('a.x-remove','click',function() {

                    if(confirm("Voulez-vous supprimer la tâche?")){
                            var id = $(this).parent().attr("id");



                            $('#' + id).detach();

                            var sprint_id_name = $("#tabs .ui-state-active").attr("aria-controls");
                            var sprint_id = sprint_id_name.replace("sprint_", "");

                            var id_no = id.replace("li_tache_","");
                            var json_liste_tache = get_all_liste_tache();
                          //  var url = "sprintactivite/rendreInactif/" + g_selected_projet_id+ "/"+ sprint_id + "/" + json_liste_tache;

                            var url = "sprintactivite/rendreInactif";
                            $.ajax({ statusCode: {
                            500: function(xhr) {
                             alert(500);
                            }},
                            //the route pointing to the post function
                            url: url,
                            data:{"projet_id" : g_selected_projet_id, "sprint_id" : sprint_id, "json" : json_liste_tache },
                            type: 'PUT',

                        success: function (result,status,xhr) {

                              $('#' + id).remove();

                        },error(xhr,status,error){
                            alert("error 1 " + status);
                            alert("error 2 " + error);
                        },
                            complete: function (xhr,status) {
                                // Handle the complete event
                             //alert("complete " + status);
                            }
                        });
                    }

                }); // $("body").delegate('a.x-remove','click',function()




                $(document).on("click", "#creer_item_liste", function() {
                    //permet d'effacer les valeurs du form et recommencer à neuf
                    $('#form_liste')[0].reset();
                    $.blockUI({
                        message: $('.div_liste_form'),
                        css: { top:'20%'}
                    });


                }); // $(document).on("click", "#creer_item_liste", function()




                $(document).on("click", "#creer_item_sprint", function() {
                    //permet d'effacer les valeurs du form et recommencer à neuf
                    $('#form_sprint')[0].reset();
                    $.blockUI({
                        message: $('.div_sprint_form'),
                        css: { top:'20%'}
                    });

                }); //$(document).on("click", "#creer_item_sprint", function() {

                  $(document).on("click", "#creer_item_projet", function() {
                      //permet d'effacer les valeurs du form et recommencer à neuf
                      $('#form_projet')[0].reset();
                      $.blockUI({
                            message: $('.div_projet_form'),
                            css: { top:'20%'}
                      });

                  }); // $(document).on("click", "#creer_item_projet", function()

           //     $( function() {
                var noSprint = $( "#no_sprint" ),
                  tabContent = $( "#tab_content" ),
                  //tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
                  tabTemplate = "<li><a href='#{href}'>#{label}</a></li>",
                  tabCounter = 2;

                var tabs = $( "#tabs" ).tabs();

                

                //AddTab form: calls addTab function on submit and closes the dialog
                $('#btn_sprint_ajouter').click(function() {

                    $.ajax({

                            url: "{{ URL::to('sprints') }}",
                            type: 'POST',
                            data: $('#form_sprint').serialize() + "&projet_id=" + g_selected_projet_id,
                            dataType: 'text',

                        success: function (result,status,xhr) {

                                var id = JSON.parse(result).last_inserted_id;
                                var numero = JSON.parse(result).numero;

                                // creer_sprint($id, $numero);
                                sprint_add_tab(id, numero);

                        },
                        error(xhr,status,error){
                            alert("error 1 " + status);
                            alert("error 2 " + error);
                        }

                    });
                }); // $('#btn_sprint_ajouter').click(function()

                // Actual addTab function: adds new tab using the input from the form above
                function sprint_add_tab(id, numero) {
                  //var label = noSprint.val() || "Sprint " + tabCounter,
                  var label = "Sprint " + numero;
                 //   id = "tabs-" + tabCounter,
                  //  id = "tabs-" + id,
                    li = $( tabTemplate.replace( /#\{href\}/g, "#sprint_" + id ).replace( /#\{label\}/g, label ) ),
                  //  tabContentHtml = tabContent.val();

                  tabs.find( ".ui-tabs-nav" ).append( li );
                  //tabs.append( "<div id='" + id + "'><p></p></div>" );
                  tabs.append( "<div id='sprint_" + id + "'><p id=\"sprint_message\"></p><p></p></div>" );
                  tabs.tabs( "refresh" );
                  tabs.tabs({ active: 0 });
                 // tabCounter++;
                }


                // $( "#creer_item_sprint" ).button().on( "click", function() {
                //     dialog.dialog( "open" );
                //   });

                // Close icon: removing the tab on click
                tabs.on( "click", "span.ui-icon-close", function() {
                  var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
                  $( "#" + panelId ).remove();
                  tabs.tabs( "refresh" );
                });

                tabs.on( "keyup", function( event ) {
                  if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
                    var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
                    $( "#" + panelId ).remove();
                    tabs.tabs( "refresh" );
                  }
                });





            //   });
}); //$(document).ready(function()





        </script>
</header>
    <body>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
