<li>
    {{ $head->name }}
    <button type="button" data-id="{{ $head->id }}" class="btn btn-info edit">Edit</button>
    <a href="{{ route('delete', $head->id) }}" class="btn btn-danger">Delete</a>

    @if ($head->children->count())
        <ul>
            @foreach ($head->children as $child)
                @include('head', ['head' => $child])
            @endforeach
        </ul>
    @endif
</li>
