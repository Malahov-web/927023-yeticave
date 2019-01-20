<nav class="nav">
    <ul class="nav__list container">
        <?php
        if ($categories) {
            foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?= h($category['title']); ?></a>
                </li>
            <?php endforeach;
        }
        ?>
    </ul>
</nav>
