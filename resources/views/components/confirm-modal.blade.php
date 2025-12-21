<div x-data="{
        show: false,
        title: 'Are you sure?',
        message: 'This action cannot be undone.',
        targetComponent: null,
        targetMethod: null,
        targetParams: null,

        openModal(event) {
            this.targetComponent = event.detail.component;
            this.targetMethod = event.detail.method;
            this.targetParams = event.detail.params;
            this.title = event.detail.title || 'Are you sure?';
            this.message = event.detail.message || 'You won\'t be able to revert this!';
            this.show = true;
        },

        confirmAction() {
            if (this.targetComponent && this.targetMethod) {
                Livewire.find(this.targetComponent).call(this.targetMethod, this.targetParams);
            }
            this.show = false;
        }
    }"
    @open-confirm-modal.window="openModal($event)"
    class="position-relative"
    style="z-index: 1060;">

    <div x-show="show"
         x-transition.opacity
         class="modal-backdrop fade show"
         style="background-color: rgba(0,0,0,0.5);"></div>

    <div x-show="show"
         x-transition.scale.origin.top
         class="modal fade"
         :class="{ 'show d-block': show }"
         style="display: none;"
         tabindex="-1" role="dialog">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i> <span x-text="title"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" @click="show = false"></button>
                </div>

                <div class="modal-body py-4 text-center">
                    <p class="text-gray-700 fs-5 mb-0" x-text="message"></p>
                </div>

                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" @click="show = false">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger px-4" @click="confirmAction()">
                        Yes, Delete it!
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
