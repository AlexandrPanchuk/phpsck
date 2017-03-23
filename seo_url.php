<?php 
// подключение к бд 
$mysqli = new Mysqli('', '', '', '');
$mysqli->set_charset('utf8');

/*
	транслитерация значений в БД		
*/

function translits($string)
{
	$converter = array(
	  'а' => 'a',   'б' => 'b', 'в'   => 'v', 'г'  => 'g',
	  'д' => 'd',   'е' => 'e', 'ё'   => 'e', 'ж'  => 'zh',
	  'з' => 'z',   'и' => 'i', 'й'   => 'y', 'к'  => 'k',
	  'л' => 'l',   'м' => 'm', 'н'   => 'n', 'м'  => 'm',
	  'н' => 'n',   'о' => 'o', 'п'   => 'p', 'р'  => 'r',
	  'с' => 's',   'т' => 't', 'у'   => 'u', 'ф'  => 'f',
	  'х' => 'h',   'ц' => 'c', 'ч'   => 'ch', 'ш' => 'sh',
	  'щ' => 'sch', 'ь' => '',  'ы'   => 'y', 'ъ'  => '',
	  'э' => 'e',   'ю' => 'u', 'я'   => 'ya', 'А' => 'a',
	  'Б' => 'b',   'В' => 'v', 'Г'   => 'g', 'Д'  => 'd',
	  'Е' => 'e',   'Ё' => 'e', 'Ж'   => 'zh', 'З' => 'z',
	  'И' => 'i',   'Й' => 'y', 'К'   => 'k', 'Л'  => 'l',
	  'М' => 'm',   'Н' => 'n', 'О'   => 'o', 'П'  => 'p',
	  'Р' => 'r',   'С' => 's', 'Т'   => 't', 'У'  => 'u',
	  'Ф' => 'f',   'Х' => 'h', 'Ц'   => 'c', 'Ч'  => 'ch',
	  'Ш' => 'sh',  'Щ' => 'sch', 'Ь' => '', 'Ы'   => 'y',
	  'Ъ' => '',    'Э' => 'e', 'Ю'   => 'u', 'Я'  => 'ya'
	);

	$string = str_replace(',', '', $string);
	$string = str_replace('-', '_', $string);
	$string = str_replace('(', '', $string);
	$string = str_replace(')', '', $string);
	$string = str_replace(';', '', $string);
	$string = str_replace('__', '_', $string);
	$string = str_replace('&quot', '', $string);
	$string = str_replace("'", '', $string);
	$string = str_replace("/", '', $string);
	$string = str_replace(".", '_', $string);
	$string = str_replace(" ", '_', $string);
	$string = str_replace('"', '', $string);
	$string = str_replace('*', '', $string);
	$string = str_replace(':', '_', $string);
	$string = str_replace("\\", '', $string);
	$string = str_replace("&", '_', $string);

	if (substr($string, -1) == '_'){
		$string = substr($string, 0, -1);
	}

	$string = trim($string);
	return mb_strtolower(strtr($string, $converter));
}

