<?php
/*
 * Useful functions for arrays
 * Requires PHP v5.6+
 */

/**
 * Builds a string of comma delimited question marks for use with parameterised query lists.
 * One question mark is added for each item in the array provided.
 * Optional pre and post strings can be used to wrap each of the question marks in strings
 * e.g.
 * $qs = q_marks($array, "LOWER(", ")");
 *
 * @param array $array
 * @param string $pre
 * @param string $post
 * @return string
 */
function q_marks($array, $pre = '', $post = '') {
	$qs = array ();
	flatten_ ( $array );
	foreach ( $array as $x ) {
		$qs [] = "$pre?$post";
	}
	return implode ( ", ", $qs );
}
/**
 * Returns the number of dimensions (depth) of the first value of a nested/multi-dimensional array.
 * Note that an empty array counts as having a depth of 1.
 * Handy for determining whether an array contains other arrays.
 *
 * @param array $array
 * @param number $count
 * @return number
 */
function dimension_count($array, $count = 0) {
	if (is_array ( $array )) {
		return dimension_count ( current ( $array ), ++ $count );
	}
	return $count;
}
/**
 * If the arg is an array that contains other arrays then returns true, otherwise returns false
 * Returns true even if the sub-arrays are empty.
 *
 * @param array $array
 * @return boolean
 */
function is_multidim(array $array) {
	foreach ( $array as $value ) {
		if (is_array ( $value ))
			return true;
	}
	return false;
}

/**
 * For an array that contains sub-arrays, move those sub arrays into the top level arrays, retaining their keys.
 * This means that top level key-value pairs will be overwritten by sub-level key value pairs if they have the same key name.
 *
 * @param array $array
 */
function array_collapse($array) {
	$collapsed = array ();
	foreach ( $array as $key => $val ) {
		if (is_array ( $val ))
			foreach ( $val as $vkey => $vval )
				$collapsed [$vkey] = $vval;
		else if (! array_key_exists ( $key, $collapsed ))
			$collapsed [$key] = $val;
	}
	return $collapsed;
}
/**
 * For an array that contains sub-arrays, move those sub arrays into the top level arrays, retaining their keys.
 * This means that top level key-value pairs will be overwritten by sub-level key value pairs if they have the same key name.
 * This function acts by reference on the original array.
 *
 * @param array $array
 */
function array_collapse_(&$array) {
	$array = array_collapse ( $array );
	return $array;
}
/**
 * For an array that contains sub-arrays, append those sub arrays onto the top level arrays.
 * Sub-level element keys are not retained, this means that top level key-value pairs will not be overwritten by sub-level key value pairs.
 *
 * @param array $array
 */
function array_collapse_cat($array) {
	$collapsed = array ();
	foreach ( $array as $key => $val ) {
		if (is_array ( $val ))
			foreach ( $val as $vkey => $vval )
				$collapsed [] = $vval;
		else
			$collapsed [] = $val;
	}
	return $collapsed;
}
function array_collapse_cat_(&$array) {
	$array = array_collapse_cat ( $array );
	return $array;
}
/**
 * Returns the arguments as a single array
 *
 * @param mixed ...$arrays
 * @return array
 */
function array_cat(...$arrays) {
	return $arrays;
}
/**
 * For all the arguments (arrays), concatenate their elements into a single array.
 * Keys will be lost.
 *
 * @param array[] ...$arrays
 * @return array
 */
function array_cat_sub(...$arrays) {
	$a = [ ];
	foreach ( $arrays as $array )
		foreach ( $array as $val )
			$a [] = $val;
	return $a;
}
/**
 * If the arg is an array then it is returned unchanged,
 * if the arg is not an array then an array is returned with the arg's value as the first element in an array.
 *
 * @param mixed $v
 * @return array
 */
function to_array($v) {
	return is_array ( $v ) ? $v : array (
			$v 
	);
}
/**
 * If the arg is an array then it is returned unchanged,
 * if the arg is not an array then it is changed into one, by reference, with the arg's value as the first element in an array.
 *
 * @param mixed $v
 * @return array
 *
 */
function to_array_(&$v) {
	return $v = to_array ( $v );
}
/**
 * Returns the first value of the given array, and also replaces (by reference) the array variable's value with the result.
 * If the arg is not an array then the variable is returned unchanged.
 * Returns FALSE if array is empty.
 *
 * @param array $a
 * @return mixed
 */
function first_(&$a) {
	return $a = first ( $a );
}
/**
 * Returns the first value of the given array.
 * If the arg is not an array then the arg is returned unchanged.
 * Returns FALSE if array is empty.
 *
 * @param array $a
 * @return mixed
 *
 */
function first($a) {
	return is_array ( $a ) ? reset ( $a ) : $a;
}
/**
 * Returns the last value of the given array, and also replaces (by reference) the array variable's value with the result.
 * If the arg is not an array then the variable is returned unchanged.
 * Returns FALSE if array is empty.
 *
 * @param array $a
 * @return mixed
 *
 */
function last_(&$a) {
	return $a = last ( $a );
}
/**
 * Returns the last value of the given array.
 * If the arg is not an array then the arg is returned unchanged.
 * Returns FALSE if array is empty.
 *
 * @param array $a
 * @return mixed
 *
 */
function last($a) {
	return is_array ( $a ) ? end ( $a ) : $a;
}
/**
 * Returns the key of the last element in an array
 *
 * @param array $a
 */
function last_key($a) {
	end ( $a );
	return key ( $a );
}
/**
 *
 * Any number of args (variadic) can be provided and will be flattened into a single-dimensional array.
 * Flattens a nested array into one flat array.
 * Keys will be lost or modified to numeric index keys but order is retained.
 * If a single scalar value is passed in instead of an array then it will be returned as a value in a single-element array.
 * If arg is null then returns an empty array.
 *
 * @param array|mixed|mixed[] ...$a
 * @return array
 */
