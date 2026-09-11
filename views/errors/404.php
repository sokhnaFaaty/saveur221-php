<?php /** @var string $title */ ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page introuvable | Saveur 221</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#A8291A',
                            dark: '#8A2013',
                            light: '#FBECEA'
                        },
                        bgdash: '#F0F6FF',
                    },
                    fontFamily: {
                        sans: ['Open Sans', 'sans-serif']
                    },
                },
            },
        };
    </script>
    <style>
        * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { width: 0; height: 0; display: none; }
    </style>
</head>

<body class="font-sans text-gray-800 bg-gradient-to-br from-primary-light via-white to-white min-h-screen flex items-center justify-center p-6">

    <main class="w-full max-w-xl text-center">

        <div class="relative inline-block mb-8">
            <span class="text-[140px] font-extrabold leading-none text-primary/15 select-none">404</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="w-20 h-20 rounded-2xl bg-primary shadow-lg shadow-primary/30 flex items-center justify-center text-white">
                    <i class="fa-solid fa-utensils text-3xl"></i>
                </span>
            </div>
        </div>

        <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Oups ! Page introuvable</h1>
        <p class="text-gray-500 mb-2">La page que vous recherchez n'existe pas ou a été déplacée.</p>
        <p class="text-gray-400 text-sm mb-8">Vérifiez l'adresse... ou découvrez notre carte de plats sénégalais.</p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="/" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-primary text-white font-bold text-sm hover:bg-primary-dark transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-house"></i> Retour à l'accueil
            </a>
            <a href="/catalogue" class="w-full sm:w-auto px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold text-sm hover:border-primary hover:text-primary transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-book-open"></i> Voir le catalogue
            </a>
        </div>

        <p class="mt-10 text-xs text-gray-400">
            <span class="font-extrabold text-gray-600">Saveur 221</span> — La haute gastronomie sénégalaise
        </p>

    </main>

</body>

</html>
