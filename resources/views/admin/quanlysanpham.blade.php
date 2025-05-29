@extends('layouts.admin')

@section('content')
    <!-- Meta tag for CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý sản phẩm</h2>
            <button id="openAddModal"
                class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i> Thêm sản phẩm
            </button>
        </div>
        @if (session('error'))
            <div class="alert alert-danger mt-3" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sản
                            phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nổi bật
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thương
                            hiệu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng
                            bán</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->product_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->product_name }}">
                                {{ $item->product_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->description }}">
                                {{ $item->description }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ number_format($item->price, 0, ',', '.') }} VND
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->is_featured ? 'Có' : 'Không' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->stock_quantity }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate"
                                title="{{ $item->category->category_name ?? 'Không có danh mục' }}">
                                {{ $item->category->category_name ?? 'Không có danh mục' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate"
                                title="{{ $item->brand->name ?? 'Không có thương hiệu' }}">
                                {{ $item->brand->name ?? 'Không có thương hiệu' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <img src="{{ asset('assets/images/' . $item->image_url) }}"
                                    alt="{{ $item->product_name }}" class="h-10 w-10 rounded object-cover"
                                    onerror="this.src='{{ asset('assets/images/camera.png') }}'; this.alt='Hình ảnh không khả dụng';">
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->sales_count }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-teal-500 hover:text-teal-600 mr-4 edit-product"
                                    data-id="{{ $item->product_id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-600 delete-product"
                                    data-id="{{ $item->product_id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500">Không có sản phẩm nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modal for Add/Edit Product -->
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm sản phẩm</h3>
            <form id="productForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tên sản phẩm</label>
                    <input type="text" name="product_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                    <textarea name="description"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Giá</label>
                    <input type="number" name="price"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        step="0.01" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nổi bật</label>
                    <select name="is_featured"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                        <option value="0">Không</option>
                        <option value="1">Có</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tồn kho</label>
                    <input type="number" name="stock_quantity"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Danh mục</label>
                    <select name="category_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                        <option value="">Chọn danh mục</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Thương hiệu</label>
                    <select name="brand_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                        <option value="">Chọn thương hiệu</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Hình ảnh</label>
                    <input type="file" name="image" accept="image/*"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                    <input type="hidden" name="image_url" id="image_url">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Số lượng bán</label>
                    <input type="number" name="sales_count"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Hủy</button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="notificationTitle" class="text-lg font-semibold text-gray-800 mb-4">Thông báo</h3>
            <p id="notificationMessage" class="text-sm text-gray-600 mb-4"></p>
            <div class="flex justify-end space-x-2">
                <button id="reloadPage"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 hidden">Tải
                    lại trang</button>
                <button id="closeNotificationModal"
                    class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">Đóng</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openAddModal = document.getElementById('openAddModal');
            const productModal = document.getElementById('productModal');
            const closeModal = document.getElementById('closeModal');
            const modalTitle = document.getElementById('modalTitle');
            const productForm = document.getElementById('productForm');
            const editButtons = document.querySelectorAll('.edit-product');
            const deleteButtons = document.querySelectorAll('.delete-product');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationModal = document.getElementById('closeNotificationModal');
            const reloadPageButton = document.getElementById('reloadPage');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            let currentProductId = null;
            let shouldReload = false;
            let isSubmitting = false; // Biến để kiểm soát trạng thái gửi form
            let abortController = null; // Để hủy yêu cầu AJAX trước đó

            function showAlert(message, type = 'success') {
                notificationTitle.textContent = type === 'success' ? 'Thành công' : 'Lỗi';
                notificationMessage.textContent = message;
                notificationMessage.className =
                    `text-sm mb-4 ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
                reloadPageButton.classList.toggle('hidden', type !== 'error' || !message.includes('tải lại trang'));
                notificationModal.classList.remove('hidden');
            }

            function closeNotificationAndReload() {
                notificationModal.classList.add('hidden');
                if (shouldReload) {
                    location.reload();
                }
            }

            // Hàm để cập nhật trạng thái nút Lưu
            function updateSubmitButtonState(disable = true) {
                const submitButton = productForm.querySelector('button[type="submit"]');
                if (disable) {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
                } else {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = 'Lưu';
                }
            }

            openAddModal.addEventListener('click', () => {
                modalTitle.textContent = 'Thêm sản phẩm';
                productForm.reset();
                currentProductId = null;
                document.getElementById('formMethod').value = 'POST';
                productForm.querySelector('[name="image"]').setAttribute('required', 'required');
                let updatedAtInput = productForm.querySelector('[name="updated_at"]');
                if (updatedAtInput) {
                    updatedAtInput.remove();
                }
                productModal.classList.remove('hidden');
            });

            closeModal.addEventListener('click', () => {
                productModal.classList.add('hidden');
                updateSubmitButtonState(false); // Reset trạng thái nút khi đóng modal
            });

            productModal.addEventListener('click', (e) => {
                if (e.target === productModal) {
                    productModal.classList.add('hidden');
                    updateSubmitButtonState(false); // Reset trạng thái nút khi đóng modal
                }
            });

            closeNotificationModal.addEventListener('click', closeNotificationAndReload);

            reloadPageButton.addEventListener('click', () => {
                location.reload();
            });

            notificationModal.addEventListener('click', (e) => {
                if (e.target === notificationModal) {
                    closeNotificationAndReload();
                }
            });

            productForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Nếu đang gửi yêu cầu, không cho phép gửi thêm
                if (isSubmitting) {
                    return;
                }

                if (!productForm.checkValidity()) {
                    showAlert('Vui lòng điền đầy đủ các trường bắt buộc!', 'error');
                    productForm.reportValidity();
                    return;
                }

                const formData = new FormData(productForm);
                const categoryId = formData.get('category_id');
                const brandId = formData.get('brand_id');
                const isFeatured = formData.get('is_featured');
                formData.set('is_featured', isFeatured === '1' ? '1' : '0');

                if (!categoryId || !brandId) {
                    showAlert('Vui lòng chọn danh mục và thương hiệu!', 'error');
                    return;
                }

                console.log('Form Data:', Object.fromEntries(formData));

                const url = currentProductId ?
                    `{{ route('admin.products.update', ['id' => ':id']) }}`.replace(':id',
                        currentProductId) :
                    `{{ route('admin.products.store') }}`;
                const method = currentProductId ? 'POST' : 'POST';

                // Vô hiệu hóa nút Lưu và hiển thị trạng thái xử lý
                isSubmitting = true;
                updateSubmitButtonState(true);

                // Hủy yêu cầu trước đó nếu có
                if (abortController) {
                    abortController.abort();
                }
                abortController = new AbortController();

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: formData,
                        signal: abortController.signal
                    });

                    const result = await response.json();
                    console.log('Response:', result);
                    if (response.ok && result.success) {
                        shouldReload = true;
                        showAlert(result.message, 'success');
                        productModal.classList.add('hidden');
                    } else if (response.status === 409) {
                        showAlert(result.message ||
                            'Sản phẩm đã được chỉnh sửa bởi người khác. Vui lòng tải lại trang để cập nhật dữ liệu mới nhất.',
                            'error');
                    } else if (response.status === 422 && result.errors) {
                        const errorMessage = Object.values(result.errors).flat().join('\n');
                        showAlert(errorMessage, 'error');
                    } else {
                        showAlert(result.message || 'Có lỗi xảy ra!', 'error');
                    }
                } catch (error) {
                    if (error.name === 'AbortError') {
                        console.log('Yêu cầu trước đó đã bị hủy');
                    } else {
                        console.error('Error:', error);
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                } finally {
                    // Kích hoạt lại nút Lưu và reset trạng thái
                    isSubmitting = false;
                    updateSubmitButtonState(false);
                    abortController = null;
                }
            });

            editButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    const productId = button.dataset.id;
                    currentProductId = productId;
                    modalTitle.textContent = 'Chỉnh sửa sản phẩm';

                    try {
                        const response = await fetch(
                            `{{ route('admin.products.show', ['id' => ':id']) }}`.replace(
                                ':id', productId), {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                        const text = await response.text();
                        console.log('Raw Response:', text);

                        let product;
                        try {
                            product = JSON.parse(text);
                            console.log('Parsed Product:', product);
                        } catch (parseError) {
                            console.error('Failed to parse JSON:', parseError);
                            showAlert('Dữ liệu từ server không hợp lệ: ' + parseError.message,
                                'error');
                            return;
                        }

                        if (response.ok) {
                            if (!product.product_name || !product.description || !product
                                .price ||
                                product.is_featured === null || !product.stock_quantity ||
                                !product.category_id || !product.brand_id || !product
                                .sales_count) {
                                showAlert(
                                    'Dữ liệu sản phẩm không đầy đủ,Dữ liệu không được rỗng ',
                                    'error');
                                return;
                            }

                            const form = productForm;
                            form.querySelector('[name="product_name"]').value = product
                                .product_name || '';
                            form.querySelector('[name="description"]').value = product
                                .description || '';
                            form.querySelector('[name="price"]').value = product.price || '';
                            form.querySelector('[name="is_featured"]').value = product
                                .is_featured ? '1' : '0';
                            form.querySelector('[name="stock_quantity"]').value = product
                                .stock_quantity || '';
                            form.querySelector('[name="category_id"]').value = product
                                .category_id || '';
                            form.querySelector('[name="brand_id"]').value = product.brand_id ||
                                '';
                            form.querySelector('[name="image_url"]').value = product
                                .image_url || '';
                            form.querySelector('[name="sales_count"]').value = product
                                .sales_count || '';

                            let updatedAtInput = form.querySelector('[name="updated_at"]');
                            if (!updatedAtInput) {
                                updatedAtInput = document.createElement('input');
                                updatedAtInput.type = 'hidden';
                                updatedAtInput.name = 'updated_at';
                                form.appendChild(updatedAtInput);
                            }
                            updatedAtInput.value = product.updated_at || '';

                            form.querySelector('[name="image"]').removeAttribute('required');
                            form.querySelector('[name="image"]').value = '';
                            document.getElementById('formMethod').value = 'PUT';
                            productModal.classList.remove('hidden');
                        } else {
                            showAlert(product.message || 'Không thể tải thông tin sản phẩm!',
                                'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                });
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    const productId = button.dataset.id;
                    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

                    try {
                        const response = await fetch(
                            `{{ route('admin.products.destroy', ['id' => ':id']) }}`
                            .replace(':id', productId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                }
                            });

                        const result = await response.json();
                        if (response.ok && result.success) {
                            shouldReload = true;
                            showAlert(result.message, 'success');
                        } else {
                            showAlert(result.message || 'Có lỗi xảy ra!', 'error');
                        }
                    } catch (error) {
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                });
            });
        });
    </script>
@endsection
