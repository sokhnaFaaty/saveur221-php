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
                        primary: { DEFAULT: '#B83518', dark: '#8f2913', light: '#FDEEE9' },
                    },
                    fontFamily: { sans: ['Open Sans', 'sans-serif'] },
                },
            },
        };
    </script>
</head>
<body class="font-sans h-screen bg-primary text-gray-800 flex">

    <aside class="relative hidden lg:flex flex-col w-[30%] shrink-0 h-screen overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725699/saveur221/images/grillade-dibiterie.jpg')"></div>
        <div class="absolute inset-0 bg-black/40 pointer-events-none"></div>

        <a href="/" class="relative z-20 inline-flex items-center gap-2 text-white font-extrabold text-xl px-10 py-8">
            <span class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-utensils text-white text-sm"></i>
            </span>
            Saveur <span class="text-primary">221</span>
        </a>

        <div class="relative z-10 flex-1 flex items-center px-6">
            <div class="relative w-[340px] h-[400px]">
                <div class="absolute top-0 left-0 w-44 h-44 rounded-full overflow-hidden"
                     style="box-shadow:0 18px 35px -8px rgba(0,0,0,.55);">
                    <img src="https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725692/saveur221/images/thieboudienne-rouge.jpg" alt="Thieboudienne"
                         class="w-full h-full object-cover">
                </div>
                <div class="absolute top-36 left-28 w-36 h-36 rounded-full overflow-hidden"
                     style="box-shadow:0 18px 35px -8px rgba(0,0,0,.55);">
                    <img src="https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725693/saveur221/images/yassa-poulet.jpg" alt="Yassa poulet"
                         class="w-full h-full object-cover">
                </div>
                <div class="absolute top-[272px] left-8 w-32 h-32 rounded-full overflow-hidden"
                     style="box-shadow:0 18px 35px -8px rgba(0,0,0,.55);">
                    <img src="https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725696/saveur221/images/brochette-dibi.jpg" alt="Brochettes"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <p class="relative z-10 text-white text-sm text-center font-semibold px-6 py-8">La teranga a votre service.</p>
    </aside>

    <main class="relative flex-1 h-screen flex flex-col items-center px-6 py-6 overflow-y-auto">
        <?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
            <div class="w-full max-w-md mb-4 px-4 py-3 rounded-lg text-sm font-semibold text-white border border-white/30 <?= $flash['type'] === 'success' ? 'bg-emerald-600/40' : 'bg-red-950/50' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="w-full my-auto flex flex-col items-center">
            <?= $content ?>
        </div>
    </main>

</body>
</html>