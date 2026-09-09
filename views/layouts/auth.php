<?php
/** @var string $content */
$title = $title ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title !== '' ? htmlspecialchars((string) $title) . ' - ' : '' ?>Saveur 221</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#A8291A', dark: '#8A2013', light: '#FBECEA' },
                    },
                    fontFamily: { sans: ['Open Sans', 'sans-serif'] },
                },
            },
        };
    </script>
    <style>
        * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { width: 0; height: 0; display: none; }

        .hero-photo {
            transition: transform .9s cubic-bezier(.2,.8,.2,1);
        }
        .hero-photo:hover {
            transform: scale(1.06) rotate(1deg);
        }
    </style>
</head>
<body class="font-sans h-screen bg-primary text-gray-800 flex">

    <aside class="relative hidden lg:flex flex-col w-[34%] shrink-0 h-screen overflow-hidden bg-gradient-to-br from-[#C43d1e] via-[#A8291A] to-[#7c1f0e]">

        <!-- fond décoratif : cercles flous -->
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-white/5 blur-2xl"></div>
        <div class="absolute bottom-0 -left-16 w-72 h-72 rounded-full bg-black/10 blur-2xl"></div>

        <!-- Logo -->
        <a href="/" class="relative z-20 inline-flex items-center gap-2.5 text-white font-extrabold text-2xl px-10 py-9">
            <span class="w-11 h-11 bg-white/15 rounded-xl flex items-center justify-center shadow-inner">
                <i class="fa-solid fa-utensils text-white text-lg"></i>
            </span>
            Saveur <span class="text-white">221</span>
        </a>

        <!-- Zone photos -->
        <div class="relative z-10 flex-1 flex flex-col items-center justify-center px-8">
            <div class="relative w-[340px] h-[420px]">
                <!-- halo derrière la composition -->
                <div class="absolute -inset-6 rounded-[3rem] bg-white/10 blur-2xl"></div>

                <!-- Anneau décoratif -->
                <div class="absolute -top-8 -left-8 w-24 h-24 rounded-full border-2 border-white/25"></div>
                <div class="absolute bottom-4 -right-6 w-16 h-16 rounded-full border-2 border-white/20"></div>

                <!-- Photo 1 -->
                <div class="absolute top-0 left-0 w-48 h-48 rounded-full overflow-hidden border-[6px] border-white/90 shadow-2xl hero-photo" style="box-shadow:0 25px 45px -12px rgba(0,0,0,.5);">
                    <img src="https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725692/saveur221/images/thieboudienne-rouge.jpg" alt="Thieboudienne"
                         class="w-full h-full object-cover">
                </div>

                <!-- Photo 2 -->
                <div class="absolute top-[136px] left-[120px] w-40 h-40 rounded-full overflow-hidden border-[6px] border-white/90 shadow-2xl hero-photo z-30" style="box-shadow:0 25px 45px -12px rgba(0,0,0,.5);">
                    <img src="https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725693/saveur221/images/yassa-poulet.jpg" alt="Yassa poulet"
                         class="w-full h-full object-cover">
                </div>

                <!-- Photo 3 -->
                <div class="absolute bottom-0 left-6 w-48 h-48 rounded-full overflow-hidden border-[6px] border-white/90 shadow-2xl hero-photo" style="box-shadow:0 25px 45px -12px rgba(0,0,0,.5);">
                    <img src="https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725696/saveur221/images/brochette-dibi.jpg" alt="Brochettes"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- Bandeau slogan -->
        <div class="relative z-10 px-10 pb-9 pt-4 text-center">
            <div class="inline-flex items-center gap-2 text-white/90 text-sm font-semibold">
                <i class="fa-solid fa-heart text-white/70"></i>
                La teranga à votre service.
                <i class="fa-solid fa-heart text-white/70"></i>
            </div>
        </div>
    </aside>

    <main class="relative flex-1 h-screen flex flex-col items-center px-6 py-6 overflow-y-auto bg-[#fffaf8]">
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-primary-light blur-3xl opacity-70 -translate-y-1/2 translate-x-1/2"></div>
        <?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
            <div class="relative w-full max-w-md mb-4 px-4 py-3 rounded-lg text-sm font-semibold text-white border border-white/30 <?= $flash['type'] === 'success' ? 'bg-emerald-600/40' : 'bg-red-950/50' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="relative w-full my-auto flex flex-col items-center">
            <?= $content ?>
        </div>
    </main>

</body>
</html>
