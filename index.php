<?php

require __DIR__ . '/inc/functions.inc.php';
require __DIR__ . '/inc/db-connect.inc.php';

$perPage = 3;
$page = $_GET['page'] ?? 1;
$page = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $perPage;

$stmtCount = $pdo->prepare('SELECT COUNT(*) AS `count` FROM `entries`');
$stmtCount->execute();
$count = $stmtCount->fetch(PDO::FETCH_ASSOC);
$pages = ceil($count['count'] / $perPage);

$stmt = $pdo->prepare('SELECT * FROM `entries` ORDER BY `date` DESC, `id` DESC LIMIT :perPage OFFSET :offset');
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php require __DIR__ . '/views/header.view.php'; ?>
<h1 class="main-heading">Entries</h1>
<?php foreach ($entries as $entry): ?>
    <div class="card">
        <div class="card__image-container">
            <img class="card__image" src="images/pexels-canva-studio-3153199.jpg" alt="" />
        </div>
        <div class="card__desc-container">
            <div class="card__desc-time"><?= escape($entry['date']) ?></div>
            <h2 class="card__heading"><?= escape($entry['title']) ?></h2>
            <p class="card__paragraph">
                <?= nl2br(escape($entry['message'])) ?>
            </p>
        </div>
    </div>
<?php endforeach; ?>

<?php if ($pages > 1): ?>
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li class="pagination__li">
                <a class="pagination__link" href="?<?= http_build_query(['page' => $page - 1]) ?>">⏴</a>
            </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="pagination__li">
                <a 
                class="pagination__link
                <?php if ($i === $page): ?>pagination__link--active<?php endif; ?>"
                href="?<?= http_build_query(['page' => $i]) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
        <?php if ($page < $pages): ?>
            <li class="pagination__li">
                <a class="pagination__link" href="?<?= http_build_query(['page' => $page + 1]) ?>">⏵</a>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>
<?php require __DIR__ . '/views/footer.view.php'; ?>
