<?php


namespace MageRules\BetterPathHints\Plugin;


use Magento\Cms\Block\Block;
use MageRules\BetterPathHints\Model\Wrapper;

/**
 * Class CmsBlockPlugin
 * @package MageRules\BetterPathHints\Plugin
 */
class CmsBlockPlugin
{
    /**
     * @var Wrapper
     */
    private $wrapper;

    /**
     * BlockPlugin constructor.
     * @param Wrapper $wrapper
     */
    public function __construct(
        Wrapper $wrapper
    ) {
        $this->wrapper = $wrapper;
    }

    /**
     * @param Block $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundToHtml(Block $subject, callable $proceed)
    {
        $result = $proceed();
        if(!$this->wrapper->getConfig()->isEnabled()){
            return $result;
        }
        return $this->wrapper->wrapHtml(
            $result,
            'cms-block',
            [
                'identifier' => $subject->getData('block_id')
            ]
        );
    }
}