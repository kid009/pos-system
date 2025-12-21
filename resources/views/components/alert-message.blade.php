<div x-data="{
        notifications: [],
        add(message, type = 'success') {
            const id = Date.now();
            this.notifications.push({ id, message, type });
            // ตั้งเวลาลบออกอัตโนมัติ 5 วินาที
            setTimeout(() => {
                this.remove(id);
            }, 5000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }"
    @notify.window="add($event.detail.message, $event.detail.type)"
    class="position-fixed top-0 end-0 p-3"
    style="z-index: 1050; max-width: 350px;">

    <template x-for="note in notifications" :key="note.id">
        <div class="alert alert-dismissible fade show shadow-sm mb-2"
             :class="note.type === 'success' ? 'alert-success' : 'alert-danger'"
             role="alert"
             x-transition.duration.500ms>

            <div class="d-flex align-items-center">
                <i class="fas me-2" :class="note.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"></i>
                <span x-text="note.message"></span>
            </div>

            <button type="button" class="btn-close" @click="remove(note.id)"></button>
        </div>
    </template>
</div>
