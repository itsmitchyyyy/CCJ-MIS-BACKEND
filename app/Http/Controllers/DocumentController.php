<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;

class DocumentController extends Controller
{
    public function store(StoreDocumentRequest $request) {
        $data = $request->validated();

        $documents = [];

        foreach ($data['document'] as $document) {
            $filePath = "documents/{$data['type']}";
            $documents[] = [
                'type' => $data['type'],
                'name' => ucfirst($document->getClientOriginalName()),
                'file_path' => $document->store($filePath),
                'user_id' => $data['user_id'],
            ];
        }

        Document::insert($documents);

        return response()->json(['message' => 'Documents uploaded successfully']);
    }
}
