<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io;

class ViewerController extends Controller
{
    public function show($id)
    {
        echo "<a href='".route('io.show',['id'=>$id])."' target='_blank'> Show IO </a>";
        return view('admin.viewer',['sakme_id'=>$id]);
    }

    private function getData($id) {
        $io = Io::with("documents")->where("id",$id)->first();
        $images = [];
        foreach ($io->documents as $index => $doc) {
            $base64 = file_get_contents("storage/".$doc->filepath);
            $base64 = base64_encode($base64);

            $images[] = [
                'index' => $index,
//                'path' => $doc->filepath,
                'mime_type' => $doc->mimetype,
                'file_base_64' => $base64,
                'id' => $doc->id,
            ];
        }
        $total = count($images);
        return [
            'data'=>$images,
            'current_page'=>(int)$id,
            'per_page'=>5,
            'result'=>"success",
            'total'=>$total,
        ];
    }

    public function dataJson($id){

        $data = $this->getData($id);

        return response()->json($data);
    }

}
