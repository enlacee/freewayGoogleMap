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
<!--
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?><?php echo "/js/jquery-ui-1.10.0.custom.min.js"; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo("template_directory"); ?><?php echo "/css/smoothness/jquery-ui-1.10.0.custom.min.css" ?>" >
-->


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
        echo "<pre>";
        print_r($_PARAM);
        //print_r($data);
        echo "</pre>";

        $data_office = $data->data;
/*
echo "<pre>";
print_r($data_office);
echo "</pre>";*/
    }
    ?>






<script type="text/javascript">
    $(document).ready(function(){
        console.log("LOAD");

    //ocultarTodos();
    $('.menu-horizonal li a').click(function(event){
        event.preventDefault();
        $link_click = $(this);
//-----------------------------------------------------
        //EACH
        $links = $('.menu-horizonal li a');
        $.each($links, function(index, value) {
            // ROMEVER ACTIVE
            $(value).removeClass('active');
            // REMOVER VISIBLES TAB
            var id = $(value).attr('href');
            $(id).addClass('hidden');
        });//endEACH
//-----------------------------------------------------
        if($link_click){ // OJOOOO HIDDEN
            $link_click.removeClass('hidden')
                        .addClass('active');
            //ver
            var id = $link_click.attr('href');
            $(id).removeClass('hidden');
            clearSearch();
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
        console.log( number );
        form.submit();
    }

// GOOGLE MAP

  function initialize(lat,lgt,direc,Phone,Atencion) {
    var latlng = new google.maps.LatLng(lat,lgt);
    var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas_custom_54699"),myOptions);
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


/*
function initialize(lat,lnt,a,b,c){

     var myLatlng = new google.maps.LatLng(lat,lnt);
      var myOptions = {
        zoom: 4,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
      }
     var map = new google.maps.Map(document.getElementById("map_canvas_custom_54699"), myOptions);

     var marker = new google.maps.Marker({
          position: myLatlng,
          title:"Hello Superposicion!"
      });

      // AGREGANDO el marker en el Mapa,s call setMap();
      marker.setMap(map);

}
*/


</script>

<script type="text/javascript">

(function(d,t){
    if (document.getElementById("mggapiloader") == null){
        var maps_api = 'http://maps.google.com/maps/api/js?sensor=false&language=en&callback=initializeMgMaps';
        var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
        g.src = maps_api;
        g.id = "mggapiloader";
        s.parentNode.insertBefore(g, s);
    }
}(document, 'script'));

if (typeof mapObjWrapper === 'undefined'){
    var mapObjWrapper = [];
}
mapObjWrapper.push({
    "id":54699,
    "map_lat":39.64673444507218, //36.026474
    "map_lng":-95.734306, //-101.734306
    "map_type":"roadmap",
    "map_zoom":10,
    "sv_active":null,
    "sv_lat":null,
    "sv_lng":null,
    "sv_heading":null,
    "sv_pitch":null,
    "sv_zoom":10,
    "cloud":null,
    "weather_info":null,
    "markers":[
    <?php
    if(isset($data_office)):
    foreach($data_office as $indice => $value):
        $value->gm_lat = str_replace(' ','',$value->gm_lat);
        $value->gm_lnt = str_replace(' ','',$value->gm_lnt);
        if($value->gm_lnt!="" && $value->gm_lat!=""){
    ?>
    {
        "lat":<?php echo (empty($value->gm_lat)) ? '38.555474567327764' : $value->gm_lat;?>,
        "lng":<?php echo (empty($value->gm_lnt)) ? '-95.66499999999996' : $value->gm_lnt;?>,
        "icon":"http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|FE7C71|000000",
        "content":"<p><?php echo $value->street_address_01;?><\/p>",
        "heading":"<p style=\"margin:0;\"><?php echo $value->office;?><\/p>",
        "is_open":true
    },
    <?php
    }
    endforeach;
    endif;
    ?>
    ]
});
var loadMgMap = function(mapobj){
    var map = new google.maps.Map(document.getElementById('map_canvas_custom_' + mapobj.id), {
        zoom: mapobj.map_zoom,
        center: new google.maps.LatLng(mapobj.map_lat,mapobj.map_lng),
        mapTypeId: google.maps.MapTypeId = mapobj.map_type
    });
    if(mapobj.weather_info){
        var weatherLayer = new google.maps.weather.WeatherLayer();
        weatherLayer.setMap(map);
    }
    if(mapobj.cloud){
        var cloudLayer = new google.maps.weather.CloudLayer();
        cloudLayer.setMap(map);
    }
    if(mapobj.bicycle_layer){
        var bikeLayer = new google.maps.BicyclingLayer();
        bikeLayer.setMap(map);
    }
    if(mapobj.transit_layer){
        var transitLayer = new google.maps.TransitLayer();
        transitLayer.setMap(map);
    }
    if(mapobj.traffic_layer){
        var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map);
    }
    if(mapobj.sv_active){
        var pa = map.getStreetView();
        pa.setPosition(new google.maps.LatLng(mapobj.sv_lat,mapobj.sv_lng));
        pa.setPov({
            heading: mapobj.sv_heading,
            pitch: mapobj.sv_pitch,
            zoom: mapobj.sv_zoom
        });
        pa.setVisible(mapobj.sv_active);
    }
    var infowindow = new google.maps.InfoWindow({
        maxWidth: 250
    }),  marker,  i,  place;
    for (i = 0; i < mapobj.markers.length; i++) {
        place = mapobj.markers[i];
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(place.lat,place.lng),
            map: map,
            icon: place.icon,
            shadow: new google.maps.MarkerImage( "http://chart.apis.google.com/chart?chst=d_map_pin_shadow", new google.maps.Size(40, 37), new google.maps.Point(0,0), new google.maps.Point(13, 37) )
        });
        marker.html = "<div style='width:100%; color: #333333; font-family: \"Helvetica Neue\",Helvetica,Arial,sans-serif; font-size: 13px; line-height: 18px;'>" + "<div style='font-weight:bold'>" + place.heading + "</div>" + "<div>" + place.content.replace(/\r\n/g,"<br>") + "</div>" + "</div>";
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(this.html);
                infowindow.open(map, this);
            }
        })(marker, i));
        if(place.is_open){
            infowindow.setContent(marker.html);
            infowindow.open(map, marker);
        }
    }
    return false;
};
var initializeMgMaps = function(){
    for (var i = 0; i < mapObjWrapper.length; i++) {
        loadMgMap(mapObjWrapper[i]);
    }
}
// FINAL GOOGLE MAP
</script>




