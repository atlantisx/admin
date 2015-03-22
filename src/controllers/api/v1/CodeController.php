<?php

namespace Atlantis\Admin\Api\V1;

use Atlantis\Core\Controller\BaseController;


class CodeController extends BaseController{

    public function index(){
        $get = \Input::all();

        if( isset($get['iDisplayLength']) ){
            /** Pagination setup */
            $current_page = ($get['iDisplayStart'] / $get['iDisplayLength']) + 1;
            $get['iTotalRecords'] = \Code::count();
            \Code::resolveConnection()->getPaginator()->setCurrentPage($current_page);

            /** Filtering result */
            if( isset($get['search']) ){
                $codes = \Code::roots()->filtering($get['search']);
            }else{
                $codes = \Code::roots();
            }

            /** Filtered record count */
            $get['iTotalDisplayRecords'] = $codes->count();

            /** Fetching result */
            $codes = $codes->paginate($get['iDisplayLength']);

            /** Collecting result */
            $get['aaData'] = $codes->toArray()['data'];

        }else{
            $get = \Code::roots()->get();
        }

        return $get;
    }


    public function show($category){
        try{
            $get = \Input::all();

            if( !empty($get['parent']) ){
                #i: Get parent
                $node = \Code::where('name',$get['parent'])->first();

                #i: Get all the children by category
                $codes = $node->children()->where('category',$category);

            }else{
                $codes = \Code::category($category);
            }

            if(isset($get['format'])){
                return array_map(create_function('$o', 'return $o["value"];'), $codes->get()->toArray());
            }

            return $codes->get();

        }catch (Exception $e){
            #i: Return error if none
            return \Response::json(array($e->getMessage()),400);
        }
    }


    public function update($id){
        $put = \Input::all();

        try{
            $code = \Code::find($id);

            if($code){
                $code->fill($put);
                $code->save();
            }

            //[i] Reponse
            $put['_status'] = array(
                'type' => 'success',
                'message' => 'Successfully update the code!'
            );

        }catch (Exception $e){
            $put['_status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        return \Response::json($put);
    }


    public function destroy($id){
        $delete = \Input::all();

        //[i] Search user
        $code = \Code::find($id);

        if($code){
            $code->delete();
            $delete['_status'] = array(
                'type' => 'success',
                'message' => 'Successfully delete the code!'
            );

        }else{
            $delete['_status'] = array(
                'type' => 'error',
                'message' => 'Error deleteing code!'
            );
        }

        return \Response::json($delete);
    }

}