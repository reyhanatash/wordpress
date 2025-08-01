<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
define('UPLOADS_DIR', site_url().'/wp-content/uploads');
$secret_key = defined('TAMLAND_PURCHASE_SECTRET_KEY') ? TAMLAND_PURCHASE_SECTRET_KEY : '';


add_theme_support( 'widgets' );

 function use_wp_default_jquery_footer() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', get_stylesheet_directory_uri().'/assets/js/jquery-3.7.1.min.js', array(), null, false); // true = load in footer
    wp_enqueue_script('jquery');
}
//add_action('wp_enqueue_scripts', 'use_wp_default_jquery_footer');

function hello_elementor_child_enqueue_scripts() {
    wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri(). '/assets/css/bootstrap.min.css' );
    wp_enqueue_style( 'bootstrap-rtl', get_stylesheet_directory_uri(). '/assets/css/bootstrap.rtl.min.css' );
    wp_enqueue_style( 'bootstrap-utilities', get_stylesheet_directory_uri(). '/assets/css/bootstrap-utilities.min.css' );
    wp_enqueue_style( 'bootstrap-utilities-rtl', get_stylesheet_directory_uri(). '/assets/css/bootstrap-utilities.rtl.min.css' );
    wp_enqueue_style( 'owl-carousel', get_stylesheet_directory_uri(). '/assets/css/owl.carousel.min.css' );
    wp_enqueue_style( 'kc-fab', get_stylesheet_directory_uri(). '/assets/css/kc.fab.css' );
    
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.4.1'
	);
	
	wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri(). '/assets/js/bootstrap.min.js' , array(), '5.2.0', true );
	wp_enqueue_script( 'owl-carousel', get_stylesheet_directory_uri(). '/assets/js/owl.carousel.min.js' , array(), '1.0.0', true );
	wp_enqueue_script( 'kc-fab', get_stylesheet_directory_uri(). '/assets/js/kc.fab.min.js' , array(), '', true );
	wp_enqueue_script( 'java', get_stylesheet_directory_uri(). '/assets/js/java.js' , array(), '1.5.28', true );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

add_action('wp_enqueue_scripts', 'remove_unwanted_assets_on_specific_page', 100);
function remove_unwanted_assets_on_specific_page() {
    // Check if it's the page with slug "about-us"
    if (is_page('course-checkout') || is_page('return-payment-gateway')) {
        // Remove styles
        //wp_dequeue_style('elementor-icons-shared-1');
        //wp_deregister_style('elementor-icons-shared-1');
        wp_dequeue_style('eael-general');
        wp_deregister_style('eael-general');
        wp_dequeue_style('owl-carousel');
        wp_deregister_style('owl-carousel');
        wp_dequeue_style('happy-icons');
        wp_deregister_style('happy-icons');
        wp_dequeue_style('font-awesome');
        wp_deregister_style('font-awesome');
        wp_dequeue_style('e-popup');
        wp_deregister_style('e-popup');
        wp_dequeue_style('jet-engine-frontend');
        wp_deregister_style('jet-engine-frontend');
        wp_dequeue_style('ep-helper');
        wp_deregister_style('ep-helper');
        wp_dequeue_style('bdt-uikit');
        wp_deregister_style('bdt-uikit');
        wp_dequeue_style('hello-elementor-header-footer');
        wp_deregister_style('hello-elementor-header-footer');
        wp_dequeue_style('kc-fab');
        wp_deregister_style('kc-fab');
        wp_dequeue_style('bootstrap-utilities');
        wp_deregister_style('bootstrap-utilities');
        wp_dequeue_style('bootstrap-utilities-rtl');
        wp_deregister_style('bootstrap-utilities-rtl');
        // Remove scripts
        wp_dequeue_script('ovenplayer');
        wp_deregister_script('ovenplayer');
        wp_dequeue_script('hls');
        wp_deregister_script('hls');
        wp_dequeue_script('element-pack-helper');
        wp_deregister_script('element-pack-helper');
        wp_dequeue_script('bdt-uikit');
        wp_deregister_script('bdt-uikit');
        wp_dequeue_script('eael-general');
        wp_deregister_script('eael-general');
        wp_dequeue_script('happy-reading-progress-bar');
        wp_deregister_script('happy-reading-progress-bar');
        wp_dequeue_script('happy-addons-pro');
        wp_deregister_script('happy-addons-pro');
        wp_dequeue_script('happy-elementor-addons');
        wp_deregister_script('happy-elementor-addons');
        wp_dequeue_script('dom-purify');
        wp_deregister_script('dom-purify');
        wp_dequeue_script('owl-carousel');
        wp_deregister_script('owl-carousel');
        wp_dequeue_script('bootstrap');
        wp_deregister_script('bootstrap');
        wp_dequeue_script('kc-fab');
        wp_deregister_script('kc-fab');
    }
}

add_filter( 'woocommerce_sale_flash', 'cssigniter_woocommerce_sale_flash_percentage', 10, 3 );
/**
 * Replaces the default "Sale!" badge text with the percentage of discount.
 * Returns the HTML code that contains the default "Sale!" badge text, replaced with the percentage of discount.
 *
 * @param string     $html
 * @param WP_Post    $post
 * @param WC_Product $product
 *
 * @return string
 */


// Disable WordPress image compression

add_filter('jpeg_quality', function($arg){return 100;});
add_filter('wp_editor_set_quality', function($arg){return 100;});


// ** Get Live Courses from API and show it with Shortcode ** //
add_shortcode('Live_Courses', 'Live_Courses_func');
function Live_Courses_func(){
    $request_url = 'https://api.tamland.ir/api/main/freeClassList/-1/1';
    $curl = curl_init($request_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'X-TamlandAPI-Host: api.tamland.ir',
      'Content-Type: application/json'
    ]);
    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);

    if(empty($response['data'])){
        return '<style>#LiveCourseSection{display:none}</style>';
        //return '<div class="no-online-class"><img src="' . wp_get_upload_dir()['baseurl'] . '/2023/02/7714651.webp" class="mb-5"><div><span>در حال حاضر هیچ کلاس آنلاینی موجود نیست.</span></div></div>';
    }

    $courses = array_filter($response['data'], function($course) {
        return $course['fldCourseType'] != '88' && $course['fldCourseType'] != '102';
    });

    if(empty($courses)){
        return '<style>#LiveCourseSection{display:none}</style>';
        //return '<div class="no-online-class"><img src="' . wp_get_upload_dir()['baseurl'] . '/2023/02/7714651.webp" class="mb-5"><div><span>در حال حاضر هیچ کلاس آنلاینی موجود نیست.</span></div></div>';
    }

    ob_start(); ?>

    <div class="live-course-place">
        <div class="row mb-5">
            <div class="col-6 text-right">
                <h2 class="live-course-title">کلاس‌های در لحظه</h2>
            </div>
            <div class="col-6 text-left">
                <a href="/live/" class="text-white">مشاهده همه <i class="fa fa-angle-left"></i></a>
            </div>
        </div>  
        <div class="live-course-wrapper">
            <div class="sa-owl-next"><i class="fa fa-angle-right"></i></div>
            <div class="owl-carousel" id="liveCourse">
                <?php foreach ($courses as $course): 
                    $starttime = explode("T", $course['fldShowStartDate']);
                    $endtime = explode("T", $course['fldShowEndDate']);
                    $starthour = wp_date('H:i', strtotime($starttime[1]), 'Asia/Tehran');
                    $endhour = wp_date('H:i', strtotime($endtime[1]), 'Asia/Tehran');
                    $day = date_i18n('l', strtotime($starttime[0]));
                ?>
                    <div class="item-box">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="playing">در حال پخش</div>
                                <div class="course-image">
                                    <a href="https://lms.tamland.ir/live/<?php echo $course['fldPkCourseStepCo']; ?>">
                                        <img src="https://stream.tamland.ir/tamland/1402/course/<?php echo esc_attr($course['fldCoursePicAddress']); ?>" alt="">
                                        <img src="<?php echo UPLOADS_DIR . '/2022/11/play-icon.png'; ?>" alt="" class="play-icon">
                                    </a>
                                </div> 
                            </div>
                            <div class="col-12 col-lg-6 text-right rtl">
                                <a href="https://lms.tamland.ir/live/<?php echo esc_attr($course['fldPkCourseStepCo']); ?>">
                                    <h3 class="course-title"><?php echo esc_html($course['courseName']); ?></h3>
                                </a>
                                <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/jalase.png" class="live-icon"> <?php echo esc_html($course['courseStepName']); ?></p>
                                <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/presentation-white.png" class="live-icon"> <?php echo esc_html($course['fldTeacherName']); ?></p>
                                <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/clock-white.png" class="live-icon"> <?php echo esc_html($day) . ' ' . esc_html($starthour) . ' الی ' . esc_html($endhour); ?></p>  
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="sa-owl-prev"><i class="fa fa-angle-left"></i></div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery("#liveCourse").owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                responsive: {
                    0: { items: 1 },
                    600: { items: 1 },
                    1000: { items: 1 }
                }
            });

            jQuery('.sa-owl-next').click(function(){
                jQuery('.owl-next').click();
            });
            jQuery('.sa-owl-prev').click(function(){
                jQuery('.owl-prev').click();
            });
        });
    </script>
    
    <?php return ob_get_clean();
}

/**     
 * Display the comment template with the [mrh_comments_template] 
 * shortcode on singular pages. 

 */
 add_shortcode( 'mrh_comments_template', function( $atts = array(), $content = '' )
 {
    if( is_singular() && post_type_supports( get_post_type(), 'comments' ) )
    {
        ob_start();
        comments_template();
        add_filter( 'comments_open',       'mrh_comments_open'   );
        add_filter( 'get_comments_number', 'mrh_comments_number' );
        return ob_get_clean();
    }
    return '';
}, 10, 2 );

function mrh_comments_open( $open )
{
    remove_filter( current_filter(), __FUNCTION__ );
    return false;
}

function mrh_comments_number( $open )
{
    remove_filter( current_filter(), __FUNCTION__ );
    return 0;
}


/* Course Loop */ 
add_shortcode('courses_loop', 'courses_loop_func' );
function courses_loop_func() {
    $args = array(
        'post_type' => 'course',
        'post_status' => 'publish',
      
    );

    $my_query = null;
    $my_query = new WP_query($args);
	$ads_counter = 0;
		

	 
	
		?> <div  class="row courses-wrapper"> 
				
		
				
		<?php
	if(have_posts()):
        while(have_posts()) : the_post();
            $custom = get_post_custom( get_the_ID() );
			$course = get_post_meta(get_the_ID()); 
			if ($ads_counter==3 || $ads_counter==6):
				echo '<img src="'.UPLOADS_DIR.'/2022/10/ind00ex.jpg" class="goto-img">';
			endif;
			$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
			?>

    
      <div class="col-3 course-col"> 
        <a href="<? the_permalink(); ?>">
          
        <img src="<?php echo $image[0]; ?>" alt="" class="course-img mx-auto d-block" width="281">
        </a>
        <span class="play-icon">
          <a href="<? echo $course['first-class-link'][0]; ?>">
            
          <img src="<?php echo UPLOADS_DIR.'/2022/11/play-icon.svg'; ?>" alt="">
          </a>
        </span>
        <a href="<? the_permalink(); ?>">
          
        <h3 class="course-title mt-2 lh-lg">
         <?php echo get_the_title(); ?>
        </h3>
        </a>
        <ul class="course-detail">
          <li class="detail">
            <img src="<?php echo UPLOADS_DIR.'/2022/09/presentation-2.svg'; ?>" alt="" class="detail-icon" width="24">
            <? echo get_the_title($course['course-teacher'][0]); ?>
          </li>
          <li class="detail">
              <img src="<?php echo UPLOADS_DIR.'/2022/09/timer.svg'; ?>" alt="" class="detail-icon" width="24">
            <? echo $course['start-hour'][0]; ?>
          </li>
          <li class="detail">
              <img src="<?php echo UPLOADS_DIR.'/2022/09/calendar.svg'; ?>" alt="" class="detail-icon" width="24">
			  تاریخ شروع دوره <? echo $course['start-date'][0]; ?>

          </li>
        </ul>
        <div class="btns row">
          <div class="col-9">
            <a href="<? the_permalink(); ?>" class="detail-btn"> مشاهده جزئیات دوره </a>
          </div>
          <div class="col" style="padding: 0px;">
            <a href="<? echo $course['purchase-link'][0]; ?>" class="purchase-btn">
              <img src="<?php echo UPLOADS_DIR.'/2022/11/Buy.svg'; ?>" alt="">
            </a>
          </div>
        </div>
      </div>
   
<?php
 
            
			 $ads_counter++;
        endwhile;
		?>  </div> <?php
        wp_reset_postdata();
    else :
    _e( 'Sorry, no posts matched your criteria.' );
    endif;

	
	//print_r($cpt_fields);
	
	?>
	<style>
img.goto-img {
    width: 64%;
    border-radius: 20px;
	padding: 10px;
}
.courses-wrapper {
    row-gap: 15px !important;
}
.courses-wrapper .course-col {
  background:#fff;
  border-radius: 20px;
  position: relative;
  margin-left:10px;
  text-align:right;
  height: 600px;
  width: 295px;
  padding: 15px;

}
.course-img {
  border-top-right-radius:12px !important;
  border-top-left-radius:12px !important;
}
.play-icon {
    position: absolute;
    left: 25px;
    float: left;
    margin-top: -7%;
    z-index: 9999;
    background: #fff;
    padding: 6px;
    border-radius: 50%;
}
h3.course-title {
    font-size: 18px !important;
    font-weight: 500;
    width: 83%;
}
.course-detail {
  list-style-type:none;
  color:gray;
  font-weight:400;
  font-size:14px;
  padding:0;
  Line-height:2.5em;
  margin-bottom:5px
}
.detail-icon {
  margin-left:5px;
 }
 .btns.row {
    bottom: 15px;
    position: absolute;
    width: 100%;
}
.detail-btn {
  color:#E52041;
  border: solid 1.5px;
  border-radius:8px;
    padding: 10px 10px;
    font-weight: 500;
    font-size: 14px;
    display: block;
    text-align: center;

}
.purchase-btn {
    background: #E52041;
    border-radius: 8px;
    padding: 11px;
    display: block;
    text-align: center;
	    width: 55px;
}
</style>
<?php
}




// Show live course items in page
function live_courses_page_func(){
    
    //https://lms.tamland.ir/api/api/course/freeClassList/-1/3
    $request_url = 'https://api.tamland.ir/api/main/freeClassList/-1/1';
    $curl = curl_init($request_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'X-TamlandAPI-Host: api.tamland.ir',
      'Content-Type: application/json'
    ]);
    $response = json_decode(curl_exec($curl), true);
      curl_close($curl);
    
    $courses = $response['data'];
    
    if (!empty($courses)):

?>
   <div class="live-course-place">
        <div class="live-course-wrapper">
    <div class="row">
       
    <?php
        foreach ($courses as $course) { ?> 
        <div class="col-12 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
           <div class="item-box">
               <div class="playing">در حال پخش</div>
                       <div class="course-image">
                            <a href="https://lms.tamland.ir/live/<?php echo $course['courseId'];?>">
                                <img src="https://stream.tamland.ir/tamland/1402/course/<?php echo $course['fldCoursePicAddress']; ?>" alt="">
                                <img src="<?php echo UPLOADS_DIR.'/2022/11/play-icon.png'; ?>" alt="" class="play-icon">
                            </a>
                        </div> 
                        <?php  echo '<a href="https://lms.tamland.ir/live/' . $course['courseId'] . '"> <h3 class="course-title">' . $course['courseName'] . '</h3> </a>'; ?>
                       <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/jalase-dark.png" class="live-icon"> <?php echo $course['courseStepName']; ?></p>
                       <div>
                        <p> <?php echo '<img src="'.get_stylesheet_directory_uri().'/assets/img/presentation-dark.png" class="live-icon"> '. $course['fldTeacherName']; ?> </p> 
                        <?php
                        // Get Start And End Time
                        $starttime = $course['fldShowStartDate'];
                        $starttime = explode("T","$starttime");
                        $endtime = $course['fldShowEndDate'];
                        $endtime = explode("T","$endtime");
                        $starthour = wp_date( 'H:i', strtotime($starttime[1]),  date_default_timezone_get('Asia/Tehran') );
                        $endhour = wp_date( 'H:i', strtotime($endtime[1]),  date_default_timezone_get('Asia/Tehran') );
                         
                        // Get Day Name
                        $day = date('l', strtotime($starttime[0]));
                        $day = str_replace("Saturday","شنبه",$day);
                        $day = str_replace("Sunday","یکشنبه",$day);
                        $day = str_replace("Monday","دوشنبه",$day);
                        $day = str_replace("Tuesday","سه‌شنبه",$day);
                        $day = str_replace("Wednesday","چهارشنبه",$day);
                        $day = str_replace("Thursday","پنجشنبه",$day);
                        $day = str_replace("Friday","جمعه",$day);
                        ?>
                       </div>
                       <div>
                           <p>
                               <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/clock-dark.png" class="live-icon"> 
                              <?php echo $day .' '. $starthour . ' الی ' . $endhour; ?>
                           </p>  
                       </div>
            </div>
            </div>
            </div>
        </div>
<?php } ?>
</div>
</div>
</div>
<style>
.playing{
    width:104px;
    height:36px;
    background:url(<?php echo get_stylesheet_directory_uri(). '/assets/img/playing.png' ?>) center center no-repeat;
    position:absolute;
    left:20px;
    top:-5px;
    font-weight: bold;
    z-index: 999;
    text-align: center;
    font-size: 13px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.item-box {
  width:100%;
  display:flex;
  flex-direction: column;
}
.item-box h2{font-size:16px;font-weight:bold;height:57px;}
.course-image{position:relative;width:100%;height:307px;border:7px solid #fff;background:#fff;
  border-radius:20px;overflow:hidden;}
.course-image img {
  width:100%;height:auto !important;border-radius:20px;
}
.course-image .play-icon {
  position: absolute;
    width: 86px !important;
    height: 86px !important;
    margin: auto;
    transition:all 0.3s;
    left: calc(50% - 43px);
    z-index: 999;
    top: calc(50% - 43px);

}
.course-image:hover .play-icon {
  width:90px !important;
    height: 90px !important;
  left: calc(50% - 45px);
    top: calc(50% - 45px);
}
.course-title {
  text-align: right;
  font-size:16px;
  font-weight:700;
  margin: 18px 0;
  line-height:1.8;
}
.course-detail {
  list-style:none;
  text-align:right !important;
  line-height:1.8em;
  margin: 10px 10px;
  padding: 0;
}
li.detail-item {
    line-height: 2.3;
    font-size:14px;
}
</style>
<?php
else:
    echo do_shortcode("[elementor-template id='3930']");
endif;
}
add_shortcode('live_courses_page', 'live_courses_page_func');

