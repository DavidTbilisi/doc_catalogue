<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Io;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Monolog\Logger;

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
            $base64bites = file_get_contents(app_path("../storage/app/public/thumbs/".$doc->filepath));
            $base64 = base64_encode($base64bites);

            if ($current_page > 1) $index = $index+$per_page*($current_page-1);

            $images[] = [
                'index' => $index,
//                'path' => $doc->filepath,
                'size_bytes' => $doc->size,
                'size_mb' => bytesToMb($doc->size),
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


    public function get_image($document_id)
    {

        $doc = Document::where("id",$document_id)->first();
        $max_image_size = env('MAX_IMAGE_SIZE');

        if (bytesToMb($doc->size) > $max_image_size) {
            if(Storage::exists("public/tiles/".$doc->tileFolder())) {
                $images = [
                    'filename' => $doc->filename,
                    'mime_type' => $doc->mimetype,
                    'id' => $doc->id,
                ];
                $data = [
                    'data'=>$images,
                    'result'=>"deepzoom",
                    'msg'=>"სურათის ზომა აღემატება {$max_image_size}mb-ს",
                ];
                return response()->json($data);
            }

            $data = [
                'data'=>null,
                'result'=>"processing",
                'msg'=>"სურათის ზომა აღემატება {$max_image_size}mb-ს",
            ];


            return response()->json($data);
        }



        $base64bites = file_get_contents(app_path("../storage/app/public/".$doc->filepath));
        $base64 = base64_encode($base64bites);

        $images = [
            'filename' => $doc->filename,
            'mime_type' => $doc->mimetype,
            'file_base_64' => $base64,
            'id' => $doc->id,
        ];

        $data = [
            'data'=>$images,
            'result'=>"success",
        ];

        return response()->json($data);
    }


    public function dataJson($id){

        $data = $this->getData($id);

        return response()->json($data);
    }

}
