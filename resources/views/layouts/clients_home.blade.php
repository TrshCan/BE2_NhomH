<!DOCTYPE html>
<html lang="en">

<head>
   <title>@yield('title', 'Trang chủ')</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="TechGear Shop Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6.5.2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"
        type="text/css">
  

   <link rel="stylesheet" href="{{ asset('assets/styles/main_styles.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/styles/contact_styles.css') }}">

</head>

<body>
   
    <div class="super_container">
 @include('clients.partials.header_home');
 <main>
    @yield('content')
 </main>

 @include('clients.partials.footer_home');

       


    </div>
    <!-- Scripts -->
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    


</body>

</html>