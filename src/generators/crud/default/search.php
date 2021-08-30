<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
//$rules = $generator->generateSearchRules();
$rules= $generator->generateSearchRulesByTableSchema();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
//$searchConditions = $generator->generateSearchConditions();
$searchConditions = $generator->generateSearchConditionsByTableSchema();
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use Triangulum\Yii\ModuleContainer\UI\Data\Search\TraitSearch;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

final class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{

use TraitSearch;

    public function rules(): array
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
    * @param array $params
    * @return ActiveDataProvider|ArrayDataProvider
    */
    public function search(array $params)
    {
        $this->initParams($params, $this->formName());
        $this->load($this->paramsGetAll());

        if (!$this->validate()) {
            return $this->gridProviderGetEmpty();
        }

        $this->setQuery( <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find());

        <?= implode("\n        ", $searchConditions) ?>

        return new ActiveDataProvider([
            'query' => $this->getQuery(),
            'sort'       => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);
    }
}
