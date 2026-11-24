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

    /**
     * json_encode function of templates: CJSON reads all the attributes of a model
     * @param mixed $value
     * @return string
     * @throws Twig_Sandbox_SecurityError
     */
    public static function jsonEncode($value)
    {
        self::denyNestedModel($value);
        return CJSON::encode($value);
    }

    /**
     * Refuse a model, including inside the values read by CJSON
     * @param mixed $value
     * @return void
     * @throws Twig_Sandbox_SecurityError
     */
    private static function denyNestedModel($value)
    {
        self::denyModel($value);
        if (is_object($value) && !$value instanceof Traversable) {
            $value = get_object_vars($value);
        }
        if (is_array($value) || $value instanceof Traversable) {
            foreach ($value as $item) {
                self::denyNestedModel($item);
            }
        }
    }
}
