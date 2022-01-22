@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-end">
            <a href="{{route('books.create')}}" class="btn btn-primary"> Add new book </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">Release date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                <tr>
                    <th scope="row">{{ $loop->index + 1}} </th>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->release_date }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $books->links() }}
        </div>

    </div>
</div>
@endsection