/*
*  СКЛЕЙКА 
*/
// для фильтров
preg_match('/\/filter\//s', $_SERVER['REQUEST_URI'], $is_filter);
if (isset($is_filter) && $is_filter[0] == '/filter/' || strpos($_SERVER['REQUEST_URI'], '/?') !== false || strpos($_SERVER['REQUEST_URI'], 'catalog') !== false){
	$uri_redirect = $_SERVER['REQUEST_URI'];

	$uri_redirect = str_replace('/filter/', '/filters/', $uri_redirect);
	
	$array_filter = array();	
	$uriRedirect = $uri_redirect;
	$uriRedirect = substr($uriRedirect, strpos($uriRedirect, '/filters/') + 9);	
	$explode_uri = explode('/', $uriRedirect);	

	foreach ($explode_uri as $value){		
		if (preg_match('/(.*)\-(.*)$/iU', $value, $match_filter)){			
			$array_filter[$match_filter[1]][] = $match_filter[2];		
		}	
	}


	foreach ($array_filter as $namefilter => $valuefilter) {
		if (strpos($valuefilter[0], '-or-') !== false){
			$exp_valuefilter = explode('-or-', $valuefilter[0]);
			foreach ($exp_valuefilter as $xml_id_filter) {
				$xml_id_filter = str_replace('is-', '', $xml_id_filter);
				$arrayFilter[$namefilter][] = $xml_id_filter;
			}
		}
		if (strpos($valuefilter[0], '-or-') == false){
			$valuefilter[0] = str_replace('is-', '', $valuefilter[0]);
			$arrayFilter[$namefilter][] = $valuefilter[0];
		}
	}

	$translit_name = array();
	foreach ($arrayFilter as $key_nameFilter => $value_optionIdFilter) {
		// выборка для брендов
		if ($key_nameFilter == 'vadim') { 
			foreach ($value_optionIdFilter as $xml_id) {
				$select_xml_id_brand = $mysqli->query("SELECT `UF_NAME_TRANSLIT` FROM `b_brenz` WHERE `UF_XML_ID` = '".$xml_id."' ");
				while ($rows_brand = $select_xml_id_brand->fetch_assoc()) {
					$translit_name = $rows_brand;
				}
				if (isset($translit_name)){
					foreach ($translit_name as $keys => $values) {
						$array_all_filter[$key_nameFilter][] = $values;
					}
				}


			}
		} else {
			foreach ($value_optionIdFilter as $xml_id) {
				$select_xml_id_brand = $mysqli->query("SELECT `VALUE_TRANSLIT` FROM `b_iblock_property_enum` WHERE `XML_ID` = '".$xml_id."' ");
				while ($rows_brand = $select_xml_id_brand->fetch_assoc()) {
					$translit_name = $rows_brand;
				}
				if (isset($translit_name)){
					foreach ($translit_name as $keys => $values) {
						$array_all_filter[$key_nameFilter][] = $values;
					}
				}
			}
		}
	}		

	if (!empty($array_all_filter)){
		foreach ($array_all_filter as $keyNameFilter => $filterNameOptions) {
			$string = implode('-', $filterNameOptions);
			$path_redirect .= $keyNameFilter.'-'.$string.'/';		
		}
	}

	preg_match('/(.*?)\/filter/s', $_SERVER['REQUEST_URI'], $path_to_category);
	$path_to_category = array_pop($path_to_category);
	$uri_to_redirect = $path_to_category.'/filters/'.$path_redirect;

	if (isset($uri_to_redirect) && $uri_to_redirect !== '/filters/'){
		if (strpos($_SERVER['REQUEST_URI'], '/catalog') === false)
		{
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: https://".$_SERVER['HTTP_HOST'].$uri_to_redirect); 
			exit(); 
		}
	}
}

// для сортировок
if (strpos($_SERVER['REQUEST_URI'], '?view') !== false){
	$sort_redirect = $_SERVER['REQUEST_URI'];

	$sort_redirect = str_replace('?', '', $sort_redirect);
	$sort_redirect = str_replace('&', '/', $sort_redirect);
	$sort_redirect = str_replace('=', '-', $sort_redirect);
	$sort_redirect .= '/';


	if (isset($sort_redirect)){
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: https://".$_SERVER['HTTP_HOST'].$sort_redirect); 
		exit(); 
	} 
}

if (strpos($_SERVER['REQUEST_URI'], 'PAGEN_1') !== false){
	$redirect_pagination = $_SERVER['REQUEST_URI'];
	$redirect_pagination = str_replace('?', '', $redirect_pagination);
	$redirect_pagination = str_replace('=', '-', $redirect_pagination);
	$redirect_pagination = strtolower($redirect_pagination).'/';

	if (isset($redirect_pagination)){
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: https://".$_SERVER['HTTP_HOST'].$redirect_pagination); 
		exit(); 
	}
}

