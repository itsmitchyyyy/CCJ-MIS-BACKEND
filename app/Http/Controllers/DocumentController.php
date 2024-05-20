<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Requests\AddDocumentRequest;
use App\Models\Document;
use App\Models\DocumentRequest;
use App\Models\UserFolder;
use App\Enums\DocumentStatus;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentRequestResource;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(StoreDocumentRequest $request) {
        $data = $request->validated();

        $folderTypes = $this->fetchStoredDocuments($request);

        $documents = [];

        foreach ($data['documents'] as $document) {
            $filePath = "documents/{$data['type']}";

            if ($request->has('folder_type')) {
                $replacedFolderType = str_replace(' ', '_', $data['folder_type']);
                $filePath = "document_files/{$replacedFolderType}";
            }

            $documents[] = [
                'type' => $data['type'],
                'folder_type' => $data['folder_type'] ?? null,
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
        ->when($request->user_id, function ($query) use ($request) {
            return $query->where('user_id', $request->user_id);
        })
        ->when($request->folder_type, function ($query) use ($request) {
            return $query->where('folder_type', $request->folder_type);
        })
        ->when($request->type, function ($query) use ($request) {
            return $query->where('type', $request->type);
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

    public function fetchStoredDocuments(Request $request) {
        $defaultDirectories = [
            'letter', 
            'waver', 
            'student_data', 
            'graduating_student_data', 
            'student_research', 
            'indiana_jones', 
            'approval_to_print_form',
            'other_documents'
        ];
        
        if ($request->has('user_id')) {
            $newFolders = UserFolder::where('user_id', $request->user_id)->get();
            $folders = $newFolders->map(function ($folder) {
                return $folder->folder_name;
            })->toArray();
        }

        if ($request->has('access_type') && $request->access_type === 'admin') {
            $newFolders = UserFolder::join('users','users.id', '=', 'user_folders.user_id')
                ->where('users.access_type', 'admin')
                ->get();
                
            $folders = $newFolders->map(function ($folder) {
                return $folder->folder_name;
            })->toArray();
        }
        
        $folders = array_merge($defaultDirectories, $folders ?? []);

        return response()->json($folders);
    }

    public function addNewFolder(Request $request) {
        $folder = $request->folder_name;
        $directoryExists = Storage::exists('document_files/' . $folder);

        if (!$directoryExists) {
            Storage::makeDirectory('document_files/' . $folder);
            UserFolder::create([
                'user_id' => $request->user_id,
                'folder_name' => $folder,
            ]);
            return response()->json(['message' => "{$folder} created"]);
        }

        return response()->json(['message' => "{$folder} already exists"]);
    }
}
