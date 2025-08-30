<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" id="confirmDeleteButton">Delete</button>
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var formToSubmit; // ตัวแปรสำหรับเก็บฟอร์มที่จะถูก submit

        // 1. เมื่อ Modal กำลังจะถูกเปิด...
        $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // ปุ่มที่ถูกคลิกเพื่อเปิด Modal
            var formId = button.data('form-id'); // ดึงค่า form-id จาก data attribute ของปุ่ม
            formToSubmit = $('#' + formId); // หาฟอร์มจาก ID ที่ได้มา แล้วเก็บไว้ในตัวแปร
        });

        // 2. เมื่อปุ่ม "Delete" ใน Modal ถูกคลิก...
        $('#confirmDeleteButton').on('click', function () {
            if (formToSubmit) {
                formToSubmit.submit(); // สั่งให้ฟอร์มที่เก็บไว้ทำงาน (submit)
            }
        });
    });
</script>