function flatten(...$a) {
	$flat = [ ];
	foreach ( $a as $array )
		if (is_array ( $array ))
			foreach ( $array as $val ) {
				if (is_array ( $val )) {
					$flat = array_merge ( $flat, flatten ( $val ) );
				} else
					$flat [] = $val;
			}
		else
			$flat [] = $array;
	return $flat;
}
/**
 * Flattens a nested array, by reference, into one flat array.
 * Unlike flatten(), this function modifies the array itself (by reference).
 * Keys will be lost or modified to numeric index keys but order is retained.
 * If a single scalar value is passed in instead of an array then it will be returned as a value in a single-element array.
 * If arg is null then returns an empty array.
 *
 * @param array $array
 * @return array
 */
function flatten_(&$array) {
	$array = flatten ( $array );
	return $array;
}
/**
 * Flattens a nested array into one string, recursively, basically the same as implode().
 * Unlike implode, this can take a string as the second argument so can be safely used even when you aren't sure of the data type.
 *
 * @param array $array
 * @return string
 */
function flatten_to_string($delim, ...$array) {
	return implode ( $delim, flatten ( $array ) );
}
/**
 * Given an array, the values are flattened and joined by ", ", except the last value which is joined by " and ".
 * e.g. flatten_to_text_list(array("horse", "dog","cat","mouse"))
 * returns
 * "horse, dog, cat and mouse"
 * Delimiters can be overridden by setting them in the 2nd and 3rd args.
 * e.g. flatten_to_text_list(array("horse", "dog","cat","mouse"), '; ', '; & ')
 * "horse; dog; cat; & mouse"
 *
 * @param array $array
 * @param string $delim
 * @param string $last_delim
 * @return string|mixed
 */
function flatten_to_text_list($array, $delim = ', ', $last_delim = ' and ') {
	flatten_ ( $array );
	$text = array_shift ( $array );
	if ($array)
		$text = implode ( $delim, $array ) . $last_delim . $text;
	return $text ? $text : '';
}
/**
 * Join strings together with a delimiter, e.g.
 * "/", but first removing any leading or trailing delimiter characters.
 * If no delimiter specified, then trims whitespace (as per trim()).
 * The start of the first string will not have the delimiter removed or added (so absolute/relative paths remain so).
 * The end of the last string will not have the delimiter removed or added.
 * Blank elements are removed.
 *
 * @param string $delim
 * @param array ...$array
 * @return string
 */
function implode_neatly($delim = '', ...$array) {
	flatten_ ( $array );
	$first = rtrim ( array_shift ( $array ), $delim );
	$last = ltrim ( array_pop ( $array ), $delim );
	if ($delim)
		foreach ( $array as &$el ) {
			$el = trim ( $el, $delim );
		}
	$array = remove_blanks ( flatten ( $first, $array, $last ) );
	return implode ( $delim, $array );
}
/**
 * Given a tree array structure, such as a directory structure as a tree,
 * return an array of strings which recursively joins each child value with its parent key value,
 * so you end up with one long string element for each final child.
 * If the final child value is null then that branch is ignored.
 * This allows you to have conditional branches and children, return null if you want that branch ignored, or else return the value (or further branches). 
 *
 * Example "tree" array:
 * <!--
 *
 * @formatter:off
 * -->
 * <pre>
 *$css = [
 *    base_url () => [
 *        'assets' => [
 *            'admin' => [
 *                '/pages/css' => [
 *                    'css.css'
 *                ],
 *                'layout/css' => [
 *                    'layout.css',
 *                    'themes/default.css',
 *                    'custom.css'
 *                ]
 *
 *            ],
 *            'global' => [
 *                'plugins' => [
 *                    'font-awesome/css/font-awesome.min.css',
 *                    'simple-line-icons/simple-line-icons.min.css',
 *                    'bootstrap/css/bootstrap.min.css',
 *                    'uniform/css/uniform.default.css',
 *                    $page === "frontpage" ? 'front_page.css' : NULL,  // Example conditional child
 *                    'select2/select2.css'
 *                ],
 *                'css' => [
 *                    'components.css',
 *                    'plugins.css'
 *                ]
 *            ],
 *            'application' => [
 *                'css' => [
 *                    'login.css'
 *                ]
 *            ]
 *        ]
 *    ]
 *];
 * </pre>
 * <!--
 * @formatter:on
 * -->
 * Then this PHP code<br/> *
 * <code>
 * print_r(explode_tree("/", $css));
 * </code><br/>
 *
 * will give you an array like:
 * <!--
 * @formatter:off
 * -->
 * <pre>
 *   Array
 *   (
 *		   [0] => assets/admin/pages/css/css.css
 *		   [1] => assets/admin/layout/css/layout.css
 *		   [2] => assets/admin/layout/css/themes/default.css
 *		   [3] => assets/admin/layout/css/custom.css
 *		   [4] => assets/global/plugins/font-awesome/css/font-awesome.min.css
 *		   [5] => assets/global/plugins/simple-line-icons/simple-line-icons.min.css
 *		   [6] => assets/global/plugins/bootstrap/css/bootstrap.min.css
 *		   [7] => assets/global/plugins/uniform/css/uniform.default.css
 *		   [8] => assets/global/plugins/select2/select2.css
 *		   [9] => assets/global/css/components.css
 *		   [10] => assets/global/css/plugins.css
 *		   [11] => assets/application/css/login.css
 *   	)
 * </pre>
 * <!--
 * @formatter:on
 * -->
 *
 * @param array $tree
 * @param string $parent
 * @param string $delim
 * @param array $strings
 * @return string
 */
function explode_tree($delim = '', $tree, $parent = '', &$strings = []) {
	foreach ( $tree as $key => $branch ) {
		if (is_array ( $branch )) {
			if ($parent)
				$thisparent = implode_neatly ( $delim, $parent, $key );
			else // first (root) element
				$thisparent = $key;
			explode_tree ( $delim, $branch, $thisparent, $strings );
		} elseif (! is_null ( $branch ))
			$strings [] = implode_neatly ( $delim, $parent, $branch );
	}
	return $strings;
}

