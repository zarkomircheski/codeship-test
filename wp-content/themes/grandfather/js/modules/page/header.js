import cookies from '../../helpers/cookies';

const header = {
  init() {
    this.init_article_header();
    this.scroll_events();
    this.click_events();
    this.header_menu();
    this.search = { status: false };
    this.mobile_menu = { status: false };
    this.mobile_email_menu = { status: false };
    this.last_scroll_top = 0;
    this.current_section();
  },

  init_article_header() {
    window.isMobile = false; // initiate as false
    // device detection
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
      || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) { // eslint-disable-line
      window.isMobile = true;
    }
    window.searchActive = false;
    window.emailActive = false;
    window.safeClick = false;
    window.abCookie = app.cookies.read_cookie('2018multi'); // AB test cookie
    let handled = false;

    // Add or remove classes based on whether the search header is open or not
    function showSearch() {
      if (window.searchActive) {
        if (window.isMobile) {
          $('.overlay').removeClass('overlay-active');
        }
        $('.nav__logo-wordmark').removeClass('nav__logo-wordmark-hide');
        $('.nav__main-menu--top-actions').removeClass('nav__main-menu--top-actions-hide');
        $('.nav__menu--search').removeClass('nav__menu--search-open');
        window.searchActive = false;
      } else {
        $('.nav__logo-wordmark').addClass('nav__logo-wordmark-hide');
        $('.overlay').addClass('overlay-active');
        $('.nav__main-menu--top-actions').addClass('nav__main-menu--top-actions-hide');
        $('.nav__menu--search').addClass('nav__menu--search-open');
        $('.nav__menu--search-form-input').focus();
        window.searchActive = true;
      }
    }

    // The following are click events for various buttons on the header/page
    $(document).on(window.isMobile ? 'touchend' : 'click', (e) => {
      if (!$(e.target).hasClass('nav__menu--search-form-input')
        && !$(e.target).hasClass('nav__menu--email-form-input')
        && !$(e.target).hasClass('newsletter__button')
        && $(e.target).parents('.newsletter__mobile').length !== 1
        && $(e.target).attr('type') !== 'submit'
        && !$(e.target).hasClass('site-navigation__right-social-item--search')
        && !$(e.target).hasClass('site-navigation__right-social-item--email')
        && !$(e.target).hasClass('article__link')) {
        if (window.searchActive) {
          showSearch();
        }
        if (window.emailActive) {
          window.showEmail();
        }
        if ($(e.target).hasClass('overlay-active')) {
          e.stopPropagation();
        }
      }
    });

    $(document).keyup((e) => { // enter
      if (e.keyCode === 27) {
        if (window.searchActive) {
          showSearch();
        }
        if (window.emailActive) {
          window.showEmail();
        }
      }
    });

    $('#header-search').on('click touch', (e) => {
      showSearch();
      e.stopPropagation();
    });

    $('#header-newsletter').on('click touch', (e) => {
      if (e.type === 'touch') {
        window.showEmail();
        e.stopPropagation();
        handled = true;
      } else if (e.type === 'click' && !handled) {
        window.showEmail();
        e.stopPropagation();
      } else {
        handled = false;
      }
    });

    window.showEmail = function () {
      if (window.emailActive) {
        $('.overlay').removeClass('overlay-active');
        $('.nav__main-menu--top .container-fluid').removeClass('error-open');
        $('.nav__logo-wordmark').removeClass('nav__logo-wordmark-hide');
        $('.nav__main-menu--top-actions').removeClass('nav__main-menu--top-actions-hide');
        $('.nav__menu--email').removeClass('nav__menu--email-open');
        $('.site-header').removeClass('nav-email-open');
        window.emailActive = false;
      } else {
        $('.overlay').addClass('overlay-active');
        $('.nav__menu--email-form-input').focus();
        $('.nav__menu--email').addClass('nav__menu--email-open');
        $('.site-header').addClass('nav-email-open');
      }
    };

    const emailInput = $('.nav__menu--email').find('input[type="email"]');

    if (emailInput.length > 0) {
      if (window.innerWidth <= 800) {
        emailInput.attr('placeholder', emailInput.data('placeholder-mobile'));
      } else {
        emailInput.attr('placeholder', emailInput.data('placeholder-desktop'));
      }
    }
  },

  resize() {
    this.mobile_resize();
  },

  mobile_resize() {
    if (app.wm.width <= 980) {
      this.hide_share_header();
    }
  },

  set_logo_position() {
    const logo = $('.logo');
    logo.css({ marginLeft: 'auto', marginRight: 'auto' });
    const margin = logo.css('marginLeft');
    logo.css({ marginLeft: margin, marginRight: margin });
  },

  update_article_urls() {
    //
    // update article sharing urls
    //
    const $currentArticle = $(`#${$('body').data('currentArticle')}`);
    const articles = $('article');
    articles.each(function () {
      // Trigger new url/pageview when new article title is in view
      let $articleTitle = $(this).find('.article__leaderboard');
      let scrollBottom = $(window).scrollTop() + $(window).height();

      if (scrollBottom > $articleTitle.offset().top &&
        scrollBottom < $(this).offset().top + $(this).height() &&
        !$('body').hasClass('single-sweepstakes') && $('body').data('currentArticle') &&
        $('body').data('currentArticle') != $(this).attr('id')
      ) {
        let articleUrl = $(`#${$(this).attr('id')}`).attr('data-url'),
          baseUrl = `${window.location.protocol}//${window.location.hostname}/`,
          newUrl = $(this).data('url').substring(baseUrl.length);
        /*
         * When we scroll back up the page we want to be sure that the
         * referrer is set to the current url before updating to the new one
         */
        window.fatherlyDataLayer.referrer = window.location.href;
        if (window.location.protocol == 'https:') {
          window.history.replaceState(
            {},
            $(this).data('title'),
            baseUrl + newUrl,
          );
        } else {
          window.history.replaceState(
            {},
            $(this).data('title'),
            articleUrl,
          );
        }
        // track current article
        $('body').data('current-article', $(this).attr('id'));
        // update data layer
        window.fatherlyDataLayer.title = $(`#${$('body').data('current-article')}`).attr('data-title');
        window.fatherlyDataLayer.categories = $(this).data('categories');
        document.title = `${window.fatherlyDataLayer.title} | Fatherly`;
        $(window).trigger('newPostLoaded');
      }
    });
  },

  scroll_events() {
    const event = this;
    $(window).scroll(() => {
      event.add_sticky();
      event.add_border();
      if ($('body').hasClass('single-post')) {
        event.update_article_urls();
      }
    });

    if (app.wm.width <= 980) {
      this.hide_mobile_header();
    }
  },

  click_events() {
    $(document).on('click', '.social-button--twitter', function (e) {
      app.header.social_share(e, this);
    });
    $(document).on('click', '.social-button--pinterest', (e) => {
      app.header.pinterest_share(e);
    });
  },

  social_share(e, elem) {
    e.preventDefault();
    const share_url = $(elem).prop('href');
    const post_title = `${$(elem).attr('data-title')} - `;
    const post_url = $(elem).attr('data-url');
    const post_utm =
      `?utm_source=${
        $(elem)
          .data('platform')
          .toLowerCase()}`;

    let url = share_url;
    if ($(elem).data('platform') == 'Twitter') url += post_title;
    url += post_url + post_utm;

    const NWin = window.open(url, '', 'scrollbars=1,height=400,width=400');
    if (window.focus) {
      NWin.focus();
    }
    return false;
  },

  pinterest_share(e) {
    e.preventDefault();
    window.setTimeout(() => {
      const e = document.createElement('script');
      e.setAttribute('type', 'text/javascript');
      e.setAttribute('charset', 'UTF-8');
      e.setAttribute(
        'src',
        `https://assets.pinterest.com/js/pinmarklet.js?r=${
          Math.random() * 99999999}`,
      );
      document.body.appendChild(e);
    }, 500);
  },

  add_sticky() {
    if ($(window).scrollTop() > 1) {
      $('header').addClass('sticky');
    } else {
      $('header').removeClass('sticky');
    }
  },

  add_border() {
    if ($(window).scrollTop() > 100) {
      $('.site-header').addClass('site-header--border');
    } else {
      $('.site-header').removeClass('site-header--border');
    }
  },

  show_share_header() {
    app.header.logo_left = true;
    $('.logo').addClass('logo--left');
    $('.site-header').addClass('site-header--short');
    $('.post-actions').addClass('post-actions--active');
    $('.site-navigation__right-social').fadeOut();
  },

  hide_share_header() {
    app.header.logo_left = false;
    $('.post-actions').removeClass('post-actions--active');
    $('.site-header').removeClass('site-header--short');
    $('.logo').removeClass('logo--left');
    $('.site-navigation__right-social').fadeIn();
  },

  hide_mobile_header() {
    let last_scroll_top = 0;
    $(window).scroll(() => {
      if (
        app.header.mobile_menu.status === false &&
        app.header.search.status === false &&
        app.header.mobile_email_menu.status === false &&
        app.wm.width <= 980
      ) {
        const scroll_top = $(window).scrollTop();

        if (scroll_top > last_scroll_top && scroll_top > 55) {
          // scroll down
          $('.site-header').addClass('site-header--hide');
        } else {
          // scroll up
          $('.site-header').removeClass('site-header--hide');
        }

        last_scroll_top = scroll_top;
      } else {
        $('.site-header').removeClass('site-header--hide');
      }
    });
  },

  current_section() {
    const id = {
      gear: '57268',
      'health-and-science': '57267',
      'love-and-money': '57265',
      parenting: '57269',
      play: '57266',
      news: '57270',
    };

    const $article = $('.article-post');
    if ($article.length > 0) {
      let section = $article.data('tracking').dimension9;
      section = section.substring(0, section.indexOf(','));

      $(`#menu-item-${id[section]}`).addClass('active');
    }
  },

  header_menu() {
    const that = this;

    $(document).on('click', '.menu-button.menu-button--closed', () => {
      that.open_menu();
    });

    $(document).on('click', '.menu-button.menu-button--open', () => {
      that.close_menu();
    });

    $(document).on('touchend click', '.overlay.overlay-active', (e) => {
      e.preventDefault();
      that.close_menu();
    });
  },

  open_menu() {
    if (app.header.logo_left == true) {
      app.header.hide_share_header();
    }
    $('.nav__logo-wordmark').removeClass('nav__logo-wordmark-hide');
    $('.menu-button').removeClass('menu-button--closed').addClass('menu-button--open');
    $('.menu-button__menu-icon').removeClass('menu-button__menu-icon--closed').addClass('menu-button__menu-icon--open');
    $('.site-header, .expandable-nav').addClass('site-header--active');
    if (!window.isMobile) {
      $('.overlay').addClass('overlay-active');
    }
  },

  close_menu() {
    $('.overlay').removeClass('overlay-active');
    $('.nav__main-menu--top .container-fluid').removeClass('error-open');
    $('.menu-button').removeClass('menu-button--open').addClass('menu-button--closed');
    $('.menu-button__menu-icon').removeClass('menu-button__menu-icon--open').addClass('menu-button__menu-icon--closed');
    $('.site-header, .expandable-nav').removeClass('site-header--active nav-email-open');
    $('.nav__menu--email-open').removeClass('nav__menu--email-open');
  },

};

export default header;
