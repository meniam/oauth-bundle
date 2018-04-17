<?php
$header = <<<EOF
This file is part of the oauth-bundle package.
EOF;

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'no_unreachable_default_argument_value' => false,
        'heredoc_to_nowdoc' => false,
//        'header_comment' => array('header' => $header),
    ))
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    )
;