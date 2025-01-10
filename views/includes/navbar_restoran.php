<!-- navbar_admin.php -->
<nav class="bg-white shadow-lg p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php?modul=restoran_dashboard" class="text-xl font-bold text-gray-800">
            <?php if (isset($_SESSION['username'])) {
                echo htmlspecialchars($_SESSION['username']);
            } ?>
        </a>
        <a href="index.php?modul=logout" class="text-blue-500 hover:text-blue-600">Logout</a>
    </div>
</nav>