/**
 * Sort a 2-dimensional array by one of the 2nd level keys (equivalent to columns).
 *
 * e.g.
 * $arr = array( 'a' => array( 'first' => 'aa', 'second' => 'bb', 'third' => 'cc', 'fourth' => 'dd'),
 * 'b' => array( 'first' => 'aa', 'second' => 'bb', 'third' => 'cx', 'fourth' => 'dd'),
 * 'c' => array( 'first' => 'aa', 'second' => 'bb', 'third' => 'ca', 'fourth' => 'dd'),
 * );
 *
 * print_r( array_sortbycol($arr, 'third'));
 *
 * @param array $array
 * @param string $col
 * @param string $ascdsc
 * @return array
 */
function array_sortbycol($array, $col, $ascdsc = SORT_ASC) {
	return array_sortbycol_ ( $array, $col, $ascdsc );
}
/**
 * Same as array_sortbycol() but acts on array by reference.
 * Sort a 2-dimensional array by one of the 2nd level keys (equivalent to columns).
 *
 * @param array $array
 * @param string $col
 * @param string $ascdsc
 * @return array
 */
function array_sortbycol_(&$array, $col, $ascdsc = SORT_ASC) {
	$sort_col = array ();
	foreach ( $array as $key => $row ) {
		$sort_col [$key] = $row [$col];
	}
	
	array_multisort ( $sort_col, $ascdsc, $array );
	return $array;
}
/**
 * Given a multidimensional array, returns a flattened array of all keys within the tree.
 *
 * @param array $ar
 * @param bool $unique
 * @return array
 */
function multiarray_keys($ar, $unique = FALSE) {
	$keys = array ();
	foreach ( $ar as $k => $v ) {
		$keys [] = $k;
		if (is_array ( $v ))
			$keys = array_merge ( $keys, multiarray_keys ( $v ) );
	}
	return $unique ? array_unique ( $keys ) : $keys;
}
/**
 * Alias for multiarray_keys()
 * Returns an array of the keys for all levels, recursively, of a multidimensional array
 *
 * @param array $ar
 * @param bool $unique
 * @return array
 */
function array_keys_recursive($array, $unique = FALSE) {
	return multiarray_keys ( $array, $unique );
}
/**
 * Given a multidimensional array, returns a flattened array of all values for a given key, within the tree.
 *
 * @param array $ar
 * @return array
 */
function multiarray_values($array, $key, $unique = FALSE) {
	$vals = array ();
	if (isset ( $array [$key] ))
		$vals = array (
				$array [$key] 
		);
	foreach ( $array as $v ) {
		if (is_array ( $v ))
			$vals = array_merge ( $vals, multiarray_values ( $v, $key ) );
	}
	return $unique ? array_unique ( $vals ) : $vals;
}

/**
 * Alias for multiarray_values()
 * Given a multidimensional array, returns a flattened array of all values for a given key within the tree, recursively.
 *
 * @param array $ar
 * @return array
 */
function array_column_recursive($array, $key, $unique = FALSE) {
	return multiarray_values ( $array, $key, $unique );
}
/**
 * Returns the supplied array with all null entries removed.
 *
 * @param array $array
 * @return array
 */
function remove_nulls($array) {
	return remove_nulls_ ( $array );
}
/**
 * Removes all null entries from supplied arrayref.
 * Also returns the new array.
 *
 * @param
 *        	array &$array
 * @return array
 */
function remove_nulls_(&$array) {
	foreach ( $array as $key => $val ) {
		if (is_null ( $val ))
			unset ( $array [$key] );
	}
	return $array;
}
/**
 * Returns the supplied array with all null and zero-length string entries removed.
 * Differs from remove_empty() in that it does not remove elements with value '0' or false.
 * If arg2 is true then elements containing only whitespace characters are also removed.
 *
 * @param array $array
 * @param bool $include_whitespace
 * @return array
 */
function remove_blanks($array, $include_whitespace = false) {
	return remove_blanks_ ( $array, $include_whitespace );
}
/**
 * Removes (by ref) all null and zero-length string elements from the supplied array.
 * If supplied arg is not an array then it is put into an array as a single element.
 * Differs from remove_empty_() in that it does not remove elements with value '0' or false.
 * If arg2 is true then elements containing only whitespace characters are also removed.
 *
 * @param array $array
 * @return array
 */
function remove_blanks_(&$array, $include_whitespace = false) {
	to_array_ ( $array );
	foreach ( $array as $key => $val ) {
		if (is_null ( $val ) || $val === '' || ($include_whitespace && ctype_space ( $val )))
			unset ( $array [$key] );
	}
	return $array;
}
/**
 * Returns the supplied array with all entries which are empty (as per empty() ) removed
 *
 * @param array $array
 * @return array
 */
function remove_empty($array) {
	return remove_empty_ ( $array );
}
/**
 * Removes (by ref) all entries which are empty (as per empty() ) from the supplied array.
 *
 * @param array $array
 * @return array
 */
function remove_empty_(&$array) {
	if (empty ( $array ))
		return [ ];
	if (! is_array ( $array ))
		return to_array_ ( $array );
	foreach ( $array as $key => $val ) {
		if (empty ( $val ))
			unset ( $array [$key] );
	}
	return $array;
}
// if (! function_exists ( "array_column" )) {
/**
 * This function exists in PHP 5.5, we are running an earlier version, so define a simple home-made version here if it is not present already.
 * Gets a named column from a 2 dimensional array.
 * Inserts a NULL for each row/array where the field doesn't exist.
 * If index key is provided then that is the field used for the new array's keys.
 * If keys are the same then the last data set found for that key is used.
 *
 * @param array $array
 * @param string $column_key
 * @param string|int $index_key
 */
/*
 * function array_column($array, $column_key, $index_key = NULL) {
 * if (! is_array ( $array ))
 * return NULL;
 * $results = array ();
 * foreach ( $array as $el ) {
 * if ($index_key)
 * $results [$el [$index_key]] = isset ( $el [$column_key] ) ? $el [$column_key] : NULL;
 * else
 * $results [] = isset ( $el [$column_key] ) ? $el [$column_key] : NULL;
 * }
 * return $results;
 * }
 */
