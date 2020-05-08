import ads from './modules/ads';
import analytics from './analytics';
import cookies from './modules/utilities/cookies';
import dataCapture from './modules/data_capture/dataCapture';
import dfp from './jqueryDfp';
import dojomojo from './modules/sweepstakes/dojomojo';
import eventTracking from './modules/analytics/eventTracking';
import faq from './modules/homepage/faq';
import header from './modules/page/header';
import helperAds from './helpers/ads';
import helperPage from './helpers/page';
import glossary from './modules/homepage/glossary';
import keywee from './modules/analytics/keywee';
import loadlisticle from './load-listicle';
import loadMore from './load-more';
import modal from './modules/utilities/modal';
import moreFromModule from './modules/page/moreFromModule';
import navigation from './navigation';
import noNewsletterCTA from './modules/page/noNewsletterCTA';
import parentingPagination from './modules/homepage/parentingPagination';
import profile from './modules/user/profile';
import randomGenerator from './modules/page/randomGenerator';
import sharing from './modules/social/sharing';
import stickyLeaderboard from './modules/ads/stickyLeaderboard';
import trending from './trending';
import utilities from './modules/utilities';
import videoPagination from './modules/page/videoPagination';

window.app = {
  init() {
    this.init_wm();
    app.header.init();
    app.sharing.init();
    app.ads.init();
    app.event_tracking.init();
    app.profile.init();
    app.modal.init();
    app.more_from.init();
    app.noNewsletterCTA.init();
    app.keywee.init();
  },
  resize() {
    app.init_wm();
    app.header.resize();
  },
  init_wm() {
    this.wm = {};
    this.wm.width = $(window).width();
    this.wm.height = $(window).height();
    this.wm.device = utilities.get_device();
    this.wm.page = utilities.get_page();
    this.wm.role = utilities.get_role();
    this.wm.login_status = utilities.is_logged_in();
    this.wm.infscrl_article = false;
    this.wm.page_height = utilities.get_page_height();
    utilities.get_scroll_pos();
  },
};

app.dfp = dfp;
app.header = header; // header.js
app.sharing = sharing; // sharing.js
app.ads = ads; // ads.js
app.event_tracking = eventTracking; // eventTracking.js
app.profile = profile; // profile.js
app.cookies = cookies; // cookies.js
app.more_from = moreFromModule; // moreFromModule.js
app.modal = modal; // modal.js
app.data_capture = dataCapture;
app.noNewsletterCTA = noNewsletterCTA;
app.parenting_pagination = parentingPagination;
app.video_pagination = videoPagination;
app.keywee = keywee;
app.load_listicle = loadlisticle;
app.navigation = navigation;
app.load_more = loadMore;
app.analytics = analytics;
app.helper_ads = helperAds;
app.helper_page = helperPage;
app.stickyLeaderboard = stickyLeaderboard;
app.random_generator = randomGenerator;
app.glossary = glossary;
app.trending = trending;
app.faq = faq;
app.init();

/**
 * Event listeners
 */
$(window).resize(app.resize);
$(window).on('load', app.ads.hide_sticky_mobile);
$(window).on('load', dojomojo.init());

/**
 * Triggers
 */
$(window).trigger('appLoaded');
