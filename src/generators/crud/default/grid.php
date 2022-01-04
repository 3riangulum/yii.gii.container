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
use <?php echo $generator->searchModelClass?>;
use <?php echo $generator->modelClass?>;
<?php echo $tableSchemaUse?>;

use \Triangulum\Yii\Unit\Front\Items\FrontItem;
use \Triangulum\Yii\Unit\Html\Label\Label;
use \Triangulum\Yii\Unit\Html\Label\LabelInline;
use \Triangulum\Yii\Unit\Html\Dropdown\Dropdown;
use \Triangulum\Yii\Unit\Html\Dropdown\FilterDropdown;
use \Triangulum\Yii\Unit\Html\Time\Time;
use \Triangulum\Yii\Unit\Html\Text\Text;
use \Triangulum\Yii\Unit\Html\Icons\Icons;
use \Triangulum\Yii\Unit\Html\Span\Span;
use \Triangulum\Yii\Unit\Html\Growl;

/**
* @method static self build(array $config)
*/
final class <?php echo $gridClass?> extends \Triangulum\Yii\Unit\Front\Items\FrontGrid
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
    * @param <?php echo $searchModelClass?> $dataExplorer
    * @return array
    */
    public function gridViewColumnsConfig($dataExplorer = null): array
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
