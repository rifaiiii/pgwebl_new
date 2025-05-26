<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointsModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;  // For file handling

class PointsController extends Controller
{
    protected $points;

    public function __construct()
    {
        $this->points = new PointsModel();
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
        // You can implement this if needed.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate(
            [
                'name' => 'required|unique:points,name',
                'description' => 'required',
                'geom_point' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_point.required' => 'Geometry point is required',
            ]
        );

        // Create 'images' directory if it doesn't exist
        if (!is_dir(public_path('storage/images'))) {
            mkdir(public_path('storage/images'), 0777, true);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());

            // Store image using Storage facade
            $image->storeAs('images', $name_image, 'public');
        } else {
            $name_image = null;
        }

        // Prepare data
        $data = [
            'geom' => $request->geom_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        //Create Data
        if (!$this->points->create($data)) {
            return redirect()->route('map')->with('error', 'Point failed to add');
        }

        //Redirect to Map
        return redirect()->route('map')->with('success', 'Point has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // You can implement this if needed.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Point',
            'id' => $id,
        ];
        return view('edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        //Validate request
        $request->validate(
            [
                'name' => 'required|unique:points,name,' . $id,
                'description' => 'required',
                'geom_point' => 'required',
                'image'=> 'nullable|mimes:jpeg,png,jpg,gif,svg|max:10000',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exist',
                'description.required' => 'Description is required',
                'geom_point.required' => 'Geometry point is required',
            ]
        );

        //Create Images directory if not exist
        if (!is_dir(public_path('storage/images'))) {
            mkdir(public_path('storage/images'), 0777, true);
        }

        //Get old image file
        $old_image = $this->points->find($id)->image;

        //Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
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
            'geom' => $request->geom_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' =>$name_image,
        ];

        //Create Data
        if (!$this->points->find($id)->update($data)) {
            return redirect()->route('map')->with('error', 'Point failed to update');
        }

        //Redirect to Map
        return redirect()->route('map')->with('success', 'Point has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->points->find($id)->image;

        if (!$this->points->destroy($id)) {
            return redirect()->route('map')->with('error', 'Point failed to delete');
        }

        // Delete image file
        if($imagefile != null){
            if (File::exists('./storage/images/' . $imagefile)) {
                unlink('./storage/images/' . $imagefile);
            }
        }
        return redirect()->route('map')->with('success', 'Point has been deleted');
    }
}
