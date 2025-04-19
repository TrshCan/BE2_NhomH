<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ qua Email và Zalo với Hiệu ứng</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    .contact-icons {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 20px;
        flex-direction: column;
        z-index: 10;
        width: fit-content;
        position: fixed;
        bottom: 20px;
        right: 15px;
    }

    .contact-icon {
        display: inline-block;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        text-decoration: none;

        transition: all 1s linear infinite;
    }

    @keyframes toa-email {
        0% {
            box-shadow: 0 0 0px 0px rgba(255, 0, 0, 0.8);
        }

        100% {
            box-shadow: 0 0 15px 5px rgba(255, 0, 0, 0.8);
        }
    }

    @keyframes toa-zalo {
        0% {
            box-shadow: 0 0 0px 0px rgba(0, 104, 255, 0.8);
        }

        100% {
            box-shadow: 0 0 15px 5px rgba(0, 104, 255, 0.8);
        }
    }

    .email {
        background-color: #ff0000;
    }

    .zalo {
        background-color: #0068ff;
    }

    .contact-icon:hover {

        box-shadow: 0 0 15px 5px rgba(255, 255, 255, 0.8);

        transform: scale(1.1);
    }

    .email:hover {
        animation: toa-email 0.33s linear infinite;
    }

    .zalo:hover {
        animation: toa-zalo 0.33s linear infinite;
    }
    </style>
</head>

<body>

</body>

</html>