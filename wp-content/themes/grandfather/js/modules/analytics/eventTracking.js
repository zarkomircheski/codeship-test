const eventTracking = {
  init() {
    const self = this;
    this.zergnet_tracking();
    $(document).ready(() => {
      self.setup_scroll_depth();
      self.setup_aud_dev_click('body');
    });

    $(window).on('newContentLoaded', () => {
      self.setup_aud_dev_click('article:last');
      self.setup_scroll_depth();
    });
    $(window).on('newPostLoaded', () => {
      self.reset_scroll_depth();
    });
    $(window).on('listContentLoaded', () => {
      self.setup_aud_dev_click('div.list-items-new');
    });
    $(window).on('trendingLoaded', () => {
      self.setup_aud_dev_click('.trending:last');
      self.setup_aud_dev_click('.recirculation:last');
    });
  },
  track_aud_dev_click(elem) {
    const event_location = $(elem).data('ev-loc');
    const event_name = $(elem).data('ev-name');
    const event_input = $(elem).data('ev-input');
    const event_value = event_name === 'Search Submit' ? elem.previousSibling.previousSibling.value : $(elem).data('ev-val');

    if (event_name == 'Buy Button') {
      window.fbq('track', 'Purchase', { currency: 'USD', value: event_input });
    }
    if (event_value === undefined) {
      ga('send', 'event', event_location, event_name);
    } else {
      ga('send', 'event', event_location, event_name, event_value);
    }
  },
  setup_aud_dev_click(element) {
    const self = this;

    $(element).find('[data-ev-loc]').each((i, e) => {
      let clickEvent = 'click';
      let handled = false;

      if ('ontouchend' in document === true) {
        clickEvent = 'click touchend';
      }
      if ($(e).data('ev-name') !== 'Email Submit') {
        let touchMoved;
        $(e).on(clickEvent, (event) => {
          /* Because of 'click touchend' both a touchend and then click event are always fired.
           * This is to catch machines that have both click and touch. To prevent double firing,
           * we check to see if the event was touchend, handle it, and set handled to true.
           * Then on the following click event (which always happens), it will skip tracking
           * function (preventing double firing), and reset handled to false so that we are back
           * in the same state to be able to track another touch. On click, we just handle the
           * event, no need to touch the handled boolean value.
           */
          if (touchMoved !== true) {
            if (event.type === 'touchend') {
              self.track_aud_dev_click(e);
              handled = true;
            } else if (event.type === 'click' && !handled) {
              self.track_aud_dev_click(e);
            } else {
              handled = false;
            }
          }
        }).on('touchmove', (event) => {
          touchMoved = true;
        }).on('touchstart', (event) => {
          touchMoved = false;
        });
      }
    });
    $('.list-items-new').removeClass('list-items-new');
  },
  set_click(action, elem_class) {
    $(document).on(action, elem_class, function () {
      const event_location = $(this).data('ev-loc');
      const event_name = $(this).data('ev-name');
      const event_value = $(this).data('ev-val');
      if (event_value === undefined) {
        ga('send', 'event', event_location, event_name);
      } else {
        ga('send', 'event', event_location, event_name, event_value);
      }
    });
  },
  reset_click(action, elem_class) {
    $(document).off(action, elem_class);
  },
  email(elem_class) {
    const event_location = $(elem_class).data('ev-loc');
    const event_name = $(elem_class).data('ev-name');
    const event_value = $(elem_class).data('ev-val');
    if (event_value === undefined) {
      ga('send', 'event', event_location, event_name);
    } else {
      ga('send', 'event', event_location, event_name, event_value);
    }
  },
  reset_scroll_depth() {
    var self = this;
    self.setup_scroll_depth();
  },
  setup_scroll_depth() {
    var self = this;
    if (app.wm.page == 'single' && $('body').hasClass('single-post')) {
      app.wm.currentArticleID = $('body').data('currentArticle');
      app.wm.currentArticle = $(`#${$('body').data('currentArticle')}`).find('.article-post__main-content');
      app.wm.articleHeight = app.wm.currentArticle.outerHeight();
      app.wm.articleOffset = app.wm.currentArticle.offset().top;
      if (typeof window.scroll_depth_records == 'undefined') {
        window.scroll_depth_records = {};
      }
      if (typeof window.scroll_depth_records[app.wm.currentArticleID] == 'undefined') {
        window.scroll_depth_records[app.wm.currentArticleID] = {
          100: false,
          75: false,
          50: false,
          25: false,
        };
      }
      $(window).scroll(self.scroll_depth);
    }
  },
  scroll_depth() {
    var scroll_depth_pos = Math.round(((($(window).scrollTop() - app.wm.articleOffset) / app.wm.articleHeight) * 100));
    if (scroll_depth_pos <= 100) {
      if (scroll_depth_pos == 100 && window.scroll_depth_records[app.wm.currentArticleID][100] == false) {
        window.scroll_depth_records[app.wm.currentArticleID][100] = true;
        ga('send', 'event', 'scroll-depth', '100', { nonInteraction: true });
      } else if (scroll_depth_pos == 75 && window.scroll_depth_records[app.wm.currentArticleID][75] == false) {
        window.scroll_depth_records[app.wm.currentArticleID][75] = true;
        ga('send', 'event', 'scroll-depth', '75', { nonInteraction: true });
      } else if (scroll_depth_pos == 50 && window.scroll_depth_records[app.wm.currentArticleID][50] == false) {
        window.scroll_depth_records[app.wm.currentArticleID][50] = true;
        ga('send', 'event', 'scroll-depth', '50', { nonInteraction: true });
      } else if (scroll_depth_pos == 25 && window.scroll_depth_records[app.wm.currentArticleID][25] == false) {
        window.scroll_depth_records[app.wm.currentArticleID][25] = true;
        ga('send', 'event', 'scroll-depth', '25', { nonInteraction: true });
      }
    }
  },
  outbound_link(loc, url) {
    ga('send', 'event', loc, 'click', url, {
      transport: 'beacon',
      hitCallback() {
        document.location = url;
      },
    });
  },
  send_event(loc, name, val, noninteractive) {
    ga('send', 'event', loc, name, val, { nonInteraction: noninteractive });
  },
  zergnet_tracking() {
    $(document.body).on('click', '.zergimg, .zergheadline a', function (event) {
      ga('send', 'event', 'Footer', $(this).hasClass('zergimg') ? 'Zergnet - Image' : 'Zergnet - Headline');
    });
  },
};
window.eventTracking = eventTracking;
export default eventTracking;
