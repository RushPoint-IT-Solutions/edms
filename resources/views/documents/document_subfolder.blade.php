<li>
    <a data-bs-toggle="collapse" href="#folder{{ $folder->id }}" role="button" aria-expanded="true" aria-controls="folder{{ $folder->id }}">
        <i class="ri-folder-2-line align-bottom me-2"></i> 
        <span class="file-list-link">{{ $folder->name }}</span>
    </a>

    <div class="collapse" id="folder{{ $folder->id }}">
        <ul class="sub-menu list-unstyled">
            @foreach ($folder->childrenFolder as $childrenFolder)
                @include('documents.document_subfolder', ['folder' => $childrenFolder])
            @endforeach
            
            @foreach ($folder->document as $document)
                <li>
                    <a href="{{ url('/documents/view-document/'.$document->id) }}" target="_blank">
                        <i class="ri-file-list-2-line align-bottom me-2"></i> 
                        <span class="file-list-link">{{ $document->control_code." - ".$document->title }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</li>