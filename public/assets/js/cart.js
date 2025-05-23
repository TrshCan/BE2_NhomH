function showMessage(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} position-fixed top-0 end-0 m-4 shadow`;
    alert.style.zIndex = 1050;
    alert.style.minWidth = '250px';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.body.appendChild(alert);

    // Auto-remove after 3 seconds
    setTimeout(() => alert.remove(), 3000);
}

function confirmDelete(id) {
    if (!window.confirm("Bạn có chắc chắn muốn xóa hoặc giảm số lượng sản phẩm này không?")) {
        return;
    }

    fetch(`/cart/delete/${id}`, {
        method: 'GET'  // Make sure to use the correct HTTP method (DELETE)
    })
        .then(res => res.json())
        .then(data => {
            showMessage(data.success || 'Đã cập nhật giỏ hàng.');
            if (data.redirect_url) {
                window.location.href = data.redirect_url;  // Redirect to the cart page
            }
        })
        .catch(() => showMessage('Lỗi khi xóa sản phẩm.', 'danger'));
}

function confirmDeleteAll() {
    if (!window.confirm("Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng không?")) {
        return;
    }

    fetch(`/cart/deleteall`, {
        method: 'GET'  // Correct HTTP method (DELETE)
    })
        .then(res => res.json())
        .then(data => {
            showMessage(data.success || 'Đã xóa toàn bộ giỏ hàng.');
            if (data.redirect_url) {
                window.location.href = data.redirect_url;  // Redirect to the cart page
            }
        })
        .catch(() => showMessage('Lỗi khi xóa toàn bộ giỏ hàng.', 'danger'));
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            let productId = this.getAttribute('data-id');
            let quantity = parseInt(this.value);

            if (quantity < 1) {
                alert('Số lượng phải lớn hơn hoặc bằng 1!');
                this.value = 1;
                quantity = 1;
            }

            fetch(`/cart/update_quantity/${productId}/${quantity}`, {
                method: 'GET'  // Use PATCH for updating quantity
            })
                .then(res => res.json())
                .then(data => {
                    showMessage(data.success || 'Đã cập nhật số lượng.');
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;  // Redirect to the cart page
                    }
                })
                .catch(() => showMessage('Lỗi khi cập nhật số lượng.', 'danger'));
        });
    });
});

function checkLoginStatus(isLoggedIn) {
    if (isLoggedIn) {
        window.location.href = "/checkout";
    } else {
        const loginModal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
        loginModal.show();
    }
}
