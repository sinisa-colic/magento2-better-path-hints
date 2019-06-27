<?php

namespace MageRules\BetterPathHints\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package MageRules\BetterPathHints\Model
 */
class Config
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $field
     * @return mixed
     */
    protected function getGeneralConfig($field)
    {
        return $this->scopeConfig->getValue(
            "better_path_hints/general/$field",
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->getGeneralConfig('enabled');
    }

    /**
     * @return bool
     */
    public function shouldWrapEmptyBlocks()
    {
        return (bool)$this->getGeneralConfig('wrap_empty');
    }

    /**
     * @return bool
     */
    public function shouldShowBlockData()
    {
        return (bool)$this->getGeneralConfig('block_data');
    }
}
