<!-- Modal -->
<div class="modal fade" id="tooltipInsertModal" tabindex="-1" role="dialog" aria-labelledby="tooltipModalTitle"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tooltipModalTitle">Insert Tooltip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="tooltipTitle">Title</label>
                    <input type="text" name="tooltipTitle" class="form-control" id="tooltipTitle"
                           placeholder=" e.g. Lorum Ipsum dummy text" required>
                </div>
                <div class="mb-3">
                    <label>Placement</label>
                    <select class="form-control" id="tooltipPlacement" name="tooltipPlacement">
                        <option>top</option>
                        <option>right</option>
                        <option>bottom</option>
                        <option>left</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="tooltipModalSaveBtn" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
