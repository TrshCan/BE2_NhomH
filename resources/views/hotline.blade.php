@php
$role = session('user_role'); 
$user_id = session('user_id'); 
@endphp

@extends('layouts.app')  

@section('content')
<h1>{{session('user_role')}}</h1>

<div class="contact-icons">
    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=thangmai0107@gmail.com" class="contact-icon email">
        <i class="fas fa-envelope"></i>
    </a>
    
    <div class="contact-icon chat" id="toggleChat">
    <i class='fab fa-facebook-messenger' style='font-size:36px;color:red'></i>
    </div>
    <a href="https://zalo.me/0817007558" class="contact-icon email">
    <i class="fas fa-comment-dots"></i>
    </a>
</div>

<iframe id="chatWidget"
    src="{{ route('chat.widget') }}"
    style="display: none; position: fixed; bottom: 90px; right: 20px; width: 350px; height: 500px; border: none; z-index: 9999; border-radius: 12px;">
</iframe>
<script>
    

        const role = "{{ session('user_role') }}";
        const toggleBtn = document.getElementById('toggleChat');
        const chatWidget = document.getElementById('chatWidget');
        
        console.log('Current user role:', role);
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                console.log('Toggle chat button clicked');
                
                if (role === 'admin') {
                    console.log('Redirecting to admin chat');
                    window.location.href = '{{ route("chat.admin") }}';
                } else if (role === 'user') {
                    console.log('Toggling chat widget for user');
                    chatWidget.style.display = (chatWidget.style.display === 'none') ? 'block' : 'none';
                } else {
                    console.log('Non-user role detected');
                    alert('Chỉ người dùng mới có thể sử dụng tính năng này!');
                }
            });
        } else {
            console.error('Toggle chat button not found');
        }
        
      
        window.addEventListener('message', function(event) {
            if (event.data === 'closeChatWidget') {
                chatWidget.style.display = 'none';
            }
        });
  
</script>
@endsection



