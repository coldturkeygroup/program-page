<?php
/**
 * Template file for displaying First Responders Program Page
 *
 * @package    WordPress
 * @subpackage Program Page
 * @author     The Cold Turkey Group
 * @since      1.2
 */

global $program_page, $wp_query;

$id = get_the_ID();
$title = get_the_title();
$logo = get_post_meta($id, 'logo', true);
$cta = get_post_meta($id, 'cta', true);
$cta_url = get_post_meta($id, 'cta_url', true);
$video_url = get_post_meta($id, 'video_url', true);
$expected_savings = get_post_meta($id, 'expected_savings', true);
$program_area = [
    'city'   => get_post_meta($id, 'program_area', true),
    'county' => get_post_meta($id, 'program_area', true)
];
$phone = get_post_meta($id, 'phone_number', true);
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
$state = get_option('platform_user_state', 'Minnesota');
$testimonials_col_md = 'col-md-6';

if ($test_1_name !== '' && $test_2_name != '' && $test_3_name != '') {
    $testimonials_col_md = 'col-md-4';
}

if (!$title || $title == '') {
    $title = 'First Responders Program';
}

if ($expected_savings == '') {
    $expected_savings = '1,500';
}

if ($program_area == '') {
    $city = get_option('platform_user_city', 'Minneapolis');
    $county = get_option('platform_user_county', 'Hennepin');
    $program_area = [
        'county' => $county . ' County',
        'city'   => 'the ' . $city . ' area'
    ];
}

if ($phone == '') {
    $phone = get_option('platform_user_phone', '');
}

// Get the page colors
$primary_color = '#2eb9ff';
$hover_color = '#2eb9ff';

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
  <title><?= $title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <?php wp_head(); ?>
  <link rel="apple-touch-icon" sizes="57x57" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.platform.marketing/assets/programs/hip/favicons/apple-touch-icon-180x180.png">
  <link rel="icon" type="image/png" href="https://cdn.platform.marketing/assets/programs/hip/favicons/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="https://cdn.platform.marketing/assets/programs/hip/favicons/favicon-194x194.png" sizes="194x194">
  <link rel="icon" type="image/png" href="https://cdn.platform.marketing/assets/programs/hip/favicons/favicon-96x96.png" sizes="96x96">
  <link rel="icon" type="image/png" href="https://cdn.platform.marketing/assets/programs/hip/favicons/android-chrome-192x192.png" sizes="192x192">
  <link rel="icon" type="image/png" href="https://cdn.platform.marketing/assets/programs/hip/favicons/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="https://cdn.platform.marketing/assets/programs/hip/favicons/manifest.json">
  <link rel="mask-icon" href="https://cdn.platform.marketing/assets/programs/hip/favicons/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="msapplication-TileImage" content="https://cdn.platform.marketing/assets/programs/hip/favicons/mstile-144x144.png">
  <meta name="theme-color" content="#ffffff">
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
            background: ' . $hover_color . ' !important; }
        ';
    }
    ?>
  </style>
</head>

<body <?php body_class(); ?>>
<div class="wrapper wrapper-bg-banner wrapper-center-block banner role-element leadstyle-container">
  <div class="bg-wrapper">
    <img src="https://cdn.platform.marketing/assets/programs/first-responders/first-responders-background.jpg" style="height:50%" class="role-element leadstyle-background-image">
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
                  <a href="#about" class="role-element leadstyle-link">About</a>
                    <?php if ($test_1_name != '' && $test_1_text != '') { ?>
                      <a href="#testimonials" class="role-element leadstyle-link">Testimonials</a>
                    <?php } ?>
                  <a href="#faqs" class="role-element leadstyle-link">FAQS</a>
                </div>
              </div>
            </div>
          </nav>
          <!-- nav menu -->
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-11 col-lg-10 center-block text-center">
            <div class="inner">
              <a class="banner-logo role-element leadstyle-image-link"><img src="<?= $logo ?>" style="max-width: 355px;"></a>
              <p class="banner-text role-element leadstyle-text">&nbsp;A new home buyer discount for <?= $state ?> first responders.</p>
              <div class="line banner-line role-element leadstyle-container"></div>
              <p class="banner-list role-element leadstyle-text">no credit requirements | no income requirements</p>
              <div class="btn-inline-wrap">
                  <?php if ($cta_url != '') { ?>
                    <a class="btn btn-inline role-element leadstyle-link" href="<?= $cta_url ?>" target="_blank"><?= $cta ?></a>
                  <?php } else { ?>
                    <a class="btn btn-inline role-element leadstyle-link" href="#" id="hip-info-link"><?= $cta ?></a>
                  <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="about" class="wrapper wrapper-center-block about role-element leadstyle-container">
  <div class="container">
    <div class="row text-center">
      <div class="col-xs-12 <?php if ($video_url != '') {
          echo 'col-md-7';
      } ?> vcenter text-left role-element leadstyle-container">
        <div class="<?php if ($video_url != '') {
            echo 'fill';
        } ?>">
          <h2 class="text-xs-center text-sm-center <?php if ($video_url == '') {
              echo 'text-center';
          } ?> role-element leadstyle-text">
            <strong><span style="color:#6818a5">&lt;</span> What is the <?= $state ?> First Responders program?&nbsp;<span style="color:#6818a5">&gt;</span></strong>
          </h2>
          <p class="role-element leadstyle-text">We’re excited to announce a new program that will benefit the first responders here in <?= $program_area['city'] ?>: the <?= $state ?> First Responders Program.<br><br>As a society, we typically honor public servants like teachers, military members, and nurses. And we should... these heroes are true public servants! But sometimes we forget that first responders are also public servants! They work long, difficult hours to protect our freedom 24/7/365.<br><br><span style="font-weight: 700;">We want to say thank you. The First Responders Program is a special home buyer credit.</span> It is multiple discounts that are applied to your loan and closing costs—it is NOT a loan. It is a
            <em>free credit</em> that will reduce the amount of money that is owed at closing time. The discounts include:
          <ul>
            <li>Discounted Appraisal</li>
            <li>Discounted Home Inspection</li>
            <li>Reduced Closing Costs</li>
            <li>Additional Money Towards Closing Costs or Moving Expenses</li>
          </ul>
          <br>This special credit will make the American dream of home ownership more affordable for the hardworking first responders that serve us here in <?= $program_area['county'] ?>.<br><br><span style="font-weight: 700;">You do NOT have to be a first time home buyer to apply for these special credits!</span>
          </p>
        </div>
      </div>
        <?php if ($video_url != '') { ?>
          <div class="col-xs-12 col-md-5 vcenter text-right text-xs-center text-sm-center role-element leadstyle-container">
            <div class="embed-responsive embed-responsive-16by9">
                <?= $video_url ?>
            </div>
          </div>
        <?php } ?>
    </div>
  </div>
