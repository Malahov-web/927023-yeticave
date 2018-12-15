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
    <form class="form form--add-lot container <?=$class_invalid;?>" action="/add.php" method="POST"  enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">

            <?php $class_invalid = isset($errors['lot-name']) ? "form__item--invalid" : ""; ?>

            <div class="form__item <?=$class_invalid;?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="lot-name" value="<?= $value = isset($lot['lot-name']) ? $lot['lot-name'] : ""; ?>" placeholder="Введите наименование лота" required>
                <span class="form__error"><?=$errors['lot-name'];?></span>
            </div>

            <?php $class_invalid = isset($errors['category']) ? "form__item--invalid" : ""; ?>

            <div class="form__item <?=$class_invalid;?>">
                <label for="category">Категория</label>
                <select id="category" name="category" required>
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= h($category['id'); ?>" <?= $class_selected = ($category['title'] === $lot['category']) ? "selected" : "" ?> ><?= h($category['title']); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?=$errors['category'];?></span>
            </div>
        </div>

        <?php $class_invalid = isset($errors['message']) ? "form__item--invalid" : ""; ?>

        <div class="form__item form__item--wide <?=$class_invalid;?>">
            <label for="message">Описание</label>
            <textarea id="message" name="message" value="<?= $value = isset($lot['message']) ? $lot['message'] : ""; ?>" required></textarea>
            <span class="form__error"><?=$errors['message'];?></span>
        </div>

        <?php $class_invalid = isset($errors['lot_image']) ? "form__item--invalid" : ""; ?>
        
        <div class="form__item form__item--file <?=$class_invalid;?>"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-" type="file" id="photo2" value="" name="lot_image">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>


        <div class="form__container-three">

            <?php $class_invalid = isset($errors['lot-rate']) ? "form__item--invalid" : ""; ?>

            <div class="form__item form__item--small <?=$class_invalid;?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="lot-rate" value="<?= $value = isset($lot['lot-rate']) ? $lot['lot-rate'] : ""; ?>" placeholder="0" required>
                <span class="form__error"><?=$errors['lot-rate'];?></span>
            </div>

            <?php $class_invalid = isset($errors['lot-step']) ? "form__item--invalid" : ""; ?>

            <div class="form__item form__item--small <?=$class_invalid;?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="lot-step" value="<?= $value = isset($lot['lot-step']) ? $lot['lot-step'] : ""; ?>" placeholder="0" required>
                <span class="form__error"><?=$errors['lot-step'];?></span>
            </div>

            <?php $class_invalid = isset($errors['lot-date']) ? "form__item--invalid" : ""; ?>

            <div class="form__item <?=$class_invalid;?>">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="lot-date" value="<?= $value = isset($lot['lot-date']) ? $lot['lot-date'] : ""; ?>" required>
                <span class="form__error"><?=$errors['lot-date'];?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>

</main>
