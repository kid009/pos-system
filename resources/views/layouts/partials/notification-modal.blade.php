@if ($message = Session::get('success') ?? Session::get('error') ?? Session::get('warning'))
@php
$modalType = Session::get('success') ? 'success' : (Session::get('error') ? 'danger' : 'warning');
$modalTitle = Session::get('success') ? 'Success!' : (Session::get('error') ? 'Error!' : 'Warning!');
@endphp

<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-{{ $modalType }}-header">
                <h5 class="modal-title" id="notificationModalLabel">{{ $modalTitle }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $message }}
            </div>
            {{-- <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div> --}}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. หา Element ของ Modal
        var modalElement = document.getElementById('notificationModal');
        
        // 2. สร้าง Instance ของ Bootstrap Modal
        var notificationModal = new bootstrap.Modal(modalElement);
        
        // 3. สั่งให้ Modal แสดงขึ้นมา
        notificationModal.show();

        // 4. (ส่วนที่เพิ่มเข้ามา) ตั้งเวลา 5 วินาทีเพื่อซ่อน Modal
        setTimeout(function() {
            notificationModal.hide();
        }, 5000); // 5000 มิลลิวินาที = 5 วินาที
    });
</script>
@endif

