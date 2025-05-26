<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolylinesModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PolylinesController extends Controller
{
    protected $polylines;

    public function __construct()
    {
        $this->polylines = new PolylinesModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Map',
        ];
        return view('map', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('polylines.create'); // Ensure the blade file exists
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validate request
         $request->validate(
            [
                'name' => 'required|unique:polylines,name',
                'description' => 'required',
                'geom_polyline' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:10240',
            ],
            [
                'name.required' => 'Name is Required',
                'name.unique' => 'Name Already Exists',
                'description.required' => 'Description is Required',
                'geom_polyline.required' => 'Geometry Polyline is Required',
            ]
        );

        // Create 'images' directory if it doesn't exist
        if (!is_dir(public_path('storage/images'))) {
            mkdir(public_path('storage/images'), 0777, true);
        }

        // Handle image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());

            // Store image using Storage facade
            $image->storeAs('images', $name_image, 'public');
        } else {
            $name_image = null;
        }

        // Prepare data for insertion
        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Create data in the database
        if (!$this->polylines->create($data)) {
            return redirect()->route('map')->with('error', 'Polyline failed to add');
        }

        //Redirect to Map
        return redirect()->route('map')->with('success', 'Polyline has been added');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Implement this if needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Polyline',
            'id' => $id,
        ];
        return view('edit-polyline', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validate request
        $request->validate(
            [
                'name' => 'required|unique:polylines,name,' . $id,
                'description' => 'required',
                'geom_polyline' => 'required',
                'image'=> 'nullable|mimes:jpeg,png,jpg,gif,svg|max:10000',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exist',
                'description.required' => 'Description is required',
                'geom_polyline.required' => 'Geometry polyline is required',
            ]
        );

        //Create Images directory if not exist
        if (!is_dir(public_path('storage/images'))) {
            mkdir(public_path('storage/images'), 0777, true);
        }

        //Get old image file
        $old_image = $this->polylines->find($id)->image;

        //Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->storeAs('images', $name_image, 'public');


        // Delete old image file
        if ($old_image != null) {
            if (File::exists('./storage/images/' . $old_image)) {
                unlink('./storage/images/' . $old_image);
            }
        }
        } else {
            $name_image = $old_image;
        }

        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' =>$name_image,
        ];

        //Create Data
        if (!$this->polylines->find($id)->update($data)) {
            return redirect()->route('map')->with('error', 'Polyline failed to update');
        }

        //Redirect to Map
        return redirect()->route('map')->with('success', 'Polyline has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->polylines->find($id)->image;

        if (!$this->polylines->destroy($id)) {
            return redirect()->route('map')->with('error', 'Polyline failed to delete');
        }

        // Delete image file
        if($imagefile != null){
            if (File::exists('./storage/images/' . $imagefile)) {
                unlink('./storage/images/' . $imagefile);
            }
        }
        return redirect()->route('map')->with('success', 'Polyline has been deleted');
    }
}
