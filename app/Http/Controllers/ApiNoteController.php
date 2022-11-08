<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth("api")->user()) {
            $items = auth("api")->user()->notes()->limit(10)->get();
            $totalCount = auth("api")->user()->notes()->count();
        } else {
            $items = [];
            $totalCount = 0;
        }

        $categories = NoteCategory::where("status", 1)->orderBy("title")->get();

        $paginationArr = [
            "skipCount" => isset($skip) ? $skip : 0,
            "itemsPerpage" => 10,
            "totalCount" => $totalCount,
            "totalPages" => ceil($totalCount / 10),
            "currentPage" => 1
        ];

        $data = ["items" => $items, "categories" => $categories, "paginationArr" => $paginationArr];

        return response()->json([
            "type" => "success",
            "data" => $data
        ], 200);

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
            "due_date" => ["nullable", "after_or_equal:" . Date('Y-m-d') . ""],
        ]);

        $formFields["user_id"] = auth("api")->id();
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

            return response()->json([
                "type" => "success",
                "message" => "Note created "
            ], 200);

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
        if ($note->user_id != auth("api")->id()) {
            return response()->json([
                "type" => "error",
                "message" => "Unauthorized Action",
            ], 401);
            exit;
        }


        $note->delete();

        return response()->json([
            "type" => "success",
            "message" => "Note  deleted succesfully "
        ], 200);

    }

    public function toggleStatus(Note $note, Request $request)
    {

        if ($note->user_id != auth("api")->user()->id) {
            return response()->json([
                "type" => "error",
                "message" => "Unauthorized  operation denied",
            ], 401);
            exit;
        }
        if ($note) {
            $newStatus = ($request->val === "true") ? 1 : 0;
            Note::where("id", $note->id)->update(["is_read" => $newStatus]);
            $message = ($newStatus == 1) ? "Note is marked as read" : "Note removed from read";
            return response()->json([
                "type" => "success",
                "message" => $message
            ], 200);
   
        }
    }
}
