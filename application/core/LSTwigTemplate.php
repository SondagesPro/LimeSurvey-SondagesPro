<?php

/**
 * Base class of compiled templates: apply the sandbox allow-list to Yii models.
 * With Twig 1, ArrayAccess is read before the allow-list, and CModel implements ArrayAccess.
 */
abstract class LSTwigTemplate extends Twig_Template
{
    /**
     * @inheritdoc
     * An attribute outside the allow-list can only be reached through an allowed method
     */
    protected function getAttribute($object, $item, array $arguments = [], $type = self::ANY_CALL, $isDefinedTest = false, $ignoreStrictCheck = false)
    {
        if ($object instanceof CModel && $type !== self::METHOD_CALL) {
            try {
                $this->env->getExtension('\Twig\Extension\SandboxExtension')->checkPropertyAllowed($object, (string) $item);
            } catch (Twig_Sandbox_SecurityNotAllowedPropertyError $exception) {
                if ($type === self::ARRAY_CALL) {
                    throw $exception;
                }
                $type = self::METHOD_CALL;
            }
        }
        return parent::getAttribute($object, $item, $arguments, $type, $isDefinedTest, $ignoreStrictCheck);
    }

    /**
     * Refuse a model where its iterator would expose all its attributes
     * @param mixed $value
     * @return mixed the value if it is not a model
     * @throws Twig_Sandbox_SecurityError
     */
    public static function denyModel($value)
    {
        if ($value instanceof CModel) {
            throw new Twig_Sandbox_SecurityError(sprintf('Iterating over a "%s" object is not allowed.', get_class($value)));
        }
        return $value;
    }
}
