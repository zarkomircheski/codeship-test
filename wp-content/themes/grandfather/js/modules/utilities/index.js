const utilities = {
  get_device: () => (app.wm.width <= 980 ? 'mobile' : 'desktop'),
  get_page() {
    let page;
    if ($('body').hasClass('home')) {
      page = 'home';
    } else if ($('body').hasClass('single-campaign')) {
      page = 'campaign';
    } else if ($('body').hasClass('single')) {
      page = 'single';
    } else if (
      $('body').hasClass('category')
      || $('body').hasClass('page-template-page-landing')
    ) {
      page = 'category';
    } else if ($('body').hasClass('tag')) {
      page = 'tag';
    } else if ($('body').hasClass('search')) {
      page = 'search';
    } else if ($('body').hasClass('archive')) {
      page = 'archive';
    } else if ($('body').hasClass('login')) {
      page = 'login';
    } else if ($('body').hasClass('register')) {
      page = 'register';
    } else if ($('body').hasClass('lostpassword')) {
      page = 'lostpassword';
    } else if ($('body').hasClass('your-profile')) {
      page = 'profile';
    } else if ($('body').hasClass('page')) {
      page = 'page';
    } else {
      page = 'auxilary';
    }
    return page;
  },
  get_role() {
    let role;
    if ($('body').hasClass('subscriber')) {
      role = 'subscriber';
    } else if ($('body').hasClass('editor')) {
      role = 'editor';
    } else if ($('body').hasClass('contributor')) {
      role = 'contributor';
    } else if ($('body').hasClass('administrator')) {
      role = 'administrator';
    } else if ($('body').hasClass('author')) {
      role = 'author';
    } else {
      role = '';
    }
    return role;
  },
  is_logged_in() {
    if ($('body').hasClass('logged-in')) {
      return true;
    }
    return false;
  },
  is_page(page) {
    if (this.get_page() === page) {
      return true;
    }
    return false;
  },
  is_device(device) {
    if (this.get_device() === device) {
      return true;
    }
    return false;
  },
  get_scroll_pos() {
    app.wm.scroll_pos_fired = {
      100: false,
      75: false,
      50: false,
      25: false,
    };
    $(window).scroll(() => {
      app.wm.scroll_pos = $(window).scrollTop();
    });
  },
  get_page_height() {
    let page_height = 0;
    if (app.wm.page == 'single') {
      page_height = $('.article-post')
        .eq(0)
        .height();
    }
    return page_height;
  },
  get_viewport() {
    const width = Math.max(
      document.documentElement.clientWidth,
      window.innerWidth || 0,
    );
    const height = Math.max(
      document.documentElement.clientHeight,
      window.innerHeight || 0,
    );

    return { width, height };
  },
  get_parameter_by_name(name) {
    name = name.replace(/[[]/, '\[').replace(/[]]/, '\]'); // eslint-disable-line
    let regex = new RegExp(`[\?&]${name}=([^&#]*)`), // eslint-disable-line no-useless-escape
      results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
  },
  is_in_viewport(element) {
    const rect = element.getBoundingClientRect();
    const html = document.documentElement;
    return (
      rect.top >= 0
      && rect.left >= 0
      && Math.floor(rect.bottom) <= (window.innerHeight || html.clientHeight)
      && Math.floor(rect.right) <= (window.innerWidth || html.clientWidth)
    );
  },
};

export default utilities;