if (strpos($_SERVER['REQUEST_URI'], 'catalog/') !== false)
{
	if (strpos($_SERVER['REQUEST_URI'], 'arrFilter') !== false)
	{
		$_uriCheck = $_SERVER['REQUEST_URI'];

		$_tempGet = array();
		$_tempGet = $_GET;
		unset($_tempGet['set_filter']);
		unset($_tempGet['rz_all_elements']);

		// записать в базу брендов кодированный xml id 
		$isset_code_xml_id = $mysqli->query('SELECT UF_XML_ID_CODE from `b_brenz` LIMIT 1', $o_con); 
		if ($isset_code_xml_id == false)
		{
			$mysqli->query("ALTER TABLE `b_brenz` ADD COLUMN `UF_XML_ID_CODE` TEXT ");
		}	

		$all_xml_id_brenz = array();	
		$select_xml_id_brenz = $mysqli->query("SELECT `UF_XML_ID` FROM `b_brenz` ");
		while ($row_brenz = $select_xml_id_brenz->fetch_assoc()) 
		{
			$all_xml_id_brenz[$row_brenz['UF_XML_ID']] = abs(crc32($row_brenz['UF_XML_ID']));
		}

		if (isset($all_xml_id_brenz))
		{
			foreach ($all_xml_id_brenz as $_xml_id => $_code_xml_id) 
			{
				$mysqli->query("UPDATE `b_brenz` SET `UF_XML_ID_CODE` = '".$_code_xml_id."' WHERE `UF_XML_ID` = '".$_xml_id."' ");
			}
		}

		// записать код значений фильтров
		$isset_id_code_property_enum = $mysqli->query('SELECT ID_CODE from `b_iblock_property_enum` LIMIT 1', $o_con); 
		if ($isset_id_code_property_enum == false)
		{
			$mysqli->query("ALTER TABLE `b_iblock_property_enum` ADD COLUMN `ID_CODE` TEXT ");
		}

		$all_id_property_enum = array();
		$select_id_property_enum = $mysqli->query("SELECT ID FROM `b_iblock_property_enum`");
		while ($row_id = $select_id_property_enum->fetch_assoc()) {
			$all_id_property_enum[$row_id['ID']] = abs(crc32($row_id['ID']));
		}

		if (isset($all_id_property_enum))
		{
			foreach ($all_id_property_enum as $_id => $_code_id) 
			{
				$mysqli->query("UPDATE `b_iblock_property_enum` SET `ID_CODE` = '".$_code_id."' WHERE `ID` = '".$_id."' ");
			}
		}

		$_filter = array();
		foreach ($_tempGet as $_arrfilter => $_valueFilter) {
			if (strpos($_arrfilter, 'arrFilter') !== false)
			{	

				preg_match('/arrFilter_([0-9]+)_/', $_arrfilter, $id_property);
				$id_property = $id_property[1];

				$nameTranslitFilter = array();
				$selecrNameTranslit = $mysqli->query("SELECT `NAME_TRANSLIT` FROM `b_iblock_property` WHERE  `ID` = '".$id_property."' ");
				while ($row_code_name = $selecrNameTranslit->fetch_assoc()) 
				{	
					$nameTranslitFilter = $row_code_name['NAME_TRANSLIT'];	
				}

				$temp_string = preg_replace('/arrFilter_[0-9]+_/', '', $_arrfilter);

				// ищем код в базе брендов
				$select_name_translit_brenz = $mysqli->query("SELECT `UF_NAME_TRANSLIT` FROM `b_brenz` WHERE `UF_XML_ID_CODE` = '".$temp_string."' ");
				while ($nameBrenz = $select_name_translit_brenz->fetch_assoc()) {
					$_filter['vadim'] = $nameBrenz['UF_NAME_TRANSLIT']; 
				}

				// ищем код в базе фильтров
				$select_val_translit_enum = $mysqli->query("SELECT `VALUE_TRANSLIT` FROM `b_iblock_property_enum` WHERE `ID_CODE` = '".$temp_string."' ");
				while ($selectNameFilter = $select_val_translit_enum->fetch_assoc()) {
					$_filter[$nameTranslitFilter] = $selectNameFilter['VALUE_TRANSLIT'];
				}
			}	
		}

		if (isset($_filter))
		{
			foreach ($_filter as $_filters => $_optionsFilter) {
				$uri_string .= $_filters.'-'.$_optionsFilter.'/';
			}
		}

		$uri_string = '/catalog/'.$uri_string;

		if (isset($uri_string))
		{
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: https://".$_SERVER['HTTP_HOST'].$uri_string); 
			exit(); 
		}
		
	}
}


if (substr($_SERVER['REQUEST_URI'], -1) != '/')
{
	if (strpos($_SERVER['REQUEST_URI'], '/compare') === false)
	{
		if (strpos($_SERVER['REQUEST_URI'], '.htm') === false)
		{
			header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/', true, 301);
			exit();
		}
	}
}