// }

/**
 * Similar to array_column, except will get multiple columns (using array_subset rules).
 *
 * @param array $array
 * @param array $column_keys
 * @return array of arrays of subset of original array
 */
function array_columns($array, $column_keys) {
	if (is_array ( $array ))
		foreach ( $array as &$el ) {
			array_subset_ ( $el, $column_keys );
		}
	return $array;
}
/**
 * Similar to array_columns, except returns the array with the columns named in the arg2 (and any further args) removed
 *
 * @param array $array
 * @param array ...$column_keys
 * @return array
 */
function array_delete_columns($array, ...$column_keys) {
	foreach ( $array as $key => &$val ) {
		array_unset_ ( $val, $column_keys );
	}
	return $array;
}

/**
 * Similar to array_columns, except will remove columns named in the arg2 (and any further args), acts directly on the array by reference.
 *
 * @param array $array
 * @param array ...$column_keys
 * @return array
 */
function array_delete_columns_(&$array, ...$column_keys) {
	return $array = array_delete_columns ( $array, $column_keys );
}
/**
 * Given an array and a set of search-values (as an array, or single string)
 * returns an array of keys for the original array, for the elements whose value was not in the provided search-values.
 * Like the array_keys() using it's search-value feature, but negated.
 *
 * @param array $array
 * @param string|array $search_values
 * @param bool $strict
 * @return array[]
 */
function array_keys_value_not($array, $search_values, $strict = NULL) {
	flatten_ ( $search_values );
	$keys = [ ];
	foreach ( $array as $k => $v ) {
		if (! in_array ( $v, $search_values, $strict ))
			$keys [] = $k;
	}
	return $keys;
}
/**
 * Given an array and a set of keys (as an array, or single string)
 * returns an array which is a subset of the original array, containing just the elements whose keys match the provided keys.
 * The keys in the resulting array will match the keys in the original array.
 * If arg1 is not an array then it is returned unaltered.
 *
 * @param array $array
 * @param string|array ...$keys
 * @return array
 */
function array_subset($array, ...$keys) {
	flatten_ ( $keys );
	return is_array ( $array ) ? array_intersect_key ( $array, array_flip ( $keys ) ) : $array;
}
/**
 * Given an array (by reference) and a set of keys (as an array, or single string)
 * removes, directly by reference, array elements which have keys that are NOT in the set of keys.
 * Returns the subset of the original array, containing just the elements whose keys match the provided keys.
 * The keys in the resulting array will match the keys in the original array.
 *
 * @param array $array
 * @param array|string|int ...$keys
 * @return array
 */
function array_subset_(&$array, ...$keys) {
	return $array = array_subset ( $array, $keys );
}
/**
 * Given an array of 2 or more dimensions and a set of keys (as an array, or single string)
 * performs array_subset() on each of the sub-arrays, returning the original array with the sub-arrays only containing the elements matching the given keys.
 * Similar to array_column() except retaining the array structure.
 *
 * @param array $array
 * @param array|string|int ...$keys
 */
function array_subsubset($array, ...$keys) {
	foreach ( $array as &$el )
		array_subset_ ( $el, $keys );
	return $array;
}
/**
 * Given an array (by reference) of 2 or more dimensions and a set of keys (as an array, or single string)
 * performs array_subset() on each of the sub-arrays, modifying the original array so that the sub-arrays only contain the elements matching the given keys.
 * Similar to array_column() except retaining the array structure.
 *
 * @param array $array
 * @param array|string|int ...$keys
 */
function array_subsubset_(&$array, ...$keys) {
	return $array = array_subsubset ( $array, $keys );
}

/**
 * Given an array of 2 or more dimensions and a key
 * Like array_subsubset() but for only 1 level 2 key, and collapses the sub-array.
 *
 * @param array $array
 * @param array|string|int ...$keys
 */
function array_subsubcollapse($array, $key) {
	foreach ( $array as &$el )
		$el = getval_def ( $el, [ ], $key );
	return $array;
}
/**
 * Given an array of 2 or more dimensions and a key
 * Like array_subsubset() but for only 1 level 2 key, and collapses the sub-array.
 *
 * @param array $array
 * @param array|string|int ...$keys
 *
 */
function array_subsubcollapse_(&$array, $key) {
	return $array = array_subsubcollapse ( $array, $key );
}
/**
 * Given an array and a set of keys (as an array, or just multiple string parameters)
 * returns an array with all elements having keys that exist in the given set removed.
 *
 * @param array $array
 * @param array|mixed ...$keys
 * @return array
 */
function array_unset($array, ...$keys) {
	return array_unset_ ( $array, $keys );
}
/**
 * Given an array and a set of keys (as an array, or just multiple string parameters)
 * removes from the array (by reference) all elements having keys that exist in the given set.
 *
 * @param array $array
 * @param array|mixed ...$keys
 * @return array
 */
function array_unset_(&$array, ...$keys) {
	return $array = is_array ( $array ) ? array_diff_key ( $array, array_flip ( flatten ( $keys ) ) ) : $array;
}
/**
 * Given an array and a set of patterns (as an array, or just multiple string parameters)
 * removes from the array (by reference) all elements having keys that match the patterns in the given set.
 * ^ and $ anchors are automatically added to the patterns.
 *
 * @param array $array
 * @param array ...$keypattns
 */
function array_unset_matching_(&$array, ...$keypattns) {
	// Combine the patterns into one big pattern
	$pattern = "/(?:^" . flatten_to_string ( "$|^", $keypattns ) . "$)/";
	$arraykeys = array_keys ( $array );
	$matching_keys = preg_grep ( $pattern, $arraykeys );
	$array = array_diff_key ( $array, array_flip ( $matching_keys ) );
	return $array;
}
/**
 * Given an array and a set of patterns (as an array, or just multiple string parameters)
 * removes from the array (by reference) all elements having keys that match the patterns in the given set.
 * ^ and $ anchors are automatically added to the patterns.
 *
 * @param array $array
 * @param array $keypattns
 */