//add_action('wp_head','add_Analytics');
function add_Analytics(){
    if (!is_page('course-checkout') && !is_page('return-payment-gateway')) {
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-FRY93YKGD1"></script>
    <script async>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-FRY93YKGD1');
    </script>
    
    <!-- Google Tag Manager -->
    <script async>(function(w,d,s,l,i){w[l]=w[l][];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5S53CVZ6');</script>
    <!-- End Google Tag Manager -->
    <?php
    }
}

add_action( 'wp_body_open', 'wpdoc_add_custom_body_open_code' );
function wpdoc_add_custom_body_open_code(){
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5S53CVZ6"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}

function query_string_redirects(){
    if( is_tax( 'grade' ) ){
    $teacher_chbox = $_GET['teacher_chbox'];
    $lesson_chbox = $_GET['lesson_chbox'];
     ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                const myTimeout = setTimeout(function(){
                    let teacher_chbox = "<?php echo $teacher_chbox; ?>";
                    let lesson_chbox = "<?php echo $lesson_chbox; ?>";
                    if(teacher_chbox != ""){
                        jQuery('div[data-query-var=teacher-archive] input[value='+teacher_chbox+']').click();
                    }
                    
                    if(teacher_chbox != ""){
                        jQuery('div[data-query-var=lesson] input[value='+lesson_chbox+']').click();
                    }
                }, 1000);
            });
        </script>
    <?php   
    }
}
//add_action('wp_footer','query_string_redirects');

add_filter( 'elementor_pro/custom_fonts/font_display', function( $current_value, $font_family, $data ) {
	return 'swap';
}, 10, 3 );




function add_float_button(){
    
    if (!is_page('course-checkout') && !is_page('return-payment-gateway')) {
    ?>
        <div class="kc_fab_wrapper"></div>
        <script>
            jQuery(document).ready(function($){
                var links = [
                    {
                        "bgcolor":"#C4161C",
                        "icon":'<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.9979 7.64865V0.734375L7.97266 7.80482V14.7191H14.8427L21.8679 7.64865H14.9979Z" fill="white"/><path d="M22.4025 15.0901V8.17578L15.3789 15.2447V22.1589H22.2489L29.2726 15.0901H22.4025Z" fill="white"/><path d="M22.7715 22.7091V29.6234L29.7967 22.553V15.6387L22.7715 22.7091Z" fill="white"/><path d="M7.45168 7.27358L14.4769 0.203125H7.57237L0.580078 7.27358H7.45168Z" fill="white"/><path d="M7.31142 22.8689C9.2613 24.8313 10.2394 27.4074 10.2472 29.9851H14.3021C14.2818 26.3773 12.9103 22.7758 10.1814 20.0294C7.43054 17.2608 3.81761 15.8773 0.203125 15.8789V19.9142C2.77841 19.9126 5.35213 20.897 7.31142 22.8689Z" fill="white"/><path d="M0.21096 25.5195C0.207825 25.5195 0.20626 25.5195 0.203125 25.5195V29.9871H4.6797C4.67186 28.7976 4.20947 27.6792 3.37247 26.8368C2.52606 25.9881 1.40377 25.5195 0.21096 25.5195Z" fill="white"/><path d="M6.79103 23.4046C4.97438 21.5762 2.58875 20.6644 0.203125 20.666V22.5512V24.7912C0.20626 24.7912 0.207825 24.7912 0.21096 24.7912C1.60127 24.7912 2.90851 25.3371 3.89129 26.3262C4.86779 27.309 5.40698 28.612 5.41482 30.0002H7.60452H9.51834C9.50894 27.5077 8.54184 25.1667 6.79103 23.4046Z" fill="white"/><path d="M14.6266 22.9227V15.4752H7.22834V8.0293H0.203125V15.1502C4.00414 15.1487 7.80672 16.6031 10.7002 19.5152C13.5717 22.4053 15.0153 26.196 15.0357 29.9931H22.0264V22.9227H14.6266Z" fill="white"/></svg>'
                    },
                    {
                        "url":"https://konkoor.tamland.ir",
                        "bgcolor":"#fff",
                        "color":"#222",
                        "icon":'<svg width="133" height="42" viewBox="0 0 133 42" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M113.947 13.8352V5.12109L105.016 14.032V22.7461H113.75L122.682 13.8352H113.947Z" fill="#C4161C"/><path d="M123.36 23.22V14.5059L114.43 23.4148V32.1289H123.164L132.094 23.22H123.36Z" fill="#C4161C"/><path d="M123.828 32.8006V41.5147L132.76 32.6038V23.8896L123.828 32.8006Z" fill="#C4161C"/><path d="M104.346 13.3592L113.278 4.44824H104.499L95.6094 13.3592H104.346Z" fill="#C4161C"/><path d="M104.17 33.0214C106.65 35.4947 107.893 38.7414 107.903 41.99H113.059C113.033 37.4431 111.289 32.9041 107.819 29.4428C104.322 25.9535 99.7284 24.2099 95.1328 24.2119V29.2976C98.4071 29.2956 101.679 30.5362 104.17 33.0214Z" fill="#2D3748"/><path d="M95.1506 36.3604C95.1466 36.3604 95.1446 36.3604 95.1406 36.3604V41.9908H100.832C100.822 40.4917 100.234 39.0821 99.1701 38.0205C98.094 36.9508 96.6671 36.3604 95.1506 36.3604Z" fill="#C4161C"/><path d="M103.516 33.6809C101.207 31.3767 98.1737 30.2275 95.1406 30.2295V32.6053V35.4285C95.1446 35.4285 95.1466 35.4285 95.1506 35.4285C96.9182 35.4285 98.5802 36.1164 99.8297 37.363C101.071 38.6016 101.757 40.2438 101.767 41.9934H104.551H106.984C106.972 38.8521 105.742 35.9017 103.516 33.6809Z" fill="#2D3748"/><path d="M113.479 33.0788V23.6927H104.073V14.3086H95.1406V23.2831C99.9733 23.2812 104.808 25.1142 108.487 28.7844C112.138 32.4267 113.973 37.2042 113.999 41.9897H122.887V33.0788H113.479Z" fill="#2D3748"/><path d="M82.1803 25.6184L85.5103 28.9406L88.8741 25.5826L85.5103 22.2266L82.1803 25.5488L78.8483 22.2266L75.4844 25.5826L78.8483 28.9406L82.1803 25.6184Z" fill="#C4161C"/><path d="M27.8806 22.2269L24.5156 25.584L27.8806 28.9411L31.2457 25.584L27.8806 22.2269Z" fill="#C4161C"/><path d="M46.9141 29.1543V30.2676V35.0134V41.9978H51.671V35.0134H65.6729V23.2812H52.799C49.5546 23.2812 46.9141 25.9156 46.9141 29.1543ZM60.9159 30.2657H51.671V29.1543C51.671 28.534 52.1772 28.029 52.799 28.029H60.9139V30.2657H60.9159Z" fill="#2D3748"/><path d="M82.3378 37.25H74.2209C73.5991 37.25 73.0929 36.7451 73.0929 36.1248V23.2812H68.3359V36.1248C68.3359 39.3635 70.9765 41.9978 74.2209 41.9978H87.0947V30.2657H82.3378V37.25Z" fill="#2D3748"/><path d="M39.4981 36.1248C39.4981 36.7451 38.9919 37.25 38.3701 37.25H30.2532V30.2657H25.4963V37.25H17.3793C16.7576 37.25 16.2514 36.7451 16.2514 36.1248V30.9058V26.2337H11.4944V30.9058V36.1248C11.4944 36.7451 10.9882 37.25 10.3665 37.25H1V41.9978H10.3665C11.6797 41.9978 12.8934 41.5663 13.8739 40.8387C14.8544 41.5663 16.068 41.9978 17.3813 41.9978H25.4983H30.2552H38.3721C41.6185 41.9978 44.2571 39.3635 44.2571 36.1248V23.2812H39.5001V36.1248H39.4981Z" fill="#2D3748"/><path d="M0.792666 18.2807L2.38223 17.2267V9.29619L4.64563 8.72602V18.4362L2.05395 20.164L0.792666 18.2807ZM6.47789 18.298L9.93347 16H8.93135C8.4015 16 7.86588 15.8387 7.32451 15.5162C6.79466 15.1822 6.35119 14.733 5.99411 14.1685C5.64856 13.6041 5.47578 12.9821 5.47578 12.3025C5.47578 11.6345 5.6428 11.0125 5.97684 10.4365C6.31088 9.8606 6.7601 9.40561 7.32451 9.07157C7.90044 8.73754 8.52244 8.57052 9.19052 8.57052H12.9225V13.7366H13.7864L14.1838 14.8769L13.7864 16H12.9225V16.7257L7.7219 20.1812L6.47789 18.298ZM7.73918 12.268C7.73918 12.6711 7.8774 13.0167 8.15385 13.3047C8.44181 13.5926 8.78737 13.7366 9.19052 13.7366H10.6591V10.8166H9.19052C8.79889 10.8166 8.45909 10.9606 8.17113 11.2486C7.88316 11.525 7.73918 11.8648 7.73918 12.268ZM12.8614 14.8769L13.2761 13.7366H16.3861L16.8007 14.8942L16.3861 16H13.2761L12.8614 14.8769ZM15.4477 14.8769L15.8623 13.7366H27.1448C27.5479 13.7366 27.8935 13.5984 28.1815 13.3219C28.4694 13.034 28.6134 12.6884 28.6134 12.2853C28.6134 11.8821 28.4694 11.5423 28.1815 11.2659C27.8935 10.9779 27.5479 10.8339 27.1448 10.8339H16.7262V8.55324L23.7065 4.4411L24.8814 6.16889L20.8729 8.57052H27.1448C27.8244 8.57052 28.4464 8.73754 29.0108 9.07157C29.5867 9.40561 30.0417 9.8606 30.3758 10.4365C30.7098 11.0009 30.8768 11.6172 30.8768 12.2853C30.8768 12.6884 31.015 13.034 31.2915 13.3219C31.5794 13.5984 31.9192 13.7366 32.3109 13.7366L32.7083 14.8769L32.3109 16C31.8271 16 31.3606 15.9021 30.9114 15.7063C30.4737 15.5105 30.0935 15.2455 29.771 14.9115C29.4255 15.2571 29.0281 15.5277 28.5789 15.7236C28.1296 15.9079 27.6516 16 27.1448 16H15.8623L15.4477 14.8769ZM31.7605 16L31.3804 14.8769L31.7778 13.7366H31.7951C32.1983 13.7366 32.5438 13.5984 32.8318 13.3219C33.1197 13.034 33.2637 12.6884 33.2637 12.2853V9.27891L35.5098 8.72602V12.2853C35.5098 12.6884 35.6538 13.034 35.9418 13.3219C36.2298 13.5984 36.5753 13.7366 36.9785 13.7366L37.3759 14.8769L36.9785 16C36.4716 16 35.9936 15.9021 35.5444 15.7063C35.0952 15.4989 34.7093 15.2225 34.3868 14.8769C34.0527 15.2225 33.6611 15.4989 33.2119 15.7063C32.7742 15.9021 32.3019 16 31.7951 16H31.7605ZM33.5056 5.13222H35.458V6.96367H33.5056V5.13222ZM36.0421 14.8769L36.4568 13.7366H39.5668L39.9814 14.8942L39.5668 16H36.4568L36.0421 14.8769ZM38.6284 14.8769L39.043 13.7366H42.1531L42.5677 14.8942L42.1531 16H39.043L38.6284 14.8769ZM41.2146 14.8769L41.6293 13.7366H44.7393L45.154 14.8942L44.7393 16H41.6293L41.2146 14.8769ZM43.8009 14.8769L44.2156 13.7366H47.3256L47.7403 14.8942L47.3256 16H44.2156L43.8009 14.8769ZM46.3872 14.8769L46.8019 13.7366H49.9119L50.3266 14.8942L49.9119 16H46.8019L46.3872 14.8769ZM48.9735 14.8769L49.3882 13.7366H52.4982L52.9128 14.8942L52.4982 16H49.3882L48.9735 14.8769ZM51.5598 14.8769L51.9744 13.7366H55.0845L55.4991 14.8942L55.0845 16H51.9744L51.5598 14.8769ZM54.1461 14.8769L54.5607 13.7366H57.6707L58.0854 14.8942L57.6707 16H54.5607L54.1461 14.8769ZM56.7323 14.8769L57.147 13.7366H60.257L60.6717 14.8942L60.257 16H57.147L56.7323 14.8769ZM59.3186 14.8769L59.7333 13.7366H62.8433L63.258 14.8942L62.8433 16H59.7333L59.3186 14.8769ZM61.9049 14.8769L62.3196 13.7366H65.4296L65.8443 14.8942L65.4296 16H62.3196L61.9049 14.8769ZM64.4912 14.8769L64.9058 13.7366H68.0159L68.4305 14.8942L68.0159 16H64.9058L64.4912 14.8769ZM67.0775 14.8769L67.4921 13.7366H70.6021L71.0168 14.8942L70.6021 16H67.4921L67.0775 14.8769ZM69.6637 14.8769L70.0784 13.7366H73.1884L73.6031 14.8942L73.1884 16H70.0784L69.6637 14.8769ZM72.25 14.8769L72.6647 13.7366H83.9471C84.3503 13.7366 84.6958 13.5984 84.9838 13.3219C85.2718 13.034 85.4158 12.6884 85.4158 12.2853C85.4158 11.8821 85.2718 11.5423 84.9838 11.2659C84.6958 10.9779 84.3503 10.8339 83.9471 10.8339H73.5286V8.55324L80.5088 4.4411L81.6837 6.16889L77.6753 8.57052H83.9471C84.6267 8.57052 85.2487 8.73754 85.8131 9.07157C86.3891 9.40561 86.8441 9.8606 87.1781 10.4365C87.5121 11.0009 87.6792 11.6172 87.6792 12.2853C87.6792 12.9533 87.5121 13.5753 87.1781 14.1513C86.8441 14.7157 86.3891 15.1649 85.8131 15.4989C85.2487 15.833 84.6267 16 83.9471 16H72.6647L72.25 14.8769Z" fill="#C4161C"/></svg>',
                        "target":"_blank",
                        "title":"کنکور تام‌لند"
                    },
                    {
                        "url":"https://mid2.tamland.ir",
                        "bgcolor":"#fff",
                        "color":"#222",
                        "icon":'<svg width="117" height="38" viewBox="0 0 117 38" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100.294 12.2761V4.53906L92.3633 12.4509V20.1879H100.119L108.05 12.2761H100.294Z" fill="#1D4A00"/><path d="M108.644 20.6199V12.8828L100.715 20.7929V28.5299H108.471L116.4 20.6199H108.644Z" fill="#1D4A00"/><path d="M109.066 29.1149V36.852L116.997 28.9402V21.2031L109.066 29.1149Z" fill="#1D4A00"/><path d="M91.7654 11.8571L99.6964 3.94531H91.9017L84.0078 11.8571H91.7654Z" fill="#1D4A00"/><path d="M91.6109 29.314C93.8122 31.5099 94.9164 34.3926 94.9253 37.277H99.5031C99.4801 33.2399 97.9318 29.2098 94.851 26.1365C91.7454 23.0385 87.6665 21.4904 83.5859 21.4922V26.0077C86.4933 26.0059 89.3989 27.1074 91.6109 29.314Z" fill="#959595"/><path d="M83.5948 32.2812C83.5912 32.2812 83.5895 32.2812 83.5859 32.2812V37.2804H88.6397C88.6308 35.9494 88.1088 34.6979 87.1639 33.7552C86.2084 32.8055 84.9414 32.2812 83.5948 32.2812Z" fill="#1D4A00"/><path d="M91.0233 29.9004C88.9724 27.8545 86.2792 26.8342 83.5859 26.8359V28.9454V31.452C83.5895 31.452 83.5912 31.452 83.5948 31.452C85.1644 31.452 86.6401 32.0628 87.7496 33.1696C88.852 34.2694 89.4608 35.7275 89.4696 37.2809H91.9416H94.1022C94.0916 34.4918 92.9998 31.8722 91.0233 29.9004Z" fill="#959595"/><path d="M99.8694 29.3688V21.0351H91.5171V12.7031H83.5859V20.6714C87.8771 20.6697 92.1701 22.2972 95.4367 25.5558C98.6785 28.7898 100.308 33.0316 100.331 37.2806H108.224V29.3688H99.8694Z" fill="#959595"/><path d="M72.0784 22.7381L75.0353 25.6878L78.0222 22.7063L75.0353 19.7266L72.0784 22.6763L69.1198 19.7266L66.1328 22.7063L69.1198 25.6878L72.0784 22.7381Z" fill="#1D4A00"/><path d="M23.863 19.7303L20.875 22.7109L23.863 25.6916L26.8509 22.7109L23.863 19.7303Z" fill="#1D4A00"/><path d="M40.7734 25.8786V26.8671V31.0807V37.282H44.9974V31.0807H57.4303V20.6641H45.9989C43.1181 20.6641 40.7734 23.003 40.7734 25.8786ZM53.2064 26.8654H44.9974V25.8786C44.9974 25.3278 45.4468 24.8795 45.9989 24.8795H53.2046V26.8654H53.2064Z" fill="#959595"/><path d="M72.222 33.0666H65.0146C64.4625 33.0666 64.013 32.6183 64.013 32.0675V20.6641H59.7891V32.0675C59.7891 34.9431 62.1337 37.282 65.0146 37.282H76.4459V26.8654H72.222V33.0666Z" fill="#959595"/><path d="M34.1844 32.0675C34.1844 32.6183 33.7349 33.0666 33.1828 33.0666H25.9754V26.8654H21.7514V33.0666H14.544C13.9919 33.0666 13.5424 32.6183 13.5424 32.0675V27.4338V23.2854H9.3185V27.4338V32.0675C9.3185 32.6183 8.86903 33.0666 8.31693 33.0666H0V37.282H8.31693C9.48307 37.282 10.5607 36.899 11.4314 36.2529C12.302 36.899 13.3796 37.282 14.5458 37.282H21.7532H25.9771H33.1846C36.0672 37.282 38.4101 34.9431 38.4101 32.0675V20.6641H34.1861V32.0675H34.1844Z" fill="#959595"/><path d="M1.01252 10.7016C1.01252 10.0982 1.16082 9.54593 1.45742 9.04478C1.75402 8.5334 2.15289 8.12942 2.65403 7.83282C3.16541 7.53622 3.72281 7.38792 4.32623 7.38792H7.6246C8.22802 7.38792 8.7803 7.53622 9.28145 7.83282C9.79283 8.12942 10.1968 8.5334 10.4934 9.04478C10.79 9.54593 10.9383 10.0982 10.9383 10.7016C10.9383 11.2948 10.79 11.8471 10.4934 12.3585C10.1968 12.8596 9.79283 13.2585 9.28145 13.5551C8.7803 13.8517 8.22802 14 7.6246 14C7.02118 14 6.46889 13.8517 5.96774 13.5551C5.4666 13.2585 5.06772 12.8596 4.77113 12.3585C4.47453 11.8573 4.32623 11.3051 4.32623 10.7016V9.39763C3.96827 9.39763 3.66144 9.52547 3.40576 9.78116C3.1603 10.0368 3.03757 10.3437 3.03757 10.7016V17.1756L1.01252 17.6819V10.7016ZM6.35127 10.7016C6.35127 11.0596 6.474 11.3664 6.71946 11.6221C6.96492 11.8676 7.26664 11.9903 7.6246 11.9903C7.98256 11.9903 8.28427 11.8676 8.52973 11.6221C8.78542 11.3664 8.91326 11.0596 8.91326 10.7016C8.91326 10.3437 8.78542 10.0368 8.52973 9.78116C8.28427 9.52547 7.98256 9.39763 7.6246 9.39763H6.35127V10.7016ZM12.4596 16.0404L15.5279 14H14.6381C14.1676 14 13.692 13.8568 13.2113 13.5704C12.7409 13.2738 12.3471 12.875 12.03 12.3738C11.7232 11.8727 11.5698 11.3204 11.5698 10.717C11.5698 10.1238 11.7181 9.57149 12.0147 9.06012C12.3113 8.54875 12.7102 8.14476 13.2113 7.84816C13.7227 7.55156 14.275 7.40327 14.8682 7.40327H18.1819V14.6443L13.5642 17.7126L12.4596 16.0404ZM13.5795 10.6863C13.5795 11.0443 13.7022 11.3511 13.9477 11.6068C14.2034 11.8625 14.5102 11.9903 14.8682 11.9903H16.1722V9.39763H14.8682C14.5204 9.39763 14.2187 9.52547 13.963 9.78116C13.7074 10.0266 13.5795 10.3283 13.5795 10.6863ZM18.9786 11.9903H22.2769V10.7016C22.2769 10.3437 22.1491 10.042 21.8934 9.7965C21.6479 9.54081 21.3462 9.41297 20.9883 9.41297H19.2087V7.40327H20.9883C21.5917 7.40327 22.144 7.55156 22.6451 7.84816C23.1463 8.14476 23.54 8.54363 23.8264 9.04478C24.123 9.54593 24.2713 10.0982 24.2713 10.7016V14H18.9786V11.9903ZM29.6621 14C29.0586 14 28.5012 13.8517 27.9899 13.5551C27.4887 13.2585 27.0898 12.8596 26.7932 12.3585C26.4966 11.8471 26.3483 11.2948 26.3483 10.7016C26.3483 10.1084 26.4966 9.56127 26.7932 9.06012C27.0898 8.54875 27.4938 8.14476 28.0052 7.84816C28.5166 7.55156 29.0689 7.40327 29.6621 7.40327H32.9451V10.6863C32.9451 11.0545 33.0729 11.3664 33.3286 11.6221C33.5843 11.8676 33.8911 11.9903 34.2491 11.9903L34.6019 13.0028L34.2491 14C33.8093 14 33.3951 13.9182 33.0064 13.7545C32.628 13.5807 32.2905 13.3403 31.9939 13.0335C31.6871 13.3403 31.3342 13.5807 30.9354 13.7545C30.5365 13.9182 30.1121 14 29.6621 14ZM28.358 10.7016C28.358 11.0596 28.4859 11.3664 28.7416 11.6221C28.9973 11.8676 29.3041 11.9903 29.6621 11.9903C30.02 11.9903 30.3217 11.8676 30.5672 11.6221C30.8126 11.3664 30.9354 11.0596 30.9354 10.7016V9.39763H29.6621C29.3041 9.39763 28.9973 9.52547 28.7416 9.78116C28.4859 10.0368 28.358 10.3437 28.358 10.7016ZM33.4215 13.0028L33.7897 11.9903H34.7408V3.73671L36.7352 3.24579V7.40327H41.8592C42.4626 7.40327 43.0149 7.55156 43.516 7.84816C44.0172 8.14476 44.4161 8.54363 44.7126 9.04478C45.0092 9.54593 45.1575 10.0982 45.1575 10.7016C45.1575 11.1516 45.0706 11.5812 44.8967 11.9903H45.9246L46.2775 13.0028L45.9246 14H33.7897L33.4215 13.0028ZM41.8592 11.9903C42.2171 11.9903 42.524 11.8676 42.7797 11.6221C43.0353 11.3664 43.1632 11.0596 43.1632 10.7016C43.1632 10.3437 43.0353 10.042 42.7797 9.7965C42.524 9.54081 42.2171 9.41297 41.8592 9.41297H36.7352V11.9903H41.8592ZM45.4186 14L45.0964 13.0028L45.4339 11.9903H45.4646C45.8226 11.9903 46.1243 11.8676 46.3697 11.6221C46.6254 11.3664 46.7533 11.0596 46.7533 10.7016V8.15499H48.763V10.9164C48.763 11.213 48.8653 11.4687 49.0698 11.6835C49.2846 11.888 49.5403 11.9903 49.8369 11.9903C50.1335 11.9903 50.384 11.888 50.5886 11.6835C50.8034 11.4687 50.9108 11.213 50.9108 10.9164V8.15499H52.9205V10.9164C52.9205 11.213 53.0227 11.4687 53.2273 11.6835C53.4421 11.888 53.6977 11.9903 53.9943 11.9903C54.2909 11.9903 54.5415 11.888 54.7461 11.6835C54.9608 11.4687 55.0682 11.213 55.0682 10.9164V8.07828L57.0779 7.57202V10.9011C57.0779 11.4636 56.9399 11.9852 56.6637 12.4659C56.3876 12.9363 56.0143 13.3096 55.5438 13.5858C55.0733 13.8619 54.5569 14 53.9943 14C53.6262 14 53.258 13.9233 52.8898 13.7699C52.5318 13.6062 52.2096 13.4017 51.9233 13.1562C51.6471 13.4221 51.3301 13.6318 50.9721 13.7852C50.6142 13.9284 50.2357 14 49.8369 14C49.4482 14 49.0596 13.9131 48.6709 13.7392C48.2925 13.5551 47.9703 13.3352 47.7044 13.0795C47.4078 13.3659 47.0652 13.5909 46.6766 13.7545C46.2982 13.9182 45.8942 14 45.4646 14H45.4186ZM58.6134 16.0404L61.6816 14H60.7918C60.3214 14 59.8458 13.8568 59.3651 13.5704C58.8946 13.2738 58.5009 12.875 58.1838 12.3738C57.877 11.8727 57.7236 11.3204 57.7236 10.717C57.7236 10.1238 57.8719 9.57149 58.1685 9.06012C58.4651 8.54875 58.8639 8.14476 59.3651 7.84816C59.8765 7.55156 60.4288 7.40327 61.0219 7.40327H64.3357V11.9903H65.1027L65.4556 13.0028L65.1027 14H64.3357V14.6443L59.7179 17.7126L58.6134 16.0404ZM59.7333 10.6863C59.7333 11.0443 59.856 11.3511 60.1015 11.6068C60.3572 11.8625 60.664 11.9903 61.0219 11.9903H62.326V9.39763H61.0219C60.6742 9.39763 60.3725 9.52547 60.1168 9.78116C59.8611 10.0266 59.7333 10.3283 59.7333 10.6863ZM64.6189 14L64.2814 13.0028L64.6342 11.9903H64.6496C65.0075 11.9903 65.3143 11.8676 65.57 11.6221C65.8257 11.3664 65.9536 11.0596 65.9536 10.7016V8.03226L67.9479 7.54134V10.7016C67.9479 11.0596 68.0758 11.3664 68.3314 11.6221C68.5871 11.8676 68.894 11.9903 69.2519 11.9903L69.6048 13.0028L69.2519 14C68.8019 14 68.3775 13.9131 67.9786 13.7392C67.5797 13.5551 67.2371 13.3096 66.9507 13.0028C66.6541 13.3096 66.3064 13.5551 65.9075 13.7392C65.5189 13.9131 65.0996 14 64.6496 14H64.6189ZM64.3581 4.38104H68.1013V5.99187H64.3581V4.38104ZM68.758 14L68.4205 13.0028L68.758 11.9903H68.7887C69.1467 11.9903 69.4535 11.8625 69.7092 11.6068C69.9649 11.3511 70.0927 11.0494 70.0927 10.7016V7.38792H73.3911C73.9843 7.38792 74.5314 7.53622 75.0326 7.83282C75.5439 8.12942 75.9479 8.5334 76.2445 9.04478C76.5411 9.54593 76.6894 10.0982 76.6894 10.7016C76.6894 11.2948 76.5411 11.8471 76.2445 12.3585C75.9479 12.8596 75.5439 13.2585 75.0326 13.5551C74.5314 13.8517 73.9843 14 73.3911 14C72.9411 14 72.5166 13.9131 72.1177 13.7392C71.7189 13.5551 71.3762 13.3045 71.0899 12.9875C70.7933 13.3045 70.4455 13.5551 70.0467 13.7392C69.658 13.9131 69.2387 14 68.7887 14H68.758ZM72.1024 10.7016C72.1024 11.0596 72.2251 11.3664 72.4706 11.6221C72.7263 11.8676 73.0331 11.9903 73.3911 11.9903C73.749 11.9903 74.0507 11.8625 74.2962 11.6068C74.5519 11.3511 74.6797 11.0494 74.6797 10.7016C74.6797 10.3437 74.5519 10.0368 74.2962 9.78116C74.0507 9.52547 73.749 9.39763 73.3911 9.39763H72.1024V10.7016Z" fill="#1D4A00"/></svg>',
                        "target":"_blank",
                        "title":"متوسطه دوم تام‌لند"
                    },
                    {
                        "url":"https://tamland.ir",
                        "bgcolor":"#fff",
                        "color":"#222",
                        "icon":'<svg width="118" height="34" viewBox="0 0 118 34" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100.736 8.28729V0.59375L92.8047 8.46106V16.1546H100.561L108.492 8.28729H100.736Z" fill="#C4161C"/><path d="M109.089 16.5686V8.875L101.16 16.7406V24.4341H108.916L116.845 16.5686H109.089Z" fill="#C4161C"/><path d="M109.512 25.0314V32.7249L117.443 24.8576V17.1641L109.512 25.0314Z" fill="#C4161C"/><path d="M92.2069 7.86732L100.138 0H92.3432L84.4492 7.86732H92.2069Z" fill="#C4161C"/><path d="M92.0522 25.2231C94.2535 27.4067 95.3577 30.2732 95.3666 33.1413H99.9444C99.9214 29.1269 98.373 25.1196 95.2923 22.0635C92.1867 18.983 88.1079 17.4436 84.0273 17.4453V21.9354C86.9347 21.9337 89.8403 23.029 92.0522 25.2231Z" fill="#58595B"/><path d="M84.0362 28.1719C84.0327 28.1719 84.0309 28.1719 84.0273 28.1719V33.1429H89.0812C89.0723 31.8194 88.5503 30.5749 87.6054 29.6376C86.6498 28.6932 85.3828 28.1719 84.0362 28.1719Z" fill="#C4161C"/><path d="M91.4647 25.805C89.4138 23.7706 86.7206 22.7561 84.0273 22.7578V24.8554V27.348C84.0309 27.348 84.0327 27.348 84.0362 27.348C85.6058 27.348 87.0816 27.9553 88.1911 29.0559C89.2935 30.1494 89.9022 31.5993 89.9111 33.144H92.3831H94.5437C94.5331 30.3706 93.4413 27.7657 91.4647 25.805Z" fill="#58595B"/><path d="M100.311 25.2751V16.9882H91.9584V8.70312H84.0273V16.6266C88.3185 16.6249 92.6114 18.2433 95.878 21.4836C99.1198 24.6993 100.75 28.9173 100.773 33.1424H108.665V25.2751H100.311Z" fill="#58595B"/><path d="M72.0824 18.3149L75.0393 21.248L78.0263 18.2833L75.0393 15.3203L72.0824 18.2534L69.1237 15.3203L66.1367 18.2833L69.1237 21.248L72.0824 18.3149Z" fill="#C4161C"/><path d="M23.863 15.3173L20.875 18.2812L23.863 21.2452L26.8509 18.2813L23.863 15.3173Z" fill="#C4161C"/><path d="M40.7695 21.4274V22.4104V26.6003V32.7667H44.9934V26.6003H57.4263V16.2422H45.995C43.1142 16.2422 40.7695 18.568 40.7695 21.4274ZM53.2023 22.4086H44.9934V21.4274C44.9934 20.8797 45.4429 20.4339 45.995 20.4339H53.2006V22.4086H53.2023Z" fill="#C4161C"/><path d="M72.2219 28.575H65.0145C64.4624 28.575 64.013 28.1292 64.013 27.5815V16.2422H59.7891V27.5815C59.7891 30.4409 62.1337 32.7667 65.0145 32.7667H76.4458V22.4086H72.2219V28.575Z" fill="#C4161C"/><path d="M34.1841 27.5815C34.1841 28.1292 33.7346 28.575 33.1825 28.575H25.9751V22.4086H21.7512V28.575H14.5439C13.9918 28.575 13.5423 28.1292 13.5423 27.5815V22.9738V18.8488H9.31842V22.9738V27.5815C9.31842 28.1292 8.86896 28.575 8.31686 28.575H0V32.7667H8.31686C9.48299 32.7667 10.5606 32.3858 11.4313 31.7434C12.3019 32.3858 13.3795 32.7667 14.5457 32.7667H21.753H25.9769H33.1843C36.0669 32.7667 38.4097 30.4409 38.4097 27.5815V16.2422H34.1858V27.5815H34.1841Z" fill="#C4161C"/><path d="M14.7758 5.70245L14.82 5.72702C14.8501 5.74281 14.8873 5.76212 14.9315 5.78319L15.2146 5.91308C15.4394 6.02366 15.657 6.09915 15.8676 6.13776C16.333 6.22377 16.7754 6.21675 17.1328 6.1167C17.3239 6.06404 17.4956 5.98856 17.6442 5.89202C17.7911 5.79723 17.9167 5.68139 18.0194 5.54974C18.122 5.41809 18.1999 5.27063 18.2529 5.11266C18.3414 4.85638 18.3609 4.59484 18.3113 4.33329C18.2618 4.07175 18.1521 3.82602 17.9857 3.60485C17.9008 3.49602 17.8141 3.38016 17.7256 3.25904C17.6371 3.13968 17.5451 3.01857 17.4513 2.89745L17.1735 2.53937C17.0815 2.42176 16.9948 2.30766 16.9152 2.19708C16.7488 1.97942 16.5825 1.76176 16.4179 1.54761L15.8039 2.004L16.7718 3.19761C16.9541 3.42054 17.1098 3.63118 17.2355 3.82426C17.3629 4.01734 17.4584 4.19463 17.5168 4.34734C17.5823 4.51936 17.5964 4.67383 17.5557 4.80547C17.515 4.93712 17.4531 5.0442 17.3717 5.1267C17.2939 5.20569 17.2018 5.26888 17.101 5.31452C17.0019 5.35665 16.8975 5.38649 16.7878 5.40053C16.5843 5.42861 16.3861 5.43388 16.1826 5.38122C16.0853 5.35665 15.9897 5.32856 15.8995 5.30048L15.7066 5.2408L15.8481 5.09862C15.9331 5.01437 16.0109 4.92659 16.0817 4.83883C16.1472 4.75633 16.2038 4.66506 16.2498 4.57203C16.2958 4.47899 16.3312 4.37894 16.356 4.27186C16.3808 4.16654 16.3932 4.04894 16.3932 3.92256C16.3932 3.76984 16.3578 3.60134 16.287 3.42054C16.2162 3.23974 16.11 3.06947 15.972 2.91325C15.834 2.75878 15.6606 2.62713 15.4588 2.52532C15.2589 2.42351 15.0271 2.37261 14.7669 2.37261C14.5351 2.37261 14.3157 2.40947 14.114 2.4832C13.914 2.55692 13.7371 2.65873 13.5902 2.78862C13.4433 2.91676 13.3265 3.07474 13.2434 3.25554C13.1602 3.43634 13.1177 3.63819 13.1177 3.8541C13.1177 3.96819 13.1283 4.07352 13.1478 4.16655C13.1673 4.25958 13.1956 4.34734 13.2345 4.42809C13.2717 4.50883 13.3195 4.58958 13.3743 4.66681C13.4309 4.7458 13.4964 4.82479 13.569 4.90904L13.7194 5.08106L13.4929 5.10915C13.4203 5.11793 13.3531 5.12495 13.2929 5.1267C13.1814 5.13372 13.0664 5.13197 12.939 5.12846C12.8771 5.1267 12.8063 5.1267 12.7267 5.1267H12.3374C12.3055 5.1267 12.2471 5.12144 12.1604 5.11091C12.0684 5.09862 11.9746 5.06878 11.8808 5.01787C11.7835 4.96697 11.6968 4.88623 11.6225 4.78091C11.5446 4.67032 11.5057 4.52112 11.5057 4.33505C11.5004 4.08931 11.4597 3.85059 11.3871 3.62415C11.3146 3.40123 11.2084 3.20464 11.0686 3.03788C10.9306 2.87288 10.7572 2.73772 10.5537 2.64118C10.3502 2.54288 10.106 2.49373 9.82992 2.49373C9.55033 2.49373 9.31321 2.54639 9.12918 2.65171C8.93984 2.75878 8.78058 2.89745 8.65848 3.06245C8.53107 3.23271 8.42844 3.42404 8.35235 3.63117C8.27272 3.84707 8.2037 4.05771 8.14708 4.25957C8.11169 4.38069 8.0763 4.48952 8.04268 4.58782C8.00728 4.69138 7.96659 4.77915 7.92412 4.85287C7.87634 4.93537 7.82502 4.99857 7.76309 5.04421C7.69408 5.09862 7.61268 5.12494 7.52066 5.12494H7.11366C7.00926 5.12494 6.89071 5.11266 6.75799 5.09159C6.6182 5.06877 4.51597 5.02314 4.38326 4.95644C4.247 4.88798 4.12137 4.78968 4.00812 4.66857C3.89133 4.54043 3.80462 4.37192 3.75507 4.16655L2.99771 1.24219L2.17841 1.43176L2.27927 1.83197L2.17133 1.86884C1.89882 1.96011 1.63693 2.06719 1.39096 2.1883C1.14499 2.30767 0.927338 2.45336 0.741536 2.62012C0.557504 2.78512 0.407093 2.98171 0.297382 3.20113C0.18767 3.41703 0.132812 3.6733 0.132812 3.96293C0.132812 4.21043 0.175282 4.43686 0.26022 4.6317C0.345158 4.8283 0.46018 4.99506 0.601743 5.13021C0.745076 5.26537 0.914951 5.37245 1.10606 5.44617C1.39804 5.56026 1.71833 5.59361 2.08285 5.51989C2.20849 5.49532 2.33236 5.4567 2.45269 5.4058C2.56948 5.35665 2.67919 5.29346 2.78005 5.21622C2.87738 5.1425 2.95701 5.05825 3.01717 4.96521L3.13573 4.78267L3.22952 4.97926C3.30914 5.14602 3.41355 5.2917 3.53919 5.41282C3.66659 5.53569 3.81523 5.6375 3.97626 5.71474C4.1426 5.79373 4.32663 5.85516 4.52305 5.89377C4.6717 5.92361 6.80045 5.94117 6.99157 5.94819L7.51889 5.95346C7.70292 5.95346 7.86218 5.92713 7.9949 5.87447C8.12585 5.82181 8.25148 5.73404 8.36827 5.60941L8.43375 5.54095L8.51692 5.58484C8.68679 5.67611 8.84251 5.75862 8.98585 5.83058C9.12033 5.89904 9.25658 5.95872 9.39107 6.00963C9.52024 6.05702 9.64765 6.09389 9.76975 6.12021C9.89185 6.14479 10.0228 6.15883 10.1555 6.15883C10.221 6.15883 10.3006 6.15356 10.3909 6.14654C10.474 6.13952 10.566 6.12021 10.6634 6.08861C10.7554 6.05877 10.8492 6.00962 10.9447 5.94643C11.0367 5.88499 11.1217 5.79899 11.1995 5.69016L11.2686 5.59537L11.3677 5.65857C11.5004 5.74458 11.649 5.81478 11.81 5.87095C11.9321 5.91308 12.0719 5.93766 12.2418 5.94468L12.4559 5.9517H12.7249C13.1248 5.9517 13.4946 5.93239 13.8256 5.89377C14.1547 5.85516 14.4573 5.79547 14.7245 5.71648L14.7758 5.70245ZM2.72343 3.96468C2.67565 4.20516 2.56594 4.38595 2.39429 4.49829C2.23503 4.60361 2.03684 4.67383 1.80326 4.70893C1.76256 4.71596 1.72186 4.71771 1.6794 4.71771C1.60861 4.71771 1.53783 4.70894 1.46882 4.68963C1.36088 4.66154 1.26178 4.6159 1.17331 4.55271C1.08306 4.48776 1.00874 4.40527 0.952112 4.30873C0.893717 4.20692 0.863635 4.09107 0.863635 3.96468C0.863635 3.65399 0.999892 3.39245 1.26886 3.18357C1.51837 2.99048 1.88466 2.8167 2.35713 2.6675L2.47746 2.62889L2.51108 2.75001C2.54824 2.8799 2.58363 2.99751 2.61549 3.10283C2.64911 3.21166 2.67742 3.31522 2.69865 3.40825C2.71989 3.50655 2.73404 3.59958 2.73935 3.68559C2.74997 3.78038 2.74289 3.87341 2.72343 3.96468ZM10.7642 4.96346C10.7129 5.08808 10.6528 5.18111 10.5802 5.24606C10.5023 5.31452 10.4263 5.35489 10.3484 5.36718C10.2811 5.37771 10.2334 5.38474 10.2033 5.38474C10.113 5.38474 10.0122 5.36718 9.89716 5.33207C9.78922 5.29872 9.67597 5.2566 9.55918 5.20745C9.44062 5.15654 9.32029 5.10037 9.19819 5.03718C9.07609 4.97574 8.96107 4.91431 8.85313 4.85287L8.75934 4.80021L8.80004 4.70192C8.84959 4.57905 8.89737 4.44213 8.94161 4.29468C8.98761 4.13846 9.04601 3.9875 9.11325 3.84708C9.1858 3.70139 9.27428 3.57851 9.37868 3.48021C9.49724 3.36963 9.64942 3.31346 9.83169 3.31346C10.0281 3.31346 10.198 3.36086 10.3325 3.45389C10.4599 3.54166 10.5625 3.64873 10.6404 3.77336C10.7147 3.89448 10.7678 4.0191 10.7979 4.14899C10.8279 4.27713 10.8421 4.37894 10.8421 4.4632C10.8403 4.67735 10.8156 4.84059 10.7642 4.96346ZM14.1317 4.44564C14.052 4.36665 13.9937 4.27713 13.9547 4.18058C13.9158 4.0858 13.8963 3.97521 13.8963 3.8541C13.8963 3.74878 13.9211 3.64698 13.9706 3.55219C14.0184 3.46091 14.0839 3.38017 14.1635 3.31171C14.2432 3.24501 14.3352 3.19059 14.4396 3.15021C14.6183 3.08351 14.8006 3.06069 15.0341 3.13968C15.1332 3.17303 15.227 3.22569 15.312 3.29415C15.3987 3.36261 15.473 3.45213 15.5332 3.55745C15.5951 3.66803 15.6269 3.79618 15.6269 3.93485C15.6269 4.0507 15.6039 4.16128 15.5597 4.25957C15.5172 4.35436 15.4606 4.44038 15.3881 4.51761C15.3173 4.59309 15.2376 4.66154 15.1456 4.71947C15.0589 4.77564 14.9669 4.82654 14.8713 4.87393L14.8147 4.90027L14.7599 4.87043C14.6307 4.80022 14.5104 4.73001 14.4024 4.66155C14.2962 4.59309 14.206 4.52112 14.1317 4.44564Z" fill="black"/><path d="M20.47 4.54966C20.4151 4.73748 20.3744 4.9253 20.3497 5.10785C20.3249 5.28865 20.3125 5.47296 20.3125 5.65376C20.3125 6.24355 20.4505 6.76312 20.723 7.20195C20.8363 7.37397 20.9708 7.53019 21.1212 7.6671C21.2734 7.80402 21.4415 7.92338 21.6202 8.01817C21.8025 8.11646 21.9989 8.19019 22.2024 8.24285C22.4076 8.29375 22.6271 8.32009 22.8518 8.32009H23.7649C24.2161 8.32009 24.6036 8.27619 24.9169 8.19018C25.2212 8.10417 25.4707 7.97428 25.6583 7.80051C25.8459 7.62849 25.9839 7.40556 26.0688 7.14051C26.1573 6.86668 26.2015 6.53317 26.2015 6.14876C26.2015 5.93286 26.1467 5.75206 26.0405 5.60812C25.9343 5.4677 25.7538 5.36413 25.5061 5.3027L23.9082 4.89546L23.8941 4.81823C23.8905 4.79541 23.8888 4.76908 23.8888 4.74451V4.69009C23.8888 4.45839 23.933 4.246 24.0197 4.06345C24.1046 3.88089 24.225 3.72116 24.3754 3.59302C24.524 3.46488 24.701 3.36658 24.9027 3.30163C25.1964 3.20334 25.5504 3.17349 25.8795 3.23317C25.9538 3.24722 26.0352 3.26653 26.1255 3.2911L26.336 2.4977C26.2069 2.4661 26.0883 2.44153 25.9786 2.42398C25.8423 2.40116 25.7043 2.39062 25.5663 2.39062C25.177 2.39062 24.8231 2.45207 24.5134 2.57143C24.2055 2.69079 23.9418 2.85578 23.7295 3.0594C23.5171 3.26302 23.3508 3.50526 23.2376 3.77909C23.1225 4.05291 23.0659 4.34955 23.0659 4.65849C23.0659 4.82173 23.0836 4.99025 23.1172 5.16052C23.1473 5.31323 23.1933 5.47121 23.2553 5.6327C23.3561 5.65727 23.4658 5.68535 23.5826 5.71519C23.7224 5.7503 23.8657 5.78716 24.0109 5.82754L25.1381 6.12594C25.2371 6.15227 25.2955 6.16807 25.3345 6.1786L25.4247 6.20492L25.4229 6.29796C25.4194 6.52264 25.384 6.71396 25.315 6.86492C25.2425 7.0229 25.1345 7.15105 24.9912 7.24583C24.8532 7.33711 24.678 7.40029 24.4692 7.43716C24.2692 7.47226 24.0339 7.48806 23.7667 7.48806H22.8536C22.5545 7.48806 22.2785 7.42487 22.0343 7.30025C21.7865 7.17562 21.5866 6.9913 21.4415 6.75434C21.2468 6.44891 21.146 6.07152 21.146 5.63444C21.146 5.48173 21.1583 5.32903 21.1796 5.17982C21.2008 5.03238 21.238 4.8744 21.2875 4.71116C21.3371 4.54967 21.399 4.37764 21.4698 4.20211C21.5282 4.05642 21.5972 3.89845 21.6786 3.72291L20.9371 3.36834C20.8416 3.56844 20.7567 3.76328 20.6823 3.94759C20.5956 4.15647 20.5248 4.35833 20.47 4.54966Z" fill="black"/><path d="M28.705 0H27.8711V5.95228H28.705V0Z" fill="black"/><path d="M36.5004 8.1879L37.1144 7.57881L36.4915 6.96094L35.8828 7.57003L36.5004 8.1879Z" fill="black"/><path d="M29.6675 7.34074C29.4552 7.40042 29.2021 7.43202 28.9137 7.43202H28.4961V8.25876H28.9137C29.2906 8.25876 29.6321 8.2026 29.9312 8.09026C30.2285 7.97967 30.4886 7.8217 30.701 7.61984C30.9151 7.41797 31.0885 7.17222 31.2177 6.88786C31.3468 6.59999 31.43 6.27527 31.4636 5.92069L31.4831 5.71883L31.6547 5.83117C31.7379 5.88558 31.8264 5.92068 31.9131 5.93297C31.9715 5.93999 32.037 5.9435 32.1007 5.94526L32.3962 5.95229H36.2255C36.4095 5.95229 36.5971 5.91893 36.7811 5.85574C36.9634 5.79254 37.1279 5.69601 37.2748 5.56611C37.4199 5.43798 37.5402 5.27298 37.6305 5.07287C37.7207 4.87452 37.7667 4.63228 37.7667 4.3567C37.7667 4.26542 37.7597 4.15835 37.7473 4.03723C37.7349 3.9126 37.7172 3.78622 37.696 3.65457C37.6747 3.52293 37.6517 3.39128 37.6269 3.25963C37.6022 3.12973 37.5792 3.01038 37.5562 2.9033C37.5332 2.79799 37.5137 2.70846 37.4942 2.63474C37.4925 2.62772 37.4907 2.62245 37.4889 2.61719L36.7068 2.85064L36.7245 2.92085C36.7457 3.0016 36.7652 3.09112 36.7864 3.18766C36.8059 3.28421 36.8271 3.38601 36.8483 3.49309C36.8714 3.60192 36.8908 3.70899 36.9085 3.81256C36.9262 3.91788 36.9404 4.01617 36.951 4.10921C36.9616 4.20399 36.9669 4.28474 36.9669 4.35144C36.9669 4.52522 36.9386 4.66212 36.8784 4.76919C36.82 4.87451 36.7493 4.95351 36.6679 5.00617C36.5882 5.05883 36.5051 5.09218 36.4237 5.10447C36.3511 5.11675 36.2945 5.12026 36.2555 5.12026H32.2316C32.2086 5.12026 32.1679 5.11851 32.0635 5.09745C31.9821 5.08165 31.8972 5.04654 31.8105 4.99388C31.722 4.94122 31.6441 4.8675 31.5786 4.77271C31.5079 4.6709 31.4725 4.54101 31.4725 4.38479V3.22276H30.6408V5.71707C30.6408 6.01547 30.6072 6.27526 30.5399 6.48941C30.4691 6.71058 30.3612 6.89313 30.2179 7.0318C30.0728 7.17573 29.8887 7.27754 29.6675 7.34074Z" fill="black"/><path d="M39.888 4.54966C39.8331 4.73923 39.7924 4.9253 39.7676 5.10785C39.7429 5.28865 39.7305 5.47296 39.7305 5.65376C39.7305 6.24355 39.8685 6.76312 40.141 7.20195C40.2543 7.37397 40.3887 7.53019 40.5392 7.6671C40.6913 7.80402 40.8594 7.92338 41.0382 8.01817C41.2204 8.11646 41.4168 8.19019 41.6203 8.24285C41.8256 8.29375 42.045 8.32009 42.2698 8.32009H43.1828C43.6341 8.32009 44.0216 8.27619 44.3348 8.19018C44.6392 8.10417 44.8887 7.97428 45.0763 7.80051C45.2638 7.62849 45.4019 7.40556 45.4868 7.14051C45.5753 6.86668 45.6195 6.53317 45.6195 6.14876C45.6195 5.93286 45.5647 5.75206 45.4585 5.60812C45.3523 5.4677 45.1718 5.36413 44.9258 5.3027L43.328 4.89546L43.3138 4.81823C43.3103 4.79541 43.3085 4.76908 43.3085 4.74451V4.69009C43.3085 4.45839 43.3527 4.246 43.4394 4.06345C43.5244 3.88089 43.6447 3.72116 43.7951 3.59302C43.9438 3.46488 44.1207 3.36658 44.3224 3.30163C44.6144 3.20334 44.9701 3.17349 45.2992 3.23317C45.3735 3.24722 45.4549 3.26653 45.5452 3.2911L45.7558 2.4977C45.6266 2.4661 45.508 2.44153 45.3983 2.42398C45.2621 2.40116 45.124 2.39062 44.986 2.39062C44.5967 2.39062 44.2428 2.45207 43.9331 2.57143C43.6252 2.69079 43.3616 2.85578 43.1492 3.0594C42.9369 3.26302 42.7705 3.50526 42.6573 3.77909C42.5423 4.05291 42.4857 4.34955 42.4857 4.65849C42.4857 4.82173 42.5033 4.99025 42.537 5.16052C42.567 5.31323 42.6131 5.47121 42.675 5.6327C42.7759 5.65727 42.8856 5.68535 43.0024 5.71519C43.1421 5.7503 43.2855 5.78716 43.4306 5.82754L44.5578 6.12594C44.6569 6.15227 44.7153 6.16807 44.7542 6.1786L44.8445 6.20492L44.8427 6.29796C44.8391 6.52439 44.8038 6.71397 44.7347 6.86668C44.6622 7.02466 44.5542 7.1528 44.4109 7.24583C44.2729 7.33711 44.0977 7.40029 43.8907 7.43716C43.6907 7.47226 43.4554 7.48806 43.1882 7.48806H42.2751C41.976 7.48806 41.7 7.42487 41.4558 7.30025C41.208 7.17562 41.0081 6.9913 40.863 6.75434C40.6683 6.44891 40.5675 6.07152 40.5675 5.63444C40.5675 5.48173 40.5798 5.32903 40.6011 5.17982C40.6241 5.03062 40.6595 4.87265 40.709 4.71116C40.7586 4.54967 40.8187 4.3794 40.8895 4.20211C40.9479 4.05642 41.0169 3.89845 41.0983 3.72291L40.3569 3.36834C40.2613 3.56844 40.1764 3.76153 40.1003 3.94759C40.0136 4.15647 39.9428 4.36009 39.888 4.54966Z" fill="black"/><path d="M48.1268 0H47.293V5.95228H48.1268V0Z" fill="black"/><path d="M57.4358 5.86296C57.6128 5.80328 57.7756 5.70499 57.9207 5.57159L58.0021 5.49611L58.0853 5.56983C58.3702 5.82084 58.7648 5.94723 59.2602 5.94723C59.4478 5.94723 59.6336 5.91738 59.8123 5.8577C59.9858 5.79978 60.1486 5.70148 60.2954 5.56807L60.3786 5.49259L60.4618 5.56807C60.7502 5.8261 61.1501 5.95776 61.6527 5.95776C61.8349 5.95425 62.0207 5.91914 62.2048 5.85419C62.3853 5.78749 62.5481 5.68743 62.6914 5.55403C62.8347 5.42238 62.9515 5.25212 63.0418 5.0485C63.132 4.84664 63.1762 4.60441 63.1762 4.33058C63.1762 4.2393 63.1692 4.13223 63.155 4.01638C63.1409 3.89351 63.1232 3.76888 63.1019 3.64074C63.0807 3.51085 63.0577 3.38271 63.0311 3.25632C63.0064 3.12819 62.9798 3.01234 62.9568 2.90877C62.9321 2.80346 62.9108 2.71393 62.8949 2.64372C62.8949 2.64021 62.8931 2.6367 62.8931 2.63319L62.111 2.8877L62.1287 2.9544C62.1481 3.02988 62.1676 3.11414 62.1888 3.20717C62.2083 3.3002 62.2295 3.39675 62.2508 3.50032C62.272 3.60388 62.2897 3.70569 62.3056 3.80574C62.3216 3.90579 62.3357 4.00233 62.3446 4.09186C62.3552 4.18664 62.3605 4.26739 62.3605 4.33409C62.3605 4.50787 62.3339 4.64303 62.2773 4.75186C62.2225 4.85717 62.1552 4.93967 62.0774 4.99759C62.0013 5.05376 61.9216 5.09414 61.8402 5.11345C61.7642 5.131 61.704 5.13977 61.658 5.13977C61.3695 5.13977 61.1537 5.07307 61.0156 4.93967C60.8776 4.80626 60.8068 4.60265 60.8068 4.33409V3.23526H59.9787V4.33409C59.9787 4.50787 59.9504 4.64653 59.8902 4.75536C59.8336 4.86068 59.7628 4.94318 59.6832 5.00111C59.6053 5.05728 59.5221 5.09238 59.4407 5.10818C59.37 5.12222 59.3116 5.12924 59.2673 5.12924C58.9771 5.12924 58.7701 5.06605 58.6338 4.9344C58.4993 4.80276 58.4303 4.6009 58.4303 4.33409V3.23526H57.5969V4.33409C57.5969 4.52893 57.5686 4.6676 57.5084 4.77292C57.45 4.87648 57.3792 4.95722 57.2996 5.01164C57.22 5.06605 57.1386 5.0994 57.0572 5.1152C56.9828 5.12924 56.9262 5.13451 56.8855 5.13451H54.2489C54.1445 5.13451 54.0259 5.12223 53.8932 5.10116C53.7534 5.07834 53.6136 5.0327 53.4809 4.966C53.3447 4.89754 53.219 4.79924 53.1058 4.67637C52.9872 4.54823 52.9023 4.37972 52.8527 4.17435L52.0954 1.25L51.2761 1.43957L51.3769 1.83978L51.269 1.87665C50.9965 1.96793 50.7346 2.075 50.4886 2.19612C50.2426 2.31548 50.025 2.46116 49.8392 2.62792C49.6552 2.79292 49.5048 2.98951 49.395 3.20893C49.2853 3.42483 49.2305 3.68111 49.2305 3.97074C49.2305 4.21824 49.2729 4.44467 49.3579 4.63951C49.4428 4.83611 49.5578 5.00286 49.6994 5.13802C49.8427 5.27318 50.0126 5.38026 50.202 5.45398C50.4939 5.56808 50.8142 5.60143 51.1787 5.5277C51.3044 5.50313 51.4282 5.46451 51.5486 5.4136C51.6654 5.36445 51.7751 5.30127 51.8759 5.22404C51.9733 5.15031 52.0529 5.06605 52.1131 4.97302L52.2316 4.79047L52.3254 4.98706C52.405 5.15382 52.5094 5.29951 52.6351 5.42063C52.7625 5.5435 52.9111 5.64531 53.0722 5.72254C53.2385 5.80153 53.4225 5.86297 53.6189 5.90159C53.8118 5.9402 54.0171 5.9595 54.2294 5.96126H56.8802C57.0678 5.95599 57.2554 5.9244 57.4358 5.86296ZM51.814 3.96722C51.7662 4.2077 51.6565 4.3885 51.4849 4.50084C51.3256 4.60616 51.1274 4.67637 50.8938 4.71148C50.8531 4.7185 50.8124 4.72025 50.77 4.72025C50.6992 4.72025 50.6284 4.71148 50.5594 4.69217C50.4515 4.66409 50.3524 4.61844 50.2639 4.55525C50.1736 4.4903 50.0993 4.40781 50.0427 4.31127C49.9843 4.20946 49.9542 4.09361 49.9542 3.96722C49.9542 3.65653 50.0905 3.39499 50.3594 3.18611C50.6089 2.99302 50.9752 2.81925 51.4477 2.67004L51.568 2.63143L51.6017 2.75255C51.6388 2.88244 51.6742 3.00005 51.7061 3.10537C51.7397 3.2142 51.768 3.31776 51.7892 3.41079C51.8105 3.50909 51.8246 3.60212 51.8299 3.68814C51.8405 3.78292 51.8335 3.87595 51.814 3.96722Z" fill="black"/><path d="M64.5586 8.08703C64.8665 7.97294 65.1301 7.80794 65.3425 7.60082C65.5566 7.39193 65.7282 7.13389 65.8521 6.83373C65.976 6.53006 66.0485 6.18427 66.0644 5.80688V3.22656H65.231V5.72087C65.231 6.01927 65.1974 6.27906 65.1301 6.49321C65.0593 6.71438 64.9514 6.89693 64.8081 7.0356C64.6647 7.17602 64.4789 7.27783 64.2577 7.34102C64.0454 7.4007 63.7923 7.4323 63.5039 7.4323H63.0898V8.25906H63.5039C63.8967 8.25906 64.2507 8.20113 64.5586 8.08703Z" fill="black"/><path d="M67.5496 5.01189C67.4045 4.97152 67.2594 4.92413 67.1178 4.87322L66.8789 5.65784C67.0859 5.71928 67.2664 5.77018 67.4204 5.81231C67.6026 5.86146 67.7637 5.90183 67.9053 5.93167C68.0433 5.96151 68.1689 5.98083 68.2769 5.99487C68.5193 6.02296 68.7564 6.01768 69.0271 5.98784C69.174 5.97029 69.3209 5.94221 69.4642 5.90184C69.6058 5.86146 69.7385 5.80705 69.8606 5.73859C69.9792 5.67365 70.0818 5.58939 70.1667 5.48934L70.2517 5.39104L70.3454 5.48057C70.6693 5.79126 71.0108 5.94923 71.3594 5.94923H72.0301C72.2123 5.94923 72.3734 5.9229 72.5061 5.87024C72.637 5.81758 72.7626 5.72981 72.8794 5.60519L72.9449 5.53673L73.0281 5.58061C73.1979 5.67189 73.3537 5.75439 73.497 5.82636C73.6315 5.89482 73.7677 5.9545 73.9022 6.0054C74.0314 6.0528 74.1588 6.09141 74.2809 6.11599C74.403 6.14056 74.534 6.1546 74.6667 6.1546C74.7357 6.1546 74.8171 6.14933 74.9144 6.14056C75.0047 6.13178 75.1002 6.10896 75.1993 6.07385C75.2949 6.03875 75.394 5.98609 75.493 5.91412C75.5868 5.84567 75.6735 5.74912 75.7514 5.62449C75.8293 5.49987 75.893 5.33663 75.9425 5.14003C75.9921 4.94168 76.0168 4.69593 76.0168 4.40806C76.0168 4.15178 75.9815 3.90254 75.9107 3.66908C75.8417 3.43913 75.7373 3.23375 75.5992 3.05822C75.463 2.8862 75.286 2.74578 75.079 2.64397C74.8702 2.54041 74.6207 2.4895 74.3393 2.4895C74.0597 2.4895 73.8226 2.54217 73.6386 2.64749C73.4492 2.75456 73.29 2.89322 73.1679 3.05822C73.0405 3.23024 72.9378 3.42157 72.8617 3.62694C72.7821 3.84285 72.7131 4.05524 72.6565 4.25535C72.6211 4.37647 72.5857 4.48529 72.5521 4.58359C72.5149 4.68891 72.4777 4.77668 72.4335 4.84865C72.3857 4.93115 72.3326 4.99434 72.2725 5.04174C72.2052 5.0944 72.1221 5.12248 72.0301 5.12248L71.3594 5.11896C71.2568 5.11896 71.1541 5.07509 71.0426 4.98381C70.9506 4.90833 70.8551 4.81179 70.7578 4.69593C70.6622 4.58184 70.5666 4.4537 70.4729 4.31854C70.3808 4.18338 70.2924 4.05349 70.2074 3.92711L68.7281 1.70312L68.0291 2.14897L69.4925 4.18865L69.6288 4.40104C69.6677 4.46072 69.696 4.51338 69.7137 4.55551C69.7367 4.60817 69.7473 4.65381 69.7473 4.69945C69.7473 4.74158 69.7367 4.78897 69.7173 4.83811C69.6748 4.94519 69.5934 5.02944 69.4766 5.09088C69.3722 5.1453 69.2466 5.18216 69.1032 5.19971C68.967 5.21726 68.8095 5.22253 68.6378 5.212C68.468 5.20147 68.291 5.17865 68.1087 5.14529C67.9282 5.11019 67.7389 5.0663 67.5496 5.01189ZM73.3077 4.69769C73.3572 4.57658 73.405 4.43966 73.4492 4.29046C73.4952 4.13248 73.5536 3.98152 73.6209 3.84285C73.6934 3.69716 73.7819 3.57429 73.8881 3.47599C74.0066 3.3654 74.1588 3.30923 74.3411 3.30923C74.5375 3.30923 74.7074 3.35663 74.8419 3.44967C74.9693 3.53568 75.0719 3.64451 75.1498 3.76913C75.2241 3.89025 75.2772 4.01663 75.3072 4.14476C75.3373 4.27115 75.3515 4.37296 75.3515 4.45897C75.3515 4.67136 75.3267 4.8346 75.2754 4.95923C75.2223 5.08386 75.1622 5.17689 75.0914 5.24184C75.0153 5.30854 74.9374 5.35067 74.8596 5.36295C74.7941 5.37348 74.7463 5.38051 74.7145 5.38051C74.6242 5.38051 74.5233 5.36295 74.4083 5.32784C74.3004 5.29449 74.1871 5.25237 74.0703 5.20322C73.9518 5.15232 73.8315 5.09614 73.7076 5.03295C73.5855 4.96976 73.4722 4.91008 73.3643 4.84865L73.2705 4.79599L73.3077 4.69769Z" fill="black"/></svg>',
                        "target":"_blank",
                        "title":"مدرسه تام‌لند"
                    },
					{
                        "url":"https://tizhooshan.tamland.ir",
                        "bgcolor":"#fff",
                        "color":"#222",
                        "icon":'<svg width="111" height="32" viewBox="0 0 111 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M94.8925 8.53247V1.271L87.5547 8.69649V15.958H94.7305L102.068 8.53247H94.8925Z" fill="#C4161C"/><path d="M102.616 16.3523V9.09082L95.2793 16.5147V23.7762H102.455L109.791 16.3523H102.616Z" fill="#C4161C"/><path d="M103.008 24.3357V31.5971L110.346 24.1716V16.9102L103.008 24.3357Z" fill="#C4161C"/><path d="M86.9997 8.13595L94.3375 0.710449H87.1257L79.8223 8.13595H86.9997Z" fill="#C4161C"/><path d="M86.8545 24.5197C88.8912 26.5807 89.9128 29.2862 89.921 31.9933H94.1564C94.1352 28.2043 92.7026 24.422 89.8522 21.5376C86.9789 18.63 83.2051 17.1771 79.4297 17.1787V21.4167C82.1196 21.415 84.8079 22.4488 86.8545 24.5197Z" fill="#58595B"/><path d="M79.4379 27.3018C79.4346 27.3018 79.433 27.3018 79.4297 27.3018V31.9936H84.1055C84.0973 30.7445 83.6143 29.5698 82.7401 28.6851C81.856 27.7938 80.6838 27.3018 79.4379 27.3018Z" fill="#C4161C"/><path d="M86.3108 25.0695C84.4133 23.1493 81.9215 22.1917 79.4297 22.1934V24.1732V26.5257C79.433 26.5257 79.4346 26.5257 79.4379 26.5257C80.8901 26.5257 82.2555 27.099 83.282 28.1377C84.3019 29.1699 84.8651 30.5383 84.8733 31.9963H87.1605H89.1595C89.1496 29.3786 88.1395 26.92 86.3108 25.0695Z" fill="#58595B"/><path d="M94.4953 24.5685V16.7471H86.7677V8.92725H79.4297V16.4058C83.3999 16.4041 87.3718 17.9316 90.3941 20.99C93.3935 24.0251 94.9014 28.0062 94.9227 31.994H102.225V24.5685H94.4953Z" fill="#58595B"/><path d="M68.788 18.3508L71.5238 21.1192L74.2873 18.321L71.5238 15.5244L68.788 18.2928L66.0507 15.5244L63.2871 18.321L66.0507 21.1192L68.788 18.3508Z" fill="#C4161C"/><path d="M24.1747 15.5248L21.4102 18.3223L24.1747 21.1197L26.9392 18.3223L24.1747 15.5248Z" fill="#C4161C"/><path d="M39.8145 21.2968V22.2246V26.1792V31.9994H43.7225V26.1792H55.2256V16.4028H44.6491C41.9838 16.4028 39.8145 18.598 39.8145 21.2968ZM51.3175 22.223H43.7225V21.2968C43.7225 20.7799 44.1383 20.3591 44.6491 20.3591H51.3159V22.223H51.3175Z" fill="#636363"/><path d="M68.9191 28.0431H62.2507C61.7399 28.0431 61.324 27.6223 61.324 27.1054V16.4028H57.416V27.1054C57.416 29.8042 59.5853 31.9994 62.2507 31.9994H72.8271V22.223H68.9191V28.0431Z" fill="#636363"/><path d="M33.7254 27.1054C33.7254 27.6223 33.3095 28.0431 32.7987 28.0431H26.1303V22.223H22.2223V28.0431H15.5539C15.0431 28.0431 14.6273 27.6223 14.6273 27.1054V22.7564V18.8631H10.7192V22.7564V27.1054C10.7192 27.6223 10.3034 28.0431 9.79257 28.0431H2.09766V31.9994H9.79257C10.8715 31.9994 11.8686 31.6399 12.6741 31.0335C13.4796 31.6399 14.4766 31.9994 15.5556 31.9994H22.2239H26.132H32.8004C35.4674 31.9994 37.6351 29.8042 37.6351 27.1054V16.4028H33.727V27.1054H33.7254Z" fill="#636363"/><path d="M3.102 12.959C2.66933 12.959 2.26967 12.8527 1.903 12.64C1.54367 12.4273 1.25767 12.1377 1.045 11.771C0.832333 11.4117 0.726 11.0157 0.726 10.583V7.921L2.178 7.558V10.583C2.178 10.8397 2.266 11.0597 2.442 11.243C2.62533 11.4263 2.84533 11.518 3.102 11.518H4.202C4.45133 11.518 4.664 11.4263 4.84 11.243C5.02333 11.0597 5.115 10.8397 5.115 10.583V5.721L6.567 5.358V10.583C6.567 11.0157 6.46067 11.4117 6.248 11.771C6.03533 12.1377 5.74933 12.4273 5.39 12.64C5.03067 12.8527 4.63467 12.959 4.202 12.959H3.102ZM2.519 5.149H3.762V6.315H2.519V5.149ZM10.0092 10C9.57656 10 9.17689 9.89367 8.81023 9.681C8.45089 9.46833 8.16489 9.18233 7.95223 8.823C7.73956 8.45633 7.63323 8.06033 7.63323 7.635V2.938L9.07423 2.575V7.635C9.07423 7.89167 9.16589 8.11167 9.34923 8.295C9.53256 8.471 9.75256 8.559 10.0092 8.559L10.2622 9.285L10.0092 10ZM9.74772 9.285L10.0117 8.559H11.9917L12.2557 9.296L11.9917 10H10.0117L9.74772 9.285ZM11.7243 9.285L11.9883 8.559H13.9683L14.2323 9.296L13.9683 10H11.9883L11.7243 9.285ZM13.7008 9.285L13.9648 8.559H15.9448L16.2088 9.296L15.9448 10H13.9648L13.7008 9.285ZM15.6774 9.285L15.9414 8.559H17.9214L18.1854 9.296L17.9214 10H15.9414L15.6774 9.285ZM17.654 9.285L17.918 8.559H19.898L20.162 9.296L19.898 10H17.918L17.654 9.285ZM19.6305 9.285L19.8945 8.559H21.8745L22.1385 9.296L21.8745 10H19.8945L19.6305 9.285ZM21.6071 9.285L21.8711 8.559H23.8511L24.1151 9.296L23.8511 10H21.8711L21.6071 9.285ZM23.5837 9.285L23.8477 8.559H25.8277L26.0917 9.296L25.8277 10H23.8477L23.5837 9.285ZM25.7912 10L25.5602 9.285L25.8022 8.559H25.8242C26.0809 8.559 26.2972 8.471 26.4732 8.295C26.6566 8.11167 26.7482 7.89167 26.7482 7.635V5.809H28.1892V7.789C28.1892 8.00167 28.2626 8.185 28.4092 8.339C28.5632 8.48567 28.7466 8.559 28.9592 8.559C29.1719 8.559 29.3516 8.48567 29.4982 8.339C29.6522 8.185 29.7292 8.00167 29.7292 7.789V5.809H31.1702V7.789C31.1702 8.00167 31.2436 8.185 31.3902 8.339C31.5442 8.48567 31.7276 8.559 31.9402 8.559C32.1529 8.559 32.3326 8.48567 32.4792 8.339C32.6332 8.185 32.7102 8.00167 32.7102 7.789V5.754L34.1512 5.391V7.778C34.1512 8.18133 34.0522 8.55533 33.8542 8.9C33.6562 9.23733 33.3886 9.505 33.0512 9.703C32.7139 9.901 32.3436 10 31.9402 10C31.6762 10 31.4122 9.945 31.1482 9.835C30.8916 9.71767 30.6606 9.571 30.4552 9.395C30.2572 9.58567 30.0299 9.736 29.7732 9.846C29.5166 9.94867 29.2452 10 28.9592 10C28.6806 10 28.4019 9.93767 28.1232 9.813C27.8519 9.681 27.6209 9.52333 27.4302 9.34C27.2176 9.54533 26.9719 9.70667 26.6932 9.824C26.4219 9.94133 26.1322 10 25.8242 10H25.7912ZM29.1242 3.422H30.6422V2.366L31.8192 2.729V4.555H29.1242V3.422ZM35.5822 11.463L37.7822 10H37.1442C36.8068 10 36.4658 9.89733 36.1212 9.692C35.7838 9.47933 35.5015 9.19333 35.2742 8.834C35.0542 8.47467 34.9442 8.07867 34.9442 7.646C34.9442 7.22067 35.0505 6.82467 35.2632 6.458C35.4758 6.09133 35.7618 5.80167 36.1212 5.589C36.4878 5.37633 36.8838 5.27 37.3092 5.27H39.6852V8.559H40.2352L40.4882 9.285L40.2352 10H39.6852V10.462L36.3742 12.662L35.5822 11.463ZM36.3852 7.624C36.3852 7.88067 36.4732 8.10067 36.6492 8.284C36.8325 8.46733 37.0525 8.559 37.3092 8.559H38.2442V6.7H37.3092C37.0598 6.7 36.8435 6.79167 36.6602 6.975C36.4768 7.151 36.3852 7.36733 36.3852 7.624ZM39.9762 9.285L40.2402 8.559H42.2202L42.4842 9.296L42.2202 10H40.2402L39.9762 9.285ZM41.9528 9.285L42.2168 8.559H44.1968L44.4608 9.296L44.1968 10H42.2168L41.9528 9.285ZM43.9294 9.285L44.1934 8.559H46.1734L46.4374 9.296L46.1734 10H44.1934L43.9294 9.285ZM45.9059 9.285L46.1699 8.559H48.1499L48.4139 9.296L48.1499 10H46.1699L45.9059 9.285ZM47.8825 9.285L48.1465 8.559H48.9935C48.7955 8.28767 48.6965 7.97967 48.6965 7.635C48.6965 7.29033 48.7955 6.97867 48.9935 6.7H48.6855V5.27H54.1085C54.5412 5.27 54.9372 5.37633 55.2965 5.589C55.6558 5.80167 55.9418 6.08767 56.1545 6.447C56.3672 6.80633 56.4735 7.20233 56.4735 7.635C56.4735 8.06767 56.3672 8.46367 56.1545 8.823C55.9418 9.18233 55.6558 9.46833 55.2965 9.681C54.9372 9.89367 54.5412 10 54.1085 10H48.1465L47.8825 9.285ZM50.1485 7.635C50.1485 7.89167 50.2365 8.11167 50.4125 8.295C50.5885 8.471 50.8048 8.559 51.0615 8.559C51.3182 8.559 51.5345 8.471 51.7105 8.295C51.8938 8.11167 51.9855 7.89167 51.9855 7.635C51.9855 7.37833 51.8938 7.15833 51.7105 6.975C51.5345 6.79167 51.3182 6.7 51.0615 6.7C50.8122 6.7 50.5958 6.79167 50.4125 6.975C50.2365 7.15833 50.1485 7.37833 50.1485 7.635ZM54.1085 8.559C54.3652 8.559 54.5815 8.471 54.7575 8.295C54.9408 8.11167 55.0325 7.89167 55.0325 7.635C55.0325 7.37833 54.9408 7.15833 54.7575 6.975C54.5815 6.79167 54.3652 6.7 54.1085 6.7H53.1405C53.3385 6.99333 53.4375 7.305 53.4375 7.635C53.4375 7.965 53.3385 8.273 53.1405 8.559H54.1085ZM56.6834 11.452L57.6954 10.781V5.732L59.1364 5.369V7.635C59.1364 7.89167 59.2244 8.11167 59.4004 8.295C59.5838 8.471 59.8038 8.559 60.0604 8.559L60.3134 9.285L60.0604 10C59.7598 10 59.4518 9.868 59.1364 9.604V11.551L57.4864 12.651L56.6834 11.452ZM57.8494 3.103H59.0924V4.258H57.8494V3.103ZM59.7848 9.285L60.0488 8.559H62.0288L62.2928 9.296L62.0288 10H60.0488L59.7848 9.285ZM61.7614 9.285L62.0254 8.559H64.0054L64.2694 9.296L64.0054 10H62.0254L61.7614 9.285ZM63.738 9.285L64.002 8.559H65.982L66.246 9.296L65.982 10H64.002L63.738 9.285ZM65.7145 9.285L65.9785 8.559H67.9585L68.2225 9.296L67.9585 10H65.9785L65.7145 9.285ZM67.9331 10L67.6911 9.285L67.9441 8.559H67.9551C68.2117 8.559 68.4317 8.471 68.6151 8.295C68.7984 8.11167 68.8901 7.89167 68.8901 7.635V5.721L70.3201 5.369V7.635C70.3201 7.89167 70.4117 8.11167 70.5951 8.295C70.7784 8.471 70.9984 8.559 71.2551 8.559L71.5081 9.285L71.2551 10C70.9324 10 70.6281 9.93767 70.3421 9.813C70.0561 9.681 69.8104 9.505 69.6051 9.285C69.3924 9.505 69.1431 9.681 68.8571 9.813C68.5784 9.93767 68.2777 10 67.9551 10H67.9331ZM66.7891 11.089H69.4841V12.233H66.7891V11.089ZM70.9889 9.285L71.2529 8.559H72.0559C72.3126 8.559 72.5326 8.471 72.7159 8.295C72.8993 8.11167 72.9909 7.89167 72.9909 7.635V5.721L74.4319 5.369V7.635C74.4319 8.06033 74.3256 8.45633 74.1129 8.823C73.9003 9.18233 73.6106 9.46833 73.2439 9.681C72.8846 9.89367 72.4886 10 72.0559 10H71.2529L70.9889 9.285ZM72.0119 3.103H74.6959V4.258H72.0119V3.103Z" fill="#C4161C"/></svg>',
                        "target":"_blank",
                        "title":"تیزهوشان تام‌لند"
                    }
                ]
                $('.kc_fab_wrapper').kc_fab(links);
            })
        </script>
        <style>
            .sub_fab_btns_wrapper button{
                width: 200px;
                height: auto;
                border-radius: 8px;
                background: #fff;
                padding: 15px;
            }
            .sub_fab_btns_wrapper button img{width:100%;}
            .sub_fab_btns_wrapper button:hover{
                border-radius:8px;
                background:#eee !important;
            }
            .sub_fab_btns_wrapper button:after{
                border-radius:8px !important;
                background:#fff !important;
                color:#222 !important;
                border:1px solid #eee !important;
            }
            button.kc_fab_main_btn{
                right:30px;
                bottom:30px;
            }
            button.kc_fab_main_btn span{
                display:flex;
                align-items:center;
                justify-content:center;
            }
            .sub_fab_btns_wrapper{
                bottom:105px;
            }
        </style>
    <?php
    }
}
add_action('wp_footer','add_float_button');


/*Start Goftino*/
function add_chat_widget() {
    
    if (!is_page('course-checkout') && !is_page('return-payment-gateway')) {
    ?>
    <style>
        #Goftino_tamland {
            background: rgb(255, 0, 44);
            border-radius: 50px;
            padding: 0px;
            font-size: 18px;
            position: fixed;
            bottom: 35px;
            left: 30px;
            color: black;
            display: none; 
            cursor: pointer;
            z-index: 9999;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 55px;
            height: 55px;
        }
        #Goftino_tamland svg {
            width: 100%;
            height: 100%;
        }
        #unread_counter {
            background: #000000 !important;
            border-radius: 50%;
            padding: 3px;
            font-size: 12px;
            position: absolute;
            color: white;
            min-width: 18px;
            height: 18px;
            top: -5px;
            right: -5px;
            z-index: 5;
            text-align: center;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (min-width: 768px) and (max-width: 1200px) {
        #goftino_w {
            position: fixed !important;
            bottom: 50px !important;
            left: 0px !important;
            z-index: 9999 !important;
        }
    }

    @media only screen and (max-width: 768px) {
        #Goftino_tamland {
            position: fixed ;
            top: 50% ;
            left: -3px;
            transform: translateY(-50%) ;
            padding: 0px;
            transition: none ;
            border-radius: 15% ;
            width: 2.5rem ;
            height: 2.5rem ;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .2), 0 1px 10px rgba(0, 0, 0, .15) ;
            margin: 0 auto ;
        }
        #Goftino_tamland svg {
                width: 80%;
                height: 80%;
            }
    }
    </style>
    <div id="Goftino_tamland">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120">
            <path d="M60.19,53.75a3,3,0,1,0,3.06,3A3,3,0,0,0,60.19,53.75Zm-11.37,0a3,3,0,1,0,3.06,3A3,3,0,0,0,48.81,53.75Zm45.94,4A35,35,0,1,0,52.75,92v12.76s14.55-4.25,30.53-19.28C94.68,74.74,94.75,59.41,94.75,59.41l0,0C94.74,58.87,94.75,58.3,94.75,57.72Zm-10.14.6s0,10.64-8,18.09A57.93,57.93,0,0,1,53,89.8V80.34A24.29,24.29,0,1,1,84.61,57.16c0,.4,0,.8,0,1.19ZM70.69,53.75a3,3,0,1,0,3.06,3A3,3,0,0,0,70.69,53.75Z" transform="translate(0.25 0.25)" style="fill:#ffffff"></path>
        </svg>
        <span id="unread_counter">0</span>
    </div>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            // بررسی و حذف آیکن‌های اضافی
            const widgetButtons = document.querySelectorAll('#Goftino_tamland');
            if (widgetButtons.length > 1) {
                for (let i = 1; i < widgetButtons.length; i++) {
                    widgetButtons[i].remove();
                }
            }

            const widgetObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        loadWidgetScript();
                        observer.disconnect(); 
                    }
                });
            });

            const widgetButton = document.getElementById('Goftino_tamland');
            widgetObserver.observe(widgetButton);

            function loadWidgetScript() {
                var d = document;
                var g = d.createElement("script"),
                    i = "kkkIbA", 
                    s = "https://www.goftino.com/widget/" + i,
                    l = localStorage.getItem("goftino_" + i);

                g.type = "text/javascript";
                g.async = true;
                g.src = l ? s + "?o=" + l : s;

                g.onload = function () {
                    if (typeof Goftino !== "undefined") {
                        Goftino.setWidget({
                            hasIcon: false,
                            counter: "#unread_counter"
                        });

                        widgetButton.style.display = "flex";

                        widgetButton.addEventListener("click", function () {
                            Goftino.toggle();
                        });
                    }
                };
                d.getElementsByTagName("head")[0].appendChild(g);
            }
        });
    </script>
    <?php
    }
}
add_action('wp_footer', 'add_chat_widget');

