@if (session('success'))
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    #successModal .modal-content {
        border-radius: 8px;
        border: none;
        background-color: #fff8f3;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    #successModal .modal-header {
        border-bottom: none;
        background-color: transparent;
        display: flex;
        align-items: center;
    }

    #successModal .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #d35400;
        display: flex;
        align-items: center;
    }

    #successModal .modal-title svg {
        width: 24px;
        height: 24px;
        margin-right: 0.5rem;
    }

    #successModal .modal-body {
        font-size: 1rem;
        color: #333;
        padding-top: 0;
    }

    #successModal .btn-custom {
        background-color: #e67e22;
        border: none;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 5px;
        font-weight: 500;
        transition: background-color 0.2s ease-in-out;
    }

    #successModal .btn-custom:hover {
        background-color: #d35400;
    }
</style>

<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">
                    <!-- Simple custom success icon (inline SVG) -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="#e67e22" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                    Thành công
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                {{ session('success') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-custom" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    });
</script>
@endif
