<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => array('syntax' => 'short'),
        'no_unreachable_default_argument_value' => false,
        'braces' => array('allow_single_line_closure' => true),
        'heredoc_to_nowdoc' => false,
        'psr_autoloading' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->notPath('vendor/')
    )
;
