@php
$role = session('user_role');
$user_id = session('user_id');
@endphp




<h1>{{session('user_role')}}</h1>

<div class="contact-icons" style="z-index: 3;">
    <a href="{{ route('faq.index') }}" style="font-size:36px;color:blue" class="contact-icon email">
        <i class="fas fa-question-circle"></i>
    </a>
    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=thangmai0107@gmail.com" style="font-size:36px;color:blue" class="contact-icon email">
        <i class="fas fa-envelope"></i>
    </a>

    <div class="contact-icon chat" id="toggleChat">
        <i class="fab fa-facebook-messenger" style="font-size:36px;color:blue"></i>
    </div>
    <a href="https://zalo.me/0817007558" class="contact-icon zalo" style='font-size:36px;color:red'>
        <img width="60" height="60" src="https://img.icons8.com/plasticine/100/zalo.png" alt="zalo" />

    </a>
</div>

<iframe id="chatWidget"
    src="{{ route('chat.widget') }}"
    style="display: none; position: fixed; bottom: 5vh; right: 100px; width: 350px; height: 500px;  z-index: 9999; border-radius: 12px;">
</iframe>
<button class="close-chat" id="closeChat" style="display: none; position: fixed; bottom: 76vh; right: 150px;z-index: 112222;font-size: 30px;background: red;">✕</button>


<script>
    const role = "{{ session('user_role') }}";
    const toggleBtn = document.getElementById('toggleChat');
    const chatWidget = document.getElementById('chatWidget');
    const closeChatBtn = document.getElementById('closeChat');
    console.log('Current user role:', role);

    if (toggleBtn && chatWidget && closeChatBtn) {
        toggleBtn.addEventListener('click', function() {
            console.log('Toggle chat button clicked');

            if (role === 'admin') {
                console.log('Redirecting to admin chat');
                window.location.href = '{{ route("chat.admin") }}';
            } else if (role === 'user') {
                console.log('Toggling chat widget for user');
                closeChatBtn.style.display = (closeChatBtn.style.display === 'none') ? 'block' : 'none';
                chatWidget.style.display = (chatWidget.style.display === 'none') ? 'block' : 'none';
            } else {
                console.log('Non-user role detected');
                alert('Chỉ người dùng mới có thể sử dụng tính năng này!');
            }
        });
        closeChatBtn.addEventListener('click', function() {
            chatWidget.style.display = 'none';
            closeChatBtn.style.display = 'none';
        });
    } else {
        console.error('Toggle chat button not found');
    }


    window.addEventListener('message', function(event) {
        if (event.data === 'closeChatWidget') {
            chatWidget.style.display = 'none';
            closeChatBtn.style.display = 'none';
        }
    });
</script>
