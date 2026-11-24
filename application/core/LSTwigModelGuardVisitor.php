<?php

/**
 * Guard the "for" tag and the allowed filters that read all the values of an iterator
 */
class LSTwigModelGuardVisitor extends Twig_BaseNodeVisitor
{
    /** @var string[] allowed filters reading an iterator */
    const GUARDED_FILTERS = ['merge', 'first', 'last', 'keys'];

    /**
     * @inheritdoc
     */
    protected function doEnterNode(Twig_Node $node, Twig_Environment $env)
    {
        return $node;
    }

    /**
     * @inheritdoc
     */
    protected function doLeaveNode(Twig_Node $node, Twig_Environment $env)
    {
        if ($node instanceof Twig_Node_For) {
            $node->setNode('seq', new LSTwigModelGuardNode($node->getNode('seq')));
        } elseif (
            $node instanceof Twig_Node_Expression_Filter
            && in_array($node->getNode('filter')->getAttribute('value'), self::GUARDED_FILTERS, true)
        ) {
            $node->setNode('node', new LSTwigModelGuardNode($node->getNode('node')));
            $arguments = $node->getNode('arguments');
            foreach ($arguments as $name => $argument) {
                $arguments->setNode($name, new LSTwigModelGuardNode($argument));
            }
        }
        return $node;
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 0;
    }
}
