<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Helpers\Carbon;
use App\Item;
use App\Resources\Item\ChecklistItemResource;
use App\Resources\Item\ChecklistItemStoreResource;
use App\Resources\Template\TemplateAssignCollection;
use App\Resources\Template\TemplateCollection;
use App\Resources\Template\TemplateResource;
use App\Template;
use App\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * List all checklists templates
     */
    public function list(Request $request){
        /**
         * validate 
         */
        if($error = $this->listValidate($request,'templates','sort')) return $error;
        if($error = $this->listValidate($request,'templates','fields')) return $error;
        
        if($request->fields){
            $fields= explode(',',$request->fields);
            array_push($fields,'id');
            array_unique($fields);
        }
        /**
         * get template
         */
        $paginator = Template::with('checklist','items')
                    ->where(function($q) use($request){
                        if($request->filter) $q->where('name',$request->filter);
                    })
                    ->orderBy($request->sort ?? 'id', 'ASC')
                    ->paginate(
                        $request->page_limit ?? 10,
                        $request->fields ? $fields : ['*'],
                        'page_offset', 
                        $request->page_offset ?? 1
                    );

        /**
         * response with collection
         */
        return response(new TemplateCollection($paginator));
    }

    /**
     * Create checklist template
     */
    public function store(Request $request){
        /**
         * validation 
         */
        $this->requestForm($request);

        /**
         * save data template
         */
        $template = Template::create($request->only('name'));
        
        /**
         * custom data checklist
         * and  save data checklist
         */
        $checklist = $this->checklistData($request);
        $template->checklist()->save(new Checklist($checklist));

        /**
         * save data items
         */
        foreach ($request->input('items') as $item) {
            $item = $this->itemData($item);
            $template->items()->save(new Item($item));
        }

        /**
         * response
         */
        $data = $this->addDataRes($template);

        return response()->json($data,201);

    }

    /**
     * Get checklist template by given templateId
     */
    public function show(Request $request, $id){
        try {
            /**
             * get checklist temlate in database
             */
            $template = Template::with('checklist','items')->findOrFail($id);

            /**
             * response single data
             */
            $resource = new TemplateResource($template);
            $data = $this->single('templates',$id,$resource,$request->fullUrl());

            $status = 200;

        } catch (Exception $e) {
            /**
             * if any arror reponse error message
             */
            $data   = ['message'=>$e->getMessage()];
            $status = 404;
        }finally{
            /**
             * response
             */
            return response()->json($data,$status);
        }

    }

    /**
     * Edit Checklist Template by given templateId
     */
    public function update(Request $request, $id){
        /**
         * validation 
         */
        $this->requestForm($request);

        try {
            /**
             * get data template
             */
            $template = Template::findOrFail($id);
            /**
             * update template
             */
            $template->update($request->only('name'));
            /**
             * update checklist
             */
            $checklist = $this->checklistData($request);
            $template->checklist()->update($checklist);

            /**
             * update item use logic:
             * delete old item, and create new item
             * 
             * delete old item
             **/
            $template->items()->delete($template->items()->pluck('id'));
            
            /**
             * create new items
             */
            foreach ($request->input('items') as $item) {
                $item = $this->itemData($item);
                $template->items()->save(new Item($item));
            }

            /**
             * response
             */
            $data = $this->addDataRes($template);
            $status = 200;

        } catch (Exception $e) {
            /**
             * if any arror reponse error message
             */
            $data   = ['message'=>$e->getMessage()];
            $status = 404;

        }finally{
            /**
             * response
             */
            return response()->json($data,$status);
        }

    }

    /**
     * Delete checklist template by given {templateId}
     */
    public function destroy($id){
        try {
            $template = Template::find($id);
            if(!$template) return $this->resp('error','Not Found',404);

            $template->delete();
            return $this->resp(null,['message'=>'success deleted'],204);

        } catch (Exception $e) {
            return $this->resp('error',$e->getMessage(),500);
        }

    }

    /**
     * Assign bulk checklists template by given templateId to many domains
     */
    public function assigns(Request $request, $id){
        /**
         * get data checklist
         */
        // $queries = Template::with('checklist','items')
        //     ->whereHas('checklist',function($q) use($request){
        //         $q->whereIn(
        //             'object_id',$request->input('*.attributes.object_id'),
        //         )->whereIn(
        //             'object_domain',$request->input('*.attributes.object_domain'),
        //         );
        //     })->find($id);
        // dd($queries);

        $queries = Checklist::with('template.items')->whereIn(
            'object_id',$request->input('*.attributes.object_id'),
        )->whereIn(
            'object_domain',$request->input('*.attributes.object_domain'),
        );

        $checklists =$queries->get();
        $items      =$queries->get()->pluck('template.items')->flatten();
        $collection =collect([
            'checklists'=>$checklists,
            'items'     =>$items,
        ]);
        /**
         * resource data 
         */ 
        $data = new TemplateAssignCollection($collection);

        /**
         * responser
         */
        return $this->resp(null,$data,200);
    }

    public function requestForm(Request $request){
        $this->validate($request,[
            'name'                      => 'required|string|max:100',
            'checklist'                 => 'required|array',
            'checklist.description'     => 'required|string|max:255',
            'checklist.due_interval'    => 'nullable|numeric',
            'checklist.due_unit'        => 'nullable|string|in:minute,hour,day,week,mounth',
            'items'                     => 'required|array',
            'items.*.description'       => 'required|string|max:255',
            'items.*.urgency'           => 'nullable|numeric',
            'items.*.due_interval'      => 'nullable|numeric',
            'items.*.due_unit'          => 'nullable|string|in:minute,hour,day,week,mounth',
        ]);
        return ;
    }

    /**
     * custom data checklist
     */
    public function checklistData(Request $request){
        $checklist['description'] = $request->input('checklist.description');
        if($request->input('checklist.due_interval') && $request->input('checklist.due_unit')){
            $checklist['due'] = Carbon::chTransform(
                $request->input('checklist.due_interval'),
                $request->input('checklist.due_unit')
            ); 
        }
        return $checklist;
    }

    /**
     * item data
     */
    public function itemData($item){
        if($item['due_interval'] && $item['due_unit']){
            $item['due'] = Carbon::chTransform(
                $item['due_interval'],
                $item['due_unit']
            ); 
        }
        return $item;
    }

    public function addDataRes($template){
        return [
            'data' => [
                'attributes'    => new TemplateResource($template)
            ]
        ];
    }


    public function tes(){
        $checklist = Checklist::get();
        $data = $checklist->random(rand(0,count($checklist)))->pluck('object_domain','object_id')->map(function($item,$key){
            return [
                'attributes' => [
                    'object_domain' => $item,
                    'object_id' => $key,

                ]
            ];
        })->flatten(1);
        return $data;
    }
}
