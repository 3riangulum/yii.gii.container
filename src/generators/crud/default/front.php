<?php

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
/* @var $frontNS string */
/* @var $frontClass string */
/* @var $gridClass string */
/* @var $routeClassName string */
/* @var $modelSearchClassName string */
/* @var $modelSearchClass string */
/* @var $crudFormClass string */
/* @var $entityClassFull string */
/* @var $routeNS string */
/* @var $crudFormNS string */

$searchModelClass = StringHelper::basename($generator->searchModelClass); ?>

<?php echo "<?php"?>

namespace <?php echo $frontNS?>;

use \<?php echo $routeNS?>;
use <?php echo $crudFormNS?>;

use Triangulum\Yii\Unit\Front\FrontCrud;
use Triangulum\Yii\Unit\Front\Items\FrontSimple;
use <?php echo $generator->searchModelClass;?>;
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
use Triangulum\Yii\Unit\Html\Menu\MenuItem;

/**
* @method static self build
*/
final class <?php echo $frontClass ?> extends FrontCrud
{
    protected string $title = 'Set title!';

    public function __construct(<?php echo $routeClassName ?> $router)
    {
        $this->gridClass = <?php echo $gridClass ;?>::class;
        parent::__construct($router);
    }

    protected function loadDataExplorer(): <?php echo $modelSearchClassName;?>
    {
        return <?php echo $modelSearchClassName;?>::build([]);
    }

    public function loadCrudForm(<?php echo $entityClassFull;?> $entity, FrontSimple $ui): <?php echo $crudFormClass;?>
    {
        return <?php echo $crudFormClass;?>::build([
            'ui'                 => $ui,
            'entity'             => $entity,
        ]);
    }

    public function menu(string $title = null): MenuItem
        {
        return MenuItem::build(
            $this->router,
            $title ?? $this->title,
            \<?php echo $routeNS?>::ACTION_INDEX
        );
    }
}