</div>

<?php if ($test_1_name != '') { ?>
  <div id="testimonials" class="wrapper wrapper-center-block profiles role-element leadstyle-container">
    <div class="container text-center">
      <div class="row">
        <div class="col-xs-12 col-md-11 col-lg-10 center-block">
          <h2 class="role-element leadstyle-text">
            <span style="font-weight: 700;">&nbsp;</span><b>Testimonials</b></h2>
          <p class="role-element leadstyle-text">The First Responders Program has already helped the community in <?= $program_area['county'] ?> save thousands of dollars on their home purchases.</p>
        </div>
      </div>
      <div class="row">
          <?php if ($test_1_name != '') { ?>
            <div class="col-xs-12 <?= $testimonials_col_md ?> center-block center-block-inline role-element leadstyle-container">
              <div class="fill">
                  <?php if (strpos($test_1_photo, 'cdn.platform.marketing') !== false) { ?>
                    <img class="img-responsive img-inline role-element leadstyle-image" src="<?= $test_1_photo ?>" style="max-width: 353px;">
                  <?php } else { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="<?= $test_1_photo ?>"></iframe>
                    </div>
                  <?php } ?>
                <div class="inner">
                  <h3 class="role-element leadstyle-text"><?= $test_1_name ?></h3>
                  <div class="line profiles-line role-element leadstyle-container"></div>
                  <p class="role-element leadstyle-text">
                    <span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; letter-spacing: normal; line-height: normal; text-align: start;"><?= $test_1_job ?></span>
                  </p>
                  <p class="role-element leadstyle-text">
                    <i style="color: rgb(47, 47, 47); font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; letter-spacing: normal; line-height: 23px; text-align: start;"><?= $test_1_text ?></i>
                  </p>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php if ($test_2_name != '') { ?>
            <div class="col-xs-12 <?= $testimonials_col_md ?> center-block center-block-inline role-element leadstyle-container">
              <div class="fill">
                  <?php if (strpos($test_2_photo, 'cdn.platform.marketing') !== false) { ?>
                    <img class="img-responsive img-inline role-element leadstyle-image" src="<?= $test_2_photo ?>" style="max-width: 353px;">
                  <?php } else { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="<?= $test_2_photo ?>"></iframe>
                    </div>
                  <?php } ?>
                <div class="inner">
                  <h3 class="role-element leadstyle-text"><?= $test_2_name ?></h3>
                  <div class="line profiles-line role-element leadstyle-container"></div>
                  <p class="role-element leadstyle-text">
                    <span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; letter-spacing: normal; line-height: normal; text-align: start;"><?= $test_2_job ?></span>
                  </p>
                  <p class="role-element leadstyle-text">
                    <i style="color: rgb(47, 47, 47); font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; letter-spacing: normal; line-height: 23px; text-align: start;"><?= $test_2_text ?></i>
                  </p>
                </div>
              </div>
            </div>
          <?php } ?>
          <?php if ($test_3_name != '') { ?>
            <div class="col-xs-12 <?= $testimonials_col_md ?> center-block center-block-inline role-element leadstyle-container">
              <div class="fill">
                  <?php if (strpos($test_3_photo, 'cdn.platform.marketing') !== false) { ?>
                    <img class="img-responsive img-inline role-element leadstyle-image" src="<?= $test_3_photo ?>" style="max-width: 353px;">
                  <?php } else { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="<?= $test_3_photo ?>"></iframe>
                    </div>
                  <?php } ?>
                <div class="inner">
                  <h3 class="role-element leadstyle-text"><?= $test_3_name ?></h3>
                  <div class="line profiles-line role-element leadstyle-container"></div>
                  <p class="role-element leadstyle-text">
                    <span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; letter-spacing: normal; line-height: normal; text-align: start;"><?= $test_3_job ?></span>
                  </p>
                  <p class="role-element leadstyle-text">
                    <i style="color: rgb(47, 47, 47); font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; letter-spacing: normal; line-height: 23px; text-align: start;"><?= $test_3_text ?></i>
                  </p>
                </div>
              </div>
            </div>
          <?php } ?>
      </div>
    </div>
  </div>
<?php } ?>

<div id="faqs" class="wrapper wrapper-center-block faq role-element leadstyle-container">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-md-11 col-lg-10 center-block text-center">
        <h2 class="role-element leadstyle-text">
          <strong><span style="color:#6818a5">&lt;</span> Frequently Asked Questions
            <span style="color:#6818a5">&gt;</span></strong></h2>
        <p class="role-element leadstyle-text">These are the most common questions about the <?= $state ?> First Responders Program. For more information call <?= $phone ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 text-center">
        <ul class="faq-list role-element leadstyle-text">
          <li>
            <span style="color: #46bec2; "><b>Is this only for first time home buyers?</b></span><br>Absolutely not! If you’re currently employed as a first responder, you can qualify for the First Responders discount. Even if you currently own a home, your next home purchase (in the state of <?= $state ?>) could be eligible for the program credit.
          </li>
          <li>
            <span style="color: #46bec2; "><b>Can I combine this discount with other programs?&nbsp;</b></span><br>Definitely. The First Responders Program is technically not a mortgage program (it is an independent discount). This means you can apply the discount on top of any other programs you may be eligible for (FHA loans, VA loans, etc).
          </li>
          <li>
            <span style="color: #46bec2; "><b>Are there income or credit requirements for the First Responders Program?</b></span><br>No. The First Responders Program is an independent discount for first responders here in <?= $state ?>. It is
            <em>not</em> a mortgage program; therefore, there are no income or credit qualifications! You can apply the First Responders discount to whatever financing or mortgage product you qualify for.
          </li>
          <li>
            <span style="color: #46bec2; "><b>Is there a purchase price limitation?</b></span><br>No way! The First Responders Program was designed to encourage higher rates of homeownership for first responders here in <?= $program_area['city'] ?>. Unlike some other programs, there are no limits on the price of home you can purchase.
          </li>
          <li>
            <span style="color: #46bec2; "><b>How long does it take to apply?</b></span><br>The First Responders discount does not require extensive paperwork or applications. Most home buyers find out within 12 hours if they will qualify for the discount.
          </li>
          <li>
            <span style="color: #46bec2; "><b>How much money can I save with the First Responders Program?</b></span><br>The exact number varies, but most home buyers in <?= $program_area['city'] ?> can expect to save around $<?= $expected_savings ?> (or more). This discount can be combined with other mortgage programs to save even more!
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

<div class="modal fade" id="hip-info" tabindex="-1" role="dialog" aria-labelledby="hipInfoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="hipInfoLabel">Get Started With The First Responders Discount</h4>
      </div>
      <div class="modal-body">
        Call or text <?= $phone ?> to apply for the First Responders home buyer discount
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-block btn-default" data-dismiss="modal">Okay!</button>
      </div>
    </div>
  </div>
</div>

<?php if ($retargeting != null) { ?>
  <!-- Facebook Pixel Code -->
  <script>
      !function (f, b, e, v, n, t, s) {
          if (f.fbq)return;
          n = f.fbq = function () {
              n.callMethod ?
                  n.callMethod.apply(n, arguments) : n.queue.push(arguments)
          };
          if (!f._fbq) f._fbq = n;
          n.push = n;
          n.loaded = !0;
          n.version = '2.0';
          n.queue = [];
          t = b.createElement(e);
          t.async = !0;
          t.src = v;
          s = b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t, s)
      }(window,
          document, 'script', '//connect.facebook.net/en_US/fbevents.js');

      fbq('init', '<?= $retargeting ?>');
      fbq('track', "PageView");</script>
  <noscript><img height="1" width="1" style="display:none"
                 src="https://www.facebook.com/tr?id=<?= $retargeting ?>&ev=PageView&noscript=1"
    /></noscript>
  <!-- End Facebook Pixel Code -->
<?php } ?>
<?php wp_footer(); ?>
<script>
    jQuery('document').ready(function () {
        jQuery('.embed-responsive').children(':first').addClass('embed-responsive-item');
    });

    jQuery('#hip-info-link').click(function (e) {
        e.preventDefault();

        jQuery('#hip-info').modal('show');
    });
</script>
