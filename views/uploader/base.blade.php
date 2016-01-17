@if ($type === 'image-single')
    @include('uploader::image-single', compact(
        'singleFile',
        'id',
        'imageWidth',
        'imageHeight',
        'removeHandler',
        'uploadHandler'
    ))
@endif