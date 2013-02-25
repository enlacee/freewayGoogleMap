<?php
/**
 * Template Name: Find an Office
 *
 * Description: Página principal de Freeway The Auto Insurance Experts
 *
 * @package WordPress
 * @subpackage Freeway
 * @since Freeway
 */

get_header(); ?>
<?php
    if(isset($_REQUEST['search'])){

        if( !empty($_REQUEST['zip']) ){
            $_PARAM['searchField']  = 'zip';
            $_PARAM['searchString'] = $_REQUEST['zip'];
            $_PARAM['searchOper']   = 'eq';
        }else if( !empty($_REQUEST['city'] )){
            $_PARAM['searchField']  = 'city';
            $_PARAM['searchString'] = $_REQUEST['city'];
            $_PARAM['searchOper']   = 'cn';
        }else if( !empty($_REQUEST['office']) ){
            $_PARAM['searchField']  = 'office';
            $_PARAM['searchString'] = $_REQUEST['office'];
            $_PARAM['searchOper']   = 'cn';
        }else{
            $_PARAM['searchField']  = 'office';
            $_PARAM['searchString'] = '0';
            $_PARAM['searchOper']   = 'cn';
        }
        $_PARAM['page']         = $_REQUEST['page'];
        $_PARAM['rows']         = $_REQUEST['rows'];
        $_PARAM['sidx']         = $_REQUEST['sidx'];
        $_PARAM['sord']         = $_REQUEST['sord'];

        $data = search($_PARAM);
        /*echo "<pre>";
        print_r($_PARAM);
        print_r($data);
        echo "</pre>";*/

        $data_office = $data->data;
    }
?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

<script type="text/javascript">
 //         Funciones de Ayuda

function IsNumber(event){
    //var nav4 = window.Event ? true : false;
    // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
    //var key = nav4 ? event.which : event.keyCode;
    var key = event.which || event.keyCode;
    //alert(nav4);
    //alert(key);
    return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
}


        //------------------------------[1]
            $(document).ready(function(){
                initialize();
                //--
                var $id_post ="<?php echo $_REQUEST['tab'];?>";

            //ocultarTodos();
            $('.menu-horizonal li a').click(function(event){
                event.preventDefault();
                $link_click = $(this);
        //-----------------------------------------------------
                //EACH
                $links = $('.menu-horizonal li a');
                $.each($links, function(index, value) {
                    var id = $(value).attr('data');
                    // ROMEVER ACTIVE
                    $(value).removeClass('active');
                    // REMOVER VISIBLES TAB
                    $(id).addClass('hidden');
                });//endEACH

        //-----------------------------------------------------
        //-----------------------------------------------------
                if($link_click){
                    $link_click.removeClass('hidden')
                                .addClass('active');
                    //ver
                    var id = $link_click.attr('data');
                    $(id).removeClass('hidden');
                    clearSearch();
                    $("#tab").val(id);
                }
            });


            });//END JQUERY READY

            clearSearch = function (){
                var form = document.myform;
                form.zip.value ='';
                form.city.value='';
            }
            // Funcion de Pagina
            changePag = function(event,number){
                event.preventDefault();
                var form = document.myform;
                form.page.value = number;
                form.submit();
            }
//------------------ google map-------------------------


</script>

