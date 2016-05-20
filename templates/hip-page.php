<?php
/**
 * Template file for displaying HIP Program Page
 *
 * @package    WordPress
 * @subpackage Program Page
 * @author     The Cold Turkey Group
 * @since      1.0.0
 */

global $program_page, $wp_query;

$id = get_the_ID();
$title = get_the_title();
$logo = get_post_meta($id, 'logo', true);
$video_url = get_post_meta($id, 'video_url', true);
$broker = get_post_meta($id, 'legal_broker', true);
$test_1_name = get_post_meta($id, 'test_1_name', true);
$test_1_job = get_post_meta($id, 'test_1_job', true);
$test_1_text = get_post_meta($id, 'test_1_text', true);
$test_1_photo = get_post_meta($id, 'test_1_photo', true);
$test_2_name = get_post_meta($id, 'test_2_name', true);
$test_2_job = get_post_meta($id, 'test_2_job', true);
$test_2_text = get_post_meta($id, 'test_2_text', true);
$test_2_photo = get_post_meta($id, 'test_2_photo', true);
$test_3_name = get_post_meta($id, 'test_3_name', true);
$test_3_job = get_post_meta($id, 'test_3_job', true);
$test_3_text = get_post_meta($id, 'test_3_text', true);
$test_3_photo = get_post_meta($id, 'test_3_photo', true);
$retargeting = get_post_meta($id, 'retargeting', true);
$city = get_option('platform_user_city', 'Minneapolis');
$state = get_option('platform_user_state', 'Minnesota');
$county = get_option('platform_user_county', 'Hennepin');
$phone = get_option('platform_user_phone', '');

if (!$title || $title == '') {
    $title = 'HIP Program';
}

// Get the page colors
if (function_exists('of_get_option')) {
    $primary_color = of_get_option('primary_color');
    $hover_color = of_get_option('secondary_color');
}

$color_setting = get_post_meta($id, 'primary_color', true);
$hover_setting = get_post_meta($id, 'hover_color', true);

if ($color_setting && $color_setting != '') {
    $primary_color = $color_setting;
}

if ($hover_setting && $hover_setting != '') {
    $hover_color = $hover_setting;
}

