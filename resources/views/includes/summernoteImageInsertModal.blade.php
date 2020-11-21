<!-- Modal -->
<div class="modal fade" id="summernoteImageInsertModal" tabindex="-1" role="dialog"
     aria-labelledby="summernoteImageInsertModalTitle"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="summernoteImageInsertModalTitle">Insert Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-gallery" role="tablist">
                        <a class="nav-item nav-link active" id="nav-gallery-images-tab" data-toggle="tab"
                           href="#nav-gallery-images" role="tab" aria-controls="nav-gallery-images"
                           aria-selected="true">Images</a>
                        <a class="nav-item nav-link" id="nav-gallery-upload-tab" data-toggle="tab"
                           href="#nav-gallery-upload" role="tab" aria-controls="nav-gallery-upload"
                           aria-selected="false">Upload</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-gallery">
                    <div class="tab-pane fade show active" id="nav-gallery-images" role="tabpanel"
                         aria-labelledby="nav-gallery-images-tab">
                        <div id="imagePreviewContainer"></div>
                        <hr class="divider"/>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="insertImageSaveBtn" data-dismiss="modal">
                                Insert
                            </button>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="nav-gallery-upload" role="tabpanel"
                         aria-labelledby="nav-gallery-upload-tab">
                        <form action="{{config('prototype.photo_url')}}"
                              onsubmit="event.preventDefault();uploadImages(this)"
                              method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="mb-3">
                                <input type="file" name="file[]" accept="image/*" class="form-control"
                                       onchange="readURLs(this,'uploadedImagePreviewBox')" multiple/>
                            </div>
                            <div id="uploadedImagePreviewBox" class="row"></div>
                            <hr class="divider"/>
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" name="action" value="upload">
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
