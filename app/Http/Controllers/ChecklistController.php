<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Resources\Checklist\ChecklistCollection;
use App\Resources\Checklist\ChecklistResource;
use Exception;
use Laravel\Lumen\Http\Request;

class ChecklistController extends Controller
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
     * Get checklist by given checklistId. 
     * Note: We can include all items in checklist with by passing include=items
     */
    public function show(Request $request, $id){
        try {
            /**
             * get checklist by id
             */
            $checklist = Checklist::findOrFail($id);

            /**
             * response single data
             */
            $data['data'] = new ChecklistResource($checklist);

            $status = 200;
        } catch (Exception $e) {
           /**
             * if any arror reponse error message
             */
            $data   = $e->getMessage();
            $status = 404;
            $type   = 'error';
        }finally{
            /**
             * response
             */
            return $this->resp($type ?? null,$data,$status);
        }
    }

    /**
     * Update checklist by given checklistId
     */
    public function update(Request $request, $id){
        try {
            /**
             * validate 
             */

            /**
             * get checklist by id
             */
            $checklist = Checklist::findOrFail($id);

            /**
             * update checklist
             */

        } catch (\Throwable $th) {
            //throw $th;
        }finally{

        }
    }

    /**
     * Delete checklist by given checklistId
     */
    public function destroy($id){
        try {
            /**
             * get checklist by id
             */
            $checklist = Checklist::findOrFail($id);

            /**
             * delete checklist
             */
            $checklist->delete();
            $data   = ['message'=>'checklist success deleted'];
            $status = 204;
        } catch (Exception $e) {
            /**
             * if any arror reponse error message
             */
            $data   = $e->getMessage();
            $status = 404;
            $type   = 'error';
        }finally{
            /**
             * response
             */
            return $this->resp($type ?? null,$data,$status);
        }
    }

    /**
     * This creates a Checklist object.
     * 
     * Testing inline code.
     */
    public function store(Request $request){
        /**
         * validate
         */

        /**
         * save data to database 
         */

        /**
         * response data
         */
    }

    /**
     * Get list of checklists.
     * 
     * Note: We can include all items in checklist with by passing include=items
     */
    public function list(Request $request){
        /**
         * get checklist 
         */
        $checklists = Checklist::paginate($request->page_limit ?? 10);

        /**
         * response
         */
        return response(new ChecklistCollection($checklists),200);
    }
}
