<?php
$this->addExternalCss("/local/components/mycompany/contactform/assets/bootstrap.min.css");
$this->addExternalJs("/local/components/mycompany/contactform/assets/bootstrap.min.js");
?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>


<div class="container mt-5">
    <h2 class="fw-bold">Новая заявка</h2>
    <?php if ($_GET['success'] == 1): ?>
        <div class="alert alert-success">Заявка успешно отправлена!</div>
    <?php elseif ($_GET['error'] == 1) : ?>
        <div class="alert alert-danger">Ошибка отправки заявки!</div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <?= bitrix_sessid_post(); ?>
        <div class="form-group w-25 mt-3">
            <label for="title">Заголовок заявки</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group mt-3">
            <p class="fw-bold fs-6">Категория</p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category1" value="option1" checked>
                <label class="form-check-label" for="category1">
                    Масла, автохимия, фильтры. Автоаксессуары, обогреватели, запчасти, сопутствующие товары.
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="category2" value="option2">
                <label class="form-check-label" for="category2">
                    Шины, диски
                </label>
            </div>
        </div>
        <div class="form-group mt-3">
            <p class="fw-bold fs-6">Вид заявки</p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="type1" value="option1" checked>
                <label class="form-check-label" for="type1">
                    Запрос цены и сроков поставки
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="type2" value="option2">
                <label class="form-check-label" for="type2">
                    Пополнение складов
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="type3" value="option2">
                <label class="form-check-label" for="type3">
                    Спецзаказ
                </label>
            </div>
        </div>
        <div class="form-group w-25 mt-3">
            <p class="fw-bold fs-6">Склад поставки</p>
            <select class="form-select" style="font-size: 12px;" aria-label="Default select example" id="warehouse" name="warehouse">
                <option selected>(выберите склад поставки)</option>
                <option value="1">Склад 1</option>
                <option value="2">Склад 2</option>
                <option value="3">Склад 3</option>
            </select>
        </div>

        <div id="items-container" class="mt-3">
            <p class="fw-bold fs-6">Состав заявки</p>
            <div class="form-group row px-2">
                <div class="text-center col-4 px-0" style="width: 180px;">
                    <label for="brand" class="fw-bold mb-1 text-danger">Бренд</label>
                    <select class="form-select text-secondary" style="font-size: 12px;" aria-label="Default select example" id="brand" name="item[]['brand']">
                        <option selected>Выберите бренд</option>
                        <option value="1">Склад 1</option>
                        <option value="2">Склад 2</option>
                        <option value="3">Склад 3</option>
                    </select>
                </div>
                <div class="text-center col-2 ps-1 pe-0">
                    <label for="name" class="fw-bold mb-1 text-danger">Наименование</label>
                    <input type="text" class="form-control py-0" style="height: 31px;" id="name" name="items[]['name']">
                </div>
                <div class="text-center col-2 ps-1 pe-0">
                    <label for="count" class="fw-bold mb-1 text-danger">Количество</label>
                    <input type="text" class="form-control" style="height: 31px;" id="count" name="items[]['count']">
                </div>
                <div class="text-center col-2 ps-1 pe-0">
                    <label for="packaging" class="fw-bold mb-1 text-danger">Фасовка</label>
                    <input type="text" class="form-control" style="height: 31px;" id="packaging" name="items[]['packaging']">
                </div>
                <div class="text-center col-2 ps-1 pe-0">
                    <label for="client" class="fw-bold mb-1 text-danger">Клиент</label>
                    <input type="text" class="form-control" style="height: 31px;" id="client" name="items[]['client']">
                </div>
                <div class="col-1 mx-auto mt-auto mb-1 text-end">
                    <button type="button" class="btn btn-primary py-0 px-1 mx-auto add-item">+</button>
                </div>
                <div class="col-1 mx-auto mt-auto mb-1">
                    <button type="button" class="btn btn-secondary py-0 px-2 remove-item">-</button>
                </div>


            </div>
        </div>

        <div class="input-group mt-3 w-25">
            <input type="file" class="form-control" name="file" style="font-size: 12px;">
        </div>

        <div class="mt-3 w-50">
            <label for="comment">Комментарий:</label>
            <textarea class="form-control" id="comment" name="comment" style="height: 110px;"></textarea>
        </div>

        <button type="submit" class="mt-3" style="font-size: 12px; border: 0.5px black solid;">Отправить</button>
    </form>
</div>

<script>
    document.getElementById('items-container').addEventListener('click', function(e) {
        var container = document.getElementById('items-container');
        if (e.target.className.includes('add-item')) {
            var newItem = document.createElement('div');
            newItem.className = 'form-group row mt-2 px-2';
            newItem.innerHTML = `<div class="text-center col-4 px-0" style="width: 180px;">
                    <label for="brand" class="fw-bold mb-1 text-danger">Бренд</label>
                    <select class="form-select text-secondary" style="font-size: 12px;" aria-label="Default select example" id="brand" name="item[]['brand']">
                        <option selected>Выберите бренд</option>
                        <option value="1">Склад 1</option>
                        <option value="2">Склад 2</option>
                        <option value="3">Склад 3</option>
                    </select>
                </div>
                <div class="text-center col-2 ps-1 pe-0">
                    <label for="name" class="fw-bold mb-1 text-danger">Наименование</label>
                    <input type="text" class="form-control py-0" style="height: 31px;" id="name" name="items[]['name']">
                </div>
                <div class="text-center col-2 ps-1 pe-0">
                    <label for="count" class="fw-bold mb-1 text-danger">Количество</label>
                    <input type="text" class="form-control" style="height: 31px;" id="count" name="items[]['count']">
                </div>
                <div class="text-center col-2 ps-1 pe-0">
                    <label for="packaging" class="fw-bold mb-1 text-danger">Фасовка</label>
                    <input type="text" class="form-control" style="height: 31px;" id="packaging" name="items[]['packaging']">
                </div>
                <div class="text-center col-2 ps-1 pe-0">
                    <label for="client" class="fw-bold mb-1 text-danger">Клиент</label>
                    <input type="text" class="form-control" style="height: 31px;" id="client" name="items[]['client']">
                </div>
                <div class="col-1 mx-auto mt-auto mb-1 text-end">
                    <button type="button" class="btn btn-primary py-0 px-1 mx-auto add-item">+</button>
                </div>
                <div class="col-1 mx-auto mt-auto mb-1">
                    <button type="button" class="btn btn-secondary py-0 px-2 remove-item">-</button>
                </div>`;
            container.appendChild(newItem);
        } else if (e.target.className.includes('remove-item')) {
            if (container.childElementCount > 2) {
                e.target.closest('.form-group').remove();
            }
        } else {
            e.preventDefault();
        }
    });
</script>