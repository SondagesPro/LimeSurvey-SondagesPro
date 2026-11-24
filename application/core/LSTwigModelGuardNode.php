<?php

/**
 * Expression refusing a Yii model at runtime, see LSTwigTemplate::denyModel()
 */
class LSTwigModelGuardNode extends Twig_Node_Expression
{
    /**
     * @param Twig_Node $node the guarded expression
     */
    public function __construct(Twig_Node $node)
    {
        parent::__construct(['node' => $node], [], $node->getTemplateLine());
    }

    /**
     * @inheritdoc
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->raw('LSTwigTemplate::denyModel(')->subcompile($this->getNode('node'))->raw(')');
    }
}