/*
* ТРАНСЛИТЕРАЦИЯ ДЛЯ ОПЦИЙ
*/
	/* есть ли столбец транслитирированных значений
	*  если не существует - создадим 	
	*/
	$isset_column_value = $mysqli->query('SELECT VALUE_TRANSLIT from `b_iblock_property_enum` LIMIT 1', $o_con); 
	if ($isset_column_value == false){
		$mysqli->query("ALTER TABLE `b_iblock_property_enum` ADD COLUMN `VALUE_TRANSLIT` TEXT ");
	}

	// выбираем опции фильтров
	$select_options_filter = $mysqli->query("SELECT  `VALUE`, `VALUE_TRANSLIT` FROM `b_iblock_property_enum` ");
	$result_options_filters = array();
	while ($row_options = $select_options_filter->fetch_assoc()) {
		$result_options_filters[] = $row_options;
	}

	// транслитирируем опции и создаем массив соответствий
	if (isset($result_options_filters)){
		foreach ($result_options_filters as $key_options => $value) {
			$result_options_filters[$key_options]['to_translit'] = translits($value['VALUE']);
		}
	}

	// запишем транслитирированые значения в таблицу
	if (isset($result_options_filters)){
		foreach ($result_options_filters as $key_options => $value_options) {
			$mysqli->query("UPDATE `b_iblock_property_enum` SET `VALUE_TRANSLIT` = '".$value_options["to_translit"]."' WHERE `VALUE` = '".$value_options["VALUE"]."' AND `VALUE_TRANSLIT` is NULL");
		}
	}

/*
*
* ТРАНСЛИТЕРАЦИЯ ДЛЯ БРЕНДОВ
*
*/	
	/* есть ли столбец транслитирированных значений
	*  если не существует - создадим 	
	*/
	$isset_column_value = $mysqli->query('SELECT UF_NAME_TRANSLIT from `b_brenz` LIMIT 1', $o_cons); 
	if ($isset_column_value == false){
		$mysqli->query("ALTER TABLE `b_brenz` ADD COLUMN `UF_NAME_TRANSLIT` TEXT ");
	}	

	// выбираем название бренда
	$select_options_brand = $mysqli->query("SELECT  `UF_NAME`, `UF_NAME_TRANSLIT` FROM `b_brenz` ");
	$result_brands = array();
	while ($row_brands = $select_options_brand->fetch_assoc()) {
		$result_brands[] = $row_brands;
	}

	// транслитирируем бренды и создаем массив соответствий
	if (isset($result_brands)){
		foreach ($result_brands as $key_brands => $value_brand) {
			$result_brands[$key_brands]['to_translits'] = translits($value_brand['UF_NAME']);
		}
	}

	// запишем транслитирированые значения в таблицу
	if (isset($result_brands)){
		foreach ($result_brands as $key_options_brand => $value_options_brand) {
			$mysqli->query("UPDATE `b_brenz` SET `UF_NAME_TRANSLIT` = '".$value_options_brand["to_translits"]."' WHERE `UF_NAME` = '".$value_options_brand["UF_NAME"]."' AND `UF_NAME_TRANSLIT` is NULL");
		}
	}

/*
*
*  ТРАНСЛИТЕРАЦИЯ НАЗВАНИЯ ФИЛЬТРА
* 
*/
	/* проверим, есть ли столбец транслитирированных значений
	*  если не существует - создадим 	
	*/
	$isset_translit_filter_name = $mysqli->query('SELECT NAME_TRANSLIT from `b_iblock_property` LIMIT 1', $o); 
	if ($isset_translit_filter_name == false){
		$mysqli->query("ALTER TABLE `b_iblock_property` ADD COLUMN `NAME_TRANSLIT` TEXT ");
	}	

	// выбираем название фильтра
	$select_filter_name = $mysqli->query("SELECT  `CODE`, `NAME_TRANSLIT` FROM `b_iblock_property` ");
	$result_name_filter = array();
	while ($row_name_filter = $select_filter_name->fetch_assoc()) {
		$result_name_filter[] = $row_name_filter;
	}

	// транслитирируем названия фильтра и создаем массив соответствий
	if (isset($result_name_filter)){
		foreach ($result_name_filter as $key_filter_name => $value_name) {
			$result_name_filter[$key_filter_name]['to_translits'] = translits($value_name['CODE']);
		}
	}

	// запишем транслитирированые значения в таблицу
	if (isset($result_name_filter)){
		foreach ($result_name_filter as $key_options_filter_name => $filter_name) {
			$mysqli->query("UPDATE `b_iblock_property` SET `NAME_TRANSLIT` = '".$filter_name["to_translits"]."' WHERE `CODE` = '".$filter_name["CODE"]."' AND `NAME_TRANSLIT` is NULL");
		}
	}


