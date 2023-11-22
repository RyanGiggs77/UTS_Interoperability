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

    public function index(Request $request)
    {
        $library = Library::OrderBy("id", "DESC")->paginate(10);

        $output = [
            "message" => "library",
            "results" => $library
        ];

        $acceptHeader = $request->header('Accept');
        if($acceptHeader === 'application/json'){
           

            return response()->json($output, 200);
        }
        else if($acceptHeader === 'application/xml'){
            $xml = new \SimpleXMLElement('<Libraries/>');
            foreach($library->items('data') as $item){
                $xmlItem = $xml->addChild('library');
                $xmlItem->addChild('id', $item->id);
                $xmlItem->addChild('judul',$item->title);
                $xmlItem->addChild('terbit', $item->publish);
                $xmlItem->addChild('deskripsi',$item->description);
                $xmlItem->addChild('users_id',$item->users_id);
                $xmlItem->addChild('created_at', $item->created_at);
                $xmlItem->addChild('updated_at', $item->updated_at);
            }
            return $xml->asXML();
        }
        else{
            return response('Not Acceptable!', 406);
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $acceptHeader = $request->header('Accept');
        $contentTypeHeader = $request->header('Content-Type');

        if($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){

            if($contentTypeHeader === 'application/json'){
                $library = library::create($input);
                return response()->json($library, 200);
            }
            else if($contentTypeHeader === 'application/xml'){
                $xml = new \SimpleXMLElement('<Library/>');

                if($xml === false){
                    return response('Invalid xml', 400);
                }

                $library = Library::create([
                    'title' => $xml->title,
                    'publish' => $xml->publish,
                    'description' => $xml->description,
                    'users_id' => $xml->users_id,
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
            $xmlItem->addChild('judul',$library->title);
            $xmlItem->addChild('terbit', $library->publish);
            $xmlItem->addChild('deskripsi',$library->description);
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

        if($acceptHeader === 'applicaation/json' || $acceptHeader === 'application/xml'){

            if($contentTypeHeader === 'application/json'){
                $library->fill($input);
                $library->save();
                return response()->json($library, 200);
            }
            else if($contentTypeHeader === 'application/xml'){
                $xml = new \SimpleXMLElement('<Library/>');

                $library->fill([
                    'title' => $xml->title,
                    'publish' => $xml->publish,
                    'description' => $xml->description,
                    'users_id' => $xml->users_id,
                ]);
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