?>
    <!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8">
        <title><?php wp_title('&middot;', true, 'right'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
        <?php wp_head(); ?>
        <style>
            <?php
            if( $primary_color != null ) {
                echo '
                .btn {
                    background: ' . $primary_color . ' !important;
                    border: 2px solid ' . $primary_color . ' !important; }
                .profiles h3, .cta h3 {
                    color: ' . $primary_color . ' !important; }
                ';
            }
            if( $hover_color != null ) {
                echo '
                .btn:hover,
                .btn:focus {
                    color: ' . $hover_color . ' !important;
                    border-color: ' . $hover_color . ' !important; }
                ';
            }
            ?>
        </style>
    </head>

<body <?php body_class(); ?>>
    <div class="wrapper wrapper-bg-banner wrapper-center-block banner role-element leadstyle-container">
        <div class="bg-wrapper">
            <img src="https://cdn.platform.marketing/assets/programs/hip/hip-background.png" class="role-element leadstyle-background-image">
        </div>
        <div class="bg-text middle">
            <div class="fill">
                <div class="container">
                    <div class="row">
                        <!-- nav menu -->
                        <nav class="global-nav role-element leadstyle-container">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <a href="#section1" class="role-element leadstyle-link">About</a>
                                        <a href="#section2" class="role-element leadstyle-link">Testimonials</a>
                                        <a href="#section5" class="role-element leadstyle-link">FAQS</a>
                                    </div>
                                </div>
                            </div>
                        </nav>
                        <!-- nav menu -->
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-11 col-lg-10 center-block text-center">
                            <div class="inner">
                                <a class="logo role-element leadstyle-image-link"><img src="<?= $logo ?>" style="max-width: 355px;"></a>
                                <p class="banner-text role-element leadstyle-text">&nbsp;A new homebuyer discount for <?= $state ?> healthcare professionals.</p>
                                <div class="line banner-line role-element leadstyle-container"></div>
                                <p class="banner-list role-element leadstyle-text">no credit requirements | no income requirements</p>
                                <div class="btn-inline-wrap">
                                    <a class="btn btn-inline role-element leadstyle-link" href="<?= $video_url ?>" target="_blank">WATCH THE VIDEO</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="section1" class="wrapper wrapper-center-block about role-element leadstyle-container">
        <div class="container">
            <div class="row text-center">
                <div class="col-xs-12 col-md-7 vcenter text-left role-element leadstyle-container">
                    <div class="fill">
                        <h2 class="text-xs-center text-sm-center role-element leadstyle-text">
                            <strong><span style="color:#6818a5">&lt;</span> What is the HIP program?&nbsp;<span style="color:#6818a5">&gt;</span></strong>
                        </h2>
                        <p class="role-element leadstyle-text">We’re excited to announce a new program that will benefit health care professionals here in the greater <?= $city ?> area: the <?= $state ?> HIP program. HIP stands for
                            <em>Health Industry Professionals.</em><br><br>As a society, we typically honor public servants like military veterans, teachers, firefighters, and police officers. And we should... these heroes are true public servants! But sometimes we forget that health care professionals are also public servants! They work long, difficult hours to make sure we receive the health care services we need, 24/7/365.<br><br><span style="font-weight: 700;">We want to say thank you. The HIP Program is a special homebuyer credit offered exclusively through <?= $broker ?>
                                <em>.</em></span> It is a discount that is applied to your closing costs—it is NOT a loan. It is a
                            <em>free credit</em> that will reduce the amount of money that is owed at closing time.<br><br>This special credit will make the American dream of homeownership more affordable for the hardworking health care professionals that serve us here in <?= $county ?> County.<br><br><span style="font-weight: 700;">You do NOT have to be a first time homebuyer to apply for these special credits!</span>
                        </p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5 vcenter text-right text-xs-center text-sm-center role-element leadstyle-container">
                    <div class="video-container role-element leadstyle-video video" data-lp-responsive="height" data-lp-ratio-height="0.5625" data-lp-ratio-width="1.7777777777777777" style="width: 100%;">
                        <iframe src="<?= $video_url ?>" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" data-aspectratio="0.5625" style="width: 458px; height: 257.625px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="section2" class="wrapper wrapper-center-block profiles role-element leadstyle-container">
        <div class="container text-center">
            <div class="row">
                <div class="col-xs-12 col-md-11 col-lg-10 center-block">
                    <h2 class="role-element leadstyle-text">
                        <span style="font-weight: 700;">&nbsp;</span><b>Testimonials</b></h2>
                    <p class="role-element leadstyle-text">The HIP™ program has already helped healthcare professionals in <?= $county ?> County save thousands of dollars on their home purchases.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-4 center-block center-block-inline role-element leadstyle-container">
                    <div class="fill">
                        <img class="img-responsive img-inline role-element leadstyle-image" src="<?= $test_1_photo ?>" style="max-width: 353px;">
                        <div class="inner">
                            <h3 class="role-element leadstyle-text"><?= $test_1_name ?></h3>
                            <div class="line profiles-line role-element leadstyle-container"></div>
                            <p class="role-element leadstyle-text">
                                <span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 12.8px; letter-spacing: normal; line-height: normal; text-align: start;"><?= $test_1_job ?></span>
                            </p>
                            <p class="role-element leadstyle-text">
                                <i style="color: rgb(47, 47, 47); font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; letter-spacing: normal; line-height: 22.8571px; text-align: start;">"<?= $test_1_text ?>"</i><br>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4 center-block center-block-inline role-element leadstyle-container">
                    <div class="fill">
                        <img class="img-responsive img-inline role-element leadstyle-image" src="<?= $test_2_photo ?>" style="max-width: 353px;">
                        <div class="inner">
                            <h3 class="role-element leadstyle-text"><?= $test_2_name ?></h3>
                            <div class="line profiles-line role-element leadstyle-container"></div>
                            <p class="role-element leadstyle-text">
                                <span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 12.8px; letter-spacing: normal; line-height: normal; text-align: start;"><?= $test_2_job ?></span>
                            </p>
                            <p class="role-element leadstyle-text">
                                <i style="color: rgb(47, 47, 47); font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; letter-spacing: normal; line-height: 22.8571px; text-align: start;">"<?= $test_2_text ?>"</i>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4 center-block center-block-inline role-element leadstyle-container">
                    <div class="fill">
                        <img class="img-responsive img-inline role-element leadstyle-image" src="<?= $test_3_photo ?>" style="max-width: 353px;">
                        <div class="inner">
                            <h3 class="role-element leadstyle-text"><?= $test_3_name ?></h3>
                            <div class="line profiles-line role-element leadstyle-container"></div>
                            <p class="role-element leadstyle-text">
                                <span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 12.8px; letter-spacing: normal; line-height: normal; text-align: start;"><?= $test_3_job ?></span>
                            </p>
                            <p class="role-element leadstyle-text">
                                <i style="color: rgb(47, 47, 47); font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; letter-spacing: normal; line-height: 22.8571px; text-align: start;">"<?= $test_3_text ?>"</i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="section5" class="wrapper wrapper-center-block faq role-element leadstyle-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-11 col-lg-10 center-block text-center">
                    <h2 class="role-element leadstyle-text">
                        <strong><span style="color:#6818a5">&lt;</span> Frequently Asked Questions
                            <span style="color:#6818a5">&gt;</span></strong></h2>
                    <p class="role-element leadstyle-text">These are the most common questions about the <?= $state ?> HIP program. For more information call <?= $phone ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <ul class="faq-list role-element leadstyle-text">
                        <li>
                            <font color="#46bec2"><b>Is this only for first time homebuyers?</b></font><br>Absolutely not! If you’re currently employed in the healthcare industry, you can qualify for the HIP discount. Even if you currently own a home, your next home purchase (in the state of <?= $state ?>) could be eligible for the HIP credit.
                        </li>
                        <li>
                            <font color="#46bec2"><b>Can I combine this discount with other programs?&nbsp;</b></font><br>Definitely. The HIP program is technically not a mortgage program (it is an independent discount). This means you can apply the discount on top of any other programs you may be eligible for (FHA loans, first time homebuyer programs, etc).
                        </li>
                        <li>
                            <font color="#46bec2"><b>Are there income or credit requirements for the HIP program?</b></font><br>No. The HIP program is an independent discount for healthcare professionals here in <?= $state ?>. It is
                            <em>not</em> a mortgage program; therefore, there are no income or credit qualifications! You can apply the HIP discount to whatever financing or mortgage product you qualify for.
                        </li>
                        <li>
                            <font color="#46bec2"><b>Is there a purchase price limitation?</b></font><br>No way! The HIP program was designed to encourage higher rates of homeownership for healthcare professionals here in the <?= $city ?> area. Unlike some other programs, there are no limits on the price of home you can purchase.
                        </li>
                        <li>
                            <font color="#46bec2"><b>How long does it take to apply?</b></font><br>The HIP discount does not require extensive paperwork or applications. Most homebuyers find out within 12 hours if they will qualify for the discount.
                        </li>
                        <li>
                            <font color="#46bec2"><b>How much money can I save with the HIP discount?</b></font><br>The exact number varies, but most homebuyers in the <?= $city ?> area can expect to save around $1,500 (or more). This discount can be combined with other mortgage programs to save even more!
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <footer class="wrapper wrapper-center-block footer role-element leadstyle-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-11 col-lg-10 center-block text-center">
                    <p class="footer-text role-element leadstyle-text">2016 - <?= date('Y') ?> &middot; <?= $broker ?></p>
                </div>
            </div>
        </div>
    </footer>

<?php if ($retargeting != null) { ?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?= $retargeting ?>');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= $retargeting ?>&ev=PageView&noscript=1"/></noscript>
<?php } ?>
<?php wp_footer(); ?>