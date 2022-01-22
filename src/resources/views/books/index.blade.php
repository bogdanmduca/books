@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-end">
            <a href="{{route('books.create')}}" class="btn btn-primary"> Add new book </a>
        </div>
        @if(session('message'))
        <div class="alert alert-secondary mt-4" role="alert">
            {{session('message')}}
        </div>
        @endif

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
                    <th scope="row">
                        <a href="/api/books/{{$book->id}}"> {{ $book->id }} </a>
                    </th>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->release_date }}</td>
                    <td class="d-flex justify-content-end">
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Do you really want to remove the book?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1">Remove</button>
                        </form>
                    </td>
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
