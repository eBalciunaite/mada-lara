<?php

namespace App\Http\Controllers;

use App\Models\Master;
use Illuminate\Http\Request;
use Validator;

class MasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masters = Master::all(); // is DB surenka visus masterius
        // $masters yra kolekcijos tipo objektas (tai objektas, kuris turi viduje masyva)
        // kolekcijos objektai turi savo metodus apdorojimui (zr laravel collection methods)
        // https://laravel.com/docs/8.x/collections#available-methods
        return view('master.index', ['masters' => $masters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'master_name' => ['required', 'min:3', 'max:64'],
            'master_surname' => ['required', 'min:3', 'max:64'],
        ]
    );

    if ($validator->fails()) {
        $request->flash();
        return redirect()->back()->withErrors($validator);
    }

        $master = new Master;
        $master->name = $request->master_name;
    // DB->stulp_vardas = Formos->name_attributas
    // sumappinam ir redirectinam
        $master->surname = $request->master_surname;
        $master->save();
        return redirect()->route('master.index')->with('success_message', 'New designer has arrived.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master  $master
     * @return \Illuminate\Http\Response
     */
    public function show(Master $master)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master  $master
     * @return \Illuminate\Http\Response
     */
    public function edit(Master $master)
    {
        return view('master.edit', ['master' => $master]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master  $master
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Master $master)
    {
        $validator = Validator::make($request->all(),
        [
            'master_name' => ['required', 'min:3', 'max:64'],
            'master_surname' => ['required', 'min:3', 'max:64'],
        ]
    );

    if ($validator->fails()) {
        $request->flash();
        return redirect()->back()->withErrors($validator);
    }
        // DB->stulp_vardas = Formos->name_attributas
        $master->name = $request->master_name;
        $master->surname = $request->master_surname;
        $master->save();
        return redirect()->route('master.index')->with('success_message', 'The designer was edited.');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master  $master
     * @return \Illuminate\Http\Response
     */
    public function destroy(Master $master)
    {
        // jei bus daugiau nei 0, bus true ir negalima istrinti
        if($master->masterHasOutfits->count()){
            return redirect()->back()->with('info_message', 'There is job to do. Can\'t delete.');
        }
        $master->delete();
        return redirect()->route('master.index')->with('success_message', 'Master gone.');
    }
}
