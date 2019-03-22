<?php

namespace MageRules\BetterPathHints\Plugin;

/**
 * Plugin class for @see \Magento\Framework\View\Layout
 */
class LayoutPlugin
{
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    public function __construct(
        \Magento\Framework\Escaper $escaper
    ) {
        $this->escaper = $escaper;
    }

    /**
     * @param \Magento\Framework\View\Layout $subject
     * @param callable $proceed
     * @param $name
     * @return string
     */
    public function aroundRenderNonCachedElement(\Magento\Framework\View\Layout $subject, callable $proceed, $name)
    {
        $result = $proceed($name);
        if ($result) {
            $type = strtoupper($subject->getElementProperty($name, 'type'));
            $rand = substr(uniqid(), -5);
            $id = "$type-$rand";
            $block = $subject->getBlock($name);

            $nameParam = $this->getParam('name', $name);
            $classParam = $this->getParam('class', $block ? get_class($block) : '');
            $parentParam = $this->getParam('parent', $subject->getParentName($name));
            $templateParam = $this->getParam(
                'template',
                $block && method_exists($block, 'getTemplateFile') ? $block->getTemplateFile() : ''
            );

            $result = "<!-- $id $nameParam $parentParam $classParam $templateParam-->$result<!-- /$id $nameParam -->";
        }
        return $result;
    }

    /**
     * Returns url style parameter if variable is not null
     * @param string $name
     * @param string $value
     * @return string
     */
    private function getParam($name, $value)
    {
        $escapedValue = $this->escaper->escapeHtml($value);
        return $escapedValue ? "$name='$escapedValue'" : '';
    }
}
