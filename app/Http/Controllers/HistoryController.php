<?php

namespace App\Http\Controllers;

use App\History;
use App\Resources\History\HistoryCollection;
use App\Resources\History\HistoryResource;
use Exception;

class HistoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * get list of hisory
     */
    public function list(){
        /**
         * get histories limit 
         */
        $histories = History::paginate(10);

        /**
         * collection data
         */
        $data = new HistoryCollection($histories);

        /**
         * response
         */
        return $this->resp(null,$data,200);

    }

    /**
     * get history by id
     */
    public function show($id){
        try {
            /**
             * find history by id
             */
            $history = History::find($id);
            if(!$history) return $this->resp('error','Not Found',404);

            /**
             * data resource 
             */
            $data['data'] = new HistoryResource($history);
            $status = 200;

        } catch (Exception $e) {
            $type   = 'error';
            $data   = $e->getMessage();
            $status = 500;
        }

        return $this->resp($type ?? null,$data,$status);
    }
}
