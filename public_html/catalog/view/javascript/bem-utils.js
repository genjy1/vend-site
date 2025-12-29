/**
 * BEM Utilities
 *
 * Утилиты для работы с BEM классами и data-атрибутами.
 * Используется для замены inline стилей на data-атрибуты.
 */

(function() {
  'use strict';

  /**
   * Применяет стили из data-position атрибута к элементу
   * Используется для промо-бейджей и других элементов с динамическим позиционированием
   */
  function applyDataPositionStyles() {
    var elements = document.querySelectorAll('[data-position]');

    elements.forEach(function(element) {
      var position = element.getAttribute('data-position');
      if (position) {
        // Парсим CSS-подобную строку и применяем стили
        var styles = parseStyleString(position);
        Object.keys(styles).forEach(function(property) {
          element.style[property] = styles[property];
        });
      }
    });
  }

  /**
   * Парсит строку стилей в объект
   * @param {string} styleString - Строка вида "position: absolute; top: 10px;"
   * @returns {Object} - Объект с CSS свойствами
   */
  function parseStyleString(styleString) {
    var styles = {};

    if (!styleString) return styles;

    styleString.split(';').forEach(function(declaration) {
      declaration = declaration.trim();
      if (!declaration) return;

      var parts = declaration.split(':');
      if (parts.length === 2) {
        var property = parts[0].trim();
        var value = parts[1].trim();

        // Конвертируем kebab-case в camelCase для JavaScript
        property = property.replace(/-([a-z])/g, function(match, letter) {
          return letter.toUpperCase();
        });

        styles[property] = value;
      }
    });

    return styles;
  }

  /**
   * Добавляет BEM модификатор к элементу
   * @param {Element} element - DOM элемент
   * @param {string} modifier - Модификатор для добавления
   */
  function addModifier(element, modifier) {
    var classes = element.className.split(' ');
    var baseClass = classes[0];

    if (baseClass && !element.classList.contains(baseClass + '--' + modifier)) {
      element.classList.add(baseClass + '--' + modifier);
    }
  }

  /**
   * Удаляет BEM модификатор с элемента
   * @param {Element} element - DOM элемент
   * @param {string} modifier - Модификатор для удаления
   */
  function removeModifier(element, modifier) {
    var classes = element.className.split(' ');
    var baseClass = classes[0];

    if (baseClass) {
      element.classList.remove(baseClass + '--' + modifier);
    }
  }

  /**
   * Переключает BEM модификатор элемента
   * @param {Element} element - DOM элемент
   * @param {string} modifier - Модификатор для переключения
   */
  function toggleModifier(element, modifier) {
    var classes = element.className.split(' ');
    var baseClass = classes[0];

    if (baseClass) {
      element.classList.toggle(baseClass + '--' + modifier);
    }
  }

  /**
   * Показывает скрытые элементы по data-hide-item атрибуту
   */
  function showHiddenItems() {
    var buttons = document.querySelectorAll('.showitems');

    buttons.forEach(function(button) {
      button.addEventListener('click', function() {
        var container = this.closest('.product__attributes') ||
                        this.closest('.parametrs') ||
                        this.parentElement;

        var hiddenItems = container.querySelectorAll('[data-hide-item="1"]');

        hiddenItems.forEach(function(item) {
          item.classList.remove('product__attribute--hidden');
          item.style.display = '';
        });

        this.style.display = 'none';
      });
    });
  }

  /**
   * Инициализация всех BEM утилит
   */
  function init() {
    applyDataPositionStyles();
    showHiddenItems();
  }

  // Экспорт в глобальную область видимости
  window.BEMUtils = {
    init: init,
    addModifier: addModifier,
    removeModifier: removeModifier,
    toggleModifier: toggleModifier,
    applyDataPositionStyles: applyDataPositionStyles,
    parseStyleString: parseStyleString
  };

  // Инициализация при загрузке DOM
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
