<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Io;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function clearDocuments($io_id)
    {
        $docs = Document::where("io_id",$io_id);
        foreach ($docs->get() as $doc):

            $tiles_folder = pathinfo($doc->filepath, PATHINFO_FILENAME);

            $base = trim(preg_replace("/documents/","",pathinfo($doc->filepath, PATHINFO_DIRNAME))); # /1-1-1/GE_1-1-1_1.jpg
            $base = trim(preg_replace("/.$tiles_folder./","",$base)); # /1-1-1/GE_1-1-1_1.jpg => /1-1-1/

            $tiles = "tiles/". $tiles_folder;
            $document = "documents".$base;
            $file = "files".$base;
            $thumbs = "thumbs/documents".$base;

            foreach([$thumbs, $tiles, $document, $file] as $dir):
                Storage::deleteDirectory("public/".$dir);
            endforeach;
        endforeach;
        $docs->delete();
        return redirect(route("io.show", ["id"=>$io_id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Document::findOrFail($id)->delete();
        return redirect(route());
    }
}
