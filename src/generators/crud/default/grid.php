<?php

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
/* @var $frontNS string */
/* @var $gridClass string */
/* @var $tableSchemaUse string */

$tableSchema = $generator->getTableSchema();
$searchModelClass = StringHelper::basename($generator->searchModelClass);
$modelClass = StringHelper::basename($generator->modelClass);
?>

<?php echo "<?php"?>

namespace <?php echo $frontNS?>;

use yii\grid\SerialColumn;
use Closure;
use Triangulum\Yii\ModuleContainer\UI\Front\Element\ElementGrid;
use <?php echo $generator->searchModelClass?>;
use <?php echo $generator->modelClass?>;
<?php echo $tableSchemaUse?>;

use Triangulum\Yii\ModuleContainer\UI\Front\Element\ElementPopup;
use Triangulum\Yii\ModuleContainer\UI\Html\Label;
use Triangulum\Yii\ModuleContainer\UI\Html\LabelInline;
use Triangulum\Yii\ModuleContainer\UI\Html\Dropdown\Dropdown;
use Triangulum\Yii\ModuleContainer\UI\Html\Dropdown\FilterDropdown;
use Triangulum\Yii\ModuleContainer\UI\Html\Time;
use Triangulum\Yii\ModuleContainer\UI\Html\Text;
use Triangulum\Yii\ModuleContainer\UI\Html\Icons;
use Triangulum\Yii\ModuleContainer\UI\Html\Span;
use Triangulum\Yii\ModuleContainer\UI\Html\Growl;

final class <?php echo $gridClass?> extends ElementGrid
{

    protected function gridViewRowOptions(): Closure
    {
        return static function (<?php echo $modelClass?> $model) {
            return [
                'data'  => [
                    'id' => $model->pkGet(),
                ],
                    'class' => ['tr-' . $model->pkGet()],
                ];
        };
    }

    /**
    * @param <?php echo $searchModelClass?> $searchModel
    * @return array
    */
    public function gridViewColumnsConfig($searchModel = null): array
    {
        $clickBase = $this->defineEditOrViewClickClass();

        return [
            [
                'class'          => SerialColumn::class,
                'contentOptions' => ['class' => [
                    'w40px',
                    /*'w80px',
                    'w100px',
                    'w130px',
                    'w150px',
                    'w200px',
                    'text-center',
                    'text-right',
                    'text-nowrap',*/
                    $clickBase,
                ]],
            ],

            <?php foreach ($tableSchema->columns as $column): ?>
                <?php $format = $generator->generateColumnFormat($column);?>
                [
                'attribute'      => <?= $generator->getTableSchemaField($column->name);?>,
                    'format'         => 'text',
                    'contentOptions' => ['class' => [
                        '' ,
                        $clickBase
                    ]],
                    'filterOptions'  => ['class' => ['text-center']],
                    'value'          => static function (<?=$modelClass?> $data) {
                        return $data->{<?= $generator->getTableSchemaField($column->name);?>};
                    },
                ],
            <?php endforeach; ?>

            $this->emptyColumn($clickBase),
            $this->loadActionColumn(),
        ];
    }
}
