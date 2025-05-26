@extends('layout.template')

@section('content')
<div class="container mt-4">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
           // loop point data
           @foreach ( $points as $point)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $point->name }}</td>
                <td>{{ $point->description }}</td>
                <td><img src="{{ asset('storage/images/' . $point->image) }}" alt="" width="100"></td>
                <td>{{ $point->created_at }}</td>
                <td>{{ $point->updated_at }}</td>
            </tr>

           @endforeach
        </tbody>
    </table>
</div>
@endsection
