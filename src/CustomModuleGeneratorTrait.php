<?php

namespace Triangulum\Yii\GiiContainer;

use Triangulum\Yii\ModuleContainer\System\Cache\RedisPrefixedCache;
use Triangulum\Yii\ModuleContainer\System\Log\BaseLog;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

trait CustomModuleGeneratorTrait
{
    use CustomModuleGeneratorSchemaTrait;

    public string $unitNs = 'backend\units\\';
    public string $repository = '';
    public string $tableSchema = '';

    public string $moduleNS    = 'backend\modules';
    public string $moduleId    = '';
    public string $containerId = '';

    public function rulesModule(): array
    {
        return [
            [['unitNs', 'repository', 'tableSchema'], 'required'],
            [['unitNs', 'repository', 'tableSchema'], 'filter', 'filter' => 'trim']
        ];
    }

    protected function getRepository(): string
    {
        return trim($this->repository, '\\');
    }

    protected function getTableSchemaClassFull(): string
    {
        return trim($this->tableSchema, '\\');
    }

    protected function getUnitNS(): string
    {
        return trim($this->unitNs, '\\');
    }

    protected function getFrontNS(): string
    {
        return $this->getUnitNS(). '\UI';
    }

    protected function getUnitAlias(): string
    {
        return $this->controllerID;
    }

    protected function getFrontClass(): string
    {
        return Inflector::id2camel($this->getUnitAlias() . 'Front');
    }

    protected function getFrontUse(): string
    {
        return 'use ' . $this->getFrontNS() . '\\' . $this->getFrontClass() . ';';
    }


    protected function getCrudFormClass(): string
    {
        return Inflector::id2camel($this->getUnitAlias() . 'CrudForm');
    }

    protected function getCrudFormNS(): string
    {
        return '\\' . $this->getFrontNS() . '\\form\\' . $this->getCrudFormClass();
    }

    protected function getRouteClassName(): string
    {
        return Inflector::id2camel($this->getUnitAlias() . 'Router');
    }


    protected function getGridClass(): string
    {
        return ucfirst(Inflector::id2camel($this->getUnitAlias() . 'Grid'));
    }





    #####


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

    protected function getContainerNS(): string
    {
        return '';

        return $this->moduleNS . '\\' . $this->moduleId . '\\' . $this->containerId;
    }

    protected function getContainerClass(): string
    {
        return '';

        return Inflector::id2camel($this->containerId . 'Container', '_');
    }

    protected function getContainerUse(): string
    {

        return '';
        return 'use ' . $this->getContainerNS() . '\\' . $this->getContainerClass() . ';';
    }


}
