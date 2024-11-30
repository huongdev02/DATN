<?php

namespace App\Http\Controllers;

use App\Models\LogoBanner;
use Illuminate\Http\Request;

class LogoBannerController extends Controller
{
    public function index()
    {
        $logoBanners = LogoBanner::latest('id')->paginate(10);
        return view('logo_banners.index', compact('logoBanners'));
    }

    public function create()
    {
        return view('logo_banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:8192',
            'image' => 'required|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('logo_banner', 'public'); // Lưu file vào storage/app/public/logo_banner
        }

        LogoBanner::create($data);
        return redirect()->route('logo_banners.index')->with('success', 'Logo/Banner added successfully.');
    }

    public function edit(LogoBanner $logoBanner)
    {
        return view('logo_banners.edit', compact('logoBanner'));
    }

    public function update(Request $request, LogoBanner $logoBanner)
    {
        $request->validate([
            'type' => 'required|in:1,2',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);

        $logoBanner->update($request->all());
        return redirect()->route('logo_banners.index')->with('success', 'Logo/Banner updated successfully.');
    }

    public function destroy(LogoBanner $logoBanner)
    {
        $logoBanner->delete();
        return redirect()->route('logo_banners.index')->with('success', 'Logo/Banner deleted successfully.');
    }
}
