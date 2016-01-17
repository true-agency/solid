
<div
    id="fileUpload_{{$id}}"
    class="field-fileupload style-image-single <?= $singleFile ? 'is-populated' : '' ?>"
    data-control="fileupload"
    data-template="#<?= $id ?>"
    data-error-template="#uploadTemplateError"
    data-unique-id="<?= $id ?>"
    data-thumbnail-width="<?= $imageWidth ?: '0' ?>"
    data-thumbnail-height="<?= $imageHeight ?: '0' ?>"
    data-url="{{$uploadHandler}}"
>

    <!-- Add New Image -->
    <a
        href="javascript:;"
        style="width: <?= $imageWidth ?>px; height: <?= $imageHeight ?>px"
        class="upload-button">
        <span class="upload-button-icon fa fa-plus"></span>
    </a>

    <!-- Existing file -->
    <div class="upload-files-container">
        <?php if ($singleFile): ?>
            <div class="upload-object is-success" data-id="<?= $singleFile->id ?>" data-path="<?= $singleFile->pathUrl ?>">
                <div class="icon-container image">
                    <img src="<?= $singleFile->thumbUrl ?>" alt="" />
                </div>
                <div class="info">
                    <h4 class="filename">
                        <span data-dz-name><?= e($singleFile->title ?: $singleFile->file_name) ?></span>
                        <a
                            href="javascript:;"
                            class="upload-remove-button"
                            data-request="<?= $removeHandler ?>"
                            data-request-confirm="<?= 'Are you sure?' ?>"
                            data-request-data="file_id: <?= $singleFile->id ?>"
                            ><i class="fa fa-times"></i></a>
                    </h4>
                </div>
                <div class="meta"></div>
            </div>
        <?php endif ?>
    </div>

</div>

<!-- Template for new file -->
<script type="text/template" id="{{ $id }}">
    <div class="upload-object dz-preview dz-file-preview">
        <div class="icon-container image">
            <img data-dz-thumbnail style="" alt="" />
        </div>
        <div class="info">
            <h4 class="filename">
                <span data-dz-name></span>
                <a
                    href="javascript:;"
                    class="upload-remove-button"
                    data-request="{{$removeHandler}}"
                    data-request-confirm="Are you sure?"
                    ><i class="fa fa-times"></i></a>
            </h4>
            <p class="size" data-dz-size></p>
        </div>
        <div class="meta">
            <div class="progress-bar"><span class="upload-progress" data-dz-uploadprogress></span></div>
            <div class="error-message"><span data-dz-errormessage></span></div>
        </div>
    </div>
</script>

@include ('uploader.partials.template-error')