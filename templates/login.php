<main>
    <?php
    if ($categories) {
        $nav = get_navigation($categories);
        print_r($nav);
    }

//    get_navigation();

    /* ?>
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
   <?php */ ?>

    <?php
//    var_dump($errors);
    $class_invalid = isset($errors) ? "form--invalid" : ""; ?>
    <form class="form container <?= $class_invalid; ?>" action="/login.php" method="POST"> <!-- form--invalid -->
        <h2>Вход</h2>

        <?php $class_invalid = isset($errors['email']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $class_invalid; ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" value="<?= $value = isset($user['email']) ? $user['email'] : ""; ?>" placeholder="Введите e-mail" required>
            <span class="form__error"><?= $errors['email']; ?></span>
        </div>

        <?php $class_invalid = isset($errors['password']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--last <?= $class_invalid; ?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" value="<?= $value = isset($user['password']) ? $user['password'] : ""; ?>" placeholder="Введите пароль" >
            <span class="form__error"><?= $errors['password']; ?></span>
        </div>

        <button type="submit" class="button">Войти</button>
    </form>

</main>