function array_unset_matching($array, ...$keypattns) {
	return array_unset_matching_ ( $array, $keypattns );
}
/**
 * Returns true if all the arg2 key/value pairs all exist with the same values in arg1 array.
 *
 * @param array $array
 * @param array $params
 * @return boolean
 */
function array_contains($array, $params) {
	$results = array ();
	$match = TRUE;
	foreach ( $params as $key => $val ) {
		if ($array [$key] != $val) {
			$match = FALSE;
			break;
		}
	}
	return $match;
}
/**
 * Returns true if all the arg2 values intersect the values of the arg1 elements with the same keys.
 *
 * @param array $array
 * @param array $params
 * @return boolean
 *
 */
function array_contains_intersect($array, $params) {
	$results = array ();
	$match = TRUE;
	foreach ( $params as $key => $val ) {
		if (! array_intersect ( to_array ( $val ), to_array ( $array [$key] ) )) {
			$match = FALSE;
			break;
		}
	}
	return $match;
}
/**
 * Returns true if all the arg2 (needle) values exist in the values of the arg1 (haystack) elements with the same keys, i.e.
 * if arg2 values are all subsets of arg1 elements which have the same key.
 *
 * @param array $array
 * @param array $params
 * @return boolean
 *
 *
 */
function array_contains_subset($array, $params) {
	$results = array ();
	$match = TRUE;
	foreach ( $params as $key => $val ) {
		if (count ( array_intersect ( to_array ( $val ), to_array ( $array [$key] ) ) ) != count ( to_array ( $val ) )) {
			$match = FALSE;
			break;
		}
	}
	return $match;
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that match all those in the provided set.
 * Like a database 'select ... where ...' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param
 *        	array key => value pairs.
 * @param bool $equals
 *        	- if false then returns all elements that have at least one non matching sub-element (like SQL NOT)
 * @return array matching subset of original array
 */
function array_where($array, $params, $equals = TRUE) {
	$results = array ();
	foreach ( $array as $arrayKey => $el ) {
		$match = TRUE;
		foreach ( $params as $key => $val ) {
			if (! array_key_exists ( $key, $el ) || $el [$key] != $val) {
				$match = FALSE;
				break;
			}
		}
		if ($equals && $match)
			$results [$arrayKey] = $el;
		elseif (! $equals && ! $match)
			$results [$arrayKey] = $el;
	}
	return $results;
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that do NOT match all those in the provided set.
 * Like a database 'delete from ... where ...',
 * or 'select ... where ... != ... ' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param array $params
 *        	key => value pairs.
 * @return array
 */
function array_where_not($array, $params) {
	return array_where ( $array, $params, FALSE );
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that match at least one of those in the provided set.
 * Like a database 'select ... where ... or ...' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param
 *        	array key => value pairs.
 * @param bool $equals
 *        	- if false then returns all elements where no sub-elements match (like SQL NOT)
 * @return array matching subset of original array
 */
function array_where_or($array, $params, $equals = TRUE) {
	$results = array ();
	foreach ( $array as $arrayKey => $el ) {
		$match = FALSE;
		foreach ( $params as $key => $val ) {
			if (array_key_exists ( $key, $el ) || $el [$key] != $val) {
				$match = TRUE;
				break;
			}
		}
		if ($equals && $match)
			$results [$arrayKey] = $el;
		elseif (! $equals && ! $match)
			$results [$arrayKey] = $el;
	}
	return $results;
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that do not match any of those in the provided set.
 * Like a database 'select ... where ... != ... and ... != ...' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param
 *        	array key => value pairs.
 * @return array subset of original array
 */
function array_where_not_all($array, $params) {
	return array_where_or ( $array, $params, FALSE );
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that have boolean (truthy or falsy)
 * values which match all those in the provided set.
 * Like a database 'select ... where ...' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param
 *        	array key => value pairs.
 * @param bool $equals
 *        	- if false then returns all NOT matching elements (like SQL NOT)
 * @return array matching subset of original array
 */
function array_where_boolval($array, $params, $equals = TRUE) {
	$results = array ();
	foreach ( $array as $arrayKey => $el ) {
		$match = TRUE;
		foreach ( $params as $key => $val ) {
			if (! array_key_exists ( $key, $el ) || ! ! $el [$key] != ! ! $val) {
				$match = FALSE;
				break;
			}
		}
		if ($equals && $match)
			$results [$arrayKey] = $el;
		elseif (! $equals && ! $match)
			$results [$arrayKey] = $el;
	}
	return $results;
}
function array_where_boolval_not($array, $params) {
	return array_where_boolval ( $array, $params, FALSE );
}

/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that match all those in the provided set,
 * or if the search parameter set contains an array then return the elements from the original array where the value is IN the search parameter array.
 *
 * Like a database 'select ... where ... in ... and ... in ...' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param
 *        	array key => value pairs (any type including arrays).
 * @param bool $equals
 *        	- if false then returns all NOT matching elements (like SQL NOT IN)
 * @return array matching subset of original array
 */
function array_where_in($array, $params, $equals = TRUE) {
	$results = array ();
	foreach ( $array as $arrayKey => $el ) {
		$match = TRUE;
		foreach ( $params as $key => $val ) {
			flatten_ ( $val );
			if (! in_array ( $el [$key], $val )) {
				$match = FALSE;
				break;
			}
		}
		if ($equals && $match)
			$results [$arrayKey] = $el;
		elseif (! $equals && ! $match)
			$results [$arrayKey] = $el;
	}
	return $results;
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that match none of those in the provided set,
 * or if the search parameter set contains an array then return the elements from the original array where the value is NOT IN the search parameter array.
 *
 * Like a database 'select ... where ... not in ... and ... not in ...' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param
 *        	array key => value pairs (any type including arrays).
 * @return array subset of original array
 */
function array_where_not_in($array, $params) {
	return array_where_in ( $array, $params, FALSE );
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that match at least 1 of those in the provided set,
 * or if the search parameter set contains an array then return the elements from the original array where the value is IN the search parameter array.
 *
 * Like a database 'select ... where ... in ... or ... in ...' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param
 *        	array key => value pairs (any type including arrays).
 * @param bool $equals
 *        	- if false then returns all elements that do not have any matching sub elements (like SQL NOT IN)
 * @return array matching subset of original array
 */
function array_where_in_or($array, $params, $equals = TRUE) {
	$results = array ();
	foreach ( $array as $arrayKey => $el ) {
		$match = FALSE;
		foreach ( $params as $key => $val ) {
			flatten_ ( $val );
			if (in_array ( $el [$key], $val )) {
				$match = TRUE;
				break;
			}
		}
		if ($equals && $match)
			$results [$arrayKey] = $el;
		elseif (! $equals && ! $match)
			$results [$arrayKey] = $el;
	}
	return $results;
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that do not match at least 1 of those in the provided set,
 * or if the search parameter set contains an array then return the elements from the original array where the value is NOT IN the search parameter array.
 *
 * Like a database 'select ... where ... not in ... or ... not in ...' does with a table.
 * Keys of the resulting array are retained from the original.
 *
 * @param array $array
 * @param array $params
 *        	key => value pairs (any type including arrays).
 * @return array matching subset of original array
 */
function array_where_not_in_all($array, $params) {
	return array_where_in_or ( $array, $params, FALSE );
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that match all those in the provided set,
 * or if the search parameter, or the array element, contains an array then return the elements from the original array
 * where there are common values in both (intersect).
 *
 * Like a database 'select ... where ... intersect ...' does with a table.
 *
 * @param array $array
 * @param array $params
 *        	key => value pairs (any type including arrays).
 * @return array matching subset of original array
 */
function array_where_intersect(array $array, array $params) {
	$results = array ();
	foreach ( $array as $elKey => $el ) {
		$match = TRUE;
		foreach ( $params as $key => $val ) {
			to_array_ ( $val );
			if (! array_intersect ( to_array ( $el [$key] ), $val )) {
				$match = FALSE;
				break;
			}
		}
		if ($match)
			$results [$elKey] = $el;
	}
	return $results;
}
/**
 * Given a 2 dimensional array, return all elements which have key-value pairs that match at least one of those in the provided set,
 * or if the search parameter, or the array element, contains an array then return the elements from the original array
 * where there are common values in both (intersect).
 *
 * Like a database 'select ... where ... intersect ...' does with a table.
 *
 * @param array $array
 * @param array $params
 *        	key => value pairs (any type including arrays).
 * @return array matching subset of original array
 *        
 */
function array_where_intersect_partial(array $array, array $params) {
	$results = array ();
	foreach ( $array as $elKey => $el ) {
		$match = FALSE;
		foreach ( $params as $key => $val ) {
			to_array_ ( $val );
			if (array_intersect ( to_array ( $el [$key] ), $val )) {
				$match = TRUE;
				break;
			}
		}
		if ($match)
			$results [$elKey] = $el;
	}
	return $results;
}
/**
 * Returns TRUE if a variable has a non-empty() value.
 * If an array then return TRUE if one of its elements has a non-empty() value.
 * If an array and a key is also provided then return TRUE if that key exists and has a non-empty() value.
 * Otherwise returns FALSE.
 * Avoids having to do "isset($bla) && $bla" checks.
 *
 * @param mixed $var
 * @param string $key
 * @return boolean
 */
function hasval($var, $key = NULL) {
	if (isset ( $var )) {
		if (is_array ( $var )) {
			if ($key) {
				if (array_key_exists ( $key, $var )) {
					if (empty ( $var [$key] ))
						return FALSE;
					else
						return TRUE;
				} else
					return FALSE;
			} else {
				if (flatten ( $var )) {
					foreach ( $var as $el ) {
						if (! empty ( $el ))
							return TRUE;
					}
				}
				return FALSE;
			}
		}
		return TRUE;
	}
	return FALSE;
}
/**
 * Given an array and a set of keys (as a second argument, or as any number of additional arguments),
 * returns the actual value if the element (array key) exists, or NULL if an element with those keys does not exist.
 * Similar to CodeIgniter's array helper element() function, but can take multiple levels of keys.
 *
 * e.g.
 * $a = ['a' => ['A','B'=>['X',"Y"],'C'], 'b' => "BB"];
 *
 * print_r( getval($a,'b')); // BB
 *
 * print_r( getval($a,'a','B','1')); // Y
 *
 * Also see getval_def() which does the same thing but allows a definable default value to be returned.
 *
 * @param mixed $var
 * @param string|array $keys
 * @return mixed|NULL
 */
function getval($var, ...$keys) {
	return getval_def ( $var, NULL, ...$keys );
}
/**
 * Given an array, a default return value, and a set of keys (as a 3rd argument, or as any number of additional arguments),
 * returns the actual value if the element (array key) exists, or the default return value if an element with those keys does not exist.
 * Similar to CodeIgniter's array helper element() function, but can take multiple levels of keys.
 *
 * e.g.
 * $a = ['a' => ['A','B'=>['X',"Y"],'C'], 'b' => "BB"];
 *
 * print_r( getval_def($a, "ZZZ", 'b')); // BB
 *
 * print_r( getval_def($a, "ZZZ", 'a','B','1')); // Y
 *
 * print_r( getval_def($a, "ZZZ", 'a','gg','1')); // ZZZ
 *
 * @param mixed $var
 * @param string $default
 * @param string|array $varkeys
 * @return mixed|NULL
 */
function getval_def($var, $default = NULL, ...$keys) {
	flatten_ ( $keys );
	$key = array_shift ( $keys ); // get the next key
	if (! array_key_exists ( $key, $var )) {
		return $default;
	} elseif ($keys)
		return getval_def ( $var [$key], $default, $keys );
	else
		return $var [$key];
}
if (! function_exists ( "element" )) {
	/*
	 * Create the element() function if it does not exist (e.g. if this library is being used outside CodeIgniter)
	 */
	/**
	 * element()
	 *
	 * Lets you determine whether an array index is set and whether it has a value.
	 * If the element is empty it returns NULL (or whatever you specify as the default value.)
	 *
	 * @param string $item
	 * @param array $array
	 * @param mixed $default
	 * @return mixed depends on what the array contains
	 */
	function element($item, array $array, $default = NULL) {
		return array_key_exists ( $item, $array ) ? $array [$item] : $default;
	}
}
/**
 * Same as getval_def() except returns the default if an element is empty.
 *
 * @param mixed $var
 * @param string $default
 * @param string|array $varkeys
 * @return mixed|NULL
 */
function getval_edef($var, $default = NULL, ...$keys) {
	flatten_ ( $keys );
	$key = array_shift ( $keys ); // get the next key
	if (! array_key_exists ( $key, $var ) || empty ( $var [$key] )) {
		return $default;
	} elseif ($keys)
		return getval_def ( $var [$key], $default, $keys );
	else
		return $var [$key];
}
/**
 * Same as getval() except returns NULL if an element is empty.
 *
 * @param mixed $var
 * @param string|array $key
 * @return mixed|NULL
 */
function getval_e($var, ...$keys) {
	return getval_edef ( $var, NULL, $keys );
}
/**
 * Returns true if all values in the array are integers (ctype_digit()).
 * If 2nd param is true then check all values recursively.
 *
 * @param array $array
 * @param string $recursive
 * @return boolean
 */
function array_ctype_digit($array, $recursive = FALSE) {
	$recursive ? flatten_ ( $array ) : null;
	return ! in_array ( FALSE, array_map ( 'ctype_digit', $array ) );
}
/**
 * Returns true if all values in the 1st argument array are integers or strings with an integer value (is_int or ctype_digit).
 * If 2nd param is true then check all values recursively.
 *
 * @param array $array
 * @param string $recursive
 * @return boolean
 */
function array_is_int_vals($array, $recursive = FALSE) {
	if (! is_array ( $array ))
		return FALSE;
	if ($recursive)
		flatten_ ( $array );
	return is_int_vals ( $array );
}
/**
 * Returns true if<ul>
 * <li>the argument is an integer or string with integer value (is_int or ctype_digit).
 * <li>or the argument is an array and all values in the array are integers or strings with an integer value.
 * </ul>
 *
 * @param int|string|array $val
 * @return boolean
 */
function is_int_vals($val) {
	to_array_ ( $val );
	foreach ( $val as $el ) {
		if (! ctype_digit ( "$el" )) {
			return FALSE;
		}
	}
	return TRUE;
}
/**
 * Given an array, returns an array of the integer (or ctype_digit) values.
 * If the arg is not an array then it will be converted to a single element of an array.
 *
 * @param array $array
 * @return integer[]
 */
function array_int_vals($array) {
	to_array_ ( $array );
	$ret = [ ];
	foreach ( $array as $key => $el ) {
		if (ctype_digit ( "$el" )) {
			$ret [$key] = $el;
		}
	}
	return $ret;
}
/**
 * For an array of arrays, use the value of the supplied key as the key for the array.
 * Modifies the supplied array, by reference, with the new keys.
 *
 * @param array $array
 * @param string $key
 * @return array
 */
function indexBy_(&$array, $key, $ksort = TRUE) {
	$array = indexBy ( $array, $key, $ksort );
	return $array;
}
/**
 * For an array of arrays, use the value of the supplied key as the key for the array.
 * Returns the array with the new keys.
 *
 * @param array $array
 * @param string $key
 * @return array
 */
function indexBy($array, $key, $ksort = TRUE) {
	if (! $key)
		return $array;
	$newArray = [ ];
	foreach ( $array as $value )
		$newArray [$value [$key]] = $value;
	if ($ksort)
		ksort ( $newArray );
	return $newArray;
}

/**
 * For an array of arrays, use the value of the supplied key as the key for the array.
 * Below each key put all matching arrays in another array.
 * Modifies the supplied array, by reference, with the new keys.
 * Original keys are retained in the (now) sub-arrays
 * Returns the array with the new keys.
 *
 * @param array $array
 * @param string $key
 * @return array
 */
function groupBy_(&$array, $key, $ksort = TRUE) {
	$array = groupBy ( $array, $key, $ksort );
	return $array;
}
/**
 * For an array of arrays, use the value of the supplied key as the key for the array.
 * Below each key put all matching arrays in another array.
 * Returns the array with the new keys.
 * Original keys are retained in the (now) sub-arrays
 *
 * @param array $array
 * @param string $key
 * @return array
 */
function groupBy($array, $key, $ksort = TRUE) {
	$newArray = array ();
	foreach ( $array as $akey => $value ) {
		$newArray [$value [$key]] [$akey] = $value;
	}
	if ($ksort)
		ksort ( $newArray );
	return $newArray;
}
/**
 * Similar to postgreSQL's array_agg()
 * Does the same thing as indexBy(), where the array is indexed by arg2 ($groupBy),
 * rows with the same key are lost, retaining only the values of the last row,
 * except that the array fields (columns) listed in arg3 ($aggregateCols) are aggregated into arrays.
 * Returns the array with the new keys being the '$groupBy' value.
 *
 * @param array $array
 * @param string $groupBy
 * @param array|string $aggregateCols
 * @param boolean $ksort
 * @return array[]
 */
function array_agg($array, $groupBy, $aggregateCols, $ksort = TRUE) {
	if (! $groupBy || ! $aggregateCols || ! $array)
		return $array;
	$newArray = [ ];
	to_array_ ( $aggregateCols );
	groupBy_ ( $array, $groupBy );
	foreach ( $array as $groupKey => $group ) {
		$newArray [$groupKey] = [ ];
		foreach ( $group as $groupRowKey => $groupRow ) {
			foreach ( $groupRow as $groupCol => $groupColVal ) {
				if (in_array ( $groupCol, $aggregateCols ))
					$newArray [$groupKey] [$groupCol] [] = $groupColVal;
				else
					$newArray [$groupKey] [$groupCol] = $groupColVal;
			}
		}
	}
	if ($ksort)
		ksort ( $newArray );
	return $newArray;
}
/**
 * Similar to postgreSQL's array_agg()
 * Does the same thing as indexBy_(), where the array is indexed by arg2 ($groupBy),
 * rows with the same key are lost, retaining only the values of the last row,
 * except that the array fields (columns) listed in arg3 ($aggregateCols) are aggregated into arrays.
 * Modifies the supplied array, by reference, with the new keys.
 * Returns the array with the new keys being the '$groupBy' value.
 *
 * @param array $array
 * @param string $groupBy
 * @param array|string $aggregateCols
 * @param boolean $ksort
 * @return array[]
 */
function array_agg_(&$array, $groupBy, $aggregateCols, $ksort = TRUE) {
	return $array = array_agg ( $array, $groupBy, $aggregateCols, $ksort = TRUE );
}
/**
 * Sourced from: http://nz2.php.net/manual/en/function.array-multisort.php#100534
 * Database-style results sort.
 * Takes care of creating intermediate arrays for you before passing control on to array_multisort().
 * The sorted array is in the return value of the function instead of being passed by reference.
 *
 * $data[] = array('volume' => 67, 'edition' => 2);
 * $data[] = array('volume' => 86, 'edition' => 1);
 * $data[] = array('volume' => 85, 'edition' => 6);
 * $data[] = array('volume' => 98, 'edition' => 2);
 * $data[] = array('volume' => 86, 'edition' => 6);
 * $data[] = array('volume' => 67, 'edition' => 7);
 *
 * // Pass the array, followed by the column names and sort flags
 * $sorted = array_orderby($data, 'volume', SORT_DESC, 'edition', SORT_ASC);
 *
 * @return mixed
 */
function array_orderby() {
	$args = func_get_args ();
	$data = array_shift ( $args );
	foreach ( $args as $n => $field ) {
		if (is_string ( $field )) {
			$tmp = array ();
			foreach ( $data as $key => $row )
				$tmp [$key] = $row [$field];
			$args [$n] = $tmp;
		}
	}
	$args [] = &$data;
	call_user_func_array ( 'array_multisort', $args );
	return array_pop ( $args );
}
/**
 * Applies boolval_ to every element in an array and returns the resulting array
 *
 * @param array $array
 * @return array
 */
function boolval_array($array) {
	return boolval_array_ ( $array );
}
/**
 * Applies boolval_ to every element in an array by reference, so modifies the array.
 *
 * @param array $array
 * @return array
 */
function boolval_array_(&$array) {
	array_walk ( $array, 'boolval_' );
	return $array;
}
/**
 * If param is an array then apply boolval to a reference to each element,
 * if it's not an array then apply boolval to the variable.
 *
 * @param array|string $var
 * @return array|string
 */
function boolvals_(&$var) {
	return is_array ( $var ) ? boolval_array_ ( $var ) : boolval_ ( $var );
}
/**
 * If param is an array then apply boolval to each element and return an array
 * if it's not an array then apply boolval to the variable and return a variable.
 *
 * @param array|string $var
 * @return array|string
 */
function boolvals($var) {
	return boolvals_ ( $var );
}
/**
 * boolvalue() - If you have php 5.5+ use boolval() if that suits your purpose.
 * boolvalue() should function much like boolval() except:
 * Strings "f" and "false" (case insensitive) return boolean false.
 * Negative numbers return false
 *
 * @param mixed $var
 * @return boolean
 */
function boolvalue($var) {
	if (is_array ( $var ) && ! $var)
		return FALSE;
	elseif (is_array ( $var ) && $var)
		return TRUE;
	elseif (is_numeric ( $var ) && $var < 1)
		return FALSE;
	elseif (! $var || strtolower ( $var ) == 'false' || strtolower ( $var ) == 'f' || strtolower ( $var ) == 'n' || strtolower ( $var ) == 'no')
		return FALSE;
	return TRUE;
}

/**
 * boolval by reference
 *
 * @param mixed $var
 * @return boolean
 */
function boolval_(&$var) {
	$var = boolvalue ( $var );
	return $var;
}

/**
 * Returns an array with its keys lowercased, recursively to the full depth of the array, or FALSE if array is not an array.
 *
 * @param array $array
 * @return array
 */
function lkeys($array) {
	foreach ( $array as $key => $val ) {
		if (is_array ( $val )) {
			lkeys_ ( $array [$key] );
		}
	}
	return array_change_key_case ( $array, CASE_LOWER );
}
/**
 * Converts, by reference, an array's keys to lowercase, recursively to the full depth of the array,or FALSE if array is not an array.
 *
 * @param array $array
 * @return array
 */
function lkeys_(&$array) {
	$array = lkeys ( $array );
	return $array;
}
/**
 * Returns an array with its keys uppercased, recursively to the full depth of the array, or FALSE if array is not an array.
 *
 * @param array $array
 * @return array
 */
function ukeys($array) {
	foreach ( $array as $key => $val ) {
		if (is_array ( $val )) {
			ukeys_ ( $array [$key] );
		}
	}
	return array_change_key_case ( $array, CASE_UPPER );
}
/**
 * Converts, by reference, an array's keys to uppercase, recursively to the full depth of the array, or FALSE if array is not an array.
 *
 * @param array $array
 * @return array
 */
function ukeys_(&$array) {
	$array = ukeys ( $array );
	return $array;
}
/**
 * Returns true if the arg is an associative array, false if it is empty or is a sequential array
 * ref: http://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
 *
 * @param array $arr
 * @return boolean
 */
function is_assoc(array $arr) {
	if (array () === $arr)
		return false;
	return array_keys ( $arr ) !== range ( 0, count ( $arr ) - 1 );
}