<script type ="text/javascript">
  function initialize() {
    var init_lat = 42.6847550;
    var init_long = -73.85430040;
<?php
if(isset($data_office)):
    foreach($data_office as $indice => $value):
        $value->gm_lat = str_replace(' ','',$value->gm_lat);
        $value->gm_lnt = str_replace(' ','',$value->gm_lnt);
        if($value->gm_lnt!="" && $value->gm_lat!=""):
            if($indice==0){ echo "init_lat=$value->gm_lat; init_long=$value->gm_lnt;";}
        endif;
    endforeach;
endif;
?>



    var latTotal = new google.maps.LatLng(init_lat, init_long);
    var myOptions = {
        zoom : 9,
        center: latTotal,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
    var infowindow = new google.maps.InfoWindow({
        content: ''
    });

    var marcadores = [
    <?php
    if(isset($data_office)):
    foreach($data_office as $indice => $value):
        $value->gm_lat = str_replace(' ','',$value->gm_lat);
        $value->gm_lnt = str_replace(' ','',$value->gm_lnt);
        if($value->gm_lnt!="" && $value->gm_lat!=""){
    ?>
        {
            positionMarcadores:{
                lat : <?php echo $value->gm_lat; ?>,
                lng : <?php echo $value->gm_lnt; ?>
            },
            contenido: "<?php echo $value->office;?>"
        },

    <?php
    }
    endforeach;
    endif;
    ?>
    ];
    for (var i = 0, j = marcadores.length; i < j; i++) {
        var description = marcadores[i].contenido;
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(marcadores[i].positionMarcadores.lat, marcadores[i].positionMarcadores.lng),
            map: map
        });
        (function(marker, description){
                google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(description);
                infowindow.open(map, marker);
            });
        })(marker,description);
    }

  }

//-------
    //
  function getdirection(lat,lgt,direc,Phone,Atencion) {
    var latlng = new google.maps.LatLng(lat,lgt);
    var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
    var contentString = direc + '<br> Phone: '+ Phone + '<br>' +Atencion

    var infowindow = new google.maps.InfoWindow({content: contentString});

    marker = new google.maps.Marker({
      map:map,
      draggable:true,
      animation: google.maps.Animation.DROP,
      position: latlng
    });

    google.maps.event.addListener(marker, 'click', function(){
      infowindow.open(map,marker);
        });
  }//end initialize

</script>

<style type="text/css">
    .hidden{
        boder:1px solid red !important;
        display: none !important;
    }

    .paginate {
    font-family:Arial, Helvetica, sans-serif;
        padding: 3px;
        margin: 3px;
    }

    .paginate a {
        padding:2px 5px 2px 5px;
        margin:2px;
        border:1px solid #999;
        text-decoration:none;
        color: #666;
    }
    .paginate a:hover, .paginate a:active {
        border: 1px solid #999;
        color: #000;
    }
    .paginate span.current {
        margin: 2px;
        padding: 2px 5px 2px 5px;
            border: 1px solid #999;

            font-weight: bold;
            background-color: #999;
            color: #FFF;
        }
        .paginate span.disabled {
            padding:2px 5px 2px 5px;
            margin:2px;
            border:1px solid #eee;
            color:#DDD;
        }