/*End Goftino*/


/**
 * Courses Groups Items.
 */
add_shortcode('course_groups','course_groups_func');
function course_groups_func(){
    $url = get_post_permalink();
    $courses_cat = get_post_meta( get_the_ID(), 'courses-cat', true );
    if ( !empty($courses_cat) ) {
    echo '<ul>';
    for($i = 0; $i < count($courses_cat); $i++){
        if($courses_cat['item-'.$i]['courses-cat-name-card'] !== ""){
            echo '<li>
                    <a href="'.$url.'#group-'.$courses_cat['item-'.$i]['courses-cat-num'].'" rel="nofollow noindex">'.
                        $courses_cat['item-'.$i]['courses-cat-name-card'].'
                    </a>
                  </li>
                ';
        }
    }
    echo '</ul>';
    }
}

/**
 * Courses list UI for pack courses type.
 */ 
 function pack_courses_items_func(){
    if(is_singular('course')){
        $courses_cat = get_post_meta( get_the_ID(), 'courses-cat', true );
        $pack_courses = get_post_meta( get_the_ID(), 'pack-courses', true );
        ?>
        <script type="text/javascript" defer>
            jQuery(document).ready(function(){
                jQuery.fn.shuffle = function() {
                    var allElems = this.get(),
                        getRandom = function(max) {
                            return Math.floor(Math.random() * max);
                        },
                        shuffled = jQuery.map(allElems, function(){
                            var random = getRandom(allElems.length),
                                randEl = jQuery(allElems[random]).clone(true)[0];
                            allElems.splice(random, 1);
                            return randEl;
                       });
             
                    this.each(function(i){
                        jQuery(this).replaceWith(jQuery(shuffled[i]));
                    });
             
                    return jQuery(shuffled);
                };
            });
        </script>
		<div class="container multiteacher">
        <?php
        for($i = 0; $i < count($courses_cat); $i++){
            ?>
            
                <li class="pack-courses-items">
                    <div class="row courses-wrapper align-items-start justify-content-center">
                        <div class="courses-title mb-3">
                            <h3>
                                <img src="<?php echo UPLOADS_DIR.'/2025/02/presention-chart.webp'; ?>" width="24px" height="24px">
                            <?php echo $courses_cat['item-'.$i]['courses-cat-name']; ?>
                            </h3>
                        </div>
						<div class="row justify-content-center" id="courseRow-<?php echo $i; ?>">
                        <?php                    
                        for($j = 0; $j < count($pack_courses); $j++){
                            if($pack_courses['item-'.$j]['pack-courses-cat-num'] === $courses_cat['item-'.$i]['courses-cat-num']){
                                ?>
									<div class="col-6 col-md-4 col-lg-3 course-row-item mb-3 course-row-item-<?php echo $pack_courses['item-'.$j]['pack-course-teacher']; ?>">
										<div class="course-teacher-item" data-item="<?php echo $j; ?>" id="teacher-<?php echo $pack_courses['item-'.$j]['pack-course-teacher']; ?>">
											<div class="course-teacher-img border shadow-sm rounded-circle overflow-hidden">
												<img src="<?php echo $pack_courses['item-'.$j]['pack-course-teacher-img']; ?>" class="rounded-circle">
											</div>
											<div class="teacher-course-name"><?php echo get_the_title($pack_courses['item-'.$j]['pack-course-teacher']); ?></div>
										</div>
									</div>
                                <?php
                            }
                        }
						?>
						</div>
						<script type="text/javascript" defer>
						jQuery(document).ready(function(){
						  jQuery("#courseRow-<?php echo $i; ?> .course-row-item").shuffle();
						});
						</script>
						<?php
                        for($j = 0; $j < count($pack_courses); $j++){
                            if($pack_courses['item-'.$j]['pack-courses-cat-num'] === $courses_cat['item-'.$i]['courses-cat-num']){
                                ?>
                                <ul class="pack-course-info row d-none pack-course-info-<?php echo $pack_courses['item-'.$j]['pack-course-teacher']; ?>" data-panel="<?php echo $j ?>" id="pack-course-info-<?php echo $j; ?>">
                                        <li class="col-12 col-md-5">
                                            <div>
                                                <?php echo $pack_courses['item-'.$j]['pack-course-title']; ?>
                                            </div>
                                        </li>
                                        <li class="col-12 col-md-4">
                                            <div>
                                                <img src="<?php echo UPLOADS_DIR.'/2022/09/presentation-2.svg'; ?>"><span class="gray-txt"> نام استاد: </span>
                                                <?php if(get_the_title($pack_courses['item-'.$j]['pack-course-teacher']) == "گروه اساتید"):
                                                    echo get_the_title($pack_courses['item-'.$j]['pack-course-teacher']); 
                                                else: ?>
                                                <a href="<?php echo get_post_permalink($pack_courses['item-'.$j]['pack-course-teacher']); ?>"  style="font-weight:bold;">
                                                    <?php echo get_the_title($pack_courses['item-'.$j]['pack-course-teacher']); ?>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                        <?php if($pack_courses['item-'.$j]['pack-course-date'] != ""): ?>
                                        <li class="col-12 col-md-3">
                                            <div>
                                                <img src="<?php echo UPLOADS_DIR.'/2024/07/calendar.svg'; ?>"><span class="gray-txt"> تاریخ شروع دوره: </span><?php echo $pack_courses['item-'.$j]['pack-course-date']; ?>
                                            </div>
                                        </li>
                                        <?php endif; 
                                        if($pack_courses['item-'.$j]['pack-course-hour'] != ""): ?>
                                        <li class="col-12">
                                            <div>
                                                <img src="<?php echo UPLOADS_DIR.'/2024/07/timer.svg'; ?>"><span class="gray-txt"> ساعت شروع دوره: </span><?php echo $pack_courses['item-'.$j]['pack-course-hour']; ?>
                                            </div>
                                        </li>
                                        <?php endif; 
                                        
                                        if($pack_courses['item-'.$j]['pack-course-desc'] != ""): ?>
                                        <li class="col-12">
                                            <div>
                                                <?php echo $pack_courses['item-'.$j]['pack-course-desc']; ?>
                                            </div>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php
                            }
                        }
                        ?>
                    </div>
                <hr class="my-4">
                </li>
            
            <?php
        }
        ?>
        </div>
        <script>
            window.onload = function(e){
                var elements = document.getElementsByClassName("course-teacher-item");
                var packCourseInfo = document.getElementsByClassName("pack-course-info");
                var myFunction = function() {
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].querySelector('.course-teacher-img').classList.remove("active");
                    }
                    this.querySelector('.course-teacher-img').classList.add("active");
                    //this.childNodes[1].classList.add('active');
                    let dataItem = this.getAttribute("data-item");
                    for(let i = 0; i < packCourseInfo.length; i++){
                        packCourseInfo[i].classList.add("d-none");
                    }
                    document.querySelector("[data-panel='"+dataItem+"']").classList.remove("d-none");
                    if (isMobile()) {
                        document.getElementById("pack-course-info-"+dataItem).scrollIntoView();
                    }
                };
                
                for (let i = 0; i < elements.length; i++) {
                    elements[i].addEventListener('click', myFunction, false);
                }
                
                let liRowCount = 0;
                let carouselNumber = 0;
                clearInterval(sortCarouselTimer);
                jQuery(".multiteacher > li").each(function() {
                    liRowCount = liRowCount + 1;
                });
                var sortCarouselTimer = setInterval(function(){
                    if(carouselNumber < liRowCount){
                        jQuery("#courseRow-"+carouselNumber+" .course-row-item").shuffle();
                        carouselNumber = carouselNumber + 1;
                    }else{
                        carouselNumber = 0;
                        jQuery("#courseRow-"+carouselNumber+" .course-row-item").shuffle();
                        carouselNumber = carouselNumber + 1;
                    }
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].addEventListener('click', myFunction, false);
                    }
                }, 5000);
                
                let url = new URL(window.location.href);
                let teacherSelected = url.href.substring(url.href.lastIndexOf('#') + 1);
                if(teacherSelected != url){
                    /*clearInterval(sortCarouselTimer);
                    jQuery(".course-row-item").addClass("d-none");
                    jQuery(".pack-course-info").addClass("d-none");
                    jQuery(".course-row-item-"+teacherSelected).removeClass("d-none");
                    jQuery(".pack-course-info-"+teacherSelected).removeClass("d-none");*/
                    jQuery("#teacher-"+teacherSelected).click();
                    document.getElementById(teacherSelected).scrollIntoView();
                }
            }
        </script>
        <?php
    }
}

