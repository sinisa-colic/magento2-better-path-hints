<?php


namespace MageRules\BetterPathHints\Plugin;


use Magento\Framework\DataObject;
use Magento\Framework\Interception\InterceptorInterface;
use Magento\Framework\Serialize\JsonConverter;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Layout;
use MageRules\BetterPathHints\Model\Wrapper;

/**
 * Class TemplatePlugin
 * @package MageRules\BetterPathHints\Plugin
 */
class TemplatePlugin
{
    /**
     * @var Wrapper
     */
    private $wrapper;
    /**
     * @var Layout
     */
    private $layout;

    /**
     * BlockPlugin constructor.
     * @param Layout $layout
     * @param Wrapper $wrapper
     */
    public function __construct(
        Layout $layout,
        Wrapper $wrapper
    ) {
        $this->wrapper = $wrapper;
        $this->layout = $layout;
    }

    public function aroundToHtml(Template $block, callable $proceed)
    {
        $name = $block->getNameInLayout();
        return $this->wrapper->wrapHtml(
            $proceed(),
            'block',
            [
                'name'      => $name,
                'parent'    => $this->layout->getParentName($name),
                'class'     => $this->getRealClass($block),
                'template'  => $block->getTemplateFile(),
                'blockData' => $this->wrapper->getConfig()->shouldShowBlockData()
                    ? JsonConverter::convert($this->sanitizeBlockData($block))
                    : 'disabled',
            ]
        );
    }

    /**
     * @param $datum
     * @return array|string
     */
    protected function sanitizeBlockData($datum)
    {
        if (is_string($datum)) {
            return $datum;
        }
        if (is_object($datum)) {
            $class = $this->getRealClass($datum);
            if ($datum instanceof DataObject) {
                return [
                    'class' => $class,
                    'data'  => array_filter(array_map([$this, 'sanitizeBlockData'], $datum->getData())),
                ];
            }
            return $class;
        }
        if (is_array($datum)) {
            return array_filter(array_map([$this, 'sanitizeBlockData'], $datum));
        }
    }

    /**
     * @param string|object $className
     * @return string
     */
    protected function getRealClass($className)
    {
        if (is_object($className)) {
            $className = get_class($className);
        }
        return in_array(InterceptorInterface::class, class_implements($className))
            ? get_parent_class($className)
            : $className;
    }
}