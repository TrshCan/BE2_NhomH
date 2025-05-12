function applyCoupon() {
    const couponCode = document.getElementById('coupon-code').value.trim();
    const subtotalElement = document.getElementById('subtotal');
    const couponDiscountElement = document.getElementById('coupon-discount');
    const totalElement = document.getElementById('total');
    
    // Extract subtotal value from text (remove currency symbol and formatting)
    let subtotalText = subtotalElement.innerText;
    let subtotal = parseFloat(subtotalText.replace(/[^\d]/g, ''));
    
    let discount = 0;
    if (couponCode.toUpperCase() === 'SAVE10') {
        discount = subtotal * 0.1;
        alert('Mã giảm giá áp dụng thành công! Bạn được giảm 10%.');
    } else if (couponCode !== '') {
        alert('Mã giảm giá không hợp lệ.');
    }
    
    // Format with locale thousands separator and add currency symbol
    couponDiscountElement.innerText = discount.toLocaleString() + 'đ';
    const total = subtotal - discount;
    totalElement.innerText = total.toLocaleString() + 'đ';
    
    // Update hidden fields for form submission
    document.getElementById('discount-amount').value = discount;
    document.getElementById('total-amount').value = total;
}

document.addEventListener("DOMContentLoaded", function() {
    // Check if success modal should be shown
    const checkoutSuccess = document.body.getAttribute('data-checkout-success') === 'true';
    if (checkoutSuccess) {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        
        // Clear the session variable via an AJAX call
        fetch('/clear_checkout_success', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    }
    
    // Location data loading
    const provinceSelect = document.getElementById("province");
    const districtSelect = document.getElementById("district");
    const wardSelect = document.getElementById("ward");
    
    if (provinceSelect) {
        let locationData = [];
        
        async function loadLocationData() {
            try {
                const response = await fetch("/assets/nested-divisions.json");
                locationData = await response.json();
                populateProvinces();
            } catch (error) {
                console.error("Error loading location data:", error);
            }
        }
        
        function populateProvinces() {
            provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
            locationData.forEach(province => {
                const option = document.createElement("option");
                option.value = province.code;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        }
        
        function populateDistricts(provinceCode) {
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;
            
            if (!provinceCode) return;
            
            const selectedProvince = locationData.find(p => p.code == provinceCode);
            if (selectedProvince && selectedProvince.districts) {
                districtSelect.disabled = false;
                selectedProvince.districts.forEach(district => {
                    const option = document.createElement("option");
                    option.value = district.code;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            }
        }
        
        function populateWards(districtCode, provinceCode) {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = true;
            
            if (!districtCode || !provinceCode) return;
            
            const selectedProvince = locationData.find(p => p.code == provinceCode);
            const selectedDistrict = selectedProvince?.districts.find(d => d.code == districtCode);
            
            if (selectedDistrict && selectedDistrict.wards) {
                wardSelect.disabled = false;
                selectedDistrict.wards.forEach(ward => {
                    const option = document.createElement("option");
                    option.value = ward.code;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
            }
        }
        
        provinceSelect.addEventListener("change", function() {
            populateDistricts(this.value);
        });
        
        districtSelect.addEventListener("change", function() {
            populateWards(this.value, provinceSelect.value);
        });
        
        loadLocationData();
    }
});