add_shortcode('pack_courses_items','pack_courses_items_func');



/**
 * Courses list UI for multi-teacher courses type.
 */ 
function teachers_course_items_func(){
    if(is_singular('course')){
        $courses_cat = get_post_meta( get_the_ID(), 'courses-cat', true );
        $teachers_courses = get_post_meta( get_the_ID(), 'teachers-course', true );
        $course_option_page  = jet_engine()->options_pages->registered_pages['courses-options'];
        $tax = $course_option_page->get( 'tax' );
        ?>
            <script type="text/javascript" defer>
                jQuery(document).ready(function(){
                    jQuery.fn.shuffle = function() {
                        var allElems = this.get(),
                            getRandom = function(max) {
                                return Math.floor(Math.random() * max);
                            },
                            shuffled = jQuery.map(allElems, function(){
                                var random = getRandom(allElems.length),
                                    randEl = jQuery(allElems[random]).clone(true)[0];
                                allElems.splice(random, 1);
                                return randEl;
                           });
                 
                        this.each(function(i){
                            jQuery(this).replaceWith(jQuery(shuffled[i]));
                        });
                 
                        return jQuery(shuffled);
                    };
                });
            </script>
            <div class="container multiteacher my-5">
                <li class="multiteacher-courses-items">
                    <div class="row courses-wrapper align-items-start justify-content-center">
                        <div class="row justify-content-center" id="courseRow">
                        <?php
                        for($j = 0; $j < count($teachers_courses); $j++){
                                ?>
									<div class="col-6 col-md-4 col-lg-3 course-row-item position-relative mb-3 course-row-item-<?php echo $teachers_courses['item-'.$j]['teacher-course-name']; ?>">
                                        <div class="course-teacher-item shadow-sm rounded overflow-hidden pb-3" data-item="<?php echo $j ?>" id="teacher-<?php echo $teachers_courses['item-'.$j]['teacher-course-name']; ?>">
                                            <div class="course-teacher-img">
                                                <img src="<?php echo $teachers_courses['item-'.$j]['teacher-course-img']; ?>">
                                            </div>
                                            <div class="text-center mb-3">
                                                <h2 class="fw-bold" style="font-size:18px"><?php echo get_the_title($teachers_courses['item-'.$j]['teacher-course-name']); ?></h2>
                                            </div>
                                            <div class="row w-100">
                                                <?php if($teachers_courses['item-'.$j]['teacher-aparat-code'] != ""): ?>
                                                    <div class="col-6 first-class-video-btn" id="<?php echo $teachers_courses['item-'.$j]['teacher-aparat-code']; ?>">
                                                        <div class="elementor-widget-container">
                                                            <a class="w-100 d-block">
                                                                <button class="w-100" style="background:#11365C;border-radius:15px;font-size:14px">
                                                                ویدیو جلسه اول
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="<?php echo ($teachers_courses['item-'.$j]['teacher-aparat-code'] != "") ? "col-6" : "col-12"; ?>">
                                                    <button class="w-100" style="border-radius:15px;font-size:14px">
                                                        جزئیات بیشتر
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                        }
                        ?>
                        </div>
						<script type="text/javascript" defer>
						jQuery(document).ready(function(){
						  jQuery("#courseRow .course-row-item").shuffle();
						});
						</script>
                        <?php
                        for($j = 0; $j < count($teachers_courses); $j++){
                                ?>
                                <div class="multiteacher-course-item shadow-sm teacher-course-info d-none multiteacher-course-item-<?php echo $teachers_courses['item-'.$j]['teacher-course-name']; ?>" data-panel="<?php echo $j ?>" id="multiteacher-course-item-<?php echo $j; ?>">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <h2 style="text-align:center">
                                                <a href="<?php echo get_post_permalink($teachers_courses['item-'.$j]['teacher-course-name']); ?>" style="font-weight:900;color:#11365C;"><?php echo get_the_title($teachers_courses['item-'.$j]['teacher-course-name']); ?></a>
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <ul>
                                                <?php if($teachers_courses['item-'.$j]['teacher-course-date'] != ""): ?>
                                                <li class="mb-3">
                                                    <div>
                                                        <img src="<?php echo UPLOADS_DIR.'/2022/09/calendar.svg'; ?>"><span class="gray-txt"> تاریخ شروع دوره: </span><span class="fw-bold text-black"><?php echo $teachers_courses['item-'.$j]['teacher-course-date']; ?></span>
                                                    </div>
                                                </li>
                                                <?php
                                                endif;
                                                if($teachers_courses['item-'.$j]['teacher-course-hour'] != ""): ?>
                                                <li class="mb-3">
                                                    <div>
                                                        <img src="<?php echo UPLOADS_DIR.'/2022/09/timer.svg'; ?>"><span class="gray-txt"> ساعت شروع دوره: </span><span class="fw-bold text-black"><?php echo $teachers_courses['item-'.$j]['teacher-course-hour']; ?></span>
                                                    </div>
                                                </li>
                                                <?php
                                                endif;
                                                if($teachers_courses['item-'.$j]['teacher-course-desc'] != ""): ?>
                                                <li class="mb-3">
                                                    <div class="text-black" style="font-size:14px;text-align:justify;">
                                                        <p><span style="color:#606B80 !important"><img src="<?php echo UPLOADS_DIR.'/2024/10/document-text-courseDetail.svg'; ?>"> توضیحات:</span></p>
                                                        <?php echo $teachers_courses['item-'.$j]['teacher-course-desc']; ?>
                                                    </div>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="row mx-0 px-4">
                                                <?php if($teachers_courses['item-'.$j]['lesson-plan-pdf-file'] != ""): ?>
                                                <div class="col-6 py-1">
                                                    <a href="<?php echo $teachers_courses['item-'.$j]['lesson-plan-pdf-file']; ?>" class="d-block btn" style="background:#FE9923CC;color:#fff;border-radius:20px">
                                                        طرح درس
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($teachers_courses['item-'.$j]['teacher-aparat-code'] != ""): ?>
                                                <div class="col-6 py-1">
                                                        <div class="col first-class-video-btn" id="<?php echo $teachers_courses['item-'.$j]['teacher-aparat-code']; ?>">
                                                            <div class="elementor-widget-container">
                                                                <a class="w-100 d-block">
                                                                    <button class="d-block btn w-100" style="background:#1EB2A6CC;color:#fff;border-radius:20px">
                                                                    ویدیو جلسه اول
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($teachers_courses['item-'.$j]['pdf-file'] != ""): ?>
                                                <div class="col-6 py-1">
                                                    <a href="<?php echo $teachers_courses['item-'.$j]['pdf-file']; ?>" class="d-block btn" style="background:#99BFEC;color:#fff;border-radius:20px">
                                                        نمونه جزوه استاد
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($teachers_courses['item-'.$j]['teacher-purchase-link'] != ""): ?>
                                                <div class="col-6 py-1">
                                                    <a href="<?php echo $teachers_courses['item-'.$j]['teacher-purchase-link']; ?>" class="d-block btn" style="background:#FF7064CC;color:#fff;border-radius:20px">
                                                        دوره های دیگر استاد
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <ul>
                                                <?php if($teachers_courses['item-'.$j]['teacher-course-price-area-1'] == "" || $teachers_courses['item-'.$j]['teacher-course-price-area-2'] == "" || $teachers_courses['item-'.$j]['teacher-course-price-area-3'] == ""): ?>
                                                <li>
                                                    <div class="row mx-0">
                                                        <div class="col-4">قیمت دوره</div>
                                                        <div class="col-8 text-left">
                                                            <div>
                                                                <?php
                                                                if($teachers_courses['item-'.$j]['teacher-course-price'] != "0"):
                                                                ?>
                                                                <strong class="<?php if($teachers_courses['item-'.$j]['teacher-course-price-sale'] == ""){echo 'price-number';}else{echo 'price-number-sale';} ?>">
                                                                    <span><?php echo $teachers_courses['item-'.$j]['teacher-course-price']; ?></span>
                                                                    تومان
                                                                </strong>
                                                                <?php
                                                                else:
                                                                ?>
                                                                <strong class="<?php if($teachers_courses['item-'.$j]['teacher-course-price-sale'] == ""){echo 'price-number';}else{echo 'price-number-sale';} ?>">
                                                                    <span>رایگان</span>
                                                                </strong>
                                                                <?php
                                                                endif;
                                                                ?>
                                                            </div>
                                                            <?php
                                                            if($teachers_courses['item-'.$j]['teacher-course-price-sale'] != ""):
                                                            ?>
                                                            <div>
                                                                <strong class="price-number">
                                                                <?php 
                                                                echo '<span>'.$teachers_courses['item-'.$j]['teacher-course-price-sale'].'</span> تومان<span>'.$teachers_courses['item-'.$j]['teacher-course-discount-percent'].'</span>'; ?>
                                                                </strong>
                                                            </div>
                                                            <?php
                                                            endif;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php else: ?>
                                                <li>
                                                    <div class="row mx-0">
                                                        <div class="col-4">قیمت منطقه یک        </div>
                                                        <div class="col-8 text-left">
                                                            <div>
                                                                <strong>
                                                                    <span><?php echo $teachers_courses['item-'.$j]['teacher-course-price-area-1']; ?></span>
                                                                تومان
                                                                </strong>
                                                            </div>
                                                            <div>
                                                                <strong>
                                                                <?php 
                                                                echo '<span>'.$teachers_courses['item-'.$j]['teacher-course-price-sale-area-1'].'</span> تومان<span>'.$teachers_courses['item-'.$j]['teacher-course-discount-percent-area-1'].'</span>'; ?>
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row mx-0">
                                                        <div class="col-4">قیمت منطقه دو      </div>
                                                        <div class="col-8 text-left">
                                                            <div>
                                                                <strong>
                                                                    <span><?php echo $teachers_courses['item-'.$j]['teacher-course-price-area-2']; ?></span>
                                                                تومان
                                                                </strong>
                                                            </div>
                                                            <div>
                                                                <strong>
                                                                <?php 
                                                                echo '<span>'.$teachers_courses['item-'.$j]['teacher-course-price-sale-area-2'].'</span> تومان<span>'.$teachers_courses['item-'.$j]['teacher-course-discount-percent-area-2'].'</span>'; ?>
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row mx-0">
                                                        <div class="col-4">قیمت منطقه سه      </div>
                                                        <div class="col-8 text-left">
                                                            <div>
                                                                <strong>
                                                                    <span><?php echo $teachers_courses['item-'.$j]['teacher-course-price-area-3']; ?></span>
                                                                تومان
                                                                </strong>
                                                            </div>
                                                            <div>
                                                                <strong>
                                                                <?php 
                                                                echo '<span>'.$teachers_courses['item-'.$j]['teacher-course-price-sale-area-3'].'</span> تومان<span>'.$teachers_courses['item-'.$j]['teacher-course-discount-percent-area-3'].'</span>'; ?>
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endif; ?>
                                        </ul>
                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                            <?php
                                            if($teachers_courses['item-'.$j]['teacher-course-price'] != "" || $teachers_courses['item-'.$j]['teacher-course-price-area-1'] || $teachers_courses['item-'.$j]['teacher-course-price-area-2'] || $teachers_courses['item-'.$j]['teacher-course-price-area-3']):
                                            ?>
                                            <p class="taxtxt">
                                                <?php echo 'به مبلغ فوق ' .$tax.' درصد مالیات بر ارزش افزوده اضافه می‌شود.'; ?>
                                            </p>
                                            <?php endif; ?>
                                            <div class="row justify-content-center">
                                                <?php if($teachers_courses['item-'.$j]['teacher-course-price-tax'] != ""): ?>
                                                <div class="col-3">
                                                    <?php
                                                            echo do_shortcode('[add_to_cart_button_course teachers_course_id="'.$teachers_courses['item-'.$j]['teacher-course-name'].'"]'); 
                                                        ?>
                                                </div>
                                                <?php
                                                endif;
                                                if($teachers_courses['item-'.$j]['teacher-old-course-link'] != ""): ?>
                                                <div class="col-auto">
                                                    <a href="<?php echo $teachers_courses['item-'.$j]['teacher-old-course-link']; ?>" class="elementor-button elementor-button-link elementor-size-sm" style="border-radius:16px;background:#fff;color:#C4161C;border:3px solid #C4161C">مشاهده دوره سال گذشته</a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                        }
                        ?>
                    </div>
                </li>
        </div>
        <script>
            window.onload = function(e){
                var elements = document.getElementsByClassName("course-teacher-item");
                var teacherCourseInfo = document.getElementsByClassName("teacher-course-info");
                var myFunction = function() {
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].classList.remove("active");
                        elements[i].style.opacity = "0.5";
                    }
                    this.classList.add("active");
                    let dataItem = this.getAttribute("data-item");
                    for(let i = 0; i < teacherCourseInfo.length; i++){
                        teacherCourseInfo[i].classList.add("d-none");
                    }
                    document.querySelector("[data-panel='"+dataItem+"']").classList.remove("d-none");
                    if (isMobile()) {
                        document.getElementById("multiteacher-course-item-"+dataItem).scrollIntoView();
                    }
                };
                
                for (let i = 0; i < elements.length; i++) {
                    elements[i].addEventListener('click', myFunction, false);
                }
                
                
                clearInterval(sortCarouselTimer);
                var sortCarouselTimer = setInterval(function(){
                    jQuery("#courseRow .course-row-item").shuffle();
                    for (let i = 0; i < elements.length; i++) {
                        elements[i].addEventListener('click', myFunction, false);
                    }
                }, 10000);
                
                let url = new URL(window.location.href);
                let teacherSelected = url.href.substring(url.href.lastIndexOf('#') + 1);
                if(teacherSelected != url){
                    clearInterval(sortCarouselTimer);
                    jQuery(".course-row-item").addClass("d-none");
                    jQuery(".multiteacher-course-item").addClass("d-none");
                    jQuery(".course-row-item-"+teacherSelected).removeClass("d-none");
                    jQuery(".multiteacher-course-item-"+teacherSelected).removeClass("d-none");
                    jQuery("#teacher-"+teacherSelected).click();
                    document.getElementById("teacher-"+teacherSelected).scrollIntoView();
                }
            }
        </script>
        <?php
    }
}

