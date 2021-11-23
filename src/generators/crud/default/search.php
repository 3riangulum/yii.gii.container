<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;
use Triangulum\Yii\GiiContainer\generators\crud\Generator;

/* @var $this yii\web\View */
/* @var $generator Generator */
/* @var $tableSchemaClassFull string */
/* @var $repositoryClassNameFull string */
/* @var $properties array */

$searchModelClass = StringHelper::basename($generator->searchModelClass);
$rules= $generator->generateSearchRulesByTableSchema();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditionsByTableSchema();


echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;


use Triangulum\Yii\Unit\Data\Db\Explorer\DbDataExplorer;
use Triangulum\Yii\Unit\Data\Explorer\ExplorerFilter;
use <?php echo $tableSchemaClassFull?>;
use <?php echo $repositoryClassNameFull?>;


/**
*
<?php foreach ($properties as $property => $data): ?>
    * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
    *
    <?php foreach ($relations as $name => $relation): ?>
        * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
    <?php endforeach; ?>
<?php endif; ?>
* @method static self build(array $config = [])
*/


final class <?= $searchModelClass ?> extends DbDataExplorer
{
    use ExplorerFilter;

    public function __construct(\<?php echo $repositoryClassNameFull?> $repository, $config = [])
    {
        $this->repository = $repository;
        parent::__construct($config);
    }


    public function rules(): array
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    public function search(array $params): DataProviderInterface
    {

        if (!$this->loadParams($params)) {
            return $this->loadEmptyProvider();
        }

        $this->setQuery($this->repository->query());
        <?= implode("\n        ", $searchConditions) ?>

        return new ActiveDataProvider([
            'query' => $this->getQuery(),
            'sort'       => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);
    }
}
