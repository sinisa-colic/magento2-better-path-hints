<?php

namespace MageRules\BetterPathHints\Model;

use Magento\Framework\Escaper;

/**
 * Class Wrapper
 * @package MageRules\BetterPathHints\Model
 */
class Wrapper
{
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var Config
     */
    private $config;

    /**
     * Wrapper constructor.
     * @param Escaper $escaper
     * @param Config $config
     */
    public function __construct(
        Escaper $escaper,
        Config $config
    ) {
        $this->escaper = $escaper;
        $this->config = $config;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $html
     * @param string $tag
     * @param array $attributes
     * @param array $closingAttributes
     * @return string
     */
    public function wrapHtml(
        $html,
        $tag = null,
        $attributes = [],
        $closingAttributes = []
    ) {
        if ((!$tag) || (!$this->config->shouldWrapEmptyBlocks() && empty($html))) {
            return $html;
        }
        $tag .= '-' . substr(uniqid(), -5);
        return sprintf(
            '%s %s %s',
            $this->createComment($tag, $attributes),
            $html,
            $this->createComment($tag, empty($closingAttributes) ? $attributes : $closingAttributes, true)
        );
    }

    /**
     * @param string $tag
     * @param array $attributes
     * @param bool $isClosing
     * @return string
     */
    public function createComment($tag, $attributes = [], $isClosing = false)
    {
        $attributes = array_filter($attributes);
        return sprintf(
            "<!-- %s%s %s -->",
            $isClosing ? '/' : '',
            strtoupper($tag),
            implode(
                ' ',
                array_map(function ($key) use ($attributes) {
                    return sprintf(
                        '%s="%s"',
                        $key,
                        str_replace(
                            '-->',
                            '--/>',
                            $attributes[$key]
                        )
                    );
                }, array_keys($attributes))
            )
        );
    }
}
