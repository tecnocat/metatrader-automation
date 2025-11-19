<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules(
        [
            '@DoctrineAnnotation'                   => true,
            '@PSR2'                                 => true,
            '@Symfony'                              => true,
            'align_multiline_comment'               => [
                'comment_type' => 'all_multiline',
            ],
            'array_syntax'                          => [
                'syntax' => 'short',
            ],
            'binary_operator_spaces'                => [
                'default'   => 'align',
                'operators' => [
                    '=='  => null,
                    '===' => null,
                    '!='  => null,
                    '!==' => null,
                    '<>'  => null,
                    '<'   => null,
                    '>'   => null,
                    '>='  => null,
                    '<='  => null,
                    '<=>' => null,
                    '??'  => null,
                    '+'   => null,
                    '-'   => null,
                    '*'   => null,
                    '/'   => null,
                    '%'   => null,
                    '**'  => null,
                ],
            ],
            'blank_line_before_statement'           => [
                'statements' => [
                    'break',
                    'continue',
                    'declare',
                    'return',
                    'throw',
                    'try',
                    'if',
                    'switch',
                    'for',
                    'foreach',
                    'while',
                ],
            ],
            'braces'                                => [
                'allow_single_line_closure'                   => false,
                'position_after_anonymous_constructs'         => 'next',
                'position_after_control_structures'           => 'next',
                'position_after_functions_and_oop_constructs' => 'next',
            ],
            'combine_consecutive_issets'            => true,
            'combine_consecutive_unsets'            => true,
            'compact_nullable_typehint'             => true,
            'concat_space'                          => [
                'spacing' => 'one',
            ],
            'declare_strict_types'                  => true,
            'doctrine_annotation_indentation'       => [
                'indent_mixed_lines' => true,
            ],
            'echo_tag_syntax'                       => true,
            'header_comment'                        => [
                'header' => '',
            ],
            'heredoc_to_nowdoc'                     => true,
            'linebreak_after_opening_tag'           => true,
            'mb_str_functions'                      => true,
            'no_null_property_initialization'       => true,
            'no_php4_constructor'                   => true,
            'no_superfluous_elseif'                 => true,
            'no_superfluous_phpdoc_tags'            => true,
            'no_unreachable_default_argument_value' => true,
            'no_useless_else'                       => true,
            'no_useless_return'                     => true,
            'ordered_class_elements'                => [
                'order'          => [
                    'use_trait',
                    'public',
                    'protected',
                    'private',
                    'constant',
                    'constant_public',
                    'constant_protected',
                    'constant_private',
                    'property',
                    'property_static',
                    'property_public',
                    'property_protected',
                    'property_private',
                    'property_public_static',
                    'property_protected_static',
                    'property_private_static',
                    'method',
                    'method_abstract',
                    'method_static',
                    'method_public',
                    'method_protected',
                    'method_private',
                    'method_public_abstract',
                    'method_protected_abstract',
                    'method_public_abstract_static',
                    'method_protected_abstract_static',
                    'method_public_static',
                    'method_protected_static',
                    'method_private_static',
                    'construct',
                    'destruct',
                    'magic',
                    'phpunit',
                ],
                'sort_algorithm' => 'alpha',
            ],
            'ordered_imports'                       => true,
            'php_unit_strict'                       => true,
            'phpdoc_add_missing_param_annotation'   => [
                'only_untyped' => false,
            ],
            'phpdoc_order'                          => true,
            'phpdoc_types_order'                    => true,
            'psr_autoloading'                       => true,
            'simplified_null_return'                => true,
            'strict_comparison'                     => true,
            'strict_param'                          => true,
        ]
    )
    ->setFinder($finder)
    ;
