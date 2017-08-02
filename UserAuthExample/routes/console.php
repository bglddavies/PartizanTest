<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('generateDocumentTransfers', function(){
    $this->comment('Generating Document Transfers');

    $adts = \App\Models\AutomaticDocumentTransfer::all();


    //LOOP THROUGH AUTOMATIC DOC TRANSFERS -
    //GRAB THE TT -
    //GRAB THE TTE'S -
    //GRAB THE OPEN MODIFIER -
    //GRAB THE OPEN ID -

    //CHECK IF DT BY SEED ID
    //IF NOT CREATE NEW ONE
    //CREATE DTE's TO GO WITH IT

    $now = new \DateTime();

    foreach($adts as $adt)
    {
        $dtt = \App\Models\DocumentTransferTemplate::where('id', $adt->transfer_template_id)->first();
        $dtes = \App\Models\DocumentTransferTemplateElement::where('document_transfer_template_id', $dtt->id)->orderBy('order_inc', 'ASC')->get();

        //Got Date applicable to
        //Got type (org, user, both) they are applicable to
        //This should be enough to grab id's here.

        //1 get whether client or org
        //grab client transfers left joined to doc transfer and check seed id + open to make sure they don't have one open
        //check the related datetime to see if it is applicable
        //create the transfer

        $idpool = array();

        if(($dtt->applies_to == 'orgs')||($dtt->applies_to == 'both'))
        {
            $idpool['orgs'] = array();

            //grab all the organisations
            //Grab all their transfers
            //make sure there isn't one for this seed where the end date is after now


            $orgs_pool = \App\Models\Organisation::getOrganisationsForAuto($dtt->id);


        }
    }
})->describe('Generates document transfers using existing document transfer templates');
