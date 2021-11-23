<?php

use yii\helpers\StringHelper;

/* @var $crudFormClass string */
/* @var $entityClassFull string */
/* @var $tableSchemaUse string */
/* @var $crudFormNS string */

echo '<?php'
?>

namespace <?= StringHelper::dirname(ltrim($crudFormNS, '\\')) ?>;

use Triangulum\Yii\Unit\Html\AutoComplete\AutoCompleteSelectForm;
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
use Triangulum\Yii\Unit\Html\Form\EntityCrudForm;
use yii\widgets\ActiveField;

<?php echo $tableSchemaUse?>;

/**
* @property <?php echo $entityClassFull;?> $entity
* @method static self build(array $config)
*/
class <?php echo $crudFormClass?> extends EntityCrudForm
{

}
