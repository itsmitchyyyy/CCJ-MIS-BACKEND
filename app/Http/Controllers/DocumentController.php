<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Requests\AddDocumentRequest;
use App\Models\Document;
use App\Models\DocumentRequest;
use App\Enums\DocumentStatus;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentRequestResource;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(StoreDocumentRequest $request) {
        $data = $request->validated();

        $documents = [];

        foreach ($data['documents'] as $document) {
            $filePath = "documents/{$data['type']}";
            $documents[] = [
                'type' => $data['type'],
                'name' => ucfirst($document->getClientOriginalName()),
                'file_path' => $document->store($filePath),
                'user_id' => $data['user_id'],
                'status' => $data['status'] ?? DocumentStatus::Pending,
                'is_private' => $data['is_private'] ?? false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Document::insert($documents);

        return response()->json(['message' => 'Documents uploaded successfully']);
    }

    public function index(Request $request) {
        $documents = Document::when($request->status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        DocumentResource::withoutWrapping();
        return DocumentResource::collection($documents);
    }

    public function update(UpdateDocumentRequest $request, Document $document) {
        $data = $request->validated();

        $document->update($data);
        return response()->json(['message' => 'Document updated successfully']);
    }

    public function destroy(Document $document) {
        Storage::delete($document->file_path);
        $document->delete();

        return response()->json(['message' => 'Document deleted successfully']);
    }

    public function addRequest(AddDocumentRequest $documentRequest) {
        $data = $documentRequest->validated();

        $documentRequest = DocumentRequest::where('document_id', $data['document_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($documentRequest) {
            $documentRequest->update([
                'request_count' => $documentRequest->request_count + 1,
                'expires_at' => now()->addDays(7),
                'reason' => $data['reason'],
                'status' => $documentRequest->status === 'rejected' ? 'pending' : $documentRequest->status,
            ]);

            return response()->json(['message' => 'Request updated successfully']);
        }

        $newDocumentData = array_merge($data, [
            'expires_at' => now()->addDays(7),
        ]);

        $documentRequest = DocumentRequest::create($newDocumentData);

        return response()->json(['message' => 'Request added successfully']);
    }

    public function fetchDocumentRequests(Request $request) {
        $documentRequests = DocumentRequest::when($request->status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        })
        ->when($request->user_id, function ($query) use ($request) {
            return $query->where('user_id', $request->user_id);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        DocumentRequestResource::withoutWrapping();
        return DocumentRequestResource::collection($documentRequests);
    }

    public function updateDocumentRequest(Request $request, DocumentRequest $documentRequest) {
        $data = $request->all();

        $data['expires_at'] = now()->addDays(7);
        $documentRequest->increment('request_count');
        $documentRequest->update($data);
        return response()->json(['message' => 'Document request updated successfully']);
    }
}
