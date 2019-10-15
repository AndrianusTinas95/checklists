<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Helpers\Carbon;
use App\Item;
use App\Resources\Template\TemplateCollection;
use App\Resources\Template\TemplateResource;
use App\Template;
use Exception;
use Illuminate\Http\Request;
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
        $paginator = Template::with('checklist','items')
        ->where(function($q) use($request){
            if($request->filter) $q->where('name',$request->filter);
        })
        ->paginate($request->page_limit ?? 10);
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
             * custom data 
             */
            $data = [
                'data' => [
                    'type'          => 'templates',
                    'id'            => $template->id,
                    'attributes'    => new TemplateResource($template),
                    'links'         =>[
                        'self'  => $request->fullUrl()
                    ] 
                ]
            ];
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
            $template = Template::findOrFail($id);
            $template->delete();
            return response()->json(['message'=>'success'],204);
        } catch (Exception $e) {
            return response()->json($e->getMessage(),404);
        }

    }

    /**
     * Assign bulk checklists template by given templateId to many domains
     */
    public function assigns($id){

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
                'id'            => $template->id,
                'attributes'    => new TemplateResource($template)
            ]
        ];
    }


    public function tes(){
        /**
         * template data
         */
        $template = factory(Template::class)->make()->only('name');

        $unit = ['minute','hour','day','week','mounth'];
        
        /**
         * checklist data
         */
        $checklist = factory(Checklist::class)->make([
            'due_interval'=> rand(1,10),'due_unit'=>$unit[rand(0,4)]
        ])->only('description','due_interval','due_unit');
        
        /**
         * item data
         */
        $items = factory(Item::class,rand(1,3))->make([
            'due_interval'=> rand(1,10),'due_unit'=>$unit[rand(0,4)]
        ]);
        $items = $items->map(function($item){
            return[
                'due_interval' => $item['due_interval'],
                'due_unit' => $item['due_unit'],
                'urgency' => $item['urgency'],
                'description' => $item['description']
            ];
        })->toArray();

        /**
         * all data
         */
        $data['name']=$template['name'];
        $data['checklist']=$checklist;
        $data['items']= $items;
        
        dd($data);
        return $template;
    }
}