/*
*  ЧПУ
*/	
$_SERVER['ORIGINAL_REQUEST_URI'] = $_SERVER['REQUEST_URI'];

if (isset($_SERVER['REQUEST_URI']) 
	&& strpos($_SERVER['REQUEST_URI'], 'filters') !== false 
	|| strpos($_SERVER['REQUEST_URI'], 'catalog') !== false
	&& strpos($_SERVER['REQUEST_URI'], '/compare/') === false
	){


	// разбиваем фильтры на название и опции. на выходе получим массив соответствий названия фильтра -> его опции
	$array_with_filter = array();	
	$uri = $_SERVER['REQUEST_URI'];

	// заглушка, если есть сортировки 
	if (strpos($uri, 'sort-') !== false){
		preg_match('/sort-.*/s', $uri, $sort_path);
		$sort_path = array_pop($sort_path);
		$uri = str_replace($sort_path, '', $uri);
	}	

	if (strpos($uri, 'pagen_1-') !== false){
		preg_match('/pagen_1-.*/s', $uri, $sort_path);
		$sort_path = array_pop($sort_path);
		$uri = str_replace($sort_path, '', $uri);
	}
	
	$uri = substr($uri, strpos($uri, '/filters/') + 9);	
	$explode = explode('/', $uri);	

	foreach ($explode as $value){		
		if (preg_match('/(.*)\-(.*)$/iU', $value, $match_filter)){			
			$array_with_filter[$match_filter[1]][] = $match_filter[2];		
		}	
	}

	// если есть несколько опций, разобьем по дефису 
	foreach ($array_with_filter as $name_filter => $valueOptionFilter) {
		foreach ($valueOptionFilter as $option) {
			if (strpos($option, '-') !== false){
				$exp_option = explode('-', $option);
				$array_with_filter[$name_filter] = $exp_option;	
			}		
		}
	}

	// получаем XML_ID опции
	$result_array_xmlId = array();
	$isFilter = array();
	$id_filter = array();
	foreach ($array_with_filter as $key_filterName => $valueOptionName) {
		foreach ($valueOptionName as $v) {
			// вытягиваем id фильтра 
			$select_id_filter  = $mysqli->query("SELECT `id` FROM `b_iblock_property` WHERE `NAME_TRANSLIT` = '".$key_filterName."' ");

			while ($row_id = $select_id_filter->fetch_assoc()) {
				$id_filter  = $row_id;
			}
			$id_filter = $id_filter['id'];
	
			if ($key_filterName == 'vadim'){
				$select_xmlId = $mysqli->query("SELECT `UF_XML_ID` FROM `b_brenz` WHERE `UF_NAME_TRANSLIT` = '".$v."' ");
			} else {
				$select_xmlId = $mysqli->query("SELECT `XML_ID` FROM `b_iblock_property_enum` WHERE `VALUE_TRANSLIT` = '".$v."' AND `PROPERTY_ID` = '".$id_filter." ' ");
			}

			while ($row = $select_xmlId->fetch_assoc()) {
				$result_array_xmlId = $row;
			}

			if (isset($result_array_xmlId)){

				if ($v == 'v_nalichii'){
					$array_with_filter[$key_filterName][$v] = 'available';
				} elseif ($v == 'net_v_nalichii') {
					$array_with_filter[$key_filterName][$v] = 'not_available';
				} elseif ($key_filterName == 'vadim') {
					$array_with_filter[$key_filterName][$v] = $result_array_xmlId["UF_XML_ID"];
				} else {
					$array_with_filter[$key_filterName][$v] = $result_array_xmlId["XML_ID"];
				}

				$isFilter[$key_filterName][] = $array_with_filter[$key_filterName][$v];
			}
	 	} 
	}


	$endUrl = '';
	// создаем строку, которую запишем в REQUEST_URI
	foreach ($isFilter as $cat_filter_name => $xml_id_option_filter) {
			$url = $cat_filter_name . '-is-';
			$str = '';
			$masik = [];
			foreach ($xml_id_option_filter as $key => $id) {
				$masik[] = $id;
			}
			$str = implode('-or-', $masik);
			$endUrl .= $url . $str . '/';
	}


	if (isset($endUrl)){
		$endUrl .= 'apply/'; 
		preg_match('/(.*?)\/filters/s', $_SERVER['REQUEST_URI'], $path);
		$path = array_pop($path);
		$to_request_url = $path.'/filter/'.$endUrl;
		$_SERVER['REQUEST_URI'] = $to_request_url;
	}
}

