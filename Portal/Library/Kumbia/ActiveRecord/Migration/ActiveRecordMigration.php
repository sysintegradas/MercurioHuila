<?php

/**
 * Kumbia Enterprise Framework
 *
 * LICENSE
 *
 * This source file is subject to the New BSD License that is bundled
 * with this package in the file docs/LICENSE.txt.

 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@loudertechnology.com so we can send you a copy immediately.
 *
 * @category 	Kumbia
 * @package 	ActiveRecord
 * @subpackage 	Migration
 * @copyright	Copyright (c) 2008-2011 Louder Technology COL. (http://www.loudertechnology.com)
 * @license 	New BSD License
 * @version 	$Id: ActiveRecordMigration.php,v 5f278793c1ae 2011/10/27 02:50:13 andres $
 */

/**
 * ActiveRecordMigration
 *
 * Subcomponente que permite realizar migraciones de DML y DDL en bases de datos
 *
 * @package 	ActiveRecord
 * @subpackage 	Migration
 * @copyright	Copyright (c) 2008-2011 Louder Technology COL. (http://www.loudertechnology.com)
 * @license 	New BSD License
 * @version 	$Id: ActiveRecordMigration.php,v 5f278793c1ae 2011/10/27 02:50:13 andres $
 */
class ActiveRecordMigration extends Object {

	/**
	 * Migration database connection
	 *
	 * @var DbBase
	 */
	private static $_connection;

	/**
	 * Path donde se guardará la migración
	 *
	 * @var string
	 */
	private static $_migrationPath = null;

	/**
	 * Prepares component
	 *
	 * @param string $environment
	 */
	public static function setup($environment=''){

		$config = CoreConfig::readAppConfig();
		if($environment==''){
			if(!isset($config->application->mode)){
				throw new ActiveRecordException('No se ha definido el entorno por defecto de la aplicación');
			} else {
				$environment = $config->application->mode;
			}
		}

		$environmentConfig = CoreConfig::readEnviroment();
		if(!isset($environmentConfig->$environment)){
			throw new ActiveRecordException('No se ha definido el entorno por defecto en enviroment.ini');
		}

		$envConfig = $environmentConfig->$environment;
		self::$_connection = DbLoader::factory($envConfig->{'database.type'}, array(
			'host' => $envConfig->{'database.host'},
			'port' => $envConfig->{'database.port'},
			'username' => $envConfig->{'database.username'},
			'password' => $envConfig->{'database.password'},
			'name' => $envConfig->{'database.name'},
			'schema' => $envConfig->{'database.schema'},
		));

		self::$_connection->setProfiling(new ActiveRecordMigrationProfiler());
	}

	/**
	 * Set the migration directory path
	 *
	 * @param string $path
	 */
	public static function setMigrationPath($path){
		self::$_migrationPath = $path;
	}

	/**
	 * Generates all the class migration defitions for certain database setup
	 *
	 * @param	string $version
	 * @param	string $exportData
	 * @return	string
	 */
	public function generateAll($version, $exportData=null){
		$classDefinition = array();
		foreach(self::$_connection->listTables() as $table){
			$classDefinition[$table] = self::generate($version, $table, $exportData);
		}
		return $classDefinition;
	}