add_shortcode('teachers_course_items','teachers_course_items_func');




/**
 * api for update courses info from LMS
 **/
 
function add_custom_button_meta_box() {
    add_meta_box(
        'updatecourse-button-meta-box', // Unique ID for the meta box
        'به‌روزرسانی اطلاعات دوره', // Title of the meta box
        'render_updatecourse', // Callback function to render the content
        'course', // Replace with your custom post type slug
        'side', // Position (e.g., 'side', 'normal', 'advanced')
        'high' // Priority (e.g., 'high', 'low')
    );
}
add_action('add_meta_boxes', 'add_custom_button_meta_box');

function render_updatecourse() {
    ?>
    <input type="button" onClick="updatecourse()" class="button button-primary button-large" value="به‌روزرسانی کن" id="updatecoursen-button" />
    <script>
     function changeTime(mydate){
        // set the date to show in PST timezone
        let date = new Date(mydate);
        let timezoneOffset = date.getTimezoneOffset();
        let pstOffset = +210; // this is the offset for the Pacific Standard Time timezone
        let adjustedTime = new Date(date.getTime() + (pstOffset + timezoneOffset) * 60 * 1000);
        // display the date and time in PST timezone
        let options = {
          day: 'numeric',
          month: 'numeric',
          year: 'numeric',
          hour: 'numeric',
          minute: 'numeric',
          second: 'numeric',
          timeZone: 'Asia/Tehran'
        };
        let pstDateTime = adjustedTime.toLocaleString('fa-IR', options);
        return pstDateTime;
    }
    function updatecourse(){
        jQuery('#updatecoursen-button').attr('disabled','disabled');
        
        let courseIdLms = jQuery('[name="course-id-lms"]').val();
        let courseType = jQuery('[name="course-type"]').val();
        
        if(courseType == "normal-course" || courseType == "multi-teacher"){
        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
        
        var raw = JSON.stringify({
          "courseId": courseIdLms, // example:1789
          "teacherId": -1,
          "courseType": 1
        });
        
        var requestOptions = {
          method: 'POST',
          headers: myHeaders,
          body: raw,
          redirect: 'follow'
        };
        
        fetch("https://api.tamland.ir/api/course/getCourse", requestOptions)
          .then(response => response.json())
          .then(
              result=>{
                let dataVal = result['data']['0'];
                //console.log(dataVal);
                
                if(courseType == "normal-course"){
                    jQuery('[name="tax_input[courses-type][]"][value="165"]').attr('checked','checked');
                }else if(courseType == "multi-teacher"){
                    jQuery('[name="tax_input[courses-type][]"][value="164"]').attr('checked','checked');
                }
                
                jQuery('[name="post_title"]').val(dataVal.fldTitle); // set Course Title
                
                jQuery('[name="purchase-link"]').val('https://'); // set Course Purchase Link
                
                let price = dataVal.fldPrediscountedprice.toString();
                price = price.substring(0,(price.length - 1));
                jQuery('[name="price"]').val(price); // set Price without discount
                
                let price_sale = dataVal.fldPrice.toString();
                price_sale = price_sale.substring(0,(price_sale.length - 1));
                jQuery('[name="price_sale"]').val(price_sale); // set Price with discount
                
                // set Start Date
                let fldShowStartDateCourseStep = dataVal.fldShowStartDateCourseStep;
                let startDate = fldShowStartDateCourseStep.split("T");
                let SDate = new Date(startDate[0]);
                jQuery('[name="start-date"]').val(new Intl.DateTimeFormat('fa-IR').format(SDate));
                
                const date = new Date(dataVal.fldShowStartDateCourseStep);
                // toLocaleTimeString() without arguments depends on the implementation,
                // the default locale, and the default time zone
                //let SHour = date.toLocaleTimeString("fa-IR", { hour: "2-digit", minute: "2-digit", timeZone: "Asia/Tehran" });
                let fldCourseStartDescription = dataVal.fldCourseStartDescription.split("-");
                let SHour = fldCourseStartDescription[1];
                jQuery('[name="start-hour"]').val(SHour);
                
                // set Main Description
                let desc = jQuery("#content_ifr").contents().find('body'); 
                desc.html(dataVal.fldDescription);
                
                // set Excerpt Description
                //let excerptDesc = jQuery('[data-control-name="course-short-desc"] iframe').contents().find('body');
                //excerptDesc.html(dataVal.fldDescription); 
                
                // set Course Field Category
                let audienceList = [
                    {audienceName:'ریاضی',audienceVal:69, audienceIndex:'1'},
                    {audienceName:'علوم تجربی',audienceVal:71, audienceIndex:'2'},
                    {audienceName:'انسانی',audienceVal:72, audienceIndex:'3'},
                    {audienceName:'هنر',audienceVal:91, audienceIndex:'4'},
                    {audienceName:'زبان',audienceVal:115, audienceIndex:'5'},
                    {audienceName:'مشاوره',audienceVal:129, audienceIndex:'9'}
                    ],
                audienceRes = audienceList.findIndex(audienceList => audienceList.audienceIndex === dataVal.audience);
                jQuery('[name="tax_input[field][]"][value="'+audienceList[audienceRes].audienceVal+'"]').attr('checked','checked'); 
                
                // set Course Grade
                let fldTypeList = [
                    {fldType:93,fldTypeVal:146},
                    {fldType:69,fldTypeVal:143},
                    {fldType:76,fldTypeVal:179},
                    {fldType:80,fldTypeVal:178},
                    {fldType:64,fldTypeVal:132},
                    {fldType:64,fldTypeVal:131},
                    {fldType:60,fldTypeVal:129},
                    {fldType:59,fldTypeVal:130},
                    {fldType:62,fldTypeVal:114},
                    {fldType:72,fldTypeVal:167},
                    {fldType:61,fldTypeVal:119}
                    ],
                fldTypeRes = fldTypeList.findIndex(fldTypeList => fldTypeList.fldType === dataVal.fldType);
                jQuery('[name="tax_input[grade][]"][value="'+fldTypeList[fldTypeRes].fldTypeVal+'"]').attr('checked','checked');
                
                //Add teacher to Array->teacherList
                let teacherList = [];
                jQuery('#teacher-archive-all ul li').each(function(n,v){
                    teacherList.push(jQuery(this).text());
                });
                let teacherRes = teacherList.filter(teacherList => teacherList.includes(dataVal.fldFullName));
                if(teacherRes[0] != "" && teacherRes[0] != null && teacherRes[0] != undefined ){
                    jQuery('#teacher-archive-all ul li label:contains('+teacherRes[0]+') [name="tax_input[teacher-archive][]"]').attr('checked','checked');
                }
                
                
              }
              )
          .catch(error =>{
              console.log('error', error);
              jQuery('#updatecourse-button-meta-box .inside').append('<p style="color:red">خطا در دریافت پاسخ از سرور</p>');
          });
        }else if(courseType == "course-pack"){
            const requestOptions = {
              method: "GET",
              redirect: "follow"
            };
            
            fetch("https://api.tamland.ir/api/course/getPackage/"+courseIdLms, requestOptions)
              .then((response) => response.json())
              .then((result) => {
                let dataVal = result['data']['0'];
                //console.log(dataVal);
                // set Main Description
                let desc = jQuery("#content_ifr").contents().find('body'); 
                desc.html(dataVal.fldDescription);
                
                jQuery('[name="price_sale_percentage"]').val(dataVal.fldDiscountPercentage);
                jQuery('[name="post_title"]').val(dataVal.fldTitle1);
                jQuery('[name="secound-title"]').val(dataVal.fldTitle2);
                
                if(dataVal.totalPackagePriceReal == 0){
                    jQuery('[name="price"]').val(dataVal.totalPackagePrice);
                }else{
                    jQuery('[name="price"]').val(dataVal.totalPackagePriceReal);
                    jQuery('[name="price_sale"]').val(dataVal.totalPackagePrice);
                }
                
                jQuery('[name="tax_input[courses-type][]"][value="163"]').attr('checked','checked');
                
                // set Start Date
                let fldStartDateTime = dataVal.fldStartDateTime;
                let startDate = fldStartDateTime.split("T");
                let SDate = new Date(startDate[0]);
                jQuery('[name="start-date"]').val(new Intl.DateTimeFormat('fa-IR',{
                    timeZone: 'Asia/Tehran',
                }).format(SDate));
                
                const requestOptions = {
                  method: "GET",
                  redirect: "follow"
                };
                
                fetch("https://api.tamland.ir/api/course/getPackageCourses/"+courseIdLms, requestOptions)
                  .then((response) => response.json())
                  .then((result) => {
                      let coursesPackVal = result.data;
                        for(i=0; i<coursesPackVal.length; i++){
                            jQuery('[data-control-name="courses-cat"] .cx-ui-repeater-add').click();
                            jQuery('[name="courses-cat[item-'+i+'][courses-cat-name]"]').val(coursesPackVal[i]['fldTitle']);
                            jQuery('[name="courses-cat[item-'+i+'][courses-cat-num]"]').val(i+1);
                            
                            jQuery('[data-control-name="pack-courses"] .cx-ui-repeater-add').click();
                            jQuery('[name="pack-courses[item-'+i+'][pack-courses-cat-num]"]').val(i+1);
                            jQuery('[name="pack-courses[item-'+i+'][pack-course-title]"]').val(coursesPackVal[i]['fldTitle']);
                            
                            // set Start Date
                            let fldStartDateCourseStep = coursesPackVal[i].fldStartDateCourseStep;
                            let courseStartDate = fldStartDateCourseStep.split("T");
                            let CSDate = new Date(courseStartDate[0]);
                            jQuery('[name="pack-courses[item-'+i+'][pack-course-date]"]').val(new Intl.DateTimeFormat('fa-IR',{
                                timeZone: 'Asia/Tehran',
                            }).format(CSDate));
                            
                            let startDate = changeTime(courseStartDate[1]);
                            let startTime = startDate[1].split(":");
                            let startHours = startTime[0];
                            let startMinutes = startTime[1];
                            jQuery('[name="pack-courses[item-'+i+'][pack-course-hour]"]').val(courseStartDate[1]);
                            
                            let CSDesc = jQuery("[id^=packcoursedesc]"+i).contents().find('body'); 
                            CSDesc.html(coursesPackVal[i]['fldDescription']);
                            
                        }
                      //console.log(coursesPackVal);
                  })
                  .catch((error) => console.error(error));
                
              })
              .catch((error) => console.error(error));
        }
          setTimeout(function(){
            jQuery('#updatecoursen-button').removeAttr('disabled');
          },1000);
    }
    </script>
    <?php
}

