<?php

namespace App\Http\Controllers;

use App\Models\Outfit;
use App\Models\Master;
use Illuminate\Http\Request;
use Validator;
use PDF;

class OutfitController extends Controller
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
    public function index(Request $request)
    {
        // shortcutas, kuris paima viska ir nesiparina del tvarkos ir panasiai
        // $outfits = Outfit::all();

        // paginate(15)->withQueryString() ivykdo uzklausa orderBy
        // $outfits = Outfit::orderBy('size', 'desc')->paginate(15)->withQueryString();

        // defaultiniai, kad neatsoktu atgal reiksmes, rodytu, kas BUVo prispaudyta
        $dir = 'asc';
        $sort = 'type';
        $default_master = 0;
        $masters = Master::all();
        $s = '';


        // RUSIAVIMAS
        if($request->sort_by && $request->dir) {
            if('type' ==  $request->sort_by && 'asc' ==  $request->dir) {
                $outfits = Outfit::orderBy('type')->paginate(15)->withQueryString();
            }
            elseif('type' ==  $request->sort_by && 'desc' ==  $request->dir) {
                $outfits = Outfit::orderBy('type','desc')->paginate(15)->withQueryString();
                $dir = 'desc';
            }
            elseif('size' ==  $request->sort_by && 'asc' ==  $request->dir) {
                $outfits = Outfit::orderBy('size')->paginate(15)->withQueryString();
                $sort = 'size';
            }
            elseif('size' ==  $request->sort_by && 'desc' ==  $request->dir) {
                $outfits = Outfit::orderBy('size', 'desc')->paginate(15)->withQueryString();
                $dir = 'desc';
                $sort = 'size';
            }
            else {
                $outfits = Outfit::paginate(15)->withQueryString();
            }
        }
        // FILTRAVIMAS
        elseif($request->master_id) {
            $outfits = Outfit::where('master_id', (int) $request->master_id)->paginate(15)->withQueryString();
            $default_master = (int) $request->master_id;
        }
        // SEARCH paieska
        elseif($request->s) {
                //'%'. searchina kazko panasaus, jei rasai coat, atiduoda rain coat ir t.t.
                // % tipo bet kas
                $outfits = Outfit::where('type', 'like', '%'.$request->s.'%')->paginate(15)->withQueryString();
                $s = $request->s;
        }
        elseif($request->do_search) {
            $outfits = Outfit::where('type', 'like', '')->paginate(15)->withQueryString();
    }
        else {
            $outfits = Outfit::paginate(15)->withQueryString();
        }

        return view('outfit.index', [
            'outfits' => $outfits, 
            'dir' => $dir, 
            'sort' => $sort,
            'masters' => $masters,
            'default_master' => $default_master,
            's' => $s
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $masters = Master::all();
        return view('outfit.create', ['masters' => $masters]);
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
            'outfit_type' => ['required', 'min:3', 'max:50'],
            'outfit_color' => ['required', 'min:3', 'max:20'],
            'outfit_size' => ['required', 'integer', 'min:5', 'max:22'],
            'outfit_about' => ['required'],
            'master_id' => ['required', 'integer', 'min:1'],
        ]
    );

    if ($validator->fails()) {
        $request->flash();
        return redirect()->back()->withErrors($validator);
    }


        $outfit = new Outfit;
        if ($request->has('outfit_photo')) {
            $photo = $request->file('outfit_photo');
            $imageName = 
            $request->outfit_type. '-' .
            $request->outfit_color. '-' .
            time(). '.' .
            $photo->getClientOriginalExtension();
            $path = public_path() . '/outfits-images/'; // serverio vidinis kelias
            $url = asset('outfits-images/'.$imageName); // url narsyklei (isorinis)
            $photo->move($path, $imageName); // is serverio tmp ===> i public folderi
            $outfit->photo = $url;
        }
        $outfit->type = $request->outfit_type;
        $outfit->color = $request->outfit_color;
        $outfit->size = $request->outfit_size;
        $outfit->about = $request->outfit_about;
        $outfit->master_id = $request->master_id;
        $outfit->save();
        return redirect()->route('outfit.index')->with('success_message', 'New outfit has arrived.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Outfit  $outfit
     * @return \Illuminate\Http\Response
     */
    public function show(Outfit $outfit)
    {
        return view('outfit.show', ['outfit' => $outfit]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Outfit  $outfit
     * @return \Illuminate\Http\Response
     */
    public function edit(Outfit $outfit)
    {
        $masters = Master::all();
        return view('outfit.edit', ['masters' => $masters, 'outfit' => $outfit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outfit  $outfit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outfit $outfit)
    {
        if ($request->has('delete_outfit_photo')) {
            if ($outfit->photo) {
                $imageName = explode('/', $outfit->photo);
                $imageName = array_pop($imageName);
                $path = public_path() . '/outfits-images/'.$imageName;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $outfit->photo = null;
        }

        if ($request->has('outfit_photo')) {

            if ($outfit->photo) {
                $imageName = explode('/', $outfit->photo);
                $imageName = array_pop($imageName);
                $path = public_path() . '/outfits-images/'.$imageName;
                if (file_exists($path)) {
                    unlink($path);
                }

            }

            $photo = $request->file('outfit_photo');
            $imageName = 
            $request->outfit_type. '-' .
            $request->outfit_color. '-' .
            time(). '.' .
            $photo->getClientOriginalExtension();
            $path = public_path() . '/outfits-images/'; // serverio vidinis kelias
            $url = asset('outfits-images/'.$imageName); // url narsyklei (isorinis)
            $photo->move($path, $imageName); // is serverio tmp ===> i public folderi
            $outfit->photo = $url;
        }
        $outfit->type = $request->outfit_type;
        $outfit->color = $request->outfit_color;
        $outfit->size = $request->outfit_size;
        $outfit->about = $request->outfit_about;
        $outfit->master_id = $request->master_id;
        $outfit->save();
        return redirect()->route('outfit.index')->with('success_message', 'Outfit was edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outfit  $outfit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outfit $outfit)
    {
        if ($outfit->photo) {
            $imageName = explode('/', $outfit->photo);
            $imageName = array_pop($imageName);
            $path = public_path() . '/outfits-images/'.$imageName;
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $outfit->delete();
        return redirect()->route('outfit.index')->with('success_message', 'Outfit was deleted.');
       
    }

    public function pdf(Outfit $outfit)
    {
        // data yra outfit => outfit
        // $pdf = PDF::loadView('pdf.invoice', $data);
        // return $pdf->download('invoice.pdf');

        $pdf = PDF::loadView('outfit.pdf', ['outfit' => $outfit]);
        return $pdf->download($outfit->type.'.pdf');
        // return view('outfit.pdf', ['outfit' => $outfit]);
    }
}
