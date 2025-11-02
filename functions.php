<?php

function renderHeader() {
$html = <<<HTML
    <header class="bg-white/80 dark:bg-slate-800/50 backdrop-blur-sm sticky top-0 z-50 shadow-md shadow-slate-200/50 dark:shadow-lg dark:shadow-slate-900/20">
      <div class="container mx-auto px-4 py-4 flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center space-x-3 mb-4 md:mb-0">
           <svg class="w-10 h-10 text-cyan-500 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
           <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-wider">
             Каталог <span class="text-cyan-500 dark:text-cyan-400">Telegram</span> Ботов
           </h1>
        </div>
        <div class="flex items-center w-full md:w-auto">
            <div class="relative w-full md:w-64 lg:w-80 mr-4">
                <input
                    type="text"
                    placeholder="Найти бота..."
                    class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-full py-2 pl-10 pr-4 text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-400">
                    <svg
    xmlns="http://www.w3.org/2000/svg"
    class="h-5 w-5"
    fill="none"
    viewBox="0 0 24 24"
    stroke="currentColor"
    stroke-width="2"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
    />
  </svg>
                </div>
            </div>
             <button
                id="theme-toggle"
                aria-label="Toggle theme"
                class="p-2 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors"
            >
                <svg id="theme-icon-sun" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <svg id="theme-icon-moon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
            </button>
        </div>
      </div>
    </header>
HTML;
return $html;
}

function renderBotCatalog($bots, $selectedCategory) {
  $filteredBots = $selectedCategory === 'Все' ? $bots : array_filter($bots, function ($bot) use ($selectedCategory) {
    return $bot['category'] === $selectedCategory;
  });

  $botCards = '';
  if (count($filteredBots) > 0) {
    foreach ($filteredBots as $bot) {
      $botCards .= renderBotCard($bot);
    }
  } else {
    $botCards = '
      <div class="text-center py-16 px-4 bg-slate-100 dark:bg-slate-800 rounded-lg col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4">
          <p class="text-slate-500 dark:text-slate-400 text-lg">В этой категории пока нет ботов.</p>
      </div>';
  }
  
  $html = <<<HTML
    <section>
       <div class="flex items-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-500 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
    </svg>
        <h2 class="text-2xl md:text-3xl font-bold ml-2 text-slate-700 dark:text-slate-200">Каталог ботов</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {$botCards}
      </div>
    </section>
HTML;
return $html;
}

function renderCategories($categories, $bots, $selectedCategory) {
  $counts = array_reduce($bots, function ($acc, $bot) {
    $acc[$bot['category']] = ($acc[$bot['category']] ?? 0) + 1;
    return $acc;
  }, []);

  $categoriesWithCounts = array_map(function ($category) use ($counts, $bots) {
    return [
      'name' => $category,
      'count' => $category === 'Все' ? count($bots) : ($counts[$category] ?? 0)
    ];
  }, $categories);

  usort($categoriesWithCounts, function ($a, $b) {
      if ($a['name'] === 'Все') return -1;
      if ($b['name'] === 'Все') return 1;
      return $b['count'] <=> $a['count'];
  });
  
  $showAll = isset($_GET['show_all']);
  $displayedCategories = $showAll ? $categoriesWithCounts : array_slice($categoriesWithCounts, 0, 8);

  $categoryButtons = '';
  foreach ($displayedCategories as $category) {
    $isSelected = $selectedCategory === $category['name'];
    $classes = 'px-4 py-2 rounded-full text-sm font-semibold transition-all duration-300 transform hover:scale-105 ' . ($isSelected ? 'bg-cyan-500 text-white shadow-lg shadow-cyan-500/20' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600');
    $categoryButtons .= "
      <a href=\"?category={$category['name']}\" class=\"{$classes}\">
        {$category['name']} <span class=\"text-xs opacity-75\">({$category['count']})</span>
      </a>";
  }

  if (count($categoriesWithCounts) > 8 && !$showAll) {
    $categoryButtons .= '
      <a href="?show_all=true" class="px-4 py-2 rounded-full text-sm font-semibold transition-all duration-300 transform hover:scale-105 bg-transparent border border-cyan-500 text-cyan-500 hover:bg-cyan-500/10">
        Показать все
      </a>';
  }

  $html = <<<HTML
    <section class="mb-12">
      <h2 class="text-2xl md:text-3xl font-bold mb-6 text-slate-700 dark:text-slate-200">Категории</h2>
      <div class="flex flex-wrap gap-2">
        {$categoryButtons}
      </div>
    </section>
HTML;
return $html;
}

function renderFeaturedBots($bots) {
  $botCards = '';
  foreach ($bots as $bot) {
    $botCards .= renderBotCard($bot, true);
  }

  $html = <<<HTML
    <section class="mb-12">
      <div class="flex items-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
  </svg>
        <h2 class="text-2xl md:text-3xl font-bold ml-2 text-yellow-500 dark:text-yellow-400">Платное размещение</h2>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {$botCards}
      </div>
    </section>
HTML;
return $html;
}

function renderBotCard($bot, $isFeatured = false) {
  $cardClasses = 'h-full flex flex-col rounded-xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl ' . ($isFeatured ? 'bg-gradient-to-br from-slate-100 to-white dark:from-slate-800 dark:to-slate-900 border-2 border-yellow-400/50 shadow-lg shadow-yellow-500/5 dark:shadow-yellow-500/10' : 'bg-white dark:bg-slate-800 shadow-lg shadow-slate-200/50 dark:shadow-black/20');
  $username = substr($bot['username'], 1);
  
  $html = '<div class="' . $cardClasses . '">';
  $html .= '<div class="p-5 flex flex-col flex-grow">';
  $html .= '<div class="flex items-center mb-4">';
  $html .= '<img src="' . $bot['avatarUrl'] . '" alt="' . $bot['name'] . ' avatar" class="w-16 h-16 rounded-full border-2 border-slate-200 dark:border-slate-600 object-cover mr-4" />';
  $html .= '<div>';
  $html .= '<h3 class="text-lg font-bold text-slate-900 dark:text-white">' . $bot['name'] . '</h3>';
  $html .= '<p class="text-sm text-cyan-500 dark:text-cyan-400">' . $bot['username'] . '</p>';
  $html .= '</div>';
  $html .= '</div>';
  $html .= '<p class="text-slate-600 dark:text-slate-400 text-sm mb-4 flex-grow">' . $bot['description'] . '</p>';
  $html .= '<div class="text-xs text-slate-500 dark:text-slate-500 font-medium mb-4">';
  $html .= '<span class="bg-slate-100 dark:bg-slate-700/50 rounded-full px-2 py-1">' . $bot['category'] . '</span>';
  $html .= '</div>';
  $html .= '</div>';
  $html .= '<div class="bg-slate-50 dark:bg-slate-700/50 px-5 py-3 mt-auto">';
  $html .= '<a href="https://t.me/' . $username . '" target="_blank" rel="noopener noreferrer" class="w-full block text-center bg-cyan-600 text-white font-bold py-2 rounded-lg hover:bg-cyan-500 transition-colors duration-300">';
  $html .= 'Добавить';
  $html .= '</a>';
  $html .= '</div>';
  $html .= '</div>';
  
  return $html;
}
