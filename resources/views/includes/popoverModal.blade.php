<!-- Modal -->
<div class="modal fade" id="popoverInsertModal" tabindex="-1" role="dialog" aria-labelledby="popoverModalTitle"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popoverModalTitle">Insert popover</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="popoverTitle">Title</label>
                    <input type="text" name="popoverTitle" class="form-control" id="popoverTitle"
                           placeholder=" e.g. Lorum Ipsum dummy title">
                </div>
                <div class="form-group">
                    <label for="popoverContent">Content</label>
                    <input type="text" name="popoverContent" class="form-control" id="popoverContent"
                           placeholder=" e.g. Lorum Ipsum dummy content" required>
                </div>
                <div class="form-group">
                    <label for="popoverPlacement">Placement</label>
                    <select class="form-control" id="popoverPlacement" name="popperPlacement" id="popoverPlacement">
                        <option>top</option>
                        <option>right</option>
                        <option>bottom</option>
                        <option>left</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="popoverModalSaveBtn" data-dismiss="modal">Save
                </button>
            </div>
        </div>
    </div>
</div>