</style>



	<section class="main-find-offices">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php if ( is_search() ) :?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>
        <?php else : ?>
        <section class="entry-content">
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
            <article class="top">

                <div class="right">
                    <?php //echo $data->paginate; ?>
                </div>

                <?php
                $tab = ($_REQUEST['tab']) ? $_REQUEST['tab'] :false;
                ?>
                <nav>
                    <ul class="menu-horizonal select">
                        <?php if($tab):?>
                        <li><a data="#zipcode" class="<?php echo ($tab=="#zipcode")? 'active':'';?>" >By ZIP Code</a> </li>
                        <li><a data="#city" class="<?php echo ($tab=="#city")? 'active':'';?>">By City</a> </li>
                        <?php else:?>
                        <li><a data="#zipcode" class="active" >By ZIP Code</a> </li>
                        <li><a data="#city" >By City</a> </li>
                        <?php endif;?>
                    </ul>
                </nav>

            </article>


            <article id="" class="list">

                <form action="" method="post" name="myform" id="myform" class="find">
                    <div class="hidden">
                    page<input type="text" name="page" value="<?php echo ($_REQUEST['page']) ? $_REQUEST['page'] : '1'; ?>"/>
                    row o limit<input type="text" name="rows" value="4"/>
                    sidx<input type="text" name="sidx" value="id"/>
                    sord<input type="text" name="sord" value="asc"/>
                    tab<input type="text" name="tab" id="tab" value="<?php echo $tab; ?>">
                    <input type="hidden" name="search" id="search" value="search"/>
                    </div>
                    <!-- <input id="zip" name="zip" placeholder="Enter a Zip code" class="field" type="text"/> -->


                    <!-- inicio tab -->
                    <?php if($tab):?>
                        <div id ="zipcode" class="<?php echo ($tab=="#zipcode")? 'active':'hidden';?>">
                        <input name="zip" type="text" class="field" placeholder="Enter Zip Code Office"  onkeypress="return IsNumber(event)"
                        value = "<?php echo ($_REQUEST['zip']) ? $_REQUEST['zip'] : ''; ?>" maxlength="5" />
                        <input type="submit" name="go" class="go" id="send" value="zipcode"/>
                        </div>

                        <div id ="city" class ="<?php echo ($tab=="#city")? 'active':'hidden';?>">
                        <input name="city" placeholder="Enter City Office" class="field" type="text"
                        value = "<?php echo ($_REQUEST['city']) ? $_REQUEST['city'] : ''; ?>" />
                        <input type="submit" name="go" class="go" id="send" value="city"/>
                        </div>
                    <?php else:?>
                        <div id ="zipcode" class="">
                        <input name="zip" type="text" class="field" placeholder="Enter Zip Code Office" onkeypress="return IsNumber(event)"
                        value = "<?php echo ($_REQUEST['zip']) ? $_REQUEST['zip'] : ''; ?>" maxlength="5" />
                        <input type="submit" name="go" class="go" id="send" value="zipcode"/>
                        </div>

                        <div id ="city" class ="hidden">
                        <input name="city" placeholder="Enter City Office" class="field" type="text"
                        value = "<?php echo ($_REQUEST['city']) ? $_REQUEST['city'] : ''; ?>" />
                        <input type="submit" name="go" class="go" id="send" value="city"/>
                        </div>
                    <?php endif;?>



                </form>

                <h2><?php if($data->records):?>
                        <?php echo $data->records; ?> Offices near <?php echo $data->recordsBase; ?>
                    <?php endif;?>
                </h2>
                <ul>
                <?php if(isset($data_office)): ?>
                    <?php foreach($data_office as $indice => $value): ?>
                    <li class="third">
                        <div class="image"><img src="<?php echo get_template_directory_uri();?>/images/office001.jpg"/></div>
                        <div class="text">
                            <h4><?php echo $value->office; ?></h4>
                            <p><?php echo $value->street_address_01;?></br>
                               <?php echo $value->city." ".$value->state;?></br>
                            Phone: <?php echo $value->phone; ?></p>
                            <a href="#">Hablamos español</a>
                        </div>
                        <div class="buttons">
                            <a href="profile.php" class="site">Visit site</a>
                            <a href="#" class="email">Email</a>
                            <a href="#" class="call">Clic to call</a>
                            <?php
                            $zoom = $value->gm_lat.",".$value->gm_lnt.",'".$value->office."','".$value->street_address_01."',null";
                            ?>
                            <a href="#" class="directions" onclick="getdirection(<?php echo $zoom;?>)">Get directions</a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                </ul>
            </article>







            <article class="map" id="map_canvas" style="background-color:blue;">
            </article>








            <article class="top bottom">
                <div class="right">
                    <?php echo $data->paginate; ?>
                </div>
            </article>
        </section><!-- .entry-content -->
        <?php endif; ?>
	</section>
    </div>
<?php get_footer(); ?>

