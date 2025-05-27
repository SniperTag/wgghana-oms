
<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p>What would you like to do?</p>
                    <button type="submit" name="action" value="check_in" class="btn btn-success m-2">Clock In</button>
                    <button type="submit" name="action" value="check_out" class="btn btn-danger m-2">Clock Out</button>
                </div>
            </div>
        </form>
    </div>
</div>
