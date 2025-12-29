# Руководство по миграции CSS классов

Этот документ описывает переход от старых CSS классов к новым именам по методологии BEM.

## Методология BEM

BEM (Block Element Modifier) - методология именования CSS классов:
- **Block** - самостоятельный компонент (`.product`, `.button`)
- **Element** - часть блока (`.product__price`, `.button__icon`)
- **Modifier** - вариация блока или элемента (`.product__price--special`, `.button--primary`)

## Таблица соответствия классов

### Layout / Структура

| Старый класс | Новый класс | Описание |
|--------------|-------------|----------|
| `.lc` | `.layout-container` | Основной контейнер страницы |
| `.wp` | `.wrapper` | Контейнер-обёртка |
| `.row` | `.grid__row` | Строка сетки |
| `.block1` | `.product__main` | Основной блок товара |

### Product / Товар

| Старый класс | Новый класс | Описание |
|--------------|-------------|----------|
| `.pr` | `.product__price` | Цена товара |
| `.oldpr` | `.product__price--original` | Старая цена |
| `.newpr` | `.product__price--special` | Специальная цена |
| `.specprice` | `.product__price-wrapper` | Обёртка для цен |
| `.parametrs` | `.product__attributes` | Атрибуты товара |
| `.pars` | `.product__attributes-list` | Список атрибутов |
| `.pdf` | `.downloads` | Секция скачивания |
| `.promo` | `.product__promo-badge` | Промо-бейдж |

### Navigation / Навигация

| Старый класс | Новый класс | Описание |
|--------------|-------------|----------|
| `.it` | `.nav__item` | Элемент навигации |
| `.nl` | `.nav__link` | Ссылка навигации |
| `.sl` | `.header__select` | Селектор в шапке |

### Buttons / Кнопки

| Старый класс | Новый класс | Описание |
|--------------|-------------|----------|
| `.btn` | `.button` | Базовая кнопка |
| `.buy` | `.button--buy` | Кнопка покупки |
| `.request` | `.button--request` | Кнопка запроса |
| `.buy-kredit` | `.button--installment` | Кнопка кредита |
| `.getoffer` | `.button--offer` | Кнопка получения предложения |
| `.getlis` | `.button--leasing` | Кнопка лизинга |

### Forms / Формы

| Старый класс | Новый класс | Описание |
|--------------|-------------|----------|
| `.prv` | `.form__checkbox-label` | Лейбл чекбокса согласия |
| `.ind` | `.form__field-wrapper` | Обёртка поля формы |

### Other / Другое

| Старый класс | Новый класс | Описание |
|--------------|-------------|----------|
| `.map` | `.map-container` | Контейнер карты |
| `.vp` | `.video-player` | Видео плеер |
| `.totalprice` | `.cart__total` | Итоговая сумма корзины |

## Замена inline стилей

### Позиционирование промо-бейджей

**До:**
```html
<div class="promo" style="position: absolute; top: 10px; left: 10px;">
```

**После:**
```html
<div class="product__promo-badge product__promo-badge--top-left">
```

### Скрытие элементов

**До:**
```html
<div class="item" style="display:none" data-hide-item="1">
```

**После:**
```html
<div class="product__attribute product__attribute--hidden" data-hide-item="1">
```

### Динамические цвета

**До:**
```html
<div class="caption" style="color: <?php echo $slide['color_caption'] ?>">
```

**После:**
```html
<div class="slider__caption" style="--caption-color: <?php echo $slide['color_caption'] ?>">
```

```css
.slider__caption {
    color: var(--caption-color, #333);
}
```

## Утилитарные классы

Для часто используемых стилей используйте утилитарные классы с префиксом `u-`:

```html
<!-- Отступы -->
<div class="u-margin-top-md">...</div>
<div class="u-margin-bottom-lg">...</div>

<!-- Текст -->
<p class="u-text-center">...</p>
<p class="u-text-right">...</p>

<!-- Скрытие -->
<div class="u-hidden">...</div>
<span class="u-visually-hidden">Только для скринридеров</span>

<!-- Float (для обратной совместимости) -->
<div class="u-float-left">...</div>
<div class="u-clearfix">...</div>
```

## Пошаговая миграция

1. **Подключите новый CSS файл:**
```html
<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/bem-utilities.css">
```

2. **Добавляйте новые классы рядом со старыми:**
```html
<!-- Переходный период -->
<div class="lc layout-container">...</div>

<!-- После полной миграции -->
<div class="layout-container">...</div>
```

3. **Удаляйте старые классы постепенно** после тестирования

4. **Запускайте stylelint** для проверки новых стилей:
```bash
npx stylelint "public_html/**/*.css"
```

## Контакты

При возникновении вопросов обращайтесь к документации BEM: https://ru.bem.info/