// если есть сортировка, добавляем её ссылку к фильтрам
if (isset($sort_path)){
	$_SERVER['REQUEST_URI'] .= $sort_path;
}

/* ЛОГИКА ДЛЯ СОРТИРОВОК */	
if (strpos($_SERVER['REQUEST_URI'], 'sort-') !== false || strpos($_SERVER['REQUEST_URI'], 'view-') !== false){
	if (strpos($_SERVER['REQUEST_URI'], 'view-') !== false){
		preg_match('/view-.*/s', $_SERVER['REQUEST_URI'], $order_sort);
		$order_sort = array_pop($order_sort);
		if (substr($order_sort, -1) == '/'){
			$order_sort = substr($order_sort, 0, -1);
		}
	} else {
		preg_match('/sort-.*/s', $_SERVER['REQUEST_URI'], $order_sort);
		$order_sort = array_pop($order_sort);
		if (substr($order_sort, -1) == '/'){
			$order_sort = substr($order_sort, 0, -1);
		}
	}

	$explode_sort = array();
	$explode_sort = explode('/', $order_sort);

	$new_array_sort = array();	
	foreach ($explode_sort as $sort_by) {
		// для GET 
		preg_match('/(.*)-(.*)/s', $sort_by, $index_sort);
		if ($index_sort[1] == 'pagen_1'){
			$index_sort[1] = 'PAGEN_1';
		}

		$new_array_sort[$index_sort[1]] = $index_sort[2]; 
		
		// для REQUEST_URI
		$new_string = str_replace('-', '=', $sort_by);
		$array_uri_request[] = $new_string; 
	}

	if (isset($new_array_sort)){
		$_GET = $new_array_sort;
		$_REQUEST = $new_array_sort;
	}

	$string_uri_request = '?'.implode('&', $array_uri_request);   // ?sort=property_rating&by=desc&pagen_1=4
	if (strpos($string_uri_request, 'pagen_1') !== false){
		$string_uri_request = str_replace('pagen_1', 'PAGEN_1', $string_uri_request);	
	}

	if (strpos($_SERVER['REQUEST_URI'], 'view-') !== false){
		$s_request_uri = $_SERVER['REQUEST_URI']; // /akusticheskie-sistemy-dlya-kinoteatrov/
		$s_request_uri = preg_replace('/view-.*/s', '', $s_request_uri);
		$to_server_uri = $s_request_uri.$string_uri_request;
	} else {
		$s_request_uri = $_SERVER['REQUEST_URI']; // /akusticheskie-sistemy-dlya-kinoteatrov/
		$s_request_uri = preg_replace('/sort-.*/s', '', $s_request_uri);
		$to_server_uri = $s_request_uri.$string_uri_request;
	}

	$full_uri = $s_request_uri.$string_uri_request;

	$_SERVER['REQUEST_URI'] = $full_uri;
}


if (!isset($new_array_sort['PAGEN_1']))
{
	if (strpos($_SERVER['REQUEST_URI'], 'pagen_1') !== false)
	{
		preg_match('/pagen_1-([0-9]+)\//s', $_SERVER['REQUEST_URI'], $number_page_pagen);
		$new_array_sort['PAGEN_1'] = $number_page_pagen[1];
	}
}


