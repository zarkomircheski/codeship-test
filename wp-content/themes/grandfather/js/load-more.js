import triggerAjaxLoad from './helpers/ajaxLoad';
import cookies from './helpers/cookies';

$(window).on('load', () => {
  const abCookie = cookies.getCookie('2018multi');

  $('.pagination').hide();
  var scrollHandling = {
    allow: true,
    reallow() {
      scrollHandling.allow = true;
    },
    delay: 400, // (milliseconds) adjust to the highest acceptable value
  };

  const parsely_cookies = decodeURIComponent(cookies.getCookie('_parsely_visitor'));
  let jsonObj = '';

  // See if user has a parsely UUID
  if (parsely_cookies !== 'undefined') {
    jsonObj = JSON.parse(parsely_cookies);

    // These ajax calls pass the users id and url to help train the profile
    // This should result in us getting better recommendations
    let params = {
      uuid: jsonObj.id ? jsonObj.id : '',
      url: window.location.href,
      apikey: 'fatherly.com',
    };

    const response = function (res) {
      if (!res.success) {
        console.log('Error: Parsely information could not be sent'); // eslint-disable-line no-console
      }
    };

    triggerAjaxLoad('https://api.parsely.com/v2/profile', 'GET', params, response);

    // Set cookie if user is coming from email
    const fromEmail = window.location.search.indexOf('utm_medium=email') > -1;
    if (fromEmail) {
      let expires = new Date(new Date().getTime() + (90 * 24 * 60 * 60 * 1000));
      document.cookie = `fromEmail=true; expires=${expires}; path=/;`;
    }

    $(window).on('newContentLoaded', () => {
      // If the user is still reading the second post by the time the third article is is being loaded
      // we can determine that the user was interested in that article
      if ($('article').length > 2) {
        params.url = window.location.href;
        triggerAjaxLoad('https://api.parsely.com/v2/profile', 'GET', params, response);
      }
      // Load new Outbrain module
      OBR.extern.researchWidget();
    });
  }

  // Parameters used to trigger parsely recommendations
  const parselyParams = {
    url: window.location.href,
    uuid: jsonObj.id ? jsonObj.id : '',
    apikey: 'fatherly.com',
    page: 1,
    limit: 5,
    sort: 'pub_date',
    boost: 'views',
  };
  let selectedParams = {
    action: 'fth_ajax_load_more',
    tax: 'selected',
  };
  let $article = $('article');

  if ($article.length > 0) {
    $('.single-infinitescroll .container-sm').append('<span class="latest-posts__load-more"></span>');
    const button = $('.latest-posts__load-more');
    let page = 0;
    let excludes = [$('article')[0].id];
    let excludesFE = [window.location.href];
    let pinned = JSON.parse(`[${$('body').data('pinned')}]`);
    let loading = false;
    let selectedParams = {
      action: 'fth_ajax_load_more',
      tax: 'selected',
    };

    // Remove pinned item if it is the first article
    if (pinned.includes(String(excludesFE[0]))) {
      pinned = pinned.filter((a) => a !== excludesFE[0]);
    }

    /**
     * Trigger Ajax load using parsely recommendations. If the ajax call
     * failed revert to old functionality
     *
     * @param {object} res - response from parsely ajax call
     */
    const parselyPost = function (parselyRec) {
      if (parselyRec.success === true) {
        for (let i in parselyRec.data) {
          let url = parselyRec.data[i].url.replace('https://www.fatherly.com', window.location.origin);
          if (!excludesFE.includes(url)) {
            pinned.push(url);
          }
        }
        return true;
      }
      return false;
    };

    if (abCookie === 'is-parsely') {
      // Trigger initial Parsely call on load to reduce infinite scroll load time
      triggerAjaxLoad('https://api.parsely.com/v2/related', 'GET', parselyParams, parselyPost);
    }

    $(window).scroll(() => {
      if (!loading && scrollHandling.allow && $('.single-post').length > 0) {
        scrollHandling.allow = false;
        setTimeout(scrollHandling.reallow, scrollHandling.delay);
        const offset = $(button).offset().top - $(window).scrollTop();
        if (offset < 2500) {
          loading = true;
          fthloadmore.query.page = page;

          const url_vars = {
            action: 'fth_ajax_load_more',
            nonce: fthloadmore.nonce,
            page,
            tax: app.wm.page,
            term: fthloadmore.query.category_name ? fthloadmore.query.category_name : '',
            query: fthloadmore.query,
            excludes,
          };

          const infscr_height = $('.home-latest').height() + 446;
          $('.latest-posts__fixed-container--absolute').css({
            bottom: `${infscr_height}px`,
          });

          const addNewPost = function (res) {
            if (res.success) {
              $('.loading-gif').hide();
              $('.single-infinitescroll .container-sm').append(res.data.html);
              if (!res.data.end) {
                $('.single-infinitescroll .container-sm').append(button);

                app.wm.infscrl_article = true;

                // triggering a resize event here will make the right rail ad
                // code work for ajax articles
                $(window).trigger('resize');
                // this is the ads trigger and we need to pass the containing element of the just added story
                $(window).trigger('newContentLoaded', [$('.latest-posts-infinite').last()]);
                page = res.data.page ? res.data.page + 1 : page + 1;
                /*eslint-disable */
                excludes = res.data.excludes;
                /* eslint-enable */
                loading = false;
              } else {
                $('.article__related-footer').addClass('hide');
              }
            }
          };

          function triggeredPinnedAjaxLoad() {
            [selectedParams.url] = pinned;
            selectedParams.excludes = excludes;
            excludesFE.push(pinned[0]);
            pinned.splice(0, 1);
            triggerAjaxLoad(fthloadmore.url, 'GET', selectedParams, addNewPost);
          }

          if (pinned[0] !== undefined) {
            triggeredPinnedAjaxLoad();
          } else if (abCookie === 'is-parsely') {
            // Get Parsely recommendations
            parselyParams.url = window.location.href;
            var triggerLoad = triggerAjaxLoad('https://api.parsely.com/v2/related', 'GET', parselyParams, parselyPost);
            if (triggerLoad) {
              triggeredPinnedAjaxLoad();
            } else {
              triggerAjaxLoad(fthloadmore.url, 'GET', url_vars, addNewPost);
            }
          }
          else {
            triggerAjaxLoad(fthloadmore.url, 'GET', url_vars, addNewPost);
          }
        }
      }
    });
  }

  function ajaxContentCards(loadMoreButton) {
    let params = {};
    fth_page_data.page_number++;
    if (typeof fth_page_data !== 'undefined') {
      params = {
        action: 'fth_module_more_from_load_more',
        data: {
          page_number: fth_page_data.page_number,
          page_type: fth_page_data.page_type,
          term: (fth_page_data.term || null),
          tag__in: (fth_page_data.tag__in || null),
          s: fth_page_data.s,
          excludes: window.excludes,
          extra: fth_page_data.extra,
        },
      };
    }

    triggerAjaxLoad('/wp-admin/admin-ajax.php', 'GET', params, (res) => {
      if (res) {
        loadMoreButton.last().before(res);
      }
    });
  }

  if ($('.more-from-load-more').length > 0) {
    const loadMoreButton = $('.more-from-load-more');
    loadMoreButton.click((event) => {
      ajaxContentCards(loadMoreButton);
    });
  }
});
