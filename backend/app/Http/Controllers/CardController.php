<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index() 
    {
        $cards = Card::all();

        return view('user.addcard', compact('cards'));

    }

    public function create() 
    {
        return view('user.card');
    }

    public function store(Request $request)
{
    $request->validate([
        'card_number' => 'required|unique:cards',
        'card_name' => 'required',
        'expiration_date' => 'required|date',
        'issue_date' => 'nullable|date', // Ngày phát hành có thể không cần thiết
        'cvv' => 'required',
    ]);

    Card::create($request->all());

    return redirect()->route('user.card')->with('success', 'Thẻ đã được thêm thành công!');
}

}
