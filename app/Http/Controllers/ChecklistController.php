<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Item;
use App\Resources\Checklist\ChecklistAddResource;
use App\Resources\Checklist\ChecklistCollection;
use App\Resources\Checklist\ChecklistResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();
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
            $checklist = Checklist::find($id);
            if(!$checklist) return $this->resp('error','Not Found',404);
            
            /**
             * response single data
             */
            $data['data'] = new ChecklistResource($checklist);

            $status = 200;
        } catch (Exception $e) {
           /**
             * if any arror reponse error message
             */
            $data   = 'Server Error';
            $status = 500;
            $type   = 'error';
        }

        /**
         * response
         */
        return $this->resp($type ?? null,$data,$status);
    }

    /**
     * Update checklist by given checklistId
     */
    public function update(Request $request, $id){
        /**
         * validate 
         */
        $this->validate($request,[
            'object_domain'     => 'required|string|max:100',
            'object_id'         => 'required|exists:templates,id',
            'description'       => 'required|string|max:200',
            'is_completed'      => 'nullable|boolean',
            'completed_at'      => 'nullable|date_format:Y-m-d H:i:s',
            'created_at'        => 'nullable|date_format:Y-m-d H:i:s',
        ]);
        
        try {
            /**
             * get checklist by id
             */
            $checklist = Checklist::find($id);
            if(!$checklist) return $this->resp('error','Not Found',404);

            /**
             * update checklist
             */
            $request['updated_by']  = $this->user->id;
            $checklist->update($request->all());
            
            /**
             * data 
             */
            $data['data']   = new ChecklistResource($checklist);
            $status = 200;
        } catch (Exception $e) {
            $type   = 'error'; 
            $data   = $e->getMessage();
            $status = 500;
        }

        return $this->resp($type ?? null, $data, $status);
    }

    /**
     * Delete checklist by given checklistId
     */
    public function destroy($id){
        try {
            /**
             * get checklist by id
             */
            $checklist = Checklist::find($id);
            if(!$checklist) return $this->resp('error','Not Found',404);
            
            /**
             * delete checklist and item
             */
            $checklist->template->items()->delete();
            $checklist->delete();

            $data   = ['message'=>'checklist success deleted'];
            $status = 204;
        } catch (Exception $e) {
            /**
             * if any arror reponse error message
             */
            $data   = $e->getMessage();
            $status = 500;
            $type   = 'error';
        }

        /**
         * response
         */
        return $this->resp($type ?? null, $data,$status);
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
        $this->validate($request,[
            'object_domain'     => 'required|string|max:100',
            'object_id'         => 'required|exists:templates,id',
            'due'               => 'nullable|date_format:Y-m-d H:i:s',
            'urgency'           => 'required|numeric',
            'description'       => 'required|string|max:200',
            'items'             => 'nullable|array',
            'items.*'           => 'nullable|string|max:100',
            'task_id'           => 'required|exists:templates,id'
        ]);

        /**
         * save data to database 
         */
        $request['created_by']  = $this->user->id;
        $checklist = Checklist::create($request->except('items','task_id'));
        /**
         * save items
         */
        if($request->input('items')){
            foreach ($request->input('items') as $input) {
                $item['description'] = $input;
                $checklist->template->items()->save(new Item($item));
            }
        }

        /**
         * response single data
         */
        $data['data'] = new ChecklistAddResource($checklist);

        return $this->resp(null,$data,201);
    }

    /**
     * Get list of checklists.
     * 
     * Note: We can include all items in checklist with by passing include=items
     */
    public function list(Request $request){
        /**
         * validate 
         */
        if($error = $this->listValidate($request,'checklists','sort')) return $error;
        if($error = $this->listValidate($request,'checklists','fields')) return $error;

        if($request->fields){
            $fields= explode(',',$request->fields);
            array_push($fields,'id','object_id');
            array_unique($fields);
        }
        
        /**
         * get checklist 
         */
        $checklists = Checklist::where(function($q) use($request){
                        if($request->filter) $q->where('description',$request->filter);
                    })
                    ->orderBy($request->sort ?? 'id', 'ASC')
                    ->paginate(
                        $request->page_limit ?? 10,
                        $request->fields ? $fields : ['*'],
                        'page_offset', 
                        $request->page_offset ?? 1
                    );

        /**
         * response
         */
        return response(new ChecklistCollection($checklists),200);
    }
}
