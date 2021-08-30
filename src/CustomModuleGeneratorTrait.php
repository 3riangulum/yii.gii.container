<?php

namespace Triangulum\Yii\GiiContainer;

use Triangulum\Yii\ModuleContainer\System\Cache\RedisPrefixedCache;
use Triangulum\Yii\ModuleContainer\System\Log\BaseLog;
use Triangulum\Yii\ModuleContainer\UI\Front\FrontBase;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

trait CustomModuleGeneratorTrait
{
    use CustomModuleGeneratorSchemaTrait;

    public string $moduleNS    = 'backend\modules';
    public string $moduleId    = '';
    public string $containerId = '';

    public function rulesModule(): array
    {
        return [
            [['moduleNS', 'moduleId', 'containerId'], 'required'],
            [['moduleNS', 'moduleId', 'containerId'], 'filter', 'filter' => 'trim'],
        ];
    }

    protected function getContainerNS(): string
    {
        return $this->moduleNS . '\\' . $this->moduleId . '\\' . $this->containerId;
    }

    protected function getContainerClass(): string
    {
        return Inflector::id2camel($this->containerId . 'Container', '_');
    }

    protected function getContainerUse(): string
    {
        return 'use ' . $this->getContainerNS() . '\\' . $this->getContainerClass() . ';';
    }


    protected function getFrontNS(): string
    {
        return $this->getContainerNS() . '\\UI';
    }

    protected function getFrontClass(): string
    {
        return Inflector::id2camel($this->containerId . FrontBase::ID, '_');
    }

    protected function getFrontUse(): string
    {
        return 'use ' . $this->getFrontNS() . '\\' . $this->getFrontClass() . ';';
    }

    protected function getFrontVar(): string
    {
        return '$' . lcfirst($this->getFrontClass());
    }

    protected function getFrontParam(): string
    {
        return $this->getFrontClass() . ' ' . $this->getFrontVar();
    }

    protected function getLogUse(): string
    {
        return 'use ' . BaseLog::class . ';';
    }

    protected function getLogClass(): string
    {
        return StringHelper::basename(BaseLog::class);
    }

    protected function getLogVar(): string
    {
        return '$' . lcfirst(Inflector::id2camel($this->containerId . BaseLog::ID, '_'));
    }

    protected function getLogParam(): string
    {
        return $this->getLogClass() . ' ' . $this->getLogVar();
    }

    protected function getCacheUse(): string
    {
        return 'use ' . RedisPrefixedCache::class . ';';
    }

    protected function getCacheClass(): string
    {
        return StringHelper::basename(RedisPrefixedCache::class);
    }

    protected function getCacheVar(): string
    {
        return '$' . lcfirst(Inflector::id2camel($this->containerId . RedisPrefixedCache::ID, '_'));
    }

    protected function getCacheParam(): string
    {
        return $this->getCacheClass() . ' ' . $this->getCacheVar();
    }

    protected function getGridClass(): string
    {
        return ucfirst(Inflector::id2camel($this->containerId . 'Grid', '_'));
    }
}
