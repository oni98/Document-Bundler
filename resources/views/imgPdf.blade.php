
<div>
    @foreach ($image as $img)
        <img src="{{ storage_path('app/public/files/' . $img) }}" alt='{{ $img }}' style="width: 100%"><br>
    @endforeach

</div>
