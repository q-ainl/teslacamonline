<?php
abstract class model extends obj {
	public static function DB(){
		return phlo('MySQL');
	}
	public static $objRecords = [];
	public static $objLoaded = [];
	public static $objCache = false;
	public static $canView = true;
	public static $canCreate = true;
	public static $canChange = true;
	public static $canDelete = true;
	public static function columns(){
		return isset(static::$columns) ? static::$columns : (method_exists(static::class, 'schema') ? static::_columns() : '*');
	}
	public static function _columns(){
		$fq = static::DB()->fieldQuotes;
		$list = array_merge(...array_values(array_filter(loop(static::fields(), fn($field, $column) => in_array($field->type, ['child', 'many', 'virtual']) ? null : ($field->columns ?: [static::$table."$fq.$fq".$column])))));
		return $fq.implode("$fq,$fq", $list).$fq;
	}
	public static function fields(){
		return method_exists(static::class, 'schema') ? static::_fields() : (static::$fields ?? []);
	}
	public static function _fields(){
		return loop(static::schema(), fn($field, $column) => last($field->name ??= $column, $field->type === 'parent' && $field->obj ??= $column, $field));
	}
	public static function field($name){
		return static::fields()[$name];
	}
	public static function create(...$args){
		return static::record(id: static::createRecord(...$args));
	}
	public static function createRecord(...$args){
		return static::DB()->create(static::$table, ...$args);
	}
	public static function change($where, ...$args){
		return static::DB()->change(static::$table, $where, ...$args);
	}
	public static function delete($where, ...$args){
		return static::DB()->delete(static::$table, $where, ...$args);
	}
	public function objSave(){
		$this->id || error('Can\'t save '.static::class.' record without an id');
		if (static::item(id: $this->id, columns: 'id')){
			static::change('id=?', $this->id, ...$this);
			return static::record(id: $this->id);
		}
		else return static::create(...$this);
	}
	public static function column(...$args){
		return static::recordsLoad($args, 'fetchAll', [PDO::FETCH_COLUMN]);
	}
	public static function item(...$args){
		return static::recordsLoad($args, 'fetch', [PDO::FETCH_COLUMN]);
	}
	public static function pair(...$args){
		return static::recordsLoad($args, 'fetchAll', [PDO::FETCH_KEY_PAIR]);
	}
	public static function records(...$args){
		return static::recordsLoad($args, 'fetchAll', [PDO::FETCH_CLASS|PDO::FETCH_UNIQUE, static::class], true);
	}
	public static function recordCount(...$args){
		return static::item(...$args, columns: 'COUNT(id)');
	}
	public static function record(...$args){
		return count($records = static::records(...$args)) > 1 ? error('Multiple records for '.static::class) : (current($records) ?: null);
	}
	public static function recordsLoad($args, $fetch, $fetchMode, $saveRelations = false){
		$args['table'] ??= static::$table;
		$saveRelations && $args['columns'] ??= static::$table.'.id as _,'.static::columns();
		isset(static::$joins) && $args['joins'] = static::$joins.(isset($args['joins']) ? " $args[joins]" : void);
		method_exists(static::class, 'where') && $args['where'] = static::where().(isset($args['where']) ? " AND $args[where]" : void);
		isset(static::$group) && $args['group'] ??= static::$group;
		isset(static::$order) && $args['order'] ??= static::$order;
		if ($cacheKey = $args['cacheKey'] ?? null) unset($args['cacheKey']);
		if ($duration = $args['cache'] ?? static::objCache()){
			unset($args['cache']);
			$records = apcu($cacheKey ?? static::class.slash.md5(json_encode($args)), fn() => static::DB()->load(...$args)->$fetch(...$fetchMode), $duration === true ? 86400 : $duration);
		}
		else $records = static::DB()->load(...$args)->$fetch(...$fetchMode);
		if ($saveRelations && $records) self::$objRecords[static::class] = (self::$objRecords[static::class] ?? []) + array_column($records, null, 'id');
		return $records;
	}
	public static function objRel($key){
		return static::$classProps[static::class][$key] ??= method_exists(static::class, $key) ? static::$key() : static::$$key ?? [];
	}
	public $objState = ['parents' => [], 'children' => [], 'many' => []];
	public function objGet($key){
		return $this->getParent($key) ?? $this->getChildren($key) ?? $this->getMany($key);
	}
	public function objIn($ids){
		return $ids ? dq.implode(dq.comma.dq, $ids).dq : 'NULL';
	}
	protected function getParent($key){
		if (array_key_exists($key, $this->objState['parents'])) return $this->objState['parents'][$key];
		$parents = self::objRel('objParents');
		if (!$relation = $parents[$key] ?? null) return;
		$isArray = is_array($relation);
		$class = $isArray ? $relation['obj'] : $relation;
		$column = $isArray ? $relation['key'] ?? $key : $key;
		if (!$parentId = $this->objData[$column] ?? null) return $this->objState['parents'][$key] = null;
		if (!isset(self::$objRecords[$class][$parentId])){
			$idsToLoad = [$parentId => true];
			$allObjData = array_map(fn($record) => $record->objData, self::$objRecords[static::class] ?? []);
			foreach ($parents as $pKey => $pRelation){
				$pIsArray = is_array($pRelation);
				$pClass = $pIsArray ? $pRelation['obj'] : $pRelation;
				if ($pClass === $class) foreach (array_column($allObjData, $pIsArray ? $pRelation['key'] ?? $pKey : $pKey) as $pId) $pId && !isset(self::$objRecords[$class][$pId]) && $idsToLoad[$pId] = true;
			}
			if ($idsToLoad = array_keys($idsToLoad)) $class::records(where: 'id IN ('.$this->objIn($idsToLoad).')');
		}
		$parentObject = self::$objRecords[$class][$parentId] ?? null;
		return $this->objState['parents'][$key] = $parentObject;
	}
	protected function getChildren($key){
		if (array_key_exists($key, $this->objState['children'])) return $this->objState['children'][$key];
		if (!$relation = self::objRel('objChildren')[$key] ?? null) return;
		$isArray = is_array($relation);
		$class = $isArray ? $relation['obj'] : $relation;
		$column = $isArray ? $relation['key'] : static::class;
		if (!isset(self::$objLoaded[static::class]['children'][$key])){
			$parentIds = array_keys(self::$objRecords[static::class] ?? []);
			if ($parentIds){
				$children = $class::records(where: '`'.$column.'` IN ('.$this->objIn($parentIds).')');
				foreach (self::$objRecords[static::class] AS $parentRecord) $parentRecord->objState['children'][$key] = [];
				foreach ($children AS $childId => $child) !is_null($pId = $child->objData[$column] ?? null) && isset(self::$objRecords[static::class][$pId]) && self::$objRecords[static::class][$pId]->objState['children'][$key][$childId] = $child;
			}
			self::$objLoaded[static::class]['children'][$key] = true;
		}
		return $this->objState['children'][$key] ?? [];
	}
	protected function getMany($key){
		if (array_key_exists($key, $this->objState['many'])) return $this->objState['many'][$key];
		if (!$relation = self::objRel('objMany')[$key] ?? null) return;
		$class = $relation['obj'];
		if (!isset(self::$objLoaded[static::class]['many'][$key])){
			$parentIds = array_keys(self::$objRecords[static::class] ?? []);
			if ($parentIds){
				$targetTable = $class::$table;
				$records = $class::recordsLoad(arr(table: $relation['table'], columns: "`$targetTable`.*, `$relation[table]`.`$relation[localKey]` as _local_key", joins: "INNER JOIN `$targetTable` ON `$relation[table]`.`$relation[foreignKey]` = `$targetTable`.`id`", where: "`$relation[table]`.`$relation[localKey]` IN (".$this->objIn($parentIds).")"), 'fetchAll', [PDO::FETCH_CLASS, $class]);
				foreach (self::$objRecords[static::class] AS $parentRecord) $parentRecord->objState['many'][$key] = [];
				foreach ($records AS $record){
					$recordId = $record->id;
					$parentId = $record->_local_key;
					unset($record->_local_key);
					if (isset(self::$objRecords[static::class][$parentId])) self::$objRecords[static::class][$parentId]->objState['many'][$key][$recordId] = $record;
				}
			}
			self::$objLoaded[static::class]['many'][$key] = true;
		}
		return $this->objState['many'][$key] ?? [];
	}
	protected function getCount($key){
		if (array_key_exists($key, $this->objState['counts'] ?? [])) return $this->objState['counts'][$key];
		if ($relation = self::objRel('objChildren')[$key] ?? null){
			if (!isset(self::$objLoaded[static::class]['children_count'][$key])){
				$parentIds = array_keys(self::$objRecords[static::class] ?? []);
				if ($parentIds){
					$isArray = is_array($relation);
					$class = $isArray ? $relation['obj'] : $relation;
					$column = $isArray ? $relation['key'] : static::class;
					$counts = $class::pair(columns: "`$column`, COUNT(*)", where: '`'.$column.'` IN ('.$this->objIn($parentIds).')', group: "`$column`");
					foreach (self::$objRecords[static::class] as $id => $record) $record->objState['counts'][$key] = (int)($counts[$id] ?? 0);
				}
				self::$objLoaded[static::class]['children_count'][$key] = true;
			}
			return $this->objState['counts'][$key] ?? 0;
		}
		if ($relation = self::objRel('objMany')[$key] ?? null){
			if (!isset(self::$objLoaded[static::class]['many_count'][$key])){
				$parentIds = array_keys(self::$objRecords[static::class] ?? []);
				if ($parentIds){
					$counts = static::DB()->load(table: $relation['table'], columns: "`$relation[localKey]`,COUNT(*)", where: '`'.$relation['localKey'].'` IN ('.$this->objIn($parentIds).')', group: "`$relation[localKey]`")->fetchAll(PDO::FETCH_KEY_PAIR);
					foreach (self::$objRecords[static::class] as $id => $record) $record->objState['counts'][$key] = (int)($counts[$id] ?? 0);
				}
				self::$objLoaded[static::class]['many_count'][$key] = true;
			}
			return $this->objState['counts'][$key] ?? 0;
		}
		return 0;
	}
	protected function getLast($key){
		if (array_key_exists($key, $this->objState['last_child'] ?? [])) return $this->objState['last_child'][$key];
		if ($relation = self::objRel('objChildren')[$key] ?? null){
			if (!isset(self::$objLoaded[static::class]['last_child'][$key])){
				if ($parentIds = array_keys(self::$objRecords[static::class] ?? [])){
					$isArray = is_array($relation);
					$class = $isArray ? $relation['obj'] : $relation;
					$column = $isArray ? $relation['key'] : static::class;
					$childTable = $class::$table;
					$whereClause = "`$column` IN (".$this->objIn($parentIds).") AND `$childTable`.`id` = (SELECT `id` FROM `$childTable` AS lc WHERE lc.`$column`=`$childTable`.`$column` ORDER BY `id` DESC LIMIT 1)";
					$lastChildren = $class::records(where: $whereClause);
					foreach (self::$objRecords[static::class] as $record) $record->objState['last_child'][$key] = null;
					foreach ($lastChildren as $child) if (isset(self::$objRecords[static::class][$parentId = $child->objData[$column]])) self::$objRecords[static::class][$parentId]->objState['last_child'][$key] = $child;
				}
				self::$objLoaded[static::class]['last_child'][$key] = true;
			}
			return $this->objState['last_child'][$key] ?? null;
		}
		return null;
	}
	public static function objParents(){
		if (property_exists(static::class, 'objParents')) return static::$objParents;
		if (!method_exists(static::class, 'schema')) return [];
		return loop(array_filter(static::fields(), fn($f) => $f->type === 'parent'), fn($f, $c) => $f->key ? arr(obj: $f->obj, key: $f->key) : ($f->obj ?? $c));
	}
	public static function objChildren(){
		if (property_exists(static::class, 'objChildren')) return static::$objChildren;
		if (!method_exists(static::class, 'schema')) return [];
		return loop(array_filter(static::fields(), fn($f) => $f->type === 'child'), fn($f, $c) => $f->key ? arr(obj: $f->obj, key: $f->key) : ($f->obj ?? $c));
	}
	public static function objMany(){
		if (property_exists(static::class, 'objMany')) return static::$objMany;
		if (!method_exists(static::class, 'schema')) return [];
		return loop(array_filter(static::fields(), fn($f) => $f->type === 'many'), fn($f) => arr(obj: $f->obj, table: $f->table, localKey: $f->localKey ?? static::class, foreignKey: $f->foreignKey ?? $f->obj));
	}
	public static function createTable(){
		method_exists(static::class, 'schema') || error(static::class.' has no schema()');
		return 'CREATE TABLE `'.static::$table.'` ('.lf.tab.implode(",\n\t", array_merge(...array_values(array_filter(loop(static::fields(), fn($field) => loop((array)$field->sql, fn($sql) => $sql.($field->required || $field->nullable === false ? ' NOT' : void).' NULL')))))).",\n\tPRIMARY KEY (`id`)\n)";
	}
}
