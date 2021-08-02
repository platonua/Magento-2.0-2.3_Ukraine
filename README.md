# Модуль Magento 2.0 - 2.3 для Украины

## Чеклист интеграции:
- [x] Установить модуль.
- [x] Передать тех поддержке PSP Platon  ссылку для коллбеков.
- [x] Провести оплату используя тестовые реквизиты.

## Установка:

Вариант 1.
https://marketplace.magento.com/platon-module-platon-pay.html

Вариант 2.

* Распакуйте архив в корень сайта.

* Установите модуль из админ панели Admin->System->Web Setup Wizard->Module Manager->Platon_PlatonPay->enable

* Включите и настройте метод оплаты Admin->Stores->Configuration->SALES->Payment Methods->OTHER PAYMENT METHODS->Platon

* В настройках указать ключ и пароль. Также можете изменить название платежного метода.

* Установить значение платежного метода в статус Enabled.

* Если метод оплаты не появился на форме выбора метода оплаты, скиньте кеш System->Cache Manager

## Иностранные валюты:
Готовые CMS модули PSP Platon по умолчанию поддерживают только оплату в UAH.

Если необходимы иностранные валюты необходимо провести правки модуля вашими программистами согласно раздела [документации](https://platon.atlassian.net/wiki/spaces/docs/pages/1810235393).

## Ссылка для коллбеков:
https://ВАШ_САЙТ/platon_platon_pay/process/index

## Тестирование:
В целях тестирования используйте наши тестовые реквизиты.

| Номер карты  | Месяц / Год | CVV2 | Описание результата |
| :---:  | :---:  | :---:  | --- |
| 4111  1111  1111  1111 | 02 / 2022 | Любые три цифры | Не успешная оплата без 3DS проверки |
| 4111  1111  1111  1111 | 06 / 2022 | Любые три цифры | Не успешная оплата с 3DS проверкой |
| 4111  1111  1111  1111 | 01 / 2022 | Любые три цифры | Успешная оплата без 3DS проверки |
| 4111  1111  1111  1111 | 05 / 2022 | Любые три цифры | Успешная оплата с 3DS проверкой |
