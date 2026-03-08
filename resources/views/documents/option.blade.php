<option value='{{$folder->id}}' @if(old('folder') == $folder->id) selected @endif>
    {!! str_repeat("&nbsp;&nbsp;&nbsp;", $level) !!} {{$folder->name}}
</option>

@if(count($folder->childrenFolder) > 0)
    @foreach ($folder->childrenFolder as $childrenFolder)
        @include("documents.option", ['folder' => $childrenFolder, 'level' => $level + 1])
    @endforeach
@endif