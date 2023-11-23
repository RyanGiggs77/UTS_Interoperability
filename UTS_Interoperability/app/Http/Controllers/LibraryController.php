<?php

namespace App\Http\Controllers;
use App\Models\Library;
use app\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

    public function index(Request $request)
{
    if(Auth::user()->email == 'admin@gmail.com'){
        $library = Library::OrderBy("id", "ASC")->paginate(10);
    }
    else{
        $library = Library::where('users_id', Auth::user()->id)->OrderBy("id", "ASC")->paginate(10);
    }
    

    $output = [
        "message" => "library",
        "results" => $library
    ];

    $acceptHeader = $request->header('Accept');
    if ($acceptHeader === 'application/json') {
        return response()->json($output, 200);
    } elseif ($acceptHeader === 'application/xml') {
        $xml = new \SimpleXMLElement('<Libraries/>');
        foreach ($library['data'] as $item) {
            $xmlItem = $xml->addChild('library');
            $xmlItem->addChild('id', $item['id']);
            $xmlItem->addChild('title', $item['title']);
            $xmlItem->addChild('publish', $item['publish']);
            $xmlItem->addChild('deskription', $item['description']);
            $xmlItem->addChild('users_id', $item['users_id']);
            $xmlItem->addChild('created_at', $item['created_at']);
            $xmlItem->addChild('updated_at', $item['updated_at']);
        }
        return $xml->asXML();
    } else {
        return response('Not Acceptable!', 406);
    }
}


public function store(Request $request)
{
    $input = $request->all();
    $acceptHeader = $request->header('Accept');
    $contentTypeHeader = $request->header('Content-Type');

    if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
        if ($contentTypeHeader === 'application/json') {
            $library = Library::create($input);
            return response()->json($library, 200);
        } else if ($contentTypeHeader === 'application/xml') {
            $xml = new \SimpleXMLElement($request->getContent());

            $library = Library::create([
                'id' => $xml->id,
                'title' => $xml->title,
                'publish' => $xml->publish,
                'description' => $xml->description,
                'users_id' => $xml->users_id,
                'created_at' => $xml->created_at,
                'updated_at' => $xml->updated_at,
            ]);
            $library->save();
            return $xml->asXML();
        } else {
            return response('Unsupported Media Type', 415);
        }
    } else {
        return response('Not Acceptable!', 406);
    }
}



    public function show($id)
    {
        $library = library::find($id);
        $acceptHeader = request()->header('Accept');
        
        if($acceptHeader === 'application/json'){
            $library = library::find($id);
            return response()->json($library, 200);
        }
        else if($acceptHeader === 'application/xml'){
            $xml = new \SimpleXMLElement('<Library/>');
            $xmlItem = $xml->addChild('library');
            $xmlItem->addChild('id', $library->id);
            $xmlItem->addChild('title',$library->title);
            $xmlItem->addChild('publish', $library->publish);
            $xmlItem->addChild('description',$library->description);
            $xmlItem->addChild('users_id',$library->users_id);
            $xmlItem->addChild('created_at', $library->created_at);
            $xmlItem->addChild('updated_at', $library->updated_at);
            return $xml->asXML();
        }
        else{
            return response('Not Acceptable!', 406);
        }

        
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $library = library::find($id);

        $acceptHeader = $request->header('Accept');
        $contentTypeHeader = $request->header('Content-Type');

        if($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){

            if($contentTypeHeader === 'application/json'){
                $library->fill($input);
                $library->save();
                return response()->json($library, 200);
            }
            else if($contentTypeHeader === 'application/xml'){
                $xml = new \SimpleXMLElement($request->getContent());

                $library->fill([
                    'id' => $xml->id,
                    'title' => $xml->title,
                    'publish' => $xml->publish,
                    'description' => $xml->description,
                    'users_id' => $xml->users_id,
                    'created_at' => $xml->created_at,
                    'updated_at' => $xml->updated_at,
                ]);
                $library->save();
                return $xml->asXML();   
            }
            else{
                return response('Unsupported Media Type', 415);
            }
        }
        else{
            return response('Not Acceptable!', 406);
        }
    }

    public function destroy($id)
    {
        $library = library::find($id);
        $acceptHeader = request()->header('Accept');

        
        if($acceptHeader === 'application/json'){
            $library->delete();
            $message = [
                'message' => 'deleted successfully',
                'library_id' => $id
            ];
            return response()->json($message, 200);
        }
        else if($acceptHeader === 'application/xml'){
            $library->delete();
            $xml = new \SimpleXMLElement('<Library/>');
            $xml->addChild('message', 'deleted successfully');
            $xml->addChild('library_id', $id);

            return $xml->asXML();
        }
        else{
            return response('Not Acceptable!', 406);
        }   
    }

    public function getUserPosts($id)
    {
        $user = User::find($id)->library()->get();
        return ($user);
        
    }
}