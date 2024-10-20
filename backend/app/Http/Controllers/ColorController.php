<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ColorController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Color::latest('id')->paginate(5);
        return view('bienthe.colors.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bienthe.colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_color'      => 'required|max:25',
        ]);

        try {
            Color::query()->create($data);
            return redirect()->route('colors.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        return view('bienthe.colors.show', compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        return view('bienthe.colors.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
{
    $data = $request->validate([
        'name_color'      => 'required|max:25',
    ]);

    try {
        $color->update($data);  // Cập nhật bản ghi cụ thể
        return redirect('colors')->with('success', true);
    } catch (\Throwable $th) {
        return back()
            ->with('success', false)
            ->with('error', $th->getMessage());
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        try {
            $color->delete();
            return back()->with('success', true);
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }
}
