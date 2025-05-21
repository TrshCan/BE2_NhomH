<!DOCTYPE html>
<html lang="vi">


<body>

</body>

</html>
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
      <link rel="stylesheet" href="{{ asset('assets/styles/single_styles.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/styles/categories_styles.css') }}">


</head>
<style>
    .carousel-control-prev,
    .carousel-control-next {
        background-color: rgba(0, 0, 0, 0.7); /* Black with 70% opacity for visibility */
        width: 60px; /* Slightly larger for prominence */
        height: 60px;
        border-radius: 50%; /* Circular buttons */
        top: 50%;
        transform: translateY(-50%); /* Vertically centered */
        transition: background-color 0.3s ease; /* Smooth hover effect */
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background-color: rgba(0, 0, 0, 0.9); /* Darker on hover */
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-image: none; /* Remove default Bootstrap arrow image */
        position: relative;
    }

    /* Custom white arrows for contrast */
    .carousel-control-prev-icon::before,
    .carousel-control-next-icon::before {
        content: '';
        display: block;
        width: 20px; /* Arrow size */
        height: 20px;
        border: solid #ffffff; /* White arrows */
        border-width: 0 4px 4px 0; /* Thicker arrow */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(135deg); /* Right arrow */
    }

    .carousel-control-prev-icon::before {
        transform: translate(-50%, -50%) rotate(-45deg); /* Left arrow */
    }
</style>

<body>
   
    <div class="super_container">
 @include('clients.partials.header');
 <main>
    @yield('content')
 </main>

 @include('clients.partials.footer');

       


    </div>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/categories_custom.js') }}"></script>
  


</body>

</html>