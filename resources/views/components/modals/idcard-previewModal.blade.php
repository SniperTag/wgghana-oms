<!-- Preview Staff ID Modal -->
<div class="modal fade" id="previewStaffModal" tabindex="-1" aria-labelledby="previewStaffLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="previewStaffLabel">Staff ID Preview</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center" id="staff-id-preview-content">
        <!-- Dynamic content will be injected here via AJAX -->
        <div class="text-muted">Loading...</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
