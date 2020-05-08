/**
 * This file serves as the single location from where all custom analytics tracking
 * is initialized and called for all our tracking platforms that require custom integration.
 *
 * - The main purpose of this file and the associated methods within is to set correct
 *   variables in the Google Tag Manager data layer and send custom events to the
 *   Google Tag Manager data layer. All tracking platforms access the information they need
 *   in the context of the Google Tag Manager container.
 *
 *
 * TODO convert custom google analytics calls to google tag manager calls when functionality is confirmed.
 */
window.fthAnalytics = {

  /**
   *  run
   *
   *  This method is called on page load and sets up initial config params for
   *  our various platforms and performs all tracking operations.
   */
  run() {
    this.setTrackingID(); // Setup the correct id for tracking with GA
    this.setGlobalData(); // Setup vars for global data
    if ($('body').hasClass('single-post')) {
      this.trackArticlePage(); // perform tracking operations for single article page
    } else {
      this.trackPage();
    }
  },

  /**
   *  trackArticlePage
   *
   *  Performs calls to setup the dimensions associated with a single article
   *  page and sends events that are related to single article pages.
   */
  trackArticlePage() {
    this.single = true;
    this.sendDataLayerEvent('is_single_page');
    const currentArticleId = $('body').data('current-article');
    const theArticle = $(`#${currentArticleId}`);
    const articleTracking = theArticle.data('tracking');
    articleTracking.dimension19 = this.read_cookie('2018multi'); // AB Testing to GA
    this.setSingleArticleGoogleAnalyticsDimensions(articleTracking);
    this.sendDataLayerVariable({
      location: window.location.href,
      referrer: window.fatherlyDataLayer.referrer,
      page: location.pathname,
      nielsenASN: window.fatherlyDataLayer.nielsen.asn,
      nielsenSegA: window.fatherlyDataLayer.nielsen.segA
    });
    ga('send', 'pageview');
  },

  /**
   * trackPage
   *
   * This method is used to track page views that aren't article pages such as category landing pages.
   */
  trackPage() {
    this.sendDataLayerVariable({
      nielsenASN: window.fatherlyDataLayer.nielsen.asn,
      nielsenSegA: window.fatherlyDataLayer.nielsen.segA
    });
    ga('send', 'pageview');
  },

  /**
   * trackCustomPageView
   *
   * This method is used to track page views with the added ability to specify the location for the page view.
   * An example of where this is used is on the category landing pages when we initiate the infinite scroll so that we can track a
   * page view each time a new result set is loaded with '/page/n' appended where n represents a count of the result sets returned.
   *
   * @param $location
   */
  trackCustomPageView($location) {
    ga('send', 'pageview', $location);
  },

  /**
   * setTrackingID
   *
   * Ran as part of the initial setup and defines the tracking id to use per env
   */
  setTrackingID() {
    dataLayer.push({ ga_tracking_id: window.fatherlyDataLayer.ga_tracking_id });
    ga('create', window.fatherlyDataLayer.ga_tracking_id, 'auto');
  },

  /**
   *  setGlobalData
   *
   *  sets variables for data that is global such as page type
   */
  setGlobalData() {
    this.sendDataLayerVariable($('body').data('pagetracking'));
    window.isScroll = document.cookie.indexOf('scroll0') > 0;
    ga('set', $('body').data('pagetracking'));
    ga('set', 'dimension20', window.isScroll);
  },

  /**
   * sendDataLayerEvent
   *
   * Sends events to the GTM datalayer.
   * @param $eventName The name of the event to send
   */
  sendDataLayerEvent($eventName) {
    dataLayer.push({ event: $eventName });
  },

  /**
   * sendDataLayerVariable
   *
   *  Sends single or multiple variables to the GTM datalayer.
   * @param $var|object The variable or variables to send to the GTM data layer
   */
  sendDataLayerVariable($var) {
    dataLayer.push($var);
  },

  /**
   * setSingleArticleGoogleAnalyticsDimensions
   *
   *  Sets all the dimension variables on the dataLayer that are unique to
   *  single article page views.
   * @param $articleTracking article tracking html data attribute values
   *
   */
  setSingleArticleGoogleAnalyticsDimensions($articleTracking) {
    this.sendDataLayerVariable($articleTracking);
    ga('set', $articleTracking);
  },

  /**
   * trackSocialInteration
   *
   * event that tracks social interation events in google analytics.
   *
   * @param $data
   */
  trackSocialInteraction($data) {
    ga('send', $data);
  },

  /**
   * trackCustomEvent
   * Used to track custom events in google analytics.
   *
   * @param $data
   */
  trackCustomEvent($data, $event) {
    ga('send', 'event', $data, $event);
  },

  /**
   * trackScrollDepth
   *
   * Used to track scroll depth events in google analytics.
   *
   * @param $depth
   */
  trackScrollDepth($depth) {
    ga('send', 'event', 'scroll-depth', $depth, { nonInteraction: true });
  },

  generateId($length) {
    // dec2hex :: Integer -> String
    function dec2hex(dec) {
      return (`0${dec.toString(16)}`).substr(-2);
    }

    // generateId :: Integer -> String
    function generateId(len) {
      const arr = new Uint8Array((len || 40) / 2);
      const crypto = window.crypto || window.msCrypto;
      crypto.getRandomValues(arr);
      return Array.from(arr, dec2hex).join('');
    }

    return generateId($length);
  },

  read_cookie(name) {
    const nameEQ = `${name}=`;
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  },

  create_cookie(name, value, days, customDate) {
    let expires = '';
    if (days) {
      const date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      expires = `; expires=${date.toUTCString()}`;
    } else if (customDate) {
      expires = `; expires=${customDate.toUTCString()}`;
    }
    document.cookie = `${name}=${value}${expires}; path=/`;
  },

  getInternalID() {
    let internalID = this.read_cookie('fth_internal_id');

    if (internalID) {
      return internalID;
    }
    internalID = this.generateId(32);
    this.create_cookie('fth_internal_id', internalID, 365, null);
    return internalID;
  },
};

(function ($) {
  window.fatherlyDataLayer.referrer = document.referrer; // This referrer variable is used by parsely to track referral sources on infinite scroll page view events.
  window.fthAnalytics.run(); // Initialized the analytics class
  $(window).on('newPostLoaded', () => {
    // This event is fired when a new post is loaded into the site through the infinite scroll mechanism. This sets the page param for GA and then re-triggers the tackArticlePage() method.
    ga('set', {
      page: location.pathname,
    });
    window.fthAnalytics.trackArticlePage();
    window.fthAnalytics.sendDataLayerEvent('pageview');
  });
}(window.jQuery || window.Zepto));
