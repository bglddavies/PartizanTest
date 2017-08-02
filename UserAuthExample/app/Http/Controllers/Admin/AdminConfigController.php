<?php
/**
 * Created by PhpStorm.
 * User: baglad
 * Date: 24/01/2017
 * Time: 14:09
 */

namespace app\Http\Controllers\Admin;

use App\Models\RelevantDatetime;
use App\Models\OrganisationType;
use App\Models\DocumentCategory;


class AdminConfigController extends \App\Http\Controllers\Controller
{
    public function getRelevantDateTimes()
    {
        $all = RelevantDatetime::getAllRelevantDateTimes();

        $ret = array();
        foreach($all as $dt)
        {
            $temp = array();
            $temp['id'] = $dt->id;
            $temp['title']=$dt->title;
            $temp['applicable_to']=$dt->applicable_to;
            $ret[] = $temp;
        }
        return \Response::json($ret, 200);
    }

    public function updateRDT($id)
    {
        $rdt = RelevantDatetime::where('id', $id)->first();
        if($rdt)
        {
            $input = \Input::all();

            $validator = \Validator::make(
                $input,
                [
                    'title'=>'required',
                    'applicable_to'=>['required','in:companies,users,both']
                ]
            );

            if(!$validator->fails())
            {
                $rdt->title = $input['title'];
                $rdt->applicable_to = $input['applicable_to'];

                $rdt->save();

                return \Response::json('success',200);
            }
            $errors = $validator->errors();
            return \Response::json('An error occured updating the Relevant DateTime',404);
        }
        return \Response::json('Could not locate Relevant DateTime of this id.',404);
    }

    public function addRDT(){
        $input = \Input::all();
        $validator = \Validator::make(
            $input,
            [
                'title'=>['required'],
                'applicable_to'=>['required','in:companies,users,both']
            ]
        );

        if(!$validator->fails())
        {
            $rdt = new RelevantDatetime();
            $rdt->title = $input['title'];
            $rdt->applicable_to = $input['applicable_to'];

            $rdt->save();

            return \Redirect::to('/admin/config')->with('success', ['success'=>'Successfully added Relevant DateTime']);
        }

        return \Redirect::to('/admin/config')->with('errors_custom', ['FAILURE'=>'Could not add Relevant DateTime']);
    }

    public function deleteRDT($id){
        $rdt = RelevantDatetime::where('id', $id)->first();
        if($rdt)
        {
            RelevantDatetime::flushRDT($id);
            $rdt->delete();
            return \Response::json('success',200);
        }

        return \Response::json('Could not locate RDT', 404);
    }

    public function getCompanyTypes(){
        $types = OrganisationType::getAllTypes();
        $ret = array();
        foreach($types as $type)
        {
            $temp = array();
            $temp['id']=$type->id;
            $temp['title']=$type->type;

            $ret[] = $temp;
        }
        return \Response::json($ret,200);
    }

    public function updateCompanyType($id){
        $type = OrganisationType::where('id', $id)->first();
        if($type)
        {
            $input = \Input::all();

            $validator=\Validator::make(
                $input,
                [
                    'title'=>'required'
                ]
            );
            if(!$validator->fails())
            {
                $type->type = $input['title'];
                $type->save();

                return \Response::json('success',200);
            }
            return \Response::json("Could not validate input",404);
        }
        return \Response::json('Could not locate company type',404);
    }

    public function deleteCompanyType($id)
    {
        $type = OrganisationType::where('id', $id)->first();

        if($type)
        {
            $type->delete();

            return \Response::json('success',200);
        }

        return \Response::json('Could not locate company type.',404);
    }

    public function addCompanyType()
    {
        $input = \Input::all();
        $validator = \Validator::make(
            $input,
            [
                'title'=>['required']
            ]
        );

        if(!$validator->fails())
        {
            $ct = new OrganisationType();
            $ct->type = $input['title'];
            $ct->save();

            return \Redirect::to('/admin/config')->with('success', ['Success'=>'Successfully added company type']);
        }
        return \Redirect::to('/admin/config')->with('errors_custom', ['Failure'=>'Could not validate input']);
    }

    public function getDocumentCategories()
    {
        $dc = DocumentCategory::getAllDocumentCategories();
        return \Response::json($dc, 200);
    }

    public function addDocumentCategory(){
        $input = \Input::all();

        $validator = \Validator::make(
            $input,
            [
                'name'=>['required', 'regex:/^[\pL\s\d]+$/'],
                'dir'=>['required', 'alpha_dash', 'unique:document_category']
            ]
        );

        if(!$validator->fails())
        {
            $dc = new DocumentCategory();
            $dc->name = $input['name'];
            $dc->dir = $input['dir'];
            $dc->save();

            return \Redirect::to('/admin/config')->with('success',['Success'=>'Successfully added document type']);
        }

        $errors = $validator->errors();
        \Log::info(var_export($errors,true));
        return \Redirect::to('/admin/config')->with('errors_custom',['FAILURE'=>'Please make sure the category name contains only numbers, letters and spaces. Please make sure the directory is UNIQUE and contains only numbers, letters, dashes and underscores.']);
    }

    public function updateDocumentCategory($id)
    {
        $dc = DocumentCategory::where('id', $id)->first();
        if ($dc) {
            $input = \Input::all();

            $validator = \Validator::make(
                $input,
                [
                    'dir' => ['required', 'alpha_dash'],
                    'name' => ['required', 'regex:/^[\pL\s\d]+$/']
                ]
            );

            if (!$validator->fails()) {
                $dc->dir = $input['dir'];
                $dc->name = $input['name'];
                $dc->save();
                return \Response::json('success', 200);
            }
            $errors = $validator->errors();

            return \Response::json($errors, 404);
        }
        return \Response::json('Could not locate document category', 404);
    }

    public function deleteDocumentCategory($id)
    {
        $dc = DocumentCategory::where('id', $id)->first();

        if($dc)
        {
            $dc->delete();
            return \Response::json('success',200);
        }
        return \Response::json('Could not locate document category',404);
    }
}