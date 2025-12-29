<?php

/**
 * PHP-CS-Fixer конфигурация для проекта OpenCart
 *
 * Запуск: vendor/bin/php-cs-fixer fix
 * Проверка без изменений: vendor/bin/php-cs-fixer fix --dry-run --diff
 */

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/public_html')
    ->exclude([
        'vendor',
        'node_modules',
        'image',
        'download',
        'storage',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules([
        // PSR-12 базовый набор правил
        '@PSR12' => true,

        // Массивы
        'array_syntax' => ['syntax' => 'short'],
        'no_whitespace_before_comma_in_array' => true,
        'whitespace_after_comma_in_array' => true,
        'trim_array_spaces' => true,
        'normalize_index_brace' => true,

        // Пробелы и форматирование
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'concat_space' => ['spacing' => 'one'],
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],
        'no_spaces_around_offset' => true,
        'object_operator_without_whitespace' => true,
        'unary_operator_spaces' => true,

        // Комментарии и PHPDoc
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'multiline_comment_opening_closing' => true,

        // Импорты и use statements
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_import_per_statement' => true,

        // Классы и методы
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'one',
            ],
        ],
        'no_blank_lines_after_class_opening' => true,
        'single_class_element_per_statement' => ['elements' => ['property']],
        'visibility_required' => ['elements' => ['method', 'property']],

        // Управляющие конструкции
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_braces' => true,
        'elseif' => true,
        'include' => true,
        'no_alternative_syntax' => false, // Разрешаем для шаблонов

        // Строки
        'single_quote' => true,
        'explicit_string_variable' => false,

        // Разное
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_whitespace_in_blank_line' => true,
        'single_blank_line_at_eof' => true,
        'encoding' => true,
        'full_opening_tag' => true,
        'no_closing_tag' => true,
        'blank_line_after_opening_tag' => false, // OpenCart стиль
        'linebreak_after_opening_tag' => false, // OpenCart стиль

        // Приведение типов
        'cast_spaces' => ['space' => 'single'],
        'lowercase_cast' => true,
        'short_scalar_cast' => true,

        // Функции
        'function_declaration' => ['closure_function_spacing' => 'one'],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'no_spaces_after_function_name' => true,
        'return_type_declaration' => ['space_before' => 'none'],

        // Пустые строки
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try'],
        ],
        'no_blank_lines_after_phpdoc' => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
