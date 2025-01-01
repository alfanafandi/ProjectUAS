<?php
$query = isset($_GET['query']) ? $_GET['query'] : '';
$restaurants = [];

if ($query) {
    $json = file_get_contents('../../json/restorans.json');
    $data = json_decode($json, true);
    foreach ($data['restorans'] as $restaurant) {
        if (stripos($restaurant['restoran_nama'], $query) !== false) {
            $restaurants[] = $restaurant;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/@heroicons/react/outline" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <header class="sticky top-0 bg-white z-10">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
    </header>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Cari Restoran</h1>
        <form action="/views/customer/search.php" method="GET" class="mb-4 flex items-center">
            <span class="bg-gray-200 px-3 py-2 rounded-l-full h-full flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.387a1 1 0 01-1.414 1.414l-4.387-4.387zM8 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                </svg>
            </span>
            <input type="text" name="query" placeholder="Cari restoran..." class="px-2 py-2 border rounded-r-full w-full h-full">
        </form>
        <h2 class="text-xl font-bold mb-4">Hasil Pencarian untuk: <?= htmlspecialchars($query); ?></h2>
        <?php if (empty($restaurants)): ?>
            <p class="text-gray-700">Tidak ada restoran yang ditemukan.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($restaurants as $restaurant): ?>
                    <a href="/views/customer/resto.php?restoran_id=<?= $restaurant['restoran_id']; ?>" class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition max-w-xs mx-auto">
                        <img src="/../<?= htmlspecialchars($restaurant['restoran_gambar']); ?>" alt="<?= htmlspecialchars($restaurant['restoran_nama']); ?>" class="w-full h-32 object-cover rounded-lg mb-2">
                        <h4 class="text-md font-bold"><?= htmlspecialchars($restaurant['restoran_nama']); ?></h4>
                        <script>
                            console.log("Image path: /../<?= htmlspecialchars($restaurant['restoran_gambar']); ?>");
                        </script>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>