const sharing = {
  init() {
    this.mobile_share_window();
    this.mobile_share_tracking();
    this.mobile_share_visibility();
    this.mobile_email_share();
  },
  mobile_share_window() {
    $('.share-tools a.fb-share, .share-tools a.twitter-share').click(function () {
      const NWin = window.open(
        $(this).prop('href'),
        '',
        'scrollbars=1,height=400,width=400',
      );
      if (window.focus) {
        NWin.focus();
      }
      return false;
    });
  },
  mobile_share_tracking() {
    $('.mobile-share__share-item').on('click', function (event) {
      const platform = $(this).data('platform'); // Facebook, Twitter, Reddit, Email...
      const device = 'mobile';

      // Send if everything is set
      if (typeof platform !== 'undefined') {
        // Facebook
        fbq('track', 'share', {
          content_name: dataLayer.title,
          content_category:
            'categories' in dataLayer ? dataLayer.categories[0] : '',
        });

        // Google Analytics
        ga('send', {
          hitType: 'social',
          socialNetwork: platform,
          socialAction: `share-${device}`,
          socialTarget: [
            location.protocol,
            '//',
            location.host,
            location.pathname,
          ].join(''),
        });
      }
    });
  },
  mobile_share_visibility() {
    if (app.wm.page == 'single' && app.wm.device == 'mobile') {
      $(window).scroll(() => {
        if (app.wm.infscrl_article == true) {
          if (
            $(window).scrollTop() > $('.single-infinitescroll').offset().top
          ) {
            app.sharing.hide_mobile_share();
          } else {
            app.sharing.show_mobile_share();
          }
        }
      });
    }
  },
  mobile_email_share() {
    // Overrides the mobile share with the email share.
    if (app.wm.page == 'single' && app.wm.device == 'mobile' && Math.random() >= 0.5) {
      $(window).scroll(() => {
        if (app.wm.infscrl_article == true) {
          if ($(window).scrollTop() > $('.single-infinitescroll').offset().top) {
            app.sharing.hide_mobile_share();
          }
        }
      });
    }
  },
  hide_mobile_share() {
    $('.mobile-share').hide();
  },
  show_mobile_share() {
    $('.mobile-share').show();
  },
  get_current_article() {
    // get current article from body ID
    // if doesn't exist it is first article, and we can just use default
    if ($('body').data('currentArticle')) {
      return $(`#${$('body').data('currentArticle')}`);
    }
    return $('article');
  },
};
export default sharing;
