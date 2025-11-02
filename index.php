<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'data.php';
require_once 'functions.php';

$selectedCategory = $_GET['category'] ?? 'Все';
?>
<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Каталог Telegram Ботов</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
      }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen text-slate-800 dark:text-white font-sans transition-colors duration-300">
    <?php echo renderHeader(); ?>
    <main class="container mx-auto px-4 py-8">
        <?php echo renderFeaturedBots($featuredBots); ?>
        <?php echo renderCategories($categories, $bots, $selectedCategory); ?>
        <?php echo renderBotCatalog($bots, $selectedCategory); ?>
    </main>
    <footer class="text-center py-6 text-slate-500 dark:text-slate-500 text-sm">
        <p>Создано с ❤️ для сообщества Telegram</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
