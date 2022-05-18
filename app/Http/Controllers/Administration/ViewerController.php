<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io;

class ViewerController extends Controller
{
    public function show($id)
    {
        $io = Io::find($id);
        return view('admin.viewer',[
            'sakme_id'=>$id,
            'title'=>$io->reference,
        ]);
    }

    private function getData($id) {
        $io = Io::with("documents")->where("id",$id)->first();
        $images = [];
        $current_page = request('page')??1;
        $per_page = request('per_page')??5;

        $docs = $io->documents()->paginate($per_page);
//        dd($docs);
        foreach ($docs as $index => $doc) {
            $base64 = file_get_contents("storage/".$doc->filepath);
            $base64 = base64_encode($base64);
            if ($current_page > 1) $index = $index+$per_page*($current_page-1);


            $images[] = [
                'index' => $index,
//                'path' => $doc->filepath,
                'mime_type' => $doc->mimetype,
                'file_base_64' => $base64,
                'id' => $doc->id,
            ];
        }




        return [
            'data'=>$images,
            'current_page'=>$docs->currentPage(),
            'per_page'=>$docs->perPage(),
            'result'=>"success",
            'total'=> $docs->total(),
        ];
    }

    public function dataJson($id){

        $data = $this->getData($id);

        return response()->json($data);
    }

}