	/**
	 * Generate specified table migration
	 *
	 * @param	string $version
	 * @param	string $table
	 * @param 	string $exportData
	 * @return	string
	 */
	public static function generate($version, $table, $exportData=null){

		$oldColumn = null;
		$allFields = array();
		$numericFields = array();
		$tableDefinition = array();
		$defaultSchema = self::$_connection->getDefaultSchema();
		$description = self::$_connection->describeTable($table, $defaultSchema);
		foreach($description as $field){
			$fieldDefinition = array();
			if(preg_match('/([a-z]+)(\(([0-9]+)(,([0-9]+))*\)){0,1}/', $field['Type'], $matches)){
				switch($matches[1]){
					case 'int':
					case 'smallint':
					case 'double':
						$fieldDefinition[] = "'type' => DbColumn::TYPE_INTEGER";
						$numericFields[$field['Field']] = true;
						break;
					case 'varchar':
						$fieldDefinition[] = "'type' => DbColumn::TYPE_VARCHAR";
						break;
					case 'char':
						$fieldDefinition[] = "'type' => DbColumn::TYPE_CHAR";
						break;
					case 'date':
						$fieldDefinition[] = "'type' => DbColumn::TYPE_DATE";
						break;
					case 'datetime':
						$fieldDefinition[] = "'type' => DbColumn::TYPE_DATETIME";
						break;
					case 'decimal':
						$fieldDefinition[] = "'type' => DbColumn::TYPE_DECIMAL";
						$numericFields[$field['Field']] = true;
						break;
					case 'text':
						$fieldDefinition[] = "'type' => DbColumn::TYPE_TEXT";
						break;
					case 'enum':
						$fieldDefinition[] = "'type' => DbColumn::TYPE_CHAR";
						$fieldDefinition[] = "'size' => 1";
						break;
					default:
						throw new ActiveRecordMigrationException('Tipo de dato no reconocido '.$matches[1].' en la columna '.$field['Field']);
				}
				if(isset($matches[3])){
					$fieldDefinition[] = "'size' => ".$matches[3];
				}
				if(isset($matches[5])){
					$fieldDefinition[] = "'scale' => ".$matches[5];
				}
				if(strpos($field['Type'], 'unsigned')){
					$fieldDefinition[] = "'unsigned' => true";
				}
			} else {
				throw new ActiveRecordMigrationException('Tipo de dato no reconocido '.$field['Type']);
			}
			if($field['Key']=='PRI'){
				$fieldDefinition[] = "'primary' => true";
			}
			if($field['Null']=='NO'){
				$fieldDefinition[] = "'notNull' => true";
			}
			if($field['Extra']=='auto_increment'){
				$fieldDefinition[] = "'autoIncrement' => true";
			}
			if($oldColumn!=null){
				$fieldDefinition[] = "'after' => '".$oldColumn."'";
			} else {
				$fieldDefinition[] = "'first' => true";
			}
			$oldColumn = $field['Field'];
			$tableDefinition[] = "\t\t\t\tnew DbColumn('".$field['Field']."', array(\n\t\t\t\t\t".join(",\n\t\t\t\t\t", $fieldDefinition)."\n\t\t\t\t))";
			$allFields[] = "'".$field['Field']."'";
		}

		$indexesDefinition = array();
		$indexes = self::$_connection->describeIndexes($table, $defaultSchema);
		foreach($indexes as $indexName => $indexColumns){
			$indexDefinition = array();
			foreach($indexColumns as $indexColumn){
				$indexDefinition[] = "'".$indexColumn."'";
			}
			$indexesDefinition[] = "\t\t\t\tnew DbIndex('".$indexName."', array(\n\t\t\t\t\t".join(",\n\t\t\t\t\t", $indexDefinition)."\n\t\t\t\t))";
		}

		$referencesDefinition = array();
		$references = self::$_connection->describeReferences($table, $defaultSchema);
		foreach($references as $constraintName => $reference){

			$columns = array();
			foreach($reference['columns'] as $column){
				$columns[] = "'".$column."'";
			}
			$referencedColumns = array();
			foreach($reference['referencedColumns'] as $referencedColumn){
				$referencedColumns[] = "'".$referencedColumn."'";
			}

			$referenceDefinition = array();
			$referenceDefinition[] = "'referencedSchema' => '".$reference['referencedSchema']."'";
			$referenceDefinition[] = "'referencedTable' => '".$reference['referencedTable']."'";
			$referenceDefinition[] = "'columns' => array(".join(",", $columns).")";
			$referenceDefinition[] = "'referencedColumns' => array(".join(",", $referencedColumns).")";

			$referencesDefinition[] = "\t\t\t\tnew DbReference('".$constraintName."', array(\n\t\t\t\t\t".join(",\n\t\t\t\t\t", $referenceDefinition)."\n\t\t\t\t))";
		}

		$optionsDefinition = array();
		$tableOptions = self::$_connection->tableOptions($table, $defaultSchema);
		foreach($tableOptions as $optionName => $optionValue){
			$optionsDefinition[] = "\t\t\t\t'$optionName' => '".$optionValue."'";
		}

		$classVersion = preg_replace('/[^0-9A-Za-z]/', '', $version);
		$className = Utils::camelize($table).'Migration_'.$classVersion;
		$classData = "class ".$className." extends ActiveRecordMigration {\n\n".
		"\tpublic function up(){\n\t\t\$this->morphTable('".$table."', array(".
		"\n\t\t\t'columns' => array(\n".join(",\n", $tableDefinition)."\n\t\t\t),".
		"\n\t\t\t'indexes' => array(\n".join(",\n", $indexesDefinition)."\n\t\t\t),".
		"\n\t\t\t'references' => array(\n".join(",\n", $referencesDefinition)."\n\t\t\t),".
		"\n\t\t\t'options' => array(\n".join(",\n", $optionsDefinition)."\n\t\t\t)\n".
		"\t\t));\n\t}";
		if($exportData=='always'||$exportData=='oncreate'){
			if($exportData=='oncreate'){
				$classData.="\n\n\tpublic function afterCreateTable(){\n";
			} else {
				$classData.="\n\n\tpublic function afterUp(){\n";
			}
			$classData.="\t\t\$this->batchInsert('$table', array(\n\t\t\t".join(",\n\t\t\t", $allFields)."\n\t\t));";

			$fileHandler = fopen(self::$_migrationPath.'/'.$table.'.dat', 'w');
			$cursor = self::$_connection->query('SELECT * FROM '.$table);
			self::$_connection->setFetchMode(DbBase::DB_ASSOC);
			while($row = self::$_connection->fetchArray($cursor)){
				$data = array();
				foreach($row as $key => $value){
					if(isset($numericFields[$key])){
						if($value===''||is_null($value)){
							$data[] = 'NULL';
						} else {
							$data[] = addslashes($value);
						}
					} else {
						$data[] = "'".addslashes($value)."'";
					}
					unset($value);
				}
				fputs($fileHandler, join('|', $data).PHP_EOL);
				unset($row);
				unset($data);
			}
			fclose($fileHandler);

			$classData.="\n\t}";
		}
		$classData.="\n\n}";
		return $classData;
	}

