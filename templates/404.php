    <main class="404">

    <?php
    if ($categories) {
        $nav = get_navigation($categories);
        print_r($nav);
    }
    /*

            <nav class="nav">
                <ul class="nav__list container">

                    <?php foreach ($categories as $category): ?>
                        <li class="nav__item">
                            <a href="pages/all-lots.html"><?= h($category['title']); ?></a>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </nav>
        <?php */ ?>
            <section class="lot-item container">
                <h2>404 Страница не найдена</h2>
                <p>Данной страницы не существует на сайте.</p>
            </section>
        </main>

