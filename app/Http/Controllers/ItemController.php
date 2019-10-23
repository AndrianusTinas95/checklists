<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Helpers\Carbon;
use App\Item;
use App\Resources\Item\ChecklistItemResource;
use App\Resources\Item\ChecklistItemStoreResource;
use App\Resources\Item\CompleteItemChecklistResource;
use App\Resources\Item\ItemChecklistResource;
use App\Resources\Item\ItemCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
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
     * Complete item(s)
     */
    public function complete(Request $request){
        /**
         * input
         */
        $ids = $this->inputComplate($request);

        /**
         * complate item
         */
        $items = Item::whereIn('id',$ids)->update([
            'is_completed'=>true,
            'completed_at'=>Carbon::now()
        ]);

        /**
         * get items
         */
        $items = Item::with('template.checklist')
                    ->whereIn('id',$ids)
                    ->where('is_completed',true)
                    ->get();
        
        /**
         * data tranform to array 
         */
        $data['data'] = CompleteItemChecklistResource::collection($items);

        /**
         * response
         */
        return $this->resp(null,$data,200);
        
    }

    /**
     * Incomplete item(s)
     */
    public function incomplete(Request $request){
        /**
         * input
         */
        $ids = $this->inputComplate($request);

        /**
         * incomplate item
         */
        $items = Item::whereIn('id',$ids)->update([
            'is_completed'=>false,
            'completed_at'=>Carbon::now()
        ]);
        
        /**
         * get items
         */
        $items = Item::with('template.checklist')
                    ->whereIn('id',$ids)
                    ->where('is_completed',false)
                    ->get();
        
        /**
         * data tranform to array 
         */
        $data['data'] = CompleteItemChecklistResource::collection($items);

        /**
         * response
         */
        return $this->resp(null,$data,200);
    }


    /**
     * Get all items by given {checklistId}
     */
    public function getItems($checklistId){
        /**
         * get checklist with item data
         */
        $checklist = Checklist::with('template.items')->findOrFail($checklistId);
        if(!$checklist) return $this->resp('error','Not Found',400);
       
        /**
         * data transform 
         */
        $data['data'] = new ItemChecklistResource($checklist);

        /**
         * success response
         */
        return $this->resp(null,$data,200);

    }

    /**
     * Create item by given checklistId
     */
    public function storeItems(Request $request,$checklistId){
        /**
         * validation
         */
        $this->formValidate($request);

        /**
         * get checklist  
         */
        $checklist = Checklist::find($checklistId);
        if(!$checklist) return $this->resp('error','Not Found',400);

        /**
         * save data item 
         */
        $request['created_by'] = $this->user->id;
        $item = $checklist->template->items()->save(new Item($request->all()));

         /**
         * data transform 
         */
        $data['data'] = new ChecklistItemStoreResource($item);

        /**
         * success response
         */
        return $this->resp(null,$data,201);

    }

    /**
     * Get checklist item by given {checklistId} and {itemId}
     */
    public function getChecklistItem($checklistId,$itemId){
        /**
         * get checklist  
         */
        $checklist = Checklist::find($checklistId);
        if(!$checklist) return $this->resp('error','Not Found',400);

         /**
         * get item  for validate
         */
        $item = $checklist->template->items()->find($itemId);
        if(!$item) return $this->resp('error','Not Found',400);

        /**
         * data resource  transform 
         */

        $data['data'] =new  ChecklistItemResource($item);

        /**
         * response
         */
        return $this->resp(null,$data,200);
    }

    /**
     * Edit Checklist Item on given {checklistId} and {itemId}
     */
    public function updateChecklistItem(Request $request,$checklistId,$itemId){
         /**
         * validation
         */
        $this->formValidate($request);

        /**
         * get checklist  
         */
        $checklist = Checklist::find($checklistId);
        if(!$checklist) return $this->resp('error','Not Found',400);

        /**
         * get item  for validate
         */
        $item = $checklist->template->items()->find($itemId);
        if(!$item) return $this->resp('error','Not Found',400);

        /**
         * update item 
         */
        $request['updated_by'] = $this->user->id;
        $item->update($request->all());

        /**
         * data resource  transform 
         */
        $data['data'] = new  ChecklistItemResource($item);

        /**
         * response
         */
        return $this->resp(null,$data,200);

    }

    /**
     * Delete checklist item by given {checklistId} and {itemId}
     */
    public function destroyChecklistItem($checklistId,$itemId){
        /**
         * get checklist  
         */
        $checklist = Checklist::find($checklistId);
        if(!$checklist) return $this->resp('error','Not Found',400);

         /**
         * get item  for validate
         */
        $item = $checklist->template->items()->find($itemId);
        if(!$item) return $this->resp('error','Not Found',400);

        /**
         * delete item
         */
        $item->delete();

        /**
         * data response
         */

        $data =['message'=>'success'];

        /**
         * response
         */
        return $this->resp(null,$data,204);
    }

    /**
     * update bulk
     */
    public function bulk(){

    }

    /**
     * Count summary of checklist’s item
     */
    public function summaries(){

    }

    /**
     * This endpoint will get all available items.
     */
    public function itemsList(Request $request){
        /**
         * queries
         */
        $queries = new Item();

        /**
         * filter
         */
        if($request->filter){
            $field      = array_keys($request->filter)[0];
            $operator   = array_keys($request->filter[$field])[0];
            if($operator=='between'){
                $values     = $request->filter[$field][$operator];
                $values     = explode(',',$values);

                $from   = $values[0] < $values[1] ? $values[0] : $values[1];
                $to     = $values[0] > $values[1] ? $values[0] : $values[1];

                $from = Carbon::chConvertTz($from,$request->tz ?? env('APP_TIMEZONE'));
                $to = Carbon::chConvertTz($to,$request->tz ?? env('APP_TIMEZONE'));
                
                $queries = $queries->where($field,'>=',$from)->where($field,'<=',$to);
            }else{
                $queries = $queries->where($request->filter);
            }
        }

        $field_name=null;
        if($request->sort){
            $firstCharacter = substr($request->sort, 0, 1);
            if($firstCharacter=='-'){
                $field_name = ltrim($request->sort,'-');
                $field_sort = 'DESC';
            }

        }

        /**
         * get items limit 
         */
        $items = $queries->orderBy($field_name ? : ( $request->sort ?? 'id'), $field_sort ?? 'ASC')
                ->paginate(
                    $request->page_limit ?? 10,
                    ['*'],
                    'page_offset', 
                    $request->page_offset ?? 1
                );

        /**
         * collection items
         */
        $data=new ItemCollection($items);
        
        /**
         * response
         */
        return $this->resp(null,$data,200);
    }

    /**
     * form validate
     */
    public function formValidate(Request $request){
        $this->validate($request,[
            'description'   => 'required|string|max:255',
            'due'           => 'nullable|date_format:Y-m-d H:i:s',
            'urgency'       => 'nullable|numeric',
            'assignee_id'   => 'required|exists:templates,id'
        ]);

        return ;
    }

    /**
     * input complete or incomplete
     * 
     * response id of items
     */
    public function inputComplate(Request $request){
        /**
         * validate
         */
        $this->validate($request,[
            'data'              => 'nullable|array',
            'data.*.item_id'    => 'nullable|exists:items,id'
        ]);

        /**
         * get item id 
         */
        $ids = $request->input('data');
        return collect($ids)->flatten()->toArray();
    }
}
