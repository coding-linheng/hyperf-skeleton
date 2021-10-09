<?php

$header = <<<'EOF'
This file is part of Hyperf.

@link     https://www.hyperf.io
@document https://hyperf.wiki
@contact  group@hyperf.io
@license  https://github.com/hyperf/hyperf/blob/master/LICENSE
EOF;

return (new PhpCsFixer\Config)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2'                                  => true,
        '@Symfony'                               => true,
        '@DoctrineAnnotation'                    => true,
        '@PhpCsFixer'                            => true,
//        'header_comment' => [                         //头注释
//            'comment_type' => 'PHPDoc',
//            'header' => $header,
//            'separate' => 'none',
//            'location' => 'after_declare_strict',
//        ],
        'array_syntax'                           => [  //array 优化 []
            'syntax' => 'short'
        ],
        'list_syntax'                            => [  //list(x,x) 优化为[x,x]
            'syntax' => 'short'
        ],
        'concat_space'                           => [  //字符串拼接间隔空格
            'spacing' => 'one'
        ],
        'blank_line_before_statement'            => [  //在关键词前有一个换行符
            'statements' => [
                'declare',
                'if',
                'foreach'
            ],
        ],
        'general_phpdoc_annotation_remove'       => [
            'annotations' => [
                'author'
            ],
        ],
        'ordered_imports'                        => [
            'imports_order'  => [
                'class', 'function', 'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'single_line_comment_style'              => [  //注释类型//
            'comment_types' => [
            ],
        ],
        'yoda_style'                             => [
            'always_move_variable' => false,
            'equal'                => false,
            'identical'            => false,
        ],
        'phpdoc_align'                           => [
            'align' => 'left',
        ],
        'multiline_whitespace_before_semicolons' => [  //链式调用分号在最后一条
            'strategy' => 'no_multi_line',
        ],
        'constant_case'                          => [
            'case' => 'lower',
        ],
        'class_attributes_separation'            => true,
        'combine_consecutive_unsets'             => true,  //unset 合并
        'declare_strict_types'                   => true,  //增加  declare(strict_types=1)
        'linebreak_after_opening_tag'            => true,  //<?php 同行没有代码
        'lowercase_static_reference'             => true,  //静态引用为小写
        'no_useless_else'                        => true,  //优化多余的else情况
        'no_unused_imports'                      => true,  //删除未使用的use引入
        'not_operator_with_successor_space'      => false, //逻辑非运算符 ( !) 应该有一个尾随空格。
        'not_operator_with_space'                => false, //逻辑 NOT 运算符 ( !) 应该有前导和尾随空格。
        'ordered_class_elements'                 => true,  //属性排序
        'php_unit_strict'                        => false,
        'phpdoc_separation'                      => false,
        'single_quote'                           => true,  //将简单字符串的双引号转换为单引号
        'standardize_not_equals'                 => true,  //全部替换<>为!=.
        'multiline_comment_opening_closing'      => true,
        'cast_spaces'                            => ['space' => 'none'],  //强制转换和变量之间有无空格
        'binary_operator_spaces'                 => ['default' => 'align_single_space']  //等号对齐、数字箭头
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('public')
            ->exclude('runtime')
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
