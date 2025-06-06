<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    protected $table = 'points';
    protected $guarded = ['id'];

    public function gejson_points()
    {
        $points = $this
        ->select(DB::raw('id, st_asgeojson (geom) as geom, name,
        description,image, created_at, updated_at'))
            ->get();

        $geojson = [
            'type'=> 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image'=> $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ],
            ];

            array_push($geojson['features'], $feature);

        }
        return($geojson);
    }

    public function gejson_point($id)
    {
        $points = $this
        ->select(DB::raw('id, st_asgeojson (geom) as geom, name,
        description,image, created_at, updated_at'))
            ->where('id', $id)
            ->get();

        $geojson = [
            'type'=> 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image'=> $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ],
            ];

            array_push($geojson['features'], $feature);

        }
        return($geojson);
    }
}

