<main>
    <nav class="nav">
        <ul class="nav__list container">

            <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?= h($category['title']); ?></a>
                </li>
            <?php endforeach; ?>

        </ul>
    </nav>

    <?php $class_invalid = isset($errors) ? "form--invalid" : ""; ?>
    <form class="form form--add-lot container <?= $class_invalid; ?>" action="/add.php" method="POST"
          enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">

            <?php $class_invalid = isset($errors['title']) ? "form__item--invalid" : ""; ?>

            <div class="form__item <?= $class_invalid; ?>"> <!-- form__item--invalid -->
                <label for="title">Наименование</label>
                <input id="title" type="text" name="title"
                       value="<?= $value = isset($lot['title']) ? $lot['title'] : ""; ?>"
                       placeholder="Введите наименование лота" required>
                <span class="form__error"><?= $errors['title']; ?></span>
            </div>

            <?php $class_invalid = isset($errors['category_id']) ? "form__item--invalid" : ""; ?>

            <div class="form__item <?= $class_invalid; ?>">
                <label for="category_id">Категория</label>
                <select id="category_id" name="category_id" required>
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= h($category['id']); ?>" <?= $class_selected = ($category['title'] === $lot['category']) ? "selected" : "" ?> ><?= h($category['title']); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?= $errors['category_id']; ?></span>
            </div>
        </div>

        <?php $class_invalid = isset($errors['description']) ? "form__item--invalid" : ""; ?>

        <div class="form__item form__item--wide <?= $class_invalid; ?>">
            <label for="description">Описание</label>
            <textarea id="description" name="description"
                      value="<?= $value = isset($lot['description']) ? $lot['description'] : ""; ?>"
                      required></textarea>
            <span class="form__error"><?= $errors['description']; ?></span>
        </div>

        <?php $class_invalid = isset($errors['img_url']) ? "form__item--invalid" : ""; ?>

        <div class="form__item form__item--file <?= $class_invalid; ?>"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-" type="file" id="photo2" value="" name="img_url">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>


        <div class="form__container-three">

            <?php $class_invalid = isset($errors['price_start']) ? "form__item--invalid" : ""; ?>

            <div class="form__item form__item--small <?= $class_invalid; ?>">
                <label for="price_start">Начальная цена</label>
                <input id="price_start" type="number" name="price_start"
                       value="<?= $value = isset($lot['price_start']) ? $lot['price_start'] : ""; ?>" placeholder="0"
                       required>
                <span class="form__error"><?= $errors['price_start']; ?></span>
            </div>

            <?php $class_invalid = isset($errors['bet_step']) ? "form__item--invalid" : ""; ?>

            <div class="form__item form__item--small <?= $class_invalid; ?>">
                <label for="bet_step">Шаг ставки</label>
                <input id="bet_step" type="number" name="bet_step"
                       value="<?= $value = isset($lot['bet_step']) ? $lot['bet_step'] : ""; ?>" placeholder="0"
                       required>
                <span class="form__error"><?= $errors['bet_step']; ?></span>
            </div>

            <?php $class_invalid = isset($errors['end_at']) ? "form__item--invalid" : ""; ?>

            <div class="form__item <?= $class_invalid; ?>">
                <label for="end_at">Дата окончания торгов</label>
                <input class="form__input-date" id="end_at" type="date" name="end_at"
                       value="<?= $value = isset($lot['end_at']) ? $lot['end_at'] : ""; ?>" required>
                <span class="form__error"><?= $errors['end_at']; ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>

</main>