//	пагинация
if (strpos($_SERVER['REQUEST_URI'], 'pagen_1')  !== false || strpos($_SERVER['REQUEST_URI'], 'sort') == false) {
	preg_match('/(pagen_1-.*\/)/s', $_SERVER['REQUEST_URI'], $pagination);
	$pagination_old = array_pop($pagination);	
	$pagination_old = str_replace('/', '', $pagination_old);	

	preg_match('/pagen_1-(.*)/', $pagination_old, $number_page);
	// var_dump($number_page);
	if (isset($number_page)){
		$_GET['PAGEN_1'] = $number_page[1];
		$_REQUEST['PAGEN_1'] = $number_page[1];
	}

	$_SERVER['REQUEST_URI'] = str_replace('pagen_1-', '?PAGEN_1=', $_SERVER['REQUEST_URI']);

}
/* !ЛОГИКА ДЛЯ СОРТИРОВОК */	

// изменить урл если в фильтре есть цена 	
if (strpos($_SERVER['REQUEST_URI'], 'filters') !== false){
	$_SERVER['REQUEST_URI'] = str_replace('filters', 'filter', $_SERVER['REQUEST_URI']);
}
if (strpos($_SERVER['REQUEST_URI'], '-site-') !== false){
	$_SERVER['REQUEST_URI'] = str_replace('-site-', '-сайт-', $_SERVER['REQUEST_URI']);
}
if (strpos($_SERVER['REQUEST_URI'], '-akciya-') !== false){
	$_SERVER['REQUEST_URI'] = str_replace('-akciya-', '-акционная-', $_SERVER['REQUEST_URI']);
}

/*
*
* ЧПУ ДЛЯ КАТАЛОГА БРЕНДОВ
* 
*
*/
if (strpos($_SERVER['ORIGINAL_REQUEST_URI'], 'catalog/') !== false && strpos($_SERVER['REQUEST_URI'], 'vadim') !== false){
	$link_to_brands = $_SERVER['REQUEST_URI'];

	$link_to_brands = str_replace('/filter/', '', $link_to_brands);
	$link_to_brands = str_replace('/apply/', '', $link_to_brands);

	$explodeLink = explode('/', $link_to_brands);

	$_arrayLink = array();
	foreach ($explodeLink as $_link) {
		$_linkExplode = explode('-is-', $_link);
		$_arrayLink[$_linkExplode[0]][] = array_pop($_linkExplode); 

	}

	foreach ($_arrayLink as $keys => $values) {
		foreach ($values as $_tempArrayKey) {
			if (strpos($_tempArrayKey, '-or-') !== false){
				$_tempExplode = explode('-or-', $_tempArrayKey);
				$_arrayLink[$keys] = $_tempExplode;
			}	
		}
	}

	$idFilterName = array();
	foreach ($_arrayLink as $_nameFilters => $_codeFilters) {
		foreach ($_codeFilters as $_xmlIdCode) {
			if ($_nameFilters == 'vadim'){
				$selectIdFilterName = $mysqli->query("SELECT `ID` FROM `b_iblock_property` WHERE `CODE` = '".$_nameFilters."' AND `IBLOCK_ID` = '16' ");
				while ($rowFilter = $selectIdFilterName->fetch_assoc()) {
					$_codeFiltersCode = abs(crc32($_xmlIdCode));
					$idFilterName['arrFilter_'.$rowFilter['ID'].'_'.$_codeFiltersCode] = 'Y'; 
				}
			} 
			else {
				$selectIdFilterName = $mysqli->query("SELECT `ID`, `PROPERTY_ID` FROM `b_iblock_property_enum` WHERE `XML_ID` = '".$_xmlIdCode."' ");
				while ($rowFilter = $selectIdFilterName->fetch_assoc()) {
					$_codeFiltersCode = abs(crc32($rowFilter['ID']));
					$idFilterName['arrFilter_'.$rowFilter['PROPERTY_ID'].'_'.$_codeFiltersCode] = 'Y'; 
				}
			}
		}
	}

	if (isset($idFilterName)){
		$_GET = $idFilterName;
		$_REQUEST = $idFilterName;
		$_GET['set_filter'] = 'y';
		$_GET['rz_all_elements'] = 'y';
		$_REQUEST['set_filter'] = 'y';
		$_REQUEST['rz_all_elements'] = 'y';
	}


	if (isset($new_array_sort))
	{
		foreach ($new_array_sort as $_keySort => $_valueSort) 
		{
			$_GET[$_keySort] = $_valueSort;
			$_REQUEST[$_keySort] = $_valueSort;
		}		
	}

	$_SERVER['REQUEST_URI'] = '/catalog/';
}