function sa_clarity(){
    if (!is_page('course-checkout')) {
    ?>
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "mehb0ip6zx");
    </script>
    <?php
    }
}
//add_action('wp_head', 'sa_clarity', 10);

//[ads_banner_1st  grade="130"]
add_shortcode('ads_banner_1st','ads_banner_1st_func');
function ads_banner_1st_func($atts){
    $atts_banner = shortcode_atts( array(
		'grade' => ''
	), $atts );
    $ads_banner_1st = get_term_meta( $atts_banner['grade'], 'ads-banner-1st', true );
    ?>
    <div class="ads-banner-1st-place">
        <div class="ads-banner-1st-wrapper d-flex align-items-center">
            <?php if(count($ads_banner_1st) > 1){ ?>
                <div class="owl-carousel" id="adsBanner1st">
                    <?php for($i = 0; $i < count($ads_banner_1st); $i++){ ?>
                    <div>
                        <div class="item-box">
                            <?php if($ads_banner_1st['item-'.$i]['ads-banner-1st-img'] != null): ?>
                            
                                <div class="ads-banner-1st-image">
                                    <a href="<?php echo $ads_banner_1st['item-'.$i]['ads-banner-1st-link']; ?>" title="<?php echo $ads_banner_1st['item-'.$i]['ads-banner-1st-title']; ?>" target="_blank">
                                    <img src="<?php echo $ads_banner_1st['item-'.$i]['ads-banner-1st-img']; ?>" alt="<?php echo $ads_banner_1st['item-'.$i]['ads-banner-1st-title']; ?>">
                                    </a>
                                </div>
                            
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <style>
                    #adsBanner1st .owl-carousel{
                        margin:auto;
                    }
                    #adsBanner1st .owl-stage {
                        display:flex;
                        flex-direction:row;
                        align-items:end;
                    }
                    #adsBanner1st svg, #adsBanner1st span{width:auto;display:inline;}
                    #adsBanner1st .owl-nav{
                    	position: absolute;
                        width: 100%;
                        top: calc(50% - 16px);
                        height: 0;
                    }
                    .ads-banner-1st-owl-next,.ads-banner-1st-owl-prev{
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        width:32px;
                        height:32px;
                        color:#fff;
                        background: #fff;
                        border-radius:50%;
                        font-size:14px;
                        cursor:pointer;
                        box-shadow:0 4px 25px rgba(0,0,0,0.07);
                        position: absolute;
                        z-index: 2;
                    }
                    .ads-banner-1st-owl-next{right:-10px;}
                    .ads-banner-1st-owl-prev{left:-10px;}
                    }
                    #adsBanner1st .owl-item{
                        width:277px;
                    }
                    #adsBanner1st .item-box {
                      width:100%;
                      display:flex;
                      flex-direction: column;
                      color:#fff;
                    }
                    #adsBanner1st .item-box h3{height:35px;}
                    #adsBanner1st .owl-stage-outer{
                        padding-top:8px;
                        padding-bottom:15px;
                    }
                    .ads-banner-1st-card{
                        background:#fff;
                        border-radius:16px;
                        padding:7px;
                        box-shadow:0 4px 25px rgba(0,0,0,0.07);
                        color:#2D3748;
                    }
                    @media(max-width:1000px){
                        #adsBanner1st .owl-carousel{
                            margin:auto;
                        }
                        .ads-banner-1st-owl-next,.ads-banner-1st-owl-prev{display:none;}
                    }
                    @media(max-width:480px){
                        #adsBanner1st .owl-carousel{
                            width:95% !important;
                        }
                        .ads-banner-1st-owl-next,.ads-banner-1st-owl-prev{display:none;}
                    }
                    </style>
                    <script type="text/javascript">
                        jQuery(document).ready(function(){
                          jQuery("#adsBanner1st").owlCarousel({
                            rtl:true,
                            loop:true,
                            margin:30,
                            nav:true,
                    		navText:['<div class="ads-banner-1st-owl-next"><svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.57812 11L6.57812 6L1.57812 1" stroke="#2D3748" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round"/></svg></div>','<div class="ads-banner-1st-owl-prev"><svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.4375 1L1.4375 6L6.4375 11" stroke="#2D3748" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round"/></svg></div>'],
                            dots:false,
                            autoplay:true,
                            autoplayTimeout:3000,
                            autoplayHoverPause:true,
                            responsiveClass:true,
                            responsive:{
                                0:{
                                    items:1
                                },
                                600:{
                                    items:1
                                },
                                1000:{
                                    items:1
                                }
                            }
                          });
                        });
                    </script>
<?php }elseif(count($ads_banner_1st) == 1){ ?>
    <div class="ads-banner-1st-image">
        <a href="<?php echo $ads_banner_1st['item-0']['ads-banner-1st-link']; ?>" title="<?php echo $ads_banner_1st['item-0']['ads-banner-1st-title']; ?>"><img src="<?php echo $ads_banner_1st['item-0']['ads-banner-1st-img']; ?>" alt="<?php echo $ads_banner_1st['item-0']['ads-banner-1st-title']; ?>"></a>
    </div>
<?php }elseif(count($ads_banner_1st) == 0){ ?>
    <div>
        <img src="<?php echo UPLOADS_DIR.'/2024/07/placeholder.webp'; ?>">
    </div>
<?php } ?>
        </div>
    </div>
<?php
}

//[ads_banner_2nd  grade="130"]
add_shortcode('ads_banner_2nd','ads_banner_2nd_func');
function ads_banner_2nd_func($atts){
    $atts_banner = shortcode_atts( array(
		'grade' => ''
	), $atts );
    $ads_banner_2nd = get_term_meta( $atts_banner['grade'], 'ads-banner-2nd', true );
    ?>
    <div class="ads-banner-2nd-place">
        <div class="ads-banner-2nd-wrapper d-flex align-items-center">
            <?php if(count($ads_banner_2nd) > 1){ ?>
            <div class="owl-carousel" id="adsBanner2nd">
                <?php for($i = 0; $i < count($ads_banner_2nd); $i++){ ?>
                <div>
                    <div class="item-box">
                        <?php if($ads_banner_2nd['item-'.$i]['ads-banner-2nd-img'] != null): ?>
                            <div class="ads-banner-2nd-image">
                                <a href="<?php echo $ads_banner_2nd['item-'.$i]['ads-banner-2nd-link']; ?>" title="<?php echo $ads_banner_2nd['item-'.$i]['ads-banner-2nd-title']; ?>"><img src="<?php echo $ads_banner_2nd['item-'.$i]['ads-banner-2nd-img']; ?>" alt="<?php echo $ads_banner_2nd['item-'.$i]['ads-banner-2nd-title']; ?>"></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <style>
            #adsBanner2nd .owl-carousel{
                margin:auto;
            }
            #adsBanner2nd .owl-stage {
                display:flex;
                flex-direction:row;
                align-items:end;
            }
            #adsBanner2nd svg, #adsBanner2nd span{width:auto;display:inline;}
            #adsBanner2nd .owl-nav{
            	position: absolute;
                width: 100%;
                top: calc(50% - 16px);
                height: 0;
            }
            .ads-banner-2nd-owl-next,.ads-banner-2nd-owl-prev{
                display:flex;
                align-items:center;
                justify-content:center;
                width:32px;
                height:32px;
                color:#fff;
                background: #fff;
                border-radius:50%;
                font-size:14px;
                cursor:pointer;
                box-shadow:0 4px 25px rgba(0,0,0,0.07);
                position: absolute;
                z-index: 2;
            }
            .ads-banner-2nd-owl-next{right:-10px;}
            .ads-banner-2nd-owl-prev{left:-10px;}
            }
            #adsBanner2nd .owl-item{
                width:277px;
            }
            #adsBanner2nd .item-box {
              width:100%;
              display:flex;
              flex-direction: column;
              color:#fff;
            }
            #adsBanner2nd .item-box h3{height:35px;}
            #adsBanner2nd .owl-stage-outer{
                padding-top:8px;
                padding-bottom:15px;
            }
            .ads-banner-2nd-card{
                background:#fff;
                border-radius:16px;
                padding:7px;
                box-shadow:0 4px 25px rgba(0,0,0,0.07);
                color:#2D3748;
            }
            @media(max-width:1000px){
                #adsBanner2nd .owl-carousel{
                    margin:auto;
                }
                .ads-banner-2nd-owl-next,.ads-banner-2nd-owl-prev{display:none;}
            }
            @media(max-width:480px){
                #adsBanner2nd .owl-carousel{
                    width:95% !important;
                }
                .ads-banner-2nd-owl-next,.ads-banner-2nd-owl-prev{display:none;}
            }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                  jQuery("#adsBanner2nd").owlCarousel({
                    rtl:true,
                    loop:true,
                    margin:30,
                    nav:true,
                    navText:['<div class="ads-banner-2nd-owl-next"><svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.57812 11L6.57812 6L1.57812 1" stroke="#2D3748" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round"/></svg></div>','<div class="ads-banner-2nd-owl-prev"><svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.4375 1L1.4375 6L6.4375 11" stroke="#2D3748" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round"/></svg></div>'],
                    dots:false,
                    responsiveClass:true,
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:1
                        },
                        1000:{
                            items:1
                        }
                    }
                  });
                });
            </script>
