<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function editor(Request $request)
    {
        // Load a default doc or pass ?file=/storage/pdfs/sample.pdf
        $file = $request->query('file', '');
        return view('pdf.editor', ['file' => $file]);
    }

    public function fabricEditor(Request $request)
    {
        // Basic Fabric.js + pdfjs-dist editor entrypoint
        $file = $request->query('file', '/storage/pdfs/sample.pdf');
        return view('pdf.fabric-editor', ['file' => $file]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf' => ['required','file','mimes:pdf','max:51200'], // 50MB
        ]);

        $path = $request->file('pdf')->store('pdfs', 'public');  // storage/app/public/pdfs/...
        return response()->json([
            'ok' => true,
            'url' => Storage::disk('public')->url($path),         // e.g. /storage/pdfs/xxx.pdf
        ]);
    }

    public function save(Request $request)
    {
        // Accept the edited PDF blob as binary
        $request->validate([
            'filename' => ['required','string'],
            'blob' => ['required'], // base64 or raw
        ]);

        // If client sends base64:
        $b64 = $request->input('blob');
        if (str_starts_with($b64, 'data:')) {
            [$meta, $data] = explode(',', $b64, 2);
            $bytes = base64_decode($data);
        } else {
            $bytes = base64_decode($b64);
        }

        $path = 'pdfs/edited_' . time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/','_', $request->input('filename'));
        Storage::disk('public')->put($path, $bytes);

        return response()->json([
            'ok' => true,
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
