<?php

namespace App\Http\Controllers;
use App\Models\Library;
use App\Models\User;



use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        $user = User::OrderBy("id", "ASC")->paginate(10);

        $output = [
            "message" => "users",
            "results" => $user
        ];

        if($acceptHeader === 'application/json'){
       
        return response()-> json($output, 200);
        } 
        else if($acceptHeader === 'application/xml'){
            $xml = new \SimpleXMLElement('<Users/>');
            foreach($user->items('data') as $item){
                $xmlItem = $xml->addChild('user');
                $xmlItem->addChild('id', $item->id);
                $xmlItem->addChild('nama', $item->nama);
                $xmlItem->addChild('email', $item->email);
                $xmlItem->addChild('password', $item->password);
                $xmlItem->addChild('alamat', $item->alamat);
                $xmlItem->addChild('umur', $item->umur);
                $xmlItem->addChild('kelas', $item->kelas);
                $xmlItem->addChild('nohp', $item->nohp);
                $xmlItem->addChild('tahun_bergabung', $item->tahun_bergabung);
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

         
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {

                $post = User::create($input);
                return response()->json($post, 200);
            }
            else if ($contentTypeHeader === 'application/xml') {
                $xml = new \SimpleXMLElement($request->getContent());
                
                if ($xml === false) {
                    return response('Invalid XML', 400);
                }
        
                $user = User::create([
                    'id' => $xml->id, // This assumes the XML root element is 'post
                    'nama' => $xml->nama,
                    'email' => $xml->email,
                    'password' => $xml->password,
                    'alamat' => $xml->alamat,
                    'umur' => $xml->umur,
                    'kelas' => $xml->kelas,
                    'nohp' => $xml->nohp,
                    'tahun_bergabung' => $xml->tahun_bergabung,
                    'created_at' => $xml->created_at,
                    'updated_at' => $xml->updated_at,
                ]);

                $user->save();
                return $xml = $user();
                
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function show($id)
    {
        $user = User::find($id);

        $acceptHeader = request()->header('Accept');
        if($acceptHeader === 'application/json'){
            return response()->json($user, 200);
        } 
        else if($acceptHeader === 'application/xml'){
            $xml = new \SimpleXMLElement('<Users/>');
            $xmlItem = $xml->addChild('user');
            $xmlItem->addChild('id', $user->id);
            $xmlItem->addChild('nama', $user->nama);
            $xmlItem->addChild('email', $user->email);
            $xmlItem->addChild('password', $user->password);
            $xmlItem->addChild('alamat', $user->alamat);
            $xmlItem->addChild('umur', $user->umur);
            $xmlItem->addChild('kelas', $user->kelas);
            $xmlItem->addChild('nohp', $user->nohp);
            $xmlItem->addChild('tahun_bergabung', $user->tahun_bergabung);
            
           
            return $xml->asXML();
         }
        else{
            return response('Not Acceptable!', 406);
    }
}
        

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $user = User::find($id);

        $acceptHeader = $request->header('Accept');
        if($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $contentTypeHeader = $request->header('Content-Type');
            if($contentTypeHeader === 'application/json'){
                $user->fill($input);
                $user->save();
                return response()->json($user, 200);
            }
            else if($contentTypeHeader === 'application/xml'){
                $xml = new \SimpleXMLElement($request->getContent());

                $user->fill([
                    'id' => $xml->id,
                    'nama' => $xml->nama,
                    'email' => $xml->email,
                    'password' => $xml->password,
                    'alamat' => $xml->alamat,
                    'umur' => $xml->umur,
                    'kelas' => $xml->kelas,
                    'nohp' => $xml->nohp,
                    'tahun_bergabung' => $xml->tahun_bergabung,
                    'created_at' => $xml->created_at,
                    'updated_at' => $xml->updated_at,
                ]);
                $user->save();
                return $xml = $user();

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
        $user = User::find($id);

        $acceptHeader = request()->header('Accept');
        if($acceptHeader === 'application/json'){
            $user->delete();
            $message = [
                'message' => 'deleted successfully',
                'user_id' => $id,
            ];

            return response()->json($message, 200);
        }
        
        else if($acceptHeader === 'application/xml'){
            $xml = new \SimpleXMLElement('<Users/>');
            $xml->addChild('message', 'deleted succesfully');
            $xml->addChild('user_id', $id);

            return $xml->asXML();
        }

        else{
            return response('Not Acceptable!', 406);
        }
    }

}
