<?php

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
/* @var $frontNS string */
/* @var $frontClass string */
/* @var $gridClass string */

$searchModelClass = StringHelper::basename($generator->searchModelClass); ?>

<?php echo "<?php"?>

namespace <?php echo $frontNS?>;

use Triangulum\Yii\ModuleContainer\UI\Front\Element\ElementGrid;
use Triangulum\Yii\ModuleContainer\UI\Front\FrontBase;
use <?php echo $generator->searchModelClass;?>;

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
use \Triangulum\Yii\ModuleContainer\UI\Front\FrontCrud;

final class <?php echo $frontClass ?> extends FrontCrud
{

    public function gridSearch(<?php echo $searchModelClass?> $searchModel, array $searchParams = []): <?php echo $gridClass?>
    {
        /** @var <?php echo $gridClass?> $grid */
        $grid = ElementGrid::builder($this->actionConfig()[self::ALIAS_GRID]);

        if ($this->editor()->isAllowed()) {
        $grid->clickEventSet($this->editor(), self::ALIAS_EDITOR);
        } elseif ($this->viewer()->isAllowed()) {
        $grid->clickEventSet($this->viewer(), self::ALIAS_VIEWER);
        }

        $grid->clickEventSet($this->duplicator(), self::ALIAS_DUPLICATOR);
        $grid->actionColumnSet([$this->duplicator()]);

        $grid->dataProviderSet($searchModel->search($searchParams));
        $grid->searchModelSet($searchModel);

        return $grid;
    }
}
