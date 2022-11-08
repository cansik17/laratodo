<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Note;
use App\Models\User;
use App\Models\NoteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{

    public function renderList(Request $request )
    {
        $formFields = $request->validate([
            "filterDueDate" => ["nullable"],
            "filterCategories" => ["nullable"],
            "filterStatus" => ["nullable"],
            "filterSort" => ["nullable"],
            "filterPage" => ["nullable"],
        ]);

        //dd($formFields);

        $user_id = auth()->id();
        $today = date('Y-m-d');
        $oneDay = date('Y-m-d',strtotime($today." +1 day"));
        $threeDays = date('Y-m-d',strtotime($today." +3 day"));
        $oneWeek = date('Y-m-d',strtotime($today." +7 day"));
        $oneMonth = date('Y-m-d',strtotime($today." +30 day"));
        $oneYear = date('Y-m-d',strtotime($today." +365 day"));

        $items = DB::table('notes');


        if (auth()->user()) {
            if ($request->has("filterCategories") && !empty($request->filterCategories)) {
                $filterCategories = $formFields["filterCategories"];
                $items->select('notes.*', 'note_categories_pivots.category_id', 'note_categories_pivots.note_id');
                $items->join('note_categories_pivots', 'notes.id', '=', 'note_categories_pivots.note_id');
                $items->whereIn('note_categories_pivots.category_id', $filterCategories);
                $items->where("notes.user_id", $user_id);

                if ($request->has("filterDueDate")) {
                    $filterDueDate = $formFields["filterDueDate"];
                    switch ($filterDueDate) {
                        case 'expired':
                            $items->where('notes.due_date', '<', $today);
                            break;
                        case 'oneDay':
                            $items->where('notes.due_date', '<=', $oneDay);
                            break;
                        case 'threeDays':
                            $items->where('notes.due_date', '<=', $threeDays);
                            break;
                        case 'oneWeek':
                            $items->where('notes.due_date', '<=', $oneWeek);
                            break;
                        case 'oneMonth':
                            $items->where('notes.due_date', '<=', $oneMonth);
                            break;
                        case 'oneYear':
                            $items->where('notes.due_date', '<=', $oneYear);
                            break;
                        case 'notExpired':
                            $items->where(function ($query) {
                                $query->where('notes.due_date', '>=', date('Y-m-d'))
                                ->orWhere('notes.due_date', null);
                            });
                            break;
                    }
                }
                if ($request->has("filterStatus") && $request->filterStatus != "") {
                    $filterStatus = $formFields["filterStatus"];
                    $items->where('notes.is_read', $filterStatus);
                }



                if ($request->has("filterSort")) {
                    $filterSort = $formFields["filterSort"];
                    switch ($filterSort) {
                        case 'dueDateAsc':
                            $items->orderBy('notes.due_date');
                            break;
                        case 'dueDateDesc':
                            $items->orderBy('notes.due_date', 'desc');
                            break;
                        case 'createDateAsc':
                            $items->orderBy('notes.created_at');
                            break;
                        case 'createDateDesc':
                            $items->orderBy('notes.created_at', 'desc');
                            break;
                    }
                }

                $items->groupBy('notes.id');

            } else {
                $items->where("user_id", $user_id);

                if ($request->has("filterDueDate")) {
                    $filterDueDate = $formFields["filterDueDate"];
                    switch ($filterDueDate) {
                        case 'expired':
                            $items->where('due_date', '<', $today);
                            
                            break;
                        case 'oneDay':
                            $items->where('due_date', '<=', $oneDay);
                            break;
                        case 'threeDays':
                            $items->where('due_date', '<=', $threeDays);
                            break;
                        case 'oneWeek':
                            $items->where('due_date', '<=', $oneWeek);
                            break;
                        case 'oneMonth':
                            $items->where('due_date', '<=', $oneMonth);
                            break;
                        case 'oneYear':
                            $items->where('due_date', '<=', $oneYear);
                            break;
                        case 'notExpired':
                            $items->where(function ($query) {
                                $query->where('due_date', '>=', date('Y-m-d'))
                                ->orWhere('due_date', null);
                            });
                   
                            break;
                    }
                }
                if ($request->has("filterStatus") && $request->filterStatus != "") {
                    $filterStatus = $formFields["filterStatus"];
                    $items->where('is_read', $filterStatus);
                }



                if ($request->has("filterSort")) {
                    $filterSort = $formFields["filterSort"];
                    switch ($filterSort) {
                        case 'dueDateAsc':
                            $items->orderBy('due_date');
                            break;
                        case 'dueDateDesc':
                            $items->orderBy('due_date', 'desc');
                            break;
                        case 'createDateAsc':
                            $items->orderBy('created_at');
                            break;
                        case 'createDateDesc':
                            $items->orderBy('created_at', 'desc');
                            break;
                    }
                }
            }

            $totalCount = $items->count();
            if ($request->has("filterPage")) {
                $skip = ($formFields["filterPage"] - 1)*10;
                $items =  $items->skip($skip)->take(10)->get();
            }else {
                $items =  $items->limit(10)->get();
            }


            
        } else {
            $items = [];
        }
        
         //dd($items);   
           
        
        $paginationArr = [
            "skipCount" => isset($skip) ? $skip : 0,
            "itemsPerpage" => 10,
            "totalCount" => $totalCount,
            "totalPages" => ceil($totalCount / 10),
            "currentPage" => isset($formFields["filterPage"]) ? $formFields["filterPage"] : 1, 
        ];
        
  

        return view("notes.components.renderList", ["items" => $items, "paginationArr" => $paginationArr]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()) {
            $items = auth()->user()->notes()->limit(10)->get();
            $totalCount = auth()->user()->notes()->count();
        } else {
            $items = [];
            $totalCount = 0;
        }

        $categories = NoteCategory::where("status",1)->orderBy("title")->get();

       

        $paginationArr = [
            "skipCount" => isset($skip) ? $skip : 0,
            "itemsPerpage" => 10,
            "totalCount" => $totalCount,
            "totalPages" => ceil($totalCount / 10),
            "currentPage" => 1
        ];
        
  
        
        return view("notes.index",["items" => $items, "categories" =>$categories, "paginationArr" => $paginationArr]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            "note" => ["required", "min:3"],
            "due_date" => ["nullable", "after_or_equal:". Date('Y-m-d').""],
        ]);

        $formFields["user_id"] =auth()->id();
        $formFields["is_read"] = 0;

        
        //dd($request);
        // Create User
        $create = Note::create($formFields);
        
        if ($create) {
            $last_id = $create->id;
            // insert categories
            if (!empty($request->categories)) {
                foreach ($request->categories as $row) {
                    DB::table('note_categories_pivots')->insert([
                        'note_id' => $last_id,
                        'category_id' => $row
                    ]);
                }
            }

            echo json_encode([
                "type" => "success",
                "message" => "Note created "
            ]);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        // Make sure loggedin user is owner
        if ($note->user_id != auth()->id()) {
            echo json_encode([
                "type" => "error",
                "message" => "Unauthorized Action"
            ]);
            exit;
        }


        $note->delete();

        echo json_encode([
            "type" => "success",
            "message" => "Note deleted succesfully"
        ]);
    }

    public function toggleStatus(Note $note, Request $request)
    {
        if ($note->user_id != auth()->user()->id) {
            echo json_encode([
                "type" => "error",
                "message" => "Unauthorized operation denied"
            ]);
            exit;
        }
        if ($note) {
            $newStatus = ($request->val === "true") ? 1 : 0;
            Note::where("id", $note->id)->update(["is_read" => $newStatus]);
            $message = ($newStatus == 1) ? "Note is marked as read" : "Note removed from read" ;
            echo json_encode([
                "type" => "success",
                "message" => $message
            ]);
        }
    }
}
