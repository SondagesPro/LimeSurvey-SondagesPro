<?php

/**
 * Twig compiler with the fix for CVE-2026-46633, since this version stays on Twig 1
 */
class LSTwigCompiler extends Twig_Compiler
{
    /**
     * Encode single quotes, same fix as Twig 3.26
     * @inheritdoc
     */
    public function string($value)
    {
        $this->source .= sprintf('"%s"', str_replace("'", '\\x27', addcslashes($value, "\0\t\"\$\\")));

        return $this;
    }
}
