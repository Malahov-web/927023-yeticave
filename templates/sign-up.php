<main>
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

    <?php $class_invalid = isset($errors) ? "form--invalid" : ""; ?>
    <form class="form container <?= $class_invalid; ?>" action="/sign-up.php" method="POST" enctype="multipart/form-data">

        <h2>Регистрация нового аккаунта</h2>

        <?php $class_invalid = isset($errors['email']) ? "form__item--invalid" : ""; ?>

        <div class="form__item <?= $class_invalid; ?>">
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" value="<?= $value = isset($user['email']) ? $user['email'] : ""; ?>" placeholder="Введите e-mail" required>
            <span class="form__error"><?= $errors['email']; ?></span>
        </div>

        <?php $class_invalid = isset($errors['password']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $class_invalid; ?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" value="<?= $value = isset($user['password']) ? $user['password'] : ""; ?>" placeholder="Введите пароль" required>
            <span class="form__error"><?= $errors['password']; ?></span>
        </div>

        <?php $class_invalid = isset($errors['name']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $class_invalid; ?>">
            <label for="name">Имя*</label>
            <input id="name" type="text" name="name" value="<?= $value = isset($user['name']) ? $user['name'] : ""; ?>" placeholder="Введите имя" required>
            <span class="form__error"><?= $errors['name']; ?> </span>
        </div>

        <?php $class_invalid = isset($errors['contacts']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $class_invalid; ?>">
            <label for="contacts">Контактные данные*</label>
            <textarea id="contacts" name="contacts" value="<?= $value = isset($user['contacts']) ? $user['contacts'] : ""; ?>" placeholder="Напишите как с вами связаться" ></textarea>
            <span class="form__error"><?= $errors['contacts']; ?></span>
        </div>

        <?php $class_invalid = isset($errors['avatar_url']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--file form__item--last <?= $class_invalid; ?>">
            <label>Аватар</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="avatar_url" name="avatar_url" value="">
                <label for="avatar_url">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>

        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
</main>
