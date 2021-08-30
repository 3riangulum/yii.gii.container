<?php


namespace Triangulum\Yii\GiiContainer;

use yii\base\NotSupportedException;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

trait CustomModuleGeneratorSchemaTrait
{
    public string $containerId = '';

    public function rulesModel(): array
    {
        return [
            [['containerId'], 'required'],
            [['containerId'], 'filter', 'filter' => 'trim'],
        ];
    }

    protected function getModelNS(): string
    {
        return trim(StringHelper::dirname($this->searchModelClass), '\\');
    }

    protected function getTableSchemaClass(): string
    {
        return Inflector::id2camel($this->containerId . 'Schema', '_');
    }

    protected function getTableSchemaUse(): string
    {
        return 'use ' . $this->getModelNS() . '\\' .$this->getTableSchemaClass();
    }

    public function getTableSchemaField(string $field): string
    {
        return $this->getTableSchemaClass() . '::' . strtoupper($field);
    }

    protected function generateLabelsBySchema(array $labels): array
    {
        $ret = [];
        foreach ($labels as $column => $label) {
            $ret[$this->getTableSchemaField($column)] = $label;
        }

        return $ret;
    }

    public function generateModelRulesBySchema($table): array
    {
        $types = [];
        $lengths = [];
        foreach ($table->columns as $column) {
            if ($column->autoIncrement) {
                continue;
            }
            if (!$column->allowNull && $column->defaultValue === null) {
                $types['required'][] = $this->getTableSchemaField($column->name);
            }
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_TINYINT:
                    $types['integer'][] = $this->getTableSchemaField($column->name);
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $this->getTableSchemaField($column->name);
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $this->getTableSchemaField($column->name);
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                case Schema::TYPE_JSON:
                    $types['safe'][] = $this->getTableSchemaField($column->name);
                    break;
                default: // strings
                    if ($column->size > 0) {
                        $lengths[$column->size][] = $this->getTableSchemaField($column->name);
                    } else {
                        $types['string'][] = $this->getTableSchemaField($column->name);
                    }
            }
        }
        $rules = [];
        $driverName = $this->getDbDriverName();
        foreach ($types as $type => $columns) {
            if ($driverName === 'pgsql' && $type === 'integer') {
                $rules[] = "[[" . implode(", \n", $columns) . "], 'default', 'value' => null]";
            }
            $rules[] = "[[" . implode(", \n", $columns) . "], '$type']";
        }
        foreach ($lengths as $length => $columns) {
            $rules[] = "[[" . implode(", \n", $columns) . "], 'string', 'max' => $length]";
        }

        $db = $this->getDbConnection();

        // Unique indexes rules
        try {
            $uniqueIndexes = array_merge($db->getSchema()->findUniqueIndexes($table), [$table->primaryKey]);
            $uniqueIndexes = array_unique($uniqueIndexes, SORT_REGULAR);
            foreach ($uniqueIndexes as $uniqueColumns) {
                // Avoid validating auto incremental columns
                if (!$this->isColumnAutoIncremental($table, $uniqueColumns)) {
                    $attributesCount = count($uniqueColumns);

                    if ($attributesCount === 1) {
                        $rules[] = "[['" . $uniqueColumns[0] . "'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $columnsList = implode("', '", $uniqueColumns);
                        $rules[] = "[['$columnsList'], 'unique', 'targetAttribute' => ['$columnsList']]";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        // Exist rules for foreign keys
        foreach ($table->foreignKeys as $refs) {
            $refTable = $refs[0];
            $refTableSchema = $db->getTableSchema($refTable);
            if ($refTableSchema === null) {
                // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                continue;
            }
            $refClassName = $this->generateClassName($refTable);
            unset($refs[0]);
            $attributes = implode("', '", array_keys($refs));
            $targetAttributes = [];
            foreach ($refs as $key => $value) {
                $targetAttributes[] = "'$key' => '$value'";
            }
            $targetAttributes = implode(', ', $targetAttributes);
            $rules[] = "[['$attributes'], 'exist', 'skipOnError' => true, 'targetClass' => $refClassName::className(), 'targetAttribute' => [$targetAttributes]]";
        }

        return $rules;
    }

    public function getColumnNamesByTableSchema(): array
    {
        $ret = [];
        foreach ($this->getColumnNames() as $column) {
            $ret[] = $this->getTableSchemaField($column);
        }

        return $ret;
    }

    public function generateSearchRulesByTableSchema(): array
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNamesByTableSchema()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $this->getTableSchemaField($column->name);
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $this->getTableSchemaField($column->name);
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $this->getTableSchemaField($column->name);
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $this->getTableSchemaField($column->name);
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[[" . implode(", \n", $columns) . "], '$type']";
        }

        return $rules;
    }

    public function generateSearchConditionsByTableSchema(): array
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model BaseActiveRecord */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = ($this->getTableSchemaField($column) . ' => $this->{' . $this->getTableSchemaField($column) . '},');
                    break;
                default:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['" . $likeKeyword . "', " . $this->getTableSchemaField($column) . ', $this->{' . $this->getTableSchemaField($column) . '}])';
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$this->getQuery()->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$this->getQuery()" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    public function generateActiveFieldByTableSchema($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, " . $this->getTableSchemaField($attribute) . ")->passwordInput()";
            }

            return "\$form->field(\$model, " . $this->getTableSchemaField($attribute) . ")";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, " . $this->getTableSchemaField($attribute) . ")->checkbox()";
        }

        if ($column->type === 'text') {
            return "\$form->field(\$model, " . $this->getTableSchemaField($attribute) . ")->textarea(['rows' => 6])";
        }

        if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
            $input = 'passwordInput';
        } else {
            $input = 'textInput';
        }

        if (is_array($column->enumValues) && count($column->enumValues) > 0) {
            $dropDownOptions = [];
            foreach ($column->enumValues as $enumValue) {
                $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
            }

            return "\$form->field(\$model," . $this->getTableSchemaField($attribute) . ")->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)) . ", ['prompt' => ''])";
        }

        if ($column->phpType !== 'string' || $column->size === null) {
            return "\$form->field(\$model, " . $this->getTableSchemaField($attribute) . ")->$input()";
        }

        return "\$form->field(\$model," . $this->getTableSchemaField($attribute) . ")->$input(['maxlength' => true])";
    }
}
