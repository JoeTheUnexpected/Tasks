<?php
if ($linksCount > 1):
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    $page = intval($_GET['page'] ?? 1);
?>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item <?= $page === 1 ? 'active' : '' ?>">
            <a class="page-link" href="<?= $url . '?page=1' ?>">1</a>
        </li>

        <?php for ($i = 2; $i < $linksCount; $i++): ?>

        <li class="page-item <?= $page === $i ? 'active' : '' ?>">
            <a class="page-link" href="<?= $url . "?page=$i" ?>"><?= $i ?></a>
        </li>

        <?php endfor; ?>

        <li class="page-item <?= $page === $linksCount ? 'active' : '' ?>">
            <a class="page-link" href="<?= $url . "?page=$linksCount" ?>"><?= $linksCount ?></a>
        </li>
    </ul>
</nav>

<?php
endif;