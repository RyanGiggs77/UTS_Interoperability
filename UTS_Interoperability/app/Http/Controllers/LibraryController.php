<?php

namespace App\Http\Controllers;
use App\Models\Library;
use App\Models\User;

use Illuminate\Http\Request;

class LibraryController extends Controller
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

    public function index()
    {
        $library = Library::OrderBy("id", "DESC")->paginate(10);

        $output = [
            "message" => "librarys",
            "results" => $library
        ];
        return response()-> json($output, 200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $library = library::create($input);

        return response()->json($library, 200);    
    }

    public function show($id)
    {
        $library = library::find($id);

        if(!$library) {
            abort(404);
        }

        return response()->json($library, 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $library = library::find($id);

        if(!$library) {
            abort(404);
        }

        $library->fill($input);
        $library->save();

        return response()->json($library, 200);
    }

    public function destroy($id)
    {
        $library = library::find($id);

        if(!$library) {
            abort(404);
        }

        $library->delete();
        $message = ['message' => 'library deleted succesfully', 'librarys_id' => $id];

        return response()->json($message, 200);
    }

    public function getUserPosts($id)
    {
        $user = User::find($id)->library()->get();
        return ($user);
        
    }
}