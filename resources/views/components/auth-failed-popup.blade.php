@if (session('error_auth'))
<!-- Bootstrap CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    #authModal .modal-content {
        border-radius: 8px;
        background-color: #fff7f3;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    #authModal .modal-header {
        background-color: transparent;
        border-bottom: none;
    }

    #authModal .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #e74c3c;
        display: flex;
        align-items: center;
    }

    #authModal .modal-title svg {
        width: 24px;
        height: 24px;
        margin-right: 0.5rem;
    }

    #authModal .modal-body {
        font-size: 1rem;
        color: #333;
    }

    #authModal .btn-login {
        background-color: #e67e22;
        border: none;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 5px;
        font-weight: 500;
    }

    #authModal .btn-login:hover {
        background-color: #d35400;
    }
</style>

<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12" y2="16" />
                    </svg>
                    Cần đăng nhập
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                {{ session('error_auth') }}
            </div>
            <div class="modal-footer">
                <a href="{{ route('login') }}" class="btn btn-login">Đăng nhập</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('authModal'));
        modal.show();
    });
</script>
@endif