/*    
*
* ПРОВЕРКА ФИЛЬТРОВ В УРЛЕ
*
*/
if (strpos($_SERVER['ORIGINAL_REQUEST_URI'], 'filters/') !== false)
{
	$uris = $_SERVER['ORIGINAL_REQUEST_URI'];
	preg_match('/(.*?)\/filters/s', $uris, $match_cat);
	$uris = str_replace($match_cat[0], '', $uris);
	$exp = explode('/', $uris);
	foreach ($exp as $_k => $_v) 
	{
		if (empty($_v))
			unset($exp[$_k]);
	}

	// массив зажатых фильтров
	$array_element_filters = array();
	foreach ($exp as $element_filter) 
	{
		$temp_array = explode('-', $element_filter);

		$temp = $temp_array[0];
		unset($temp_array[0]);

		$array_element_filters[$temp] = $temp_array;
	}


	if (isset($array_element_filters) && !empty($array_element_filters))
	{
		// вытягиваем все названия фильтров
		$select_all_name_filter = array();
		$_filterNameAll = $mysqli->query("SELECT `NAME_TRANSLIT` FROM `b_iblock_property`");
		while ($select_all_name_filter = $_filterNameAll->fetch_assoc()) 
		{
			$allNameFilter[] = $select_all_name_filter['NAME_TRANSLIT'];
		}


		// вытягиваем все опции фильтров
		$select_all_name_filter_options = array();
		$_filterOptionsAll = $mysqli->query("SELECT `VALUE_TRANSLIT` FROM `b_iblock_property_enum`");
		while ($select_all_name_filter_options = $_filterOptionsAll->fetch_assoc()) 
		{
			$_temp_allOptionsFilter[] = $select_all_name_filter_options['VALUE_TRANSLIT'];
		}

		// вытягиваем все название брендов
		$select_all_name_brands = array();
		$_filterNameBrand = $mysqli->query("SELECT `UF_NAME_TRANSLIT` FROM `b_brenz` ");
		while ($select_all_name_brands = $_filterNameBrand->fetch_assoc()) 
		{
			$allNameBrands[] = $select_all_name_brands['UF_NAME_TRANSLIT'];
		}

		$allOptionsFilter = array_merge($_temp_allOptionsFilter, $allNameBrands);

		// добавить сортировки 
		$arSort = array(
			'created',
			'name',
			'shows',
			'property_rating',
			'price',
			'property_hit',
			'desc',
			'asc'
			);

		$allOptionsFilter = array_merge($allOptionsFilter, $arSort);

		$allNameFilter[] = 'pagen_1';	
		$allNameFilter[] = 'sort';	
		$allNameFilter[] = 'by';	

		/* 
			----------------------------------------------------
			$array_element_filters   -  массив зажатых фильтров
			$allNameFilter           -  название фильтров	
			$allOptionsFilter        -  название опций фильтров
			----------------------------------------------------
		*/
		if (isset($array_element_filters['pagen_1']))
			unset($array_element_filters['pagen_1']);	

		
		// проверка на соответствие фильтров в урле с всеми существующими фильтрами	
		foreach ($array_element_filters as $_filtersName => $_optionsName) 
		{
			if (!in_array($_filtersName, $allNameFilter))
			{
				header("HTTP/1.1 301 Moved Permanently"); 
				header("Location: https://".$_SERVER['HTTP_HOST'].'/404/'); 
				exit(); 
			}

			foreach ($_optionsName as $_options) 
			{

				if (!in_array($_options, $allOptionsFilter))
				{
					header("HTTP/1.1 301 Moved Permanently"); 
					header("Location: https://".$_SERVER['HTTP_HOST'].'/404/'); 
					exit(); 
				}
			}
		}
	}
}


if (preg_match('/pagen_1-(.*?)\//s', $_SERVER['ORIGINAL_REQUEST_URI'], $select_pagen))
{
	$select_pagen = array_pop($select_pagen);
	if (!preg_match('/^([0-9]+)$/s', $select_pagen))
	{
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: https://".$_SERVER['HTTP_HOST'].'/404/'); 
		exit(); 
	}
}



?>