	public static function migrateFile($version, $filePath){
		if(file_exists($filePath)){
			$fileName = basename($filePath);
			$classVersion = preg_replace('/[^0-9A-Za-z]/', '', $version);
			$className = Utils::camelize(str_replace('.php', '', $fileName)).'Migration_'.$classVersion;
			require KEF_ABS_PATH.$filePath;
			if(class_exists($className)){
				$migration = new $className();
				if(method_exists($migration, 'up')){
					$migration->up();
					if(method_exists($migration, 'afterUp')){
						$migration->afterUp();
					}
				}
			} else {
				throw new ActiveRecordMigrationException('No se encontró la clase de migración '.$className.' en '.$filePath);
			}
		}
	}

	/**
	 * Look for table structure modifications and apply to them
	 *
	 * @param string $tableName
	 * @param array $tableColumns
	 */
	public function morphTable($tableName, $definition){

		$defaultSchema = self::$_connection->getDefaultSchema();
		$tableExists = self::$_connection->tableExists($tableName, $defaultSchema);
		if(isset($definition['columns'])){
			if(count($definition['columns'])==0){
				throw new ActiveRecordMigrationException('La tabla debe tener al menos una columna');
			}
			$fields = array();
			foreach($definition['columns'] as $tableColumn){
				if(!is_object($tableColumn)){
					throw new ActiveRecordMigrationException('La tabla debe tener al menos una columna');
				}
				$fields[$tableColumn->getName()] = $tableColumn;
			}
			if($tableExists==true){
				$localFields = array();
				$description = self::$_connection->describeTable($tableName, $defaultSchema);
				foreach($description as $field){
					$localFields[$field['Field']] = $field;
				}
				foreach($fields as $fieldName => $tableColumn){
					if(!isset($localFields[$fieldName])){
						self::$_connection->addColumn($tableName, $tableColumn->getSchemaName(), $tableColumn);
					} else {
						$changed = false;
						$columnDefinition = strtolower(self::$_connection->getColumnDefinition($tableColumn));
						if($localFields[$fieldName]['Type']!=$columnDefinition){
							$changed = true;
						}
						if($tableColumn->isNotNull()!=true&&$localFields[$fieldName]['Null']=='NO'){
							$changed = true;
						} else {
							if($tableColumn->isNotNull()==true&&$localFields[$fieldName]['Null']=='YES'){
								$changed = true;
							}
						}
						if($changed==true){
							self::$_connection->modifyColumn($tableName, $tableColumn->getSchemaName(), $tableColumn);
						}
					}
				}
				foreach($localFields as $fieldName => $localField){
					if(!isset($fields[$fieldName])){
						self::$_connection->dropColumn($tableName, null, $fieldName);
					}
				}
			} else {
				self::$_connection->createTable($tableName, $defaultSchema, $definition);
				if(method_exists($this, 'afterCreateTable')){
					$this->afterCreateTable();
				}
			}
		}

		if(isset($definition['references'])){
			if($tableExists==true){
				$references = array();
				foreach($definition['references'] as $tableReference){
					$references[$tableReference->getName()] = $tableReference;
				}
				$localReferences = self::$_connection->describeReferences($tableName, $defaultSchema);
				foreach($definition['references'] as $tableReference){
					if(!isset($localReferences[$tableReference->getName()])){
						self::$_connection->addForeignKey($tableName, $tableReference->getSchemaName(), $tableReference);
					} else {
						$changed = false;
						if($tableReference->getReferencedTable()!=$localReferences[$tableReference->getName()]['referencedTable']){
							$changed = true;
						}
						if($changed==false){
							if(count($tableReference->getColumns())!=count($localReferences[$tableReference->getName()]['columns'])){
								$changed = true;
							}
						}
						if($changed==false){
							if(count($tableReference->getReferencedColumns())!=count($localReferences[$tableReference->getName()]['referencedColumns'])){
								$changed = true;
							}
						}
						if($changed==false){
							foreach($tableReference->getColumns() as $columnName){
								if(!in_array($columnName, $localReferences[$tableReference->getName()]['columns'])){
									$changed = true;
									break;
								}
							}
						}
						if($changed==false){
							foreach($tableReference->getReferencedColumns() as $columnName){
								if(!in_array($columnName, $localReferences[$tableReference->getName()]['referencedColumns'])){
									$changed = true;
									break;
								}
							}
						}
						if($changed==true){
							self::$_connection->dropForeignKey($tableName, $tableReference->getSchemaName(), $tableReference->getName());
							self::$_connection->addForeignKey($tableName, $tableReference->getSchemaName(), $tableReference);
						}
					}
				}
				foreach($localReferences as $referenceName => $reference){
					if(!isset($references[$referenceName])){
						self::$_connection->dropForeignKey($tableName, null, $referenceName);
					}
				}
			}
		}

		if(isset($definition['indexes'])){
			if($tableExists==true){
				$indexes = array();
				foreach($definition['indexes'] as $tableIndex){
					$indexes[$tableIndex->getName()] = $tableIndex;
				}
				$localIndexes = self::$_connection->describeIndexes($tableName, $defaultSchema);
				foreach($definition['indexes'] as $tableIndex){
					if(!isset($localIndexes[$tableIndex->getName()])){
						if($tableIndex->getName()=='PRIMARY'){
							self::$_connection->addPrimaryKey($tableName, $tableColumn->getSchemaName(), $tableIndex);
						} else {
							self::$_connection->addIndex($tableName, $tableColumn->getSchemaName(), $tableIndex);
						}
					} else {
						$changed = false;
						if(count($tableIndex->getColumns())!=count($localIndexes[$tableIndex->getName()])){
							$changed = true;
						} else {
							foreach($tableIndex->getColumns() as $columnName){
								if(!in_array($columnName, $localIndexes[$tableIndex->getName()])){
									$changed = true;
									break;
								}
							}
						}
						if($changed==true){
							if($tableIndex->getName()=='PRIMARY'){
								self::$_connection->dropPrimaryKey($tableName, $tableColumn->getSchemaName());
								self::$_connection->addPrimaryKey($tableName, $tableColumn->getSchemaName(), $tableIndex);
							} else {
								self::$_connection->dropIndex($tableName, $tableColumn->getSchemaName(), $tableIndex->getName());
								self::$_connection->addIndex($tableName, $tableColumn->getSchemaName(), $tableIndex);
							}
						}
					}
				}
				foreach($localIndexes as $indexName => $indexColumns){
					if(!isset($indexes[$indexName])){
						self::$_connection->dropIndex($tableName, null, $indexName);
					}
				}
			}
		}

	}

	/**
	 * Inserta datos desde un archivo de datos de migración en una tabla
	 *
	 * @param string $tableName
	 * @param string $fields
	 */
	public function batchInsert($tableName, $fields){
		$migrationData = self::$_migrationPath.'/'.$tableName.'.dat';
		if(file_exists($migrationData)){
			self::$_connection->begin();
			self::$_connection->delete($tableName);
			$batchHandler = fopen($migrationData, 'r');
			while(($line = fgets($batchHandler))!==false){
				self::$_connection->insert($tableName, explode('|', rtrim($line)), $fields, false);
				unset($line);
			}
			fclose($batchHandler);
			self::$_connection->commit();
		}
	}

}