<?php }elseif(count($ads_banner_2nd) == 1){ ?>
    <div class="ads-banner-2nd-image">
        <a href="<?php echo $ads_banner_2nd['item-0']['ads-banner-2nd-link']; ?>" title="<?php echo $ads_banner_2nd['item-0']['ads-banner-2nd-title']; ?>"><img src="<?php echo $ads_banner_2nd['item-0']['ads-banner-2nd-img']; ?>" alt="<?php echo $ads_banner_2nd['item-0']['ads-banner-2nd-title']; ?>"></a>
    </div>
<?php }elseif(count($ads_banner_2nd) == 0){ ?>
    <div>
        <img src="<?php echo UPLOADS_DIR.'/2024/07/placeholder.webp'; ?>">
    </div>
<?php } ?>
        </div>
    </div>
<?php
}



function hide_based_onhref() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.pack-course-info  .col-lg-2 a').each(function() {
            if (!$(this).attr('href') || $(this).attr('href') === '') {
                $(this).closest('.pack-course-info  .col-lg-2').hide();
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'hide_based_onhref');


/**
    * Add Iran mobile format 
*/
add_filter( 'gform_phone_formats', 'ir_phone_format', 10, 2 );

function ir_phone_format( $phone_formats ) {
    $phone_formats['ir'] = array(
        'label'       => 'شماره موبایل ایران',
        'mask'        => '',
        'regex'       => '/^09(0[0-9]|1[0-9]|2[0-9]|3[0-9]|9[0-9])\-?[0-9]{3}\-?[0-9]{4}$/',
        'instruction' => 'شماره وارد شده صحیح نمی‌باشد',
    );
 
    return $phone_formats;
}

add_filter('gform_field_validation', 'custom_phone_validation', 10, 4);
function custom_phone_validation($result, $value, $form, $field) {
    if ($field->type == 'phone' && strlen(preg_replace('/\D/', '', $value)) > 11) {
        $result['is_valid'] = false;
        $result['message'] = 'لطفاً شماره موبایل معتبر وارد کنید (حداکثر ۱۱ رقم).';
    }
    return $result;
}

add_shortcode('add_to_cart_button_course', 'add_to_cart_button_course_func');
function add_to_cart_button_course_func($atts){
    $checkout_page_url = site_url().'/course-checkout';
    $post = get_post();
    $item_name = str_replace("|","-",$post->post_title);
    $secound_title = str_replace("|","-", get_post_meta($post->ID, 'secound-title', true));
    $post_type = get_post_type($post->ID);
    $course_type = get_post_meta( $post->ID, 'course-type', true );
    
    //Creating item array.
    $items = array();
    $button_atts = shortcode_atts( array(
            'is_archive' => false,
    		'teachers_course_id' => ''
    ), $atts );
    if($course_type == 'normal-course' || $course_type == 'course-pack' || ($course_type == 'multi-teacher' && $button_atts['is_archive'] == true) || $post_type == 'exams'){
        // Get Prices
        $price = get_post_meta( $post->ID, 'price_tax', true );
        $price_sale = get_post_meta( $post->ID, 'price_sale_tax', true );
        $region_price = get_post_meta( $post->ID, 'region-price', true );
        $course_id_lms = get_post_meta( $post->ID, 'course-id-lms', true );
        if($region_price !=""){
            for( $i = 0; $i < 1; $i++ ){
                if($region_price['item-'.$i]['region-price-sale-tax'] != ""){
                    $items[] = array( 'price' => $region_price['item-'.$i]['region-price-sale-tax'], 'text' => $item_name.' (با تخفیف)');
                }else{
                    $items[] = array( 'price' => $region_price['item-'.$i]['region-price-tax'], 'text' => $item_name);
                }
            }
        }else{
            if($price_sale == ""){
                $items[] = array( 'price' => $price, 'text' => $item_name.' '.$secound_title);
            }else{
                $items[] = array( 'price' => $price_sale, 'text' => $item_name.' (با تخفیف)');
            }
        }
        
    }elseif($course_type == 'multi-teacher'){
        $teachers_course = get_post_meta( $post->ID, 'teachers-course', true );
        foreach( $teachers_course as $teachers_course_item ){
            if($teachers_course_item['teacher-course-name'] === $button_atts['teachers_course_id']){
                $course_id_lms = $teachers_course_item['teacher-course-id-lms'];
                if($teachers_course_item['teacher-course-price-sale-area-1-tax'] != ""){
                    for( $i = 1; $i < 2; $i++ ){
                        if($teachers_course_item['teacher-course-price-sale-area-'.$i.'-tax'] != ""){
                            $items[] = array( 'price' => $teachers_course_item['teacher-course-price-sale-area-'.$i.'-tax'], 'text' => $item_name.' '.get_the_title($teachers_course_item['teacher-course-name']).' (با تخفیف)', 'isSelected' => true);
                        }else{
                            $items[] = array( 'price' => $teachers_course_item['teacher-course-price-area-'.$i.'-tax'], 'text' => $item_name.' '.get_the_title($teachers_course_item['teacher-course-name']), 'isSelected' => true);
                        }
                    }
                }else{
                    if($teachers_course_item['teacher-course-price-sale-tax'] == ""){
                        $items[] = array( 'price' => $teachers_course_item['teacher-course-price-tax'], 'text' => $item_name.''.$secound_title.' '.get_the_title($teachers_course_item['teacher-course-name']), 'isSelected' => true);
                    }else{
                       $items[] = array( 'price' => $teachers_course_item['teacher-course-price-sale-tax'], 'text' => $item_name.' '.get_the_title($teachers_course_item['teacher-course-name']).' (با تخفیف)', 'isSelected' => true);
                    }
                }
            }
        }
    }
    
    $token = hash_hmac('sha256', $course_id_lms . '|' . $items[0]["price"], $secret_key);
    ?>
    <form method="post" action="<?php echo $checkout_page_url; ?>">
        <?php
        if($button_atts['is_archive'] == true){
            ?>
            <button type="submit" class="add-to-cart-button cart-icon"><img src="https://mid1.tamland.ir/wp-content/uploads/2025/02/card.svg"></button>
            <?php
        }else{
            ?>
            <button type="submit" class="add-to-cart-button w-100">ثبت نام در این دوره</button>
            <?php
        }
        ?>
        <input type="hidden" name="course_id_lms" value="<?php echo $course_id_lms; ?>">
        <input type="hidden" name="ref_url_payment" value="<?php the_permalink(); ?>">
        <?php
        if($course_type == 'normal-course'){
            ?>
                <input type="hidden" name="course_type" value="دوره معمولی">
            <?php
        }elseif($course_type == 'multi-teacher'){
            ?>
                <input type="hidden" name="course_type" value="چند استاده">
            <?php
        }elseif($course_type == 'course-pack'){
            ?>
                <input type="hidden" name="course_type" value="بسته">
            <?php
        }
        for($i = 0; $i < count($items); $i++){
            echo '
            <input type="hidden" name="course_name_'.$i.'" value="'.$items[$i]["text"].'">
            <input type="hidden" name="course_price_'.$i.'" value="'.$items[$i]["price"].'">
            ';
        }
        ?>
        <input type="hidden" name="course_numbers" value="<?php echo count($items); ?>">
        <input type="hidden" name="utm_source" value="<?php echo htmlspecialchars($_GET['utm_source'] ?? ''); ?>">
        <input type="hidden" name="utm_medium" value="<?php echo htmlspecialchars($_GET['utm_medium'] ?? ''); ?>">
        <input type="hidden" name="utm_campaign" value="<?php echo htmlspecialchars($_GET['utm_campaign'] ?? ''); ?>">
        <input type="hidden" name="utm_term" value="<?php echo htmlspecialchars($_GET['utm_term'] ?? ''); ?>">
        <input type="hidden" name="utm_content" value="<?php echo htmlspecialchars($_GET['utm_content'] ?? ''); ?>">
        <input type="hidden" name="secure_token" value="<?= $token ?>">
    </form>
    <?php
}
add_filter('gform_pre_render_4', 'validate_secure_token');

function validate_secure_token($form) {
     if(!is_admin()){
        if (isset($_GET['gf_token'])) {
            $draft_token = sanitize_text_field($_GET['gf_token']);
            // دریافت مقادیر پیش‌نویس مرتبط با توکن
            $draft_values = GFFormsModel::get_draft_submission_values($draft_token);
            // بررسی اینکه داده‌ها موجود هستند
            if (!empty($draft_values['submission'])) {
                // دیکد کردن JSON درون کلید submission
                $submission = json_decode($draft_values['submission'], true);
                $input_7 = explode("|", $submission['submitted_values']['7']);
                if(!isset($input_7[1], $submission['submitted_values']['8'], $submission['submitted_values']['26'])){
                    die('اطلاعات ناقص است');
                }
                
                $expected_token = hash_hmac('sha256', $submission['submitted_values']['8'] . '|' . $input_7[1], $secret_key);

                if ($submission['submitted_values']['26'] !== $expected_token) {
                    die('درخواست شما نامعتبر است');
                }
            }
        }else{
            // بررسی وجود مقادیر در POST
            if (!isset($_POST['course_id_lms'], $_POST['course_price_0'], $_POST['secure_token'])) {
                die('اطلاعات ناقص است');
            }
        
            $expected_token = hash_hmac('sha256', $_POST['course_id_lms'] . '|' . $_POST['course_price_0'], $secret_key);
        
            if ($_POST['secure_token'] !== $expected_token) {
                die('درخواست شما نامعتبر است');
            }
        }
    
 }
    return $form;
    
}

add_filter( 'gform_pre_render_4', 'add_courses_fields' );
 
//Note: when changing choice values, we also need to use the gform_pre_validation so that the new values are available when validating the field.
add_filter( 'gform_pre_validation_4', 'add_courses_fields' );
 
//Note: when changing choice values, we also need to use the gform_admin_pre_render so that the right values are displayed when editing the entry.
add_filter( 'gform_admin_pre_render_4', 'add_courses_fields' );
 
//Note: this will allow for the labels to be used during the submission process in case values are enabled
add_filter( 'gform_pre_submission_filter_4', 'add_courses_fields' );
function add_courses_fields( $form ) {
 if(!is_admin()){
    if ( $form["id"] != 4 ) {
        return $form;
    }
    
    if (isset($_GET['gf_token'])) {
        $draft_token = sanitize_text_field($_GET['gf_token']);
        // دریافت مقادیر پیش‌نویس مرتبط با توکن
        $draft_values = GFFormsModel::get_draft_submission_values($draft_token);
        // بررسی اینکه داده‌ها موجود هستند
        if (!empty($draft_values['submission'])) {
            ?>
            <style>
                .tamland-schools{
                    display:none;
                }
            </style>
            <?php
            // دیکد کردن JSON درون کلید submission
            $submission = json_decode($draft_values['submission'], true);
            foreach ( $form['fields'] as &$field ) {
                 if($field->id == 7){
                    $input_7 = explode("|", $submission['submitted_values'][$field->id]);
                    $items[] = array( 'value' => $input_7[0], 'price' => $submission['submitted_values']['6'], 'text' => $input_7[0], 'isSelected' => true);
                    foreach ( $form['fields'] as &$field ) {
                        if ( $field->id == 7 ) {
                            $field->choices = $items;
                        }
                    }
                 }else{
                     $field->defaultValue = $submission['submitted_values'][$field->id];
                 }
            }
        }
    }else{
        if (!empty($_POST)) {
            ?>
            <style>
                .tamland-schools{
                    display:none;
                }
            </style>
            <?php
            
        if(isset($_POST['course_numbers'])){
            $items_number = (int)$_POST['course_numbers'];
        }else{
           $items_number = 1;
        }
        
        if(isset($_POST['course_id_lms'])){
            $course_id_lms = $_POST['course_id_lms'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 8 ) {
                    $field->defaultValue = $course_id_lms;
                }
            }
        }else{
            $course_id_lms = $_POST['input_8'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 8 ) {
                    $field->defaultValue = $course_id_lms;
                }
            }
        }
        
        if(isset($_POST['course_type'])){
            $course_type = $_POST['course_type'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 9 ) {
                    $field->defaultValue = $course_type;
                }
            }
        }else{
            $course_type = $_POST['input_9'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 9 ) {
                    $field->defaultValue = $course_type;
                }
            }
        }
        
        if(isset($_POST['ref_url_payment'])){
            $ref_url_payment = $_POST['ref_url_payment'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 10 ) {
                    $field->defaultValue = $ref_url_payment;
                }
            }
            ?>
            <script>
                jQuery(document).ready(function(){
                    localStorage.setItem("refUrlPayment", "<?php echo $ref_url_payment; ?>");
                });
            </script>
            <?php
        }else{
            $ref_url_payment = $_POST['input_10'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 10 ) {
                    $field->defaultValue = $ref_url_payment;
                }
            }
        }
        if(isset($_POST['course_numbers'])){
            $items = array();
        
            for( $i = 0; $i < $items_number; $i++ ){
                $items[] = array( 'value' => $_POST['course_name_'.$i], 'price' => $_POST['course_price_'.$i], 'text' => $_POST['course_name_'.$i], 'isSelected' => true);
            }
            
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 7 ) {
                    $field->choices = $items;
                }
            }
        }else{
            $input_7 = explode("|", $_POST['input_7']);
            $items[] = array( 'value' => $input_7[0], 'price' => $_POST['input_6'], 'text' => $input_7[0], 'isSelected' => true);
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 7 ) {
                    $field->choices = $items;
                }
            }
        }
        
        if(isset($_POST['utm_source'])){
            $utm_source = $_POST['utm_source'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 11 ) {
                    $field->defaultValue = $utm_source;
                }
            }
        }else{
            $utm_source = $_POST['input_11'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 11 ) {
                    $field->defaultValue = $utm_source;
                }
            }
        }
        
        if(isset($_POST['utm_medium'])){
            $utm_medium = $_POST['utm_medium'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 12 ) {
                    $field->defaultValue = $utm_medium;
                }
            }
        }else{
            $utm_medium = $_POST['input_12'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 12 ) {
                    $field->defaultValue = $utm_medium;
                }
            }
        }
        
        if(isset($_POST['utm_campaign'])){
            $utm_campaign = $_POST['utm_campaign'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 13 ) {
                    $field->defaultValue = $utm_campaign;
                }
            }
        }else{
            $utm_campaign = $_POST['input_13'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 13 ) {
                    $field->defaultValue = $utm_campaign;
                }
            }
        }
        
        if(isset($_POST['utm_term'])){
            $utm_term = $_POST['utm_term'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 14 ) {
                    $field->defaultValue = $utm_term;
                }
            }
        }else{
            $utm_term = $_POST['input_14'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 14 ) {
                    $field->defaultValue = $utm_term;
                }
            }
        }
        
        if(isset($_POST['utm_content'])){
            $utm_content = $_POST['utm_content'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 15 ) {
                    $field->defaultValue = $utm_content;
                }
            }
        }else{
            $utm_content = $_POST['input_15'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 15 ) {
                    $field->defaultValue = $utm_content;
                }
            }
        }
        
        if(isset($_POST['secure_token'])){
            $secure_token = $_POST['secure_token'];
            
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 26 ) {
                    $field->defaultValue = $secure_token;
                }
            }
        }else{
            $secure_token = $_POST['input_26'];
            foreach ( $form['fields'] as &$field ) {
                if ( $field->id == 26 ) {
                    $field->defaultValue = $secure_token;
                }
            }
        }
        
    } else {
        ?>
        <style>
            #gform_wrapper_4, .vpnalert, .viewcourselink{
                display:none !important;
            }
            #gradesLink{
                display:flex;
            }
            .view-course-form{
                display:none;
            }
        </style>
        <div>
            <h3>هنوز هیچ دوره‌ای انتخاب نشده!</h3>
           <img src="https://mid1.tamland.ir/wp-content/uploads/2025/01/000-1.webp"> 
           <p>برای خرید دوره مورد نظر، پایه تحصیلی خود را انتخاب کنید</p>
        </div>
        <?php
    }
    }
    
    
 } 
    
    return $form;
 
}


add_action('gform_after_submission_4', 'post_to_third_party', 10, 2);

function post_to_third_party($entry, $form) {
    $entry_id = $entry['id'];
    //error_log('Entry: ' . print_r($entry, true));
    $amount = calculate_amount($entry);
    if($entry['9'] == "دوره معمولی" || $entry['9'] == "چند استاده" || $entry['9'] == "آزمون"){
        $course_type_lms = 1;
    }elseif($entry['9'] == "بسته"){
        $course_type_lms = 2;
    }
    $lmsdata = array(
	    "Name" => $entry['1'],
		"Mobile" => $entry['2'],
		"CourseId" => (int)$entry['8'],
		"Price" => $amount,
		"Status" => 10,
		"TrackingCode" => "",
		"Type" => (int)$course_type_lms,
	    "PaymentDate" => $entry['date_created'],
		"WPCode" => (int)$entry['id'],
		"MaskedCardNumber" => "",
		"UtmSource" => isset($entry[11]) ? $entry[11] : "",
		"UtmMedium" => isset($entry[12]) ? $entry[12] : "",
		"UtmChannel" => isset($entry[13]) ? $entry[13] : ""
	);
                    
	// Encode the data as a JSON string
	$lmsdatas = json_encode($lmsdata);
    error_log('(After Sub) LMS Data Encoded: ' . print_r($lmsdatas, true));
    
	$curl2 = curl_init();
    curl_setopt_array($curl2, array(
		CURLOPT_URL => 'https://api.tamland.ir/api/payment/savePayment',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
	    CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>$lmsdatas,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Cookie: cookiesession1=678B28A8A9990742D7412CE00BD0687F'
		),
	));

	$lmsresponses = curl_exec($curl2);
    error_log("lmsresponses: " . $lmsresponses);               
    if ($lmsresponses === false) {
        error_log("(After Sub) cURL error: " . curl_error($curl2));
    }
                        
	curl_close($curl2);
    
    
    $return_path = site_url('/return-payment-gateway?entry_id=' . $entry_id);

    gform_update_meta($entry_id, 'payment_status', 'Processing');
    if($amount !== '0'){
        $data = prepare_payment_data($entry_id, $amount, $return_path);
        $response = send_payment_request($data);
        if ($response && isset($response->token)) {
            redirect_to_payment($response->token);
        } else {
            handle_payment_error($response);
        }
    }else{
        wp_redirect($return_path);
        exit;
    }
}

function calculate_amount($entry) {
    if($entry['6'] == '0'){ 
        $amount_field = $entry['6'];
        return $amount_field; // فرض کنید مقادیر همیشه عدد هستند
    }elseif($entry['18'] == '0'){
        $amount_field = $entry['18'];
        return $amount_field; // فرض کنید مقادیر همیشه عدد هستند
    }else{
        $amount_field = $entry['18'] ?: $entry['6'];
        return $amount_field . '0'; // فرض کنید مقادیر همیشه عدد هستند
    }
}

function prepare_payment_data($entry_id, $amount, $return_path) {
    return array(
        "CorporationPin" => "3F20B9936DD04AE6A9A7AFB98887A42D",
        "Amount" => $amount,
        "OrderId" => $entry_id,
        "CallBackUrl" => esc_url($return_path)
    );
}

function send_payment_request($data) {
    $curl = curl_init();

    $post_fields = json_encode($data);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://pna.shaparak.ir/mhipg/api/Payment/NormalSale',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_fields,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: cookiesession1=678B28A8A9990742D7412CE00BD0687F'
        ),
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        error_log('Curl Error: ' . $error); // ثبت خطا در لاگ وردپرس
        return null;
    }

    return json_decode($response);
}

function redirect_to_payment($token) {
    $payment_url = 'https://pna.shaparak.ir/mhui/home/index/' . urlencode($token);
    echo "<script>window.location.href = '" . esc_url($payment_url) . "';</script>";
    exit;
}

function handle_payment_error($response) {
    error_log('Payment Error: ' . print_r($response, true)); // ثبت خطای احتمالی
    wp_die('یک خطا هنگام پروسه پرداخت شما صورت گرفته است. لطفا مجدد تلاش کنید.');
}

add_filter('gform_entry_meta', 'custom_entry_meta', 10, 2);

function custom_entry_meta($entry_meta, $form_id) {
    // افزودن شناسه پرداخت درگاه پرداخت به متادیتاهای ورودی
    $entry_meta['gateway_transition_id'] = array(
        'label'             => 'شناسه پرداخت درگاه پرداخت',
        'is_numeric'        => true,
        'is_default_column' => true,
    );

    // افزودن شماره کارت به متادیتاهای ورودی
    $entry_meta['card_number'] = array(
        'label'             => 'شماره کارت',
        'is_numeric'        => false,
        'is_default_column' => true,
    );

    return $entry_meta;
}


function update_view_more_button_url(){
    if(is_singular('teacher')){
        $post_id = get_the_ID();
        ?>
        <script>
            jQuery.fn.appendAttr = function(attrName, suffix) {
                this.attr(attrName, function(i, val) {
                    return val + suffix;
                });
                return this;
            };
            jQuery(document).ready(function(){
                let teacherID = "<?php echo $post_id ?>";
                jQuery('.sa-view-more a').appendAttr('href','#'+teacherID);
            });
        </script>
        <?php
    }
}
add_action('wp_footer','update_view_more_button_url');



/**
 * add iframe html support
 */
add_filter( 'wp_kses_allowed_html', function ( $tags, $context ) {
if ( 'post' === $context ) {
$tags['iframe'] = array(
'src' => true,
'width' => true,
'height' => true,
'width' => true, 
'frameborder' => true,
'allowtransparency' => true,
'allow' => true,
);
}
return $tags;
},10,2);


//add_action( 'phpmailer_init', 'my_phpmailer_smtp' );

