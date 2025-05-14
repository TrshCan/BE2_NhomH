// Hàm đếm ngược
function startCountdown(dealEndTime) {
    var countdown = setInterval(function() {
        var now = new Date().getTime();
        var distance = dealEndTime * 1000 - now; // Tính thời gian còn lại (ms)

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Hiển thị kết quả trong các phần tử trên trang
        document.getElementById("day").innerHTML = days;
        document.getElementById("hour").innerHTML = hours;
        document.getElementById("minute").innerHTML = minutes;
        document.getElementById("second").innerHTML = seconds;

        // Nếu thời gian còn lại là 0, dừng đếm ngược
        if (distance < 0) {
            clearInterval(countdown);
            document.getElementById("day").innerHTML = "00";
            document.getElementById("hour").innerHTML = "00";
            document.getElementById("minute").innerHTML = "00";
            document.getElementById("second").innerHTML = "00";
        }
    }, 1000); // Cập nhật mỗi giây
}