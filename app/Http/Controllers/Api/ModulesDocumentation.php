<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModuleDocumentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulesDocumentation extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $module_documentation = DB::table('module_documentation as md')
          ->select('md.module_name', 'md.description', 'md.updated_at', 'md.created_at', 's.submenu_name', 'md.url_path', 'md.attachment_file')
            ->join('submenu as s', 'md.url_path', '=', 's.submenu_link')->get();
        return view('layouts.main_pages.modules_documentation.modules_documentation', compact('module_documentation'));
    }

   
    /**
     * Show the form for creating a new resource.
     */
    public function module_create()
    {
        $submenu = DB::table('submenu as s')
            ->select('submenu_link', 'submenu_name')
            ->leftJoin('module_documentation as md', 's.submenu_link', '=', 'md.url_path')
            ->where('md.url_path', '=', null)
            ->where('main_menu', '<>', 10)->get();
        return view('layouts.main_pages.modules_documentation.create.modules_create', compact('submenu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required',
            'url_path' => 'required',
            'description' => 'required',
            'attachment_file' => 'required|file|mimes:pdf'
        ],
        [
            'module_name.required' => 'Nama Module harus diisi',
            'url_path.required' => 'Pilih module dahulu',
            'description.required' => 'Deskripsi module harus diisi',
            'attachment_file'=> 'Harus upload dokument & file harus PDF'
        ]);

        $module = $request->url_path;

        if($request->hasFile('attachment_file')){
            $file = $request->file('attachment_file'); 
            $folderPath = 'module-documentation/' . $module; 
            $pdfPath = $file->storeAs($folderPath, uniqid() . '.' . $file->getClientOriginalExtension(),'public');

            ModuleDocumentation::create([
                'module_name' => $request->module_name,
                'attachment_file' => $pdfPath,
                'url_path' => $request->url_path,
                'description' => $request->description,
                'created_at' => now(),
                'updated_at' => null
            ]);
        }

        session()->flash('message_success', 'Data Dokumentasi Module berhasil disimpan!');
        return redirect()->route('modules_documentation.index');

    }

    /**
     * Display the specified resource.
     */
    public function show_module(string $url)
    {   $module_documentation = ModuleDocumentation::where('url_path', $url)->first();
        return view('layouts.main_pages.modules_documentation.module_show', compact('module_documentation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {

        $url = $request->url_path;
        $module = DB::table('module_documentation as md')
        ->select('md.module_name', 'md.description', 'md.updated_at', 's.submenu_name', 'md.url_path')
            ->join('submenu as s', 'md.url_path', '=', 's.submenu_link')
            ->where('md.url_path', $url)->first();
        return view('layouts.main_pages.modules_documentation.edit.module_edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'module_name' => 'required',
            'description' => 'required'
        ],
        [
            'module_name.required' => 'Nama Module harus diisi',
            'description.required' => 'Deskripsi module harus diisi'
        ]);

        $url = $request->url_path;


        ModuleDocumentation::where('url_path', $url)->update([
            'module_name' => $request->module_name,
            'description' => $request->description,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Data Dokumentasi Module berhasil diperbarui!');
        return redirect()->route('modules_documentation.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $url = $request->url_path;
        $module =  ModuleDocumentation::where('url_path', $url)->first();

        if($module){
            if ($module && $module->attachment_file) {
                $dropPdf = public_path('storage/' . $module->attachment_file);

                if (file_exists($dropPdf)) {
                    unlink($dropPdf);
                }

                $folderPath = dirname($dropPdf);
                if (is_dir($folderPath) && count(scandir($folderPath)) == 2) {
                        rmdir($folderPath);
                }
            }
            $module->delete();
        }

        session()->flash('message_success', 'Data Dokumentasi Module berhasil dihapus!');
        return redirect()->back();


    }
}