<?php
function search($_PARAM){
    global $wpdb;
    $tableName="wp_master_office";
    $targetpage = "";

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
    if (isset($_PARAM['searchField']) /*&& ($_PARAM['searchString'] != null)*/) {
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
    $query2 = "SELECT count(*) as count FROM $tableName $WHERE;";
    $count = $wpdb->get_var($query2,0,0);
    // echo "<pre>2";
    // print_r($query2);
    // echo "</pre>";

    $count_base = $wpdb->get_var("SELECT count(*) as count FROM $tableName",0,0);

    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    }else{
        $total_pages = 0;
    }
    //valida
    if ($page == 0){$page = 1;}
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
    $query = "
    SELECT
    code,
    office,
    city,
    phone,
    street_address_01,
    state,
    gm_lat,
    gm_lnt
    FROM $tableName $cadena;";
    // echo "<pre>";
    // print_r($query);
    // echo "</pre>";
    $lista = $wpdb->get_results($query);

// inicio script pag
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total_pages/$limit);
    $LastPagem1 = $lastpage - 1;

    $stages = 3;

    // echo "<br>stages = $stages";
    // echo "<br>lastpage = $lastpage";
    // echo "<br>LastPagem1 = $LastPagem1";
    // echo "<br>total_pages = $total_pages";
    // echo "<br>page = $page";
    // echo "<br>prev = $prev";
    // echo "<br>next = $next";
    // echo "<br>";


    $paginate = '';
    if($lastpage > 1)
    {
        $paginate .= "<div class='paginate'>";
        // Previous
        if ($page > 1){
            $paginate.= "<a href='#' onclick='changePag(event,$prev)' >previous</a>";
        }else{
            $paginate.= "<span class='disabled'>previous</span>";   }



        // Pages
        if ($lastpage < 7 + ($stages * 2))  // Not enough pages to breaking it up
        {
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page){
                    $paginate.= "<span class='current'>$counter</span>";
                }else{
                    $paginate.= "<a href='#' onclick='changePag(event,$counter)'>$counter</a>";}
            }
        }
        elseif($lastpage > 5 + ($stages * 2))   // Enough pages to hide a few?
        {
            // Beginning only hide later pages
            if($page < 1 + ($stages * 2))
            {
                for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
                {
                    if ($counter == $page){
                        $paginate.= "<span class='current'>$counter</span>";
                    }else{
                        $paginate.= "<a href='#' onclick='changePag(event,$counter)' >$counter</a>";}
                }
                $paginate.= "...";
                $paginate.= "<a href='#' onclick='changePag(event,$LastPagem1)' >$LastPagem1</a>";
                $paginate.= "<a href='#' onclick='changePag(event,$lastpage)' >$lastpage</a>";
            }
            // Middle hide some front and some back
            elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
            {
                $paginate.= "<a href='#' onclick='changePag(event,1)' >1</a>";
                $paginate.= "<a href='#' onclick='changePag(event,2)' >2</a>";
                $paginate.= "...";
                for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
                {
                    if ($counter == $page){
                        $paginate.= "<span class='current'>$counter</span>";
                    }else{
                        $paginate.= "<a href='#' onclick='changePag(event,$counter)' >$counter</a>";}
                }
                $paginate.= "...";
                $paginate.= "<a href='#' onclick='changePag(event,$LastPagem1)' >$LastPagem1</a>";
                $paginate.= "<a href='#' onclick='changePag(event,$lastpage)' >$lastpage</a>";
            }
            // End only hide early pages
            else
            {
                $paginate.= "<a href='#' onclick='changePag(event,1)' >1</a>";  /*"<a href='$targetpage?page=1'>1</a>"*/
                $paginate.= "<a href='#' onclick='changePag(event,2)' >2</a>";
                $paginate.= "...";
                for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page){
                        $paginate.= "<span class='current'>$counter</span>";
                    }else{
                        $paginate.= "<a href='#' onclick='changePag(event,$counter)' >$counter</a>";}
                }
            }
        }

                // Next
        if ($page < $counter - 1){
            $paginate.= "<a href='#' onclick='changePag(event,$next)' >next</a>";
        }else{
            $paginate.= "<span class='disabled'>next</span>";
            }

        $paginate.= "</div>";


    }

// final script pag
    $response->page = $page;
    $response->total = $total_pages;
    //$response->start = $start;
    $response->records = $count;
    $response->recordsBase = $count_base;
    $response->data = $lista;
    //----------pagination--------------
    $response->paginate = $paginate;
    return $response;
}
?>
