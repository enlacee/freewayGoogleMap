<?php
/**
 * Template Name: Search
 *
 * Displays Full Search.
 *
 * @package Path
 * @subpackage Template
 * @since 0.1.0
 */

get_header(); // Loads the header.php template. ?>

<script type="text/javascript" src="<?php bloginfo("template_directory"); ?><?php echo "/js/jquery-ui-1.10.0.custom.min.js"; ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php bloginfo("template_directory"); ?><?php echo "/css/smoothness/jquery-ui-1.10.0.custom.min.css" ?>" >

<form action="" name="frmSearch" method="POST">

    <input type="text" name="page" value="1"/>
    <input type="text" name="rows" value="12"/>
    <input type="text" name="sidx" value="id_master_office"/>
    <input type="text" name="sord" value="asc"/>
    <hr>

	<input type="submit" name="search" value="Search" class="button-primary"/>
	<label>Zip</label>
	<input type="text" name="zip" value="">
	<br>
	<label>City</label>
	<input type="text" name="city" value="">
	<br>
	<label>Estate</label>
	<input type="text" name="state" value="">
</form>


<?php
if(isset($_POST['search'])){
	// echo "<pre>";
	// print_r($_REQUEST);
	// echo "<pre>";
    if( !empty($_POST['zip']) ){
        $_PARAM['searchField'] = 'zip';
        $_PARAM['searchString'] = $_POST['zip'];
        $_PARAM['searchOper']	= 'eq';
    }else if( !empty($_POST['city'] )){
        $_PARAM['searchField'] = 'city';
        $_PARAM['searchString'] = $_POST['city'];
        $_PARAM['searchOper']	= 'cn';
    }else if( !empty($_POST['state']) ){
        $_PARAM['searchField'] = 'state';
        $_PARAM['searchString'] = $_POST['state'];
        $_PARAM['searchOper']	= 'cn';
    }

    $_PARAM['page'] 		= $_POST['page'];
    $_PARAM['rows'] 		= $_POST['rows'];
    $_PARAM['sidx'] 		= $_POST['sidx'];
    $_PARAM['sord'] 		= $_POST['sord'];

    $data = search($_PARAM);
	echo "<pre>";
	print_r($data);
	echo "<pre>";
}
?>


<?php get_footer(); // Loads the footer.php template. ?>

<?php
function search($_PARAM){
    global $wpdb;

    $page = $_PARAM['page']; //1
    $limit = $_PARAM['rows'];//12
    $sidx = $_PARAM['sidx'];
    $sord = $_PARAM['sord'];

    if (!$sidx)
        $sidx = 1;
    // echo "<pre>";
    // print_r($_PARAM);
    // echo "</pre>";

    $WHERE = '';
    if (isset($_PARAM['searchField']) && ($_PARAM['searchString'] != null)) {
        $operadores["eq"] = "=";
        $operadores["ne"] = "<>";
        $operadores["lt"] = "<";
        $operadores["le"] = "<=";
        $operadores["gt"] = ">";
        $operadores["ge"] = ">=";
        $operadores["cn"] = "LIKE";
        if ($_PARAM['searchOper'] == "cn")
            $WHERE = "WHERE " . $_PARAM['searchField'] . " " . $operadores[$_PARAM['searchOper']] . " '%" . $_PARAM['searchString'] . "%' ";
        else
            $WHERE = "WHERE " . $_PARAM['searchField'] . " " . $operadores[$_PARAM['searchOper']] . "'" . $_PARAM['searchString'] . "'";
    }

    //-------------------------- query
    $query2 = "SELECT count(*) as count FROM wp_master_office $WHERE;";
    $count = $wpdb->get_var($query2,0,0);

    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    }else{
    	$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit;
    //valida
    if ($start < 0)
        $start = 0;

    //-------------------------- query
    if (!empty($WHERE)) {
        $cadena = "$WHERE  ORDER BY $sidx $sord LIMIT $start,  $limit";
    }else{
    	$cadena = null;
    }
    $query = "SELECT code, office FROM wp_master_office $cadena;";
    $lista = $wpdb->get_results($query);

    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $responce->data = $lista;
	return $responce;

}
?>