add_filter('use_block_editor_for_post', '__return_false');
// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action( 'wp_enqueue_scripts', function() {
    // Remove CSS on the front end.
    wp_dequeue_style( 'wp-block-library' );

    // Remove Gutenberg theme.
    wp_dequeue_style( 'wp-block-library-theme' );

    // Remove inline global CSS on the front end.
    wp_dequeue_style( 'global-styles' );
}, 20 );


add_shortcode('view_course_form', 'view_course_form_func');
function view_course_form_func(){
    if(isset($_POST['ref_url_payment'])){
        $ref_url_payment = $_POST['ref_url_payment'];
    }else{
        $ref_url_payment = $_POST['input_10'];
    }
    echo '<a href="'.$ref_url_payment.'" class="view-course-form" target="_blank">مشاهده دوره</a>';
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("#tamlandLogo a").href = "'.$ref_url_payment.'";
        });
    </script>';
}


function export_gravity_forms_entries() {
    $form_id = 4; // Replace with your form ID

    // Set the start and end time for the day
    $start_time = strtotime('yesterday midnight'); // 00:00 of yesterday
    $end_time = strtotime('tomorrow midnight') - 1; // 23:59:59 of today

    // Define the search criteria for entries within the day
    $search_criteria = [
        'status' => 'active', // Fetch only active entries
        'field_filters' => [],
        'start_date' => date('Y-m-d H:i:s', $start_time),
        'end_date' => date('Y-m-d H:i:s', $end_time),
    ];
    
    // Set paging to get all records (large number to ensure all are retrieved)
    $paging = [
        'offset' => 0,  // Start from the first record
        'page_size' => 1000 // Set a large limit (change as needed)
    ];
    
    // Fetch the entries
    $entries = GFAPI::get_entries($form_id, $search_criteria, null, $paging);

    if (!empty($entries)) {
        $csv_data = [];

        // Collect entry headers
        $csv_data[] = array_keys($entries[0]);

        // Collect entry values
        foreach ($entries as $entry) {
            $csv_data[] = array_values($entry);
        }

        // Create the CSV file
        $filename = 'gravity_forms_entries_' . date('Y-m-d') . '_' . date('H-i') . '.csv';
        $file_path = WP_CONTENT_DIR . '/purchased-form-entries/' . $filename;

        // Ensure the directory exists
        if (!file_exists(WP_CONTENT_DIR . '/purchased-form-entries')) {
            mkdir(WP_CONTENT_DIR . '/purchased-form-entries', 0755, true);
        }

        $file = fopen($file_path, 'w');

        // Add UTF-8 BOM to ensure proper encoding
        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        foreach ($csv_data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }
}

// Hook the export function to the cron event
add_action('gravity_forms_entries_export_event', 'export_gravity_forms_entries');

// Add custom cron intervals
function custom_cron_intervals($schedules) {
    $schedules['daily_midnight'] = array(
        'interval' => 86400, // 24 hours in seconds
        'display'  => __('Daily at Midnight')
    );
    return $schedules;
}
add_filter('cron_schedules', 'custom_cron_intervals');


// Schedule the event with the custom interval
if (!wp_next_scheduled('gravity_forms_entries_export_event')) {
    wp_schedule_event(strtotime('tomorrow midnight'), 'daily_midnight', 'gravity_forms_entries_export_event');
}
/*
// Clear the scheduled event
function remove_gravity_forms_entries_export_cron() {
    $timestamp = wp_next_scheduled('gravity_forms_entries_export_event');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'gravity_forms_entries_export_event');
    }
}
add_action('init', 'remove_gravity_forms_entries_export_cron');
*/
add_filter( 'gform_incomplete_submission_pre_save', 'modify_incomplete_submission', 10, 3 );
function modify_incomplete_submission( $submission_json, $resume_token, $form){
    //change the user first name to Test in the saved data
    $updated_json = json_decode( $submission_json );
    $name = urlencode($updated_json->submitted_values->{'1'});
    $mobile = $updated_json->submitted_values->{'2'};
    $courseId = $updated_json->submitted_values->{'8'};
    $amount = $updated_json->submitted_values->{'6'};
    $type = $updated_json->submitted_values->{'9'};
    if($type == "دوره معمولی" || $type == "چند استاده" || $type == "آزمون"){
        $course_type_lms = 1;
    }elseif($type == "بسته"){
        $course_type_lms = 2;
    }
    $utmSource = $updated_json->submitted_values->{'11'};
    $utmMedium = $updated_json->submitted_values->{'12'};
    $utmChannel = $updated_json->submitted_values->{'13'};
    
    $lmsdata = array(
	    "Name" => $name,
		"Mobile" => $mobile,
		"CourseId" => (int)$courseId,
		"Price" => $amount,
		"Status" => 11,
		"TrackingCode" => "",
		"Type" => (int)$course_type_lms,
	    "PaymentDate" => "",
		"WPCode" => (int)$entry['id'],
		"MaskedCardNumber" => "",
		"UtmSource" => isset($utmSource) ? $utmSource : "",
		"UtmMedium" => isset($utmMedium) ? $utmMedium : "",
		"UtmChannel" => isset($utmChannel) ? $utmChannel : ""
		
	);
                    
	// Encode the data as a JSON string
	$lmsdatas = json_encode($lmsdata);
    error_log('(Save and Continue) LMS Data Encoded: ' . print_r($lmsdatas, true));
    
	$curl2 = curl_init();
    curl_setopt_array($curl2, array(
		CURLOPT_URL => 'https://api.tamland.ir/api/payment/savePayment',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
	    CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>$lmsdatas,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Cookie: cookiesession1=678B28A8A9990742D7412CE00BD0687F'
		),
	));

	$lmsresponses = curl_exec($curl2);
                        
    if ($lmsresponses === false) {
        error_log("(Save and continue) cURL error: " . curl_error($curl2));
    }
                        
	curl_close($curl2);
	
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.kavenegar.com/v1/6A67394D58385358526B4A2F3672373758307661362B622B5649506831745550/verify/lookup.json?receptor='.$mobile.'&token='.$resume_token.'&token10='.$name.'&template=paymentsavelink',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: cookiesession1=678A8C407DF7C786E38447A80506772C'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;
    
    return $submission_json;
}

add_action('init', function() {
    load_textdomain('happy-elementor-addons', WP_LANG_DIR . '/plugins/happy-elementor-addons-fa_IR.mo');
});
/*
function course_checkout_page_script(){
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function () {
        let discountClickCount = 0;
        let totalPrice = {
            text: "",
            number: 0,
        };
        let finalPrice = {
            text: "",
            number: 0,
        };

        const resetPrice = () => {
            finalPrice.number = totalPrice.number;
            finalPrice.text = totalPrice.text;
            jQuery('#input_4_6').val(finalPrice.text);
            jQuery('#input_4_18').val(finalPrice.number);
        };

        const applyDiscountSuccess = (discountCode, discountAmount) => {
            finalPrice.number = totalPrice.number - ((totalPrice.number * discountAmount) / 100);
            finalPrice.text = finalPrice.number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " تومان";
            jQuery('#input_4_6').val(finalPrice.text);
            jQuery('#input_4_18').val(finalPrice.number);
            jQuery('.discount-validate-message')
                .html(`کد تخفیف <b>${discountCode}</b> با موفقیت اعمال شد<button type="button" class="del-discount-code"><i class="fa fa-times"></i></button>`)
                .removeClass('not-valid d-none')
                .addClass('valid');
           setCookie("discountIsSet", "true", 9); // Expires in 9 minutes 
        };

        const applyDiscountFailure = (message) => {
            resetPrice();
            jQuery('.discount-validate-message')
                .text(message)
                .removeClass('valid d-none')
                .addClass('not-valid');
            setCookie("discountIsSet", "false", 9); // Expires in 9 minutes 
        };

        jQuery('#apply_discount').on('click', function () {
            const mobile = jQuery('#input_4_2').val();
            const discountCode = jQuery('#input_4_20').val();
            const courseId = jQuery('#input_4_8').val();
            const courseType = jQuery('#input_4_9').val();
            let courseTypeNum = 0;

            if (courseType === "دوره معمولی" || courseType === "چند استاده") {
                courseTypeNum = 1;
            } else if (courseType === "بسته") {
                courseTypeNum = 2;
            }

            if (discountClickCount === 0) {
                totalPrice.text = jQuery('#input_4_6').val();
                totalPrice.number = parseInt(totalPrice.text.replace(/ تومان|,/g, ''));
                discountClickCount++;
            }

            if (discountCode) {
                fetch("https://api.tamland.ir/api/payment/checkDiscount", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        Mobile: mobile,
                        CourseId: courseId,
                        Type: courseTypeNum,
                        DiscountCode: discountCode,
                    }),
                })
                    .then((response) => response.json())
                    .then((result) => {
                        const dataVal = result['0'];
                        switch (dataVal['status']) {
                            case 0:
                                applyDiscountSuccess(discountCode, dataVal['fldPercentage']);
                                break;
                            case 1:
                                applyDiscountFailure('زمان کد تخفیف به پایان رسیده است');
                                break;
                            case 2:
                                applyDiscountFailure('کد تخفیف وارد شده برای این دوره مجاز نمی‌باشد');
                                break;
                            case 3:
                                applyDiscountFailure('کد تخفیف وارد شده نامعتبر است');
                                break;
                            case 4:
                                applyDiscountFailure('تعداد استفاده از کد تخفیف بیشتر از حد مجاز است');
                                break;
                            default:
                                console.error('وضعیت ناشناخته دریافت شد');
                        }
                    })
                    .catch((error) => console.error('خطا در ارتباط با سرور:', error));
            }
        });

        jQuery(document).on('click', '.del-discount-code', function () {
            resetPrice();
            jQuery('.discount-validate-message').removeClass('valid').addClass('d-none').empty();
            setCookie("discountIsSet", "false", 9); // Expires in 5 minutes 
        });
    });
</script>
    <?php
}
add_action('wp_footer','course_checkout_page_script');
*/
add_action('wp_head', 'check_show_first_session_videos_page');
function check_show_first_session_videos_page() {
    if(is_page(8121)){
        if(isset($_COOKIE["submited_first_session_videos_form"]) && $_COOKIE["submited_first_session_videos_form"] == "yes") {
           ?>
            <style>
                .first-video-page-form-section{
                    display:none !important;
                }
                .first-video-page-section{
                    display:flex !important;
                }
            </style>
            <?php 
        }
    }
}

add_action( 'gform_after_submission_9', 'show_first_session_videos', 10, 2 );
function show_first_session_videos( $entry, $form ) {
    setcookie("submited_first_session_videos_form", "yes", time() + (86400 * 30), "/"); // 86400 = 1 day
    ?>
    <style>
        .first-video-page-form-section{
            display:none !important;
        }
        .first-video-page-section{
            display:flex !important;
        }
    </style>
    <?php
}

//کد های مربوط به سرعت سایت 

if (!is_user_logged_in() && !is_admin() && !defined('DOING_AJAX')) {
    function remove_css_js_ver($src) {
        return strpos($src, '?ver=') ? remove_query_arg('ver', $src) : $src;
    }
    add_filter('style_loader_src', 'remove_css_js_ver', 10, 2);
    add_filter('script_loader_src', 'remove_css_js_ver', 10, 2);


    add_action('init', function() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        add_filter('the_generator', '__return_empty_string');
    }, 11);


    add_action('wp_enqueue_scripts', function() {
        wp_deregister_script('wp-embed');
    }, 100);


    // ob_start(function($html) {
    //     return preg_replace('/\s+/', ' ', $html);
    // });


    add_action('wp_head', function() {
        echo '<link rel="preload" as="image" href="https://mid1.tamland.ir/wp-content/uploads/2025/04/load.webp" type="image/webp">' . "\n";
    });


    add_action('send_headers', function() {
        $value = wp_is_mobile()
            ? 'public, max-age=86400, must-revalidate'
            : 'public, max-age=86400';
        header("Cache-Control: $value");
    });


    add_filter('heartbeat_send', '__return_false');
}

function disable_feed() {
    wp_die(__('No feed available.', 'textdomain'));
}
add_action('do_feed', 'disable_feed', 1);
add_action('do_feed_rdf', 'disable_feed', 1);
add_action('do_feed_rss', 'disable_feed', 1);
add_action('do_feed_rss2', 'disable_feed', 1);
add_action('do_feed_atom', 'disable_feed', 1);


add_filter('the_generator', '__return_empty_string');


remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');


add_action('init', function() {
    add_filter('tiny_mce_plugins', function($plugins) {
        return array_diff($plugins, ['wpemoji']);
    });
}, 9999);


add_filter('gform_disable_css', '__return_true');
add_filter('gform_disable_js', '__return_true');


//loading


function is_known_bot() {
    if (!isset($_SERVER['HTTP_USER_AGENT'])) return false;
    $bots = [
        'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'YandexBot',
        'facebookexternalhit', 'Twitterbot', 'LinkedInBot', 'WhatsApp', 'TelegramBot'
    ];
    foreach ($bots as $bot) {
        if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) return true;
    }
    return false;
}


function enqueue_universal_lazyload_script() {
    if (is_admin() || is_known_bot()) return;
    ?>
    <style>
        .lazy-section-placeholder {
            background: #f0f0f0;
            margin: 1rem 0;
            display: block;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const lazyElements = document.querySelectorAll('[class*="lazy-"], .hero-section');

        lazyElements.forEach(el => {

            const style = window.getComputedStyle(el);

            const placeholder = document.createElement('div');
            placeholder.className = 'lazy-section-placeholder';
            placeholder.style.height = el.offsetHeight + 'px';
            placeholder.style.width = el.offsetWidth + 'px';
            placeholder.style.marginTop = style.marginTop;
            placeholder.style.marginBottom = style.marginBottom;
            placeholder.style.marginLeft = style.marginLeft;
            placeholder.style.marginRight = style.marginRight;
            placeholder.style.paddingTop = style.paddingTop;
            placeholder.style.paddingBottom = style.paddingBottom;
            placeholder.style.paddingLeft = style.paddingLeft;
            placeholder.style.paddingRight = style.paddingRight;
            placeholder.style.boxSizing = style.boxSizing;

            el.style.display = 'none';
            el.parentNode.insertBefore(placeholder, el);

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        el.style.display = '';
                        placeholder.remove();
                        obs.unobserve(el);
                    }
                });
            }, { rootMargin: '0px 0px 200px 0px', threshold: 0.1 });

            observer.observe(placeholder);
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'enqueue_universal_lazyload_script', 100);

function enqueue_mobile_only_lazyload_script() {
    if (is_admin() || is_known_bot()) return;
    ?>
    <style>
        .lazy-section-placeholder {
            background: #f0f0f0;
            margin: 1rem 0;
            display: block;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // فقط موبایل
        if (!/Mobi|Android/i.test(navigator.userAgent)) return;

        const lazyElements = document.querySelectorAll('[class*="lazy-"], .hero-section');

        lazyElements.forEach(el => {
            const style = window.getComputedStyle(el);

            const placeholder = document.createElement('div');
            placeholder.className = 'lazy-section-placeholder';
            placeholder.style.height = el.offsetHeight + 'px';
            placeholder.style.width = el.offsetWidth + 'px';
            placeholder.style.marginTop = style.marginTop;
            placeholder.style.marginBottom = style.marginBottom;
            placeholder.style.marginLeft = style.marginLeft;
            placeholder.style.marginRight = style.marginRight;
            placeholder.style.paddingTop = style.paddingTop;
            placeholder.style.paddingBottom = style.paddingBottom;
            placeholder.style.paddingLeft = style.paddingLeft;
            placeholder.style.paddingRight = style.paddingRight;
            placeholder.style.boxSizing = style.boxSizing;

            el.style.display = 'none';
            el.parentNode.insertBefore(placeholder, el);

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        el.style.display = '';
                        placeholder.remove();
                        obs.unobserve(el);
                    }
                });
            }, { rootMargin: '0px 0px 200px 0px', threshold: 0.1 });

            observer.observe(placeholder);
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'enqueue_mobile_only_lazyload_script', 100);


//loading end


function add_elementor_responsive_js() {
    ?>
    <script>
    (function() {
        function getDevice() {

            var w = window.innerWidth;
            if (w >= 1025) return 'desktop';
            if (w >= 768) return 'tablet';
            return 'phone';
        }

        function removeHiddenElements() {
            var device = getDevice();


            var classMap = {
                desktop: 'elementor-hidden-desktop',
                tablet: 'elementor-hidden-tablet',
                phone: 'elementor-hidden-phone'
            };

            var hiddenClass = classMap[device];

            if (!hiddenClass) return;


            var elems = document.querySelectorAll('.' + hiddenClass);
            elems.forEach(function(el) {
                if (el && el.parentNode) {
                    el.parentNode.removeChild(el);
                }
            });
        }


        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            removeHiddenElements();
        } else {
            window.addEventListener('DOMContentLoaded', removeHiddenElements);
        }
    })();
    </script>
    <?php
}
add_action('wp_footer', 'add_elementor_responsive_js');


add_action('wp_head', function() {
    ?>

    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2024/06/Digi-Nofar-Bold-1-1.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2024/06/Aviny-2.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2024/05/Morabba-Black.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2024/05/Morabba-Bold.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2024/05/Morabba-Regular.ttf" as="font" type="font/ttf" crossorigin>

    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWebFaNum_UltraLight.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWebFaNum_Medium.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWebFaNum_Light.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWebFaNum_Bold.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWebFaNum.ttf" as="font" type="font/ttf" crossorigin>

    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWeb_UltraLight.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWeb_Medium.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWeb_Light.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWeb_Bold.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="https://mid1.tamland.ir/wp-content/uploads/2022/09/IRANSansWeb.ttf" as="font" type="font/ttf" crossorigin>


    <!--<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">-->
    <?php
});

// تابع اصلی برای گرفتن پست تبلیغاتی مرتبط
function get_current_related_ad_post() {
    if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
    if (function_exists('nocache_headers')) nocache_headers();

    static $ad_post = null;

    if ($ad_post !== null) {
        return $ad_post;
    }

    $post_id = get_the_ID();
    if (!$post_id) return null;

    $terms_field = wp_get_post_terms($post_id, 'lesson', ['fields' => 'ids']);
    $terms_grade = wp_get_post_terms($post_id, 'grade', ['fields' => 'ids']);

    if (empty($terms_field) && empty($terms_grade)) {
        return null;
    }

    $args = [
        'post_type' => 'ads',
        'posts_per_page' => 1,
        'orderby' => 'rand',
        'tax_query' => [
            'relation' => 'OR',
            [
                'taxonomy' => 'lesson',
                'field' => 'term_id',
                'terms' => $terms_field,
            ],
            [
                'taxonomy' => 'grade',
                'field' => 'term_id',
                'terms' => $terms_grade,
            ]
        ]
    ];

    $ads = get_posts($args);
    $ad_post = !empty($ads) ? $ads[0] : null;
    return $ad_post;
}

// شورتکد برای لینک ویدیو تبلیغاتی
function get_related_ad_video_func() {
    $ad_post = get_current_related_ad_post();
    $video_url = $ad_post ? get_post_meta($ad_post->ID, 'ad_video', true) : '';
    return esc_url($video_url ?: '');
}
add_shortcode('get_related_ad_video', 'get_related_ad_video_func');

// شورتکد برای لینک صفحه تبلیغاتی
function get_related_ad_page_link_func() {
    $ad_post = get_current_related_ad_post();
    $page_link = $ad_post ? get_post_meta($ad_post->ID, 'ad_link', true) : '';
    return esc_url($page_link ?: '#');
}
add_shortcode('get_related_ad_link', 'get_related_ad_page_link_func');

add_action('wp_footer', 'add_yektanet_script_to_footer');
function add_yektanet_script_to_footer() {
    if (is_page('course-checkout')) {
        ?>
        <script async>
            !function (t, e, n) {
                t.yektanetAnalyticsObject = n, t[n] = t[n] || function () {
                    t[n].q.push(arguments)
                }, t[n].q = t[n].q || [];
                var a = new Date, r = a.getFullYear().toString() + "0" + a.getMonth() + "0" + a.getDate() + "0" + a.getHours(),
                    c = e.getElementsByTagName("script")[0], s = e.createElement("script");
                s.id = "ua-script-DSLcJKBG"; s.dataset.analyticsobject = n;
                s.async = 1; s.type = "text/javascript";
                s.src = "https://cdn.yektanet.com/rg_woebegone/scripts_v3/DSLcJKBG/rg.complete.js?v=" + r, c.parentNode.insertBefore(s, c)
            }(window, document, "yektanet");
        </script>
        <?php
    }
}

function conditional_compression() {
    if (is_page('course-checkout') || is_page('return-payment-gateway')) {
        // CSS Compression
        if (!is_admin()) {
            function compress_css($buffer) {
                $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
                $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
                return $buffer;
            }
            ob_start("compress_css");
        }
        
        // JS Compression
        add_filter('script_loader_tag', function($tag, $handle) {
            if (is_admin()) return $tag;
            return str_replace(" src", " defer src", $tag);
        }, 10, 2);
    }
}
//add_action('template_redirect', 'conditional_compression');
/*
add_filter('pre_http_request', function($pre, $args, $url) {
    error_log("🔍 Request to: " . $url);
    return false;
}, 10, 3);
add_filter('pre_http_request', 'tamland_block_external_requests_with_logging', 10, 3);

function tamland_block_external_requests_with_logging($pre, $parsed_args, $url) {
    // دامین فعلی سایت
    $site_domain = $_SERVER['SERVER_NAME'];

    // لیست دامنه‌های مجاز
    $allowed_domains = [
        $site_domain,
        'localhost',
        '127.0.0.1',
        '*.tamland.ir*',
        '*.shaparak.ir*',
        'mid1.tamland.ir/wp-cron.php', // مستثنا کردن wp-cron.php
    ];

    // چک کردن آیا این URL در لیست مجاز هست
    $allowed = false;
    foreach ($allowed_domains as $allowed_domain) {
        if (strpos($url, $allowed_domain) !== false) {
            $allowed = true;
            break;
        }
    }

    // اگر مجاز نیست، لاگ و بلاک کن
    if (!$allowed) {
        $log_message = date('Y-m-d H:i:s') . " ❌ Blocked external request to: $url\n";
        error_log($log_message, 3, WP_CONTENT_DIR . '/tamland-blocked-requests.log');

        return new WP_Error('external_request_blocked', __('External requests are blocked.'));
    }

    // ادامه بده، مشکلی نیست
    return false;
}

add_action('plugins_loaded', function () {
    load_plugin_textdomain('wpsh', false, dirname(plugin_basename(__FILE__)) . '/languages/');
});

add_filter('xmlrpc_enabled', '__return_false');
add_filter('rest_authentication_errors', function($result) {
    if (!is_user_logged_in()) {
        return new WP_Error('rest_forbidden', 'REST API restricted.', array('status' => 403));
    }
    return $result;
});
*/
function aparat_video_shortcode($atts) {
    if (function_exists('get_field')) {
        $video_id = get_field('course-preview-pack'); 
    } elseif (function_exists('get_post_meta')) {
        $video_id = get_post_meta(get_the_ID(), 'course-preview-pack', true);
    }
    
    if (!empty($video_id)) {
        return '<div class="aparat-video-wrapper">
                    <iframe src="https://www.aparat.com/video/video/embed/videohash/' . esc_attr($video_id) . '/vt/frame" 
                            style="border-radius: 15px;" width="640" height="190" allowfullscreen></iframe>
                </div>';
    }
    
    return '';
}
add_shortcode('aparat_player', 'aparat_video_shortcode');