<style type="text/css">
.hidden{
    boder:1px solid red !important;
    display: none !important;
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
                <?php if(false): ?>
                <div class="right">
                    <span>Agents 1 to 8 of 31</span>
                    <ul class="pagination">
                        <li><a href="#?searchField="><<</a></li>
                        <li><a href="#">1</a></li>
                        <li><a href="#" class="active">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#">6</a></li>
                        <li><a href="#">>></a></li>
                    </ul>
                </div>
                <?php endif;?><!-- end Pagination -->


                <nav>
                    <ul class="menu-horizonal select">
                        <li><a href="#zipcode" class="active" >By ZIP Code</a> </li>
                        <li><a href="#city">By City</a> </li>
                    </ul>
                </nav>

            </article>


            <article id="" class="list">

                <form action="" method="post" name="myform" id="myform" class="find">
                    <div class="">
                    page<input type="text" name="page" value="<?php echo ($_REQUEST['page']) ? $_REQUEST['page'] : '1'; ?>"/>
                    row o limit<input type="text" name="rows" value="4"/>
                    sidx<input type="text" name="sidx" value="id"/>
                    sord<input type="text" name="sord" value="asc"/>
                    <input type="hidden" name="search" id="search" value="search"/>
                    </div>
                    <!-- <input id="zip" name="zip" placeholder="Enter a Zip code" class="field" type="text"/> -->


                    <!-- inicio tab -->
                    <div id ="zipcode" class="active">
                    <input name="zip" placeholder="Enter Zip Code Office" class="field" type="text"
                    value = "<?php echo ($_REQUEST['zip']) ? $_REQUEST['zip'] : ''; ?>" />
                    <input type="submit" name="go" class="go" id="send" value="Find"/>
                    </div>

                    <div id ="city" class ="hidden">
                    <input name="city" placeholder="Enter City Office" class="field" type="text"
                    value = "<?php echo ($_REQUEST['city']) ? $_REQUEST['city'] : ''; ?>" />
                    <input type="submit" name="go" class="go" id="send" value="Find"/>
                    </div>
                </form>

                <h2><?php echo $data->records; ?> Offices near 90048</h2>
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
                            <a href="#" class="directions" onclick="initialize(<?php echo $zoom;?>)">Get directions</a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                </ul>
            </article>
            <!-- <div id="map_canvas_custom_54699" style="width:580px; height:785px; margin:10px;" > -->
            <article class="map" id="map_canvas_custom_54699">

                <!-- <iframe width="99%" height="99%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.es/maps?f=q&amp;source=s_q&amp;hl=es&amp;geocode=&amp;q=Los+%C3%81ngeles,+California,+Estados+Unidos&amp;aq=0&amp;oq=los+an&amp;sll=40.396764,-3.713379&amp;sspn=11.689818,26.784668&amp;ie=UTF8&amp;hq=&amp;hnear=Los+%C3%81ngeles,+Condado+de+Los+%C3%81ngeles,+California,+Estados+Unidos&amp;ll=34.052234,-118.243685&amp;spn=0.795316,1.674042&amp;t=m&amp;z=10&amp;output=embed"></iframe> -->
            </article>
            <div id="map_canvas_custom_54698"></div>


            <?php if( ($data->total) >= 2): ?>

            <article class="top bottom">
                <div class="right">
                    <span>Agents 1 to 8 of 31</span>
                    <ul class="pagination">
                        <li><a href="#"><<</a></li>
                        <?php
                        $contador = 1;
                        for($i=1; $i<=($data->total);$i++){
                            $class = ($data->page == $i) ? 'class="active"' : '';
                            $click = ' onclick="changePag(event,'.$i.')"';

                            echo '<li><a href="#" '.$class. $click.' >'.$i.'</a></li>';

                        }
                        ?>
                        <li><a href="#">>></a></li>
                    </ul>
                </div>
            </article>
        <?php endif;?><!-- end Paginacion -->


        </section><!-- .entry-content -->
        <?php endif; ?>
	</section>

    </div>


<?php get_footer(); ?>

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
    $query2 = "SELECT count(*) as count FROM wp_master_office $WHERE;";
    // echo "<pre>2";
    // print_r($query2);
    // echo "</pre>";
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
    FROM wp_master_office $cadena;";
    echo "<pre>";
    print_r($query);
    echo "</pre>";
    $lista = $wpdb->get_results($query);

//








    $responce->page = $page;
    $responce->total = $total_pages;
    //$responce->start = $start;
    $responce->records = $count;
    $responce->data = $lista;
    //----------pagination--------------
    $response->lastPage = '';
    return $responce;

}
?>
