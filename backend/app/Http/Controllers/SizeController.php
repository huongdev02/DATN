<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Size::latest('id')->paginate(5)->get();
        return view('bienthe.sizes.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bienthe.sizes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'size'      => 'required|max:25',
        ]);

        try {
            Size::query()->create($data);
            return redirect()->route('sizes.index')
                ->with('success', 'Thêm mới thành công');
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {
        return view('bienthe.sizes.show', compact('size'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Size $size)
    {
        return view('bienthe.sizes.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
{
    $data = $request->validate([
        'size'      => 'required|max:25',
    ]);

    try {
        $size->update($data);  // Cập nhật bản ghi cụ thể
        return redirect('sizes')->with('success', true);
    } catch (\Throwable $th) {
        return back()
            ->with('success', false)
            ->with('error', $th->getMessage());
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        try {
            $size->delete();
            return back()->with('success', true);
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }
}
