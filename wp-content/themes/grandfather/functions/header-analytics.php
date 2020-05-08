<?php
/**
 * Initialize Google Tag Manager.
 */
function fth_analytics_gtm_init()
{
    $gtm_container_id = Fatherly\Config\FatherlyConfig::config()->get('gtm_container_id');
    $output = "
  <script>(function (w, d, s, l, i) {
          w[l] = w[l] || [];
          w[l].push({
              'gtm.start': new Date().getTime(), event: 'gtm.js', isScrollUser: document.cookie.indexOf('scroll0=') > 0 
          });
          var f = d.getElementsByTagName(s)[0],
              j = d.createElement(s),
              dl = l != 'dataLayer' ? '&l=' + l : '';
          j.async = true;
          j.src =
              'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
          f.parentNode.insertBefore(j, f);
      })(window, document, 'script', 'dataLayer', '$gtm_container_id');</script>
 
  ";
    echo $output;
}
add_action('header_analytics', 'fth_analytics_gtm_init');


/**
 * Initialize Google Analytics without sending pageview tracking.
 */
function fth_analytics_ga_init()
{
    $ga_id = \Fatherly\Config\FatherlyConfig::config()->get('ga_id');
    $output = "
  <script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
    var ga_id = '{$ga_id}';
    ga('create', ga_id, 'auto');
  </script>
  ";
    echo $output;
}
add_action('header_analytics', 'fth_analytics_ga_init');

/**
 * Add any additional details for the gtm data layer
 */
function fth_analytics_add_data_layer_variables()
{
    $page_type = get_page_type();

    $output = "
    <script>
      dataLayer.push({'page_type': '{$page_type}'});
    </script>
  ";
    echo $output;
}
add_action('header_analytics', 'fth_analytics_add_data_layer_variables');
