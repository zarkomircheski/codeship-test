import utilities from '../utilities';
import ScrollTrigger from '../../scrollTrigger';
import cookies from '../../helpers/cookies';

function generateBiddingObject(mapping, placementId) {
  return {
    name: mapping,
    code: mapping,
    div: mapping,
    sizes: window.fatherlyMapping[mapping],
    bids: [{
      bidder: 'brealtime',
      params:
        {
          placementId
        }
    }]
  };
}

const ads = {
  init() {
    this.updateDailyViewsCount();
    this.setEvents();
    this.stickyTimeout;
    this.in = 0;


    // Initialize the apstag.js library on the page to allow bidding
    apstag.init({
      pubID: '41bd2405-773e-4de8-b0cf-53b9c51f329e', // Enter your pub ID here as shown above, it must within quotes
      adServer: 'googletag',
    });
  },

  /**
   * Updates a cookie with the users daily views count.
   */
  updateDailyViewsCount() {
    // Read the cookie
    let views = app.cookies.read_cookie('fth_ads_view_count') || 0;

    // Update cookie values
    const d = new Date();
    d.setHours(23, 59, 59);
    views = parseInt(views) + 1;

    // set cookie and dfpData object
    app.cookies.create_cookie('fth_ads_view_count', views.toString(), null, d);
    window.dfpData.uv = views < 10 ? `0${views}` : views.toString();
  },

  getMapRange(width) {
    let map = 0;
    const ranges = [
      [0, 319],
      [320, 359],
      [360, 567],
      [568, 731],
      [732, 767],
      [768, 1023],
      [1024, 1199],
      [1200, 9999999],
    ];
    ranges.forEach((range) => {
      if (width >= range[0] && width <= range[1]) {
        map = range[0]; // eslint-disable-line prefer-destructuring
      }
    });
    return map;
  },

  setDfpDataVariables() {
    const vp = utilities.get_viewport();
    const map = app.ads.getMapRange(vp.width);

    const $latestArticle = $('article:last').length > 0 ? $('article:last') : $('body');
    const $dfp = $latestArticle.data('dfp') ? $latestArticle.data('dfp') : window.dfpData;
    $dfp.vp = `${vp.width}`;
    $dfp.map = map.toString();
    $dfp.uv = window.dfpData.uv;
    $latestArticle.data('dfp', $dfp);
  },

  getInVariable() {
    this.in += 1;
    return this.in < 10 ? `0${this.in}` : this.in.toString();
  },

  /**
   * Loads all ads inside the given element.
   * @param {event object} evt
   * @param {jQuery element} el
   */
  loadAds(evt, el) {
    if (window.isScroll) {
      document.body.className += ' scrolluser';
    }
    if (!window.isScroll) {
      const $url = window.location.href;
      const indexM = $url.indexOf('utm_medium=');
      const indexS = $url.indexOf('utm_source=');

      let medium = 'none';
      let source = 'none';

      if (indexM > -1) {
        const indexE = $url.indexOf('&', indexM + 11) > -1 ? $url.indexOf('&', indexM + 11) : $url.length;
        medium = $url.substring(indexM + 11, indexE).replace(/[^a-zA-Z0-9_-]/g, '');
      }

      if (indexS > -1) {
        const indexE = $url.indexOf('&', indexS + 11) > -1 ? $url.indexOf('&', indexS + 11) : $url.length;
        source = $url.substring(indexS + 11, indexE).replace(/[^a-zA-Z0-9rr_-]/g, '');
      }

      const viewableAds = [];
      const nativoAds = [];

      if (evt.originalEvent && evt.originalEvent.detail) {
        el = $(evt.originalEvent.detail);
      }

      el = el || $('body');

      const nativoIds = {
        list_native1: { ntvPlacement: '1097432' }, // HP - Trending Slot
        list_native2: { ntvPlacement: '1097433' }, // More From Slot
        list_native3: { ntvPlacement: '1097435' }, // Bottom of Article Slot
        list_native4: { ntvPlacement: '1097436' }, // Related Articles Slot
      };

      // Load additional variables if they haven't been set.
      if (!('vp' in window.dfpData)) {
        app.ads.setDfpDataVariables();
      }

      const ads = el.find('.fth-ad');

      // Loop through all the ads and set the 'in' targeting
      // variable to the ones that don't have it set.
      // The 'in' variable simply increments by 1 every time
      // it is assigned to a new ad unit. It is used to track
      // ads in infinite scroll.
      ads.each((indx, ad) => {
        // get the current targeting object
        let targeting = $(ad).data('targeting');
        let nativoTargeting = {};
        let isNativoAd = false;
        let inArticleAd = false;

        for (var size in nativoIds) {
          if ($(ad).data('size-mapping').includes(size)) {
            nativoTargeting = nativoIds[size];
            isNativoAd = true;
          }
        }
        if (targeting) {
          // make sure that an object is returned, not an array or
          // a json string.
          if (Array.isArray(targeting) && targeting.length == 0) {
            targeting = {};
          } else if (typeof targeting === 'string') {
            targeting = JSON.parse(targeting);
          }
          // only add the new variable if the targeting object
          // does not contain it already
          if (!('in' in targeting)) {
            targeting.in = app.ads.getInVariable();
            $(ad).attr('data-targeting', JSON.stringify(targeting));
          }
          if (!('ntvPlacement' in targeting) && isNativoAd) {
            targeting.ntvPlacement = nativoTargeting.ntvPlacement;
            $(ad).attr('data-targeting', JSON.stringify(targeting));
          }
        }
        if (utilities.is_in_viewport(ad) || $(ad).attr('data-size-mapping').includes('oop')) {
          if (isNativoAd) {
            nativoAds.push([ad, nativoTargeting]);
          } else {
            viewableAds.push(ad);
          }
        } else {
          if ($(ad).data('size-mapping').includes('inarticle')) {
            inArticleAd = true;
          }
          const options = {
            callback(ad) {
              const $ad = $(ad);
              // Enable header bidding for lazy loaded ads
              if (typeof biddr !== 'undefined') {
                biddr.addAdUnits(generateBiddingObject($ad.attr('data-size-mapping'), '13697759'));
              }

              let dfpTargeting = $ad.closest('article').data('dfp') || window.dfpData;
              dfpTargeting.utm_medium = medium;
              dfpTargeting.utm_source = source;

              if (isNativoAd && dfpTargeting) {
                dfpTargeting.ntvPlacement = nativoTargeting.ntvPlacement;
              }

              $ad.dfp({
                dfpID: '72233705',
                enableSingleRequest: true,
                sizeMapping: window.fatherlyMapping,
                setTargeting: dfpTargeting,
              });
            },
            type: 2,
            threshold: [0, 1.0],
            intersection: 0,
            rootMargin: inArticleAd ? '300px 0px 300px 0px' : '0px 0px 0px 0px',
          };
          const lazyAd = [ad];
          const scrollAd = new ScrollTrigger(lazyAd, ad, options);
        }
      });

      // Load ads

      if ($('article').length > 1 && typeof biddr !== 'undefined') {
        let viewableArray = [];
        for (var ad in viewableAds) {
          viewableArray.push(generateBiddingObject($(viewableAds[ad]).attr('data-size-mapping'), 13697757));
        }
        biddr.addAdUnits(viewableArray);
      }
      let dfpTargetingViewable = $('article:last').length > 0 ? $('article:last').data('dfp') : window.dfpData;
      dfpTargetingViewable.utm_medium = medium;
      dfpTargetingViewable.utm_source = source;

      if (viewableAds.length > 0) {
        $(viewableAds).dfp({
          dfpID: '72233705',
          enableSingleRequest: true,
          intitalRequest: true,
          sizeMapping: window.fatherlyMapping,
          setTargeting: dfpTargetingViewable,
        });
      }

      if (nativoAds.length > 0) {
        for (var nativoAd in nativoAds) {
          let dfpNativoTargetingViewable = dfpTargetingViewable;
          if (dfpTargetingViewable) {
            dfpNativoTargetingViewable.ntvPlacement = nativoAds[nativoAd][1].ntvPlacement;
          }
          $(nativoAds[nativoAd][0]).dfp({
            dfpID: '72233705',
            enableSingleRequest: true,
            sizeMapping: window.fatherlyMapping,
            setTargeting: dfpNativoTargetingViewable,
          });
        }
      }
    }
  },
  setEvents() {
    $(window).on('newPostLoaded', this.updateDailyViewsCount); // triggered in load-more.js
    $(window).on('dfpMappingLoaded', this.loadAds); // triggered in ads.js
    $(window).on('newContentLoaded', (event, element) => {
      app.ads.loadAds(event, element);
    }); // triggered in load-more.js
    // Allow ads to load on list pages
    $(window).on('listContentLoaded', (event, element) => {
      app.ads.loadAds(event, element);
    });
  },

  hide_sticky_mobile() {
  //   let stickyAdNav = $('.mobile-ad-nav');
  //   let count = 0;
  //
  //   app.ads.stickyTimeout = setInterval(() => {
  //     if (window.innerWidth < 1000 && ($('.single-post').length > 0 || $('.single-newsletter_signup').length > 0)) {
  //       let location = false;
  //       if ($('body').hasClass('single-newsletter_signup')) {
  //         location = 'newsletter - footer';
  //       } else if (!$('body').hasClass('single-post')) {
  //         location = 'other - footer';
  //       }
  //       if (location) {
  //         $('.newsletter__button').attr('data-ev-loc', location);
  //       }
  //       // if user came did not come from email show Tap to Subscribe after 15s
  //       if (!cookies.getCookie('fromEmail')) {
  //         // Show flyout on mobile
  //         if (count === 1) {
  //           $('.email-submit.flyout').addClass('show');
  //           clearInterval(app.ads.stickyTimeout);
  //         } else {
  //           $('.newsletter__button').addClass('show');
  //           stickyAdNav
  //             .addClass('mobile-ad-nav--share')
  //             .slideDown();
  //           window.mobile_refresh_lead2 = false;
  //         }
  //       } else if (window.mobile_refresh_lead2 === false) {
  //         // else if refresh is suppressed and user came from email
  //         // show ad then hide entire container after 15s
  //         stickyAdNav.addClass('hide');
  //       }
  //     }
  //     // Show flyout on desktop
  //     if (count === 1) {
  //       if (!cookies.getCookie('fromEmail')) {
  //         $('.email-submit.flyout').addClass('show');
  //       }
  //       clearInterval(app.ads.stickyTimeout);
  //     }
  //     count++;
  //   }, window.isScroll ? 0 : 15000);
  },
};

export default ads;
