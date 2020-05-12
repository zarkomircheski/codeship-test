const IntersectionObserver = require('intersection-observer-polyfill/dist/IntersectionObserver');

(function ($) {
  const supported = typeof window.IntersectionObserver === 'function';

  /*
    Set up an intersection observer
    This function has two main pieces of functionality
    1. Create sticky content by appending sticky, stickybottom, and removing those classes
    2. Trigger a callback when intersecting with the provided sticky item at the threshold given
   */
  function scrollTrigger(sentinels, stickyItem, options) {
    const defaultOptions = {
      callback() {},
      type: 1,
      threshold: [0, 1.0],
      width: 600,
    };

    // Merge options and default so all necessary values are set
    options = $.extend({}, defaultOptions, options);

    this.sentinels = sentinels;
    const thresholds = {
      threshold: options.threshold,
    };

    function observeEntries(entries, observer) {
      entries.forEach((entry) => {
        const ratio = entry.intersectionRatio;
        const targetInfo = entry.boundingClientRect;
        const rootBoundsInfo = entry.rootBounds;
        // Used for sticky content
        if (options.type === 1 && window.innerWidth > options.width) {
          const sticky = entry.target.parentElement.parentElement.parentElement.querySelector(stickyItem);
          if (entry.target.className.indexOf('top') > -1) {
            if (targetInfo.bottom < rootBoundsInfo.top) {
              sticky.classList.add('sticky');
              options.callback(sticky);
            }
            if (targetInfo.bottom >= rootBoundsInfo.top && targetInfo.bottom < rootBoundsInfo.bottom) {
              sticky.classList.remove('sticky');
              options.callback(sticky);
            }
          } else {
            if (targetInfo.bottom > rootBoundsInfo.top && (supported && ratio === 1 || !supported && $(window).scrollTop() > $('.article__content').position().top)) {
              sticky.classList.add('sticky');
              sticky.classList.remove('sticky-bottom');
            }
            if (targetInfo.top < rootBoundsInfo.top && targetInfo.bottom < rootBoundsInfo.bottom) {
              sticky.classList.remove('sticky');
              sticky.classList.add('sticky-bottom');
            }
          }
        }
        // Used for intersection content
        else if (options.type === 2) {
          if ((supported && ratio > options.intersection) || !supported) {
            observer.disconnect();
            options.callback(stickyItem);
          }
        }
        else if (options.type === 3) {
          if (entry.target.className.indexOf('top') > -1) {
            if (targetInfo.bottom < rootBoundsInfo.top && targetInfo.bottom < 0) {
              options.callback(entry.target);
            }
          } else if (targetInfo.bottom > rootBoundsInfo.top && (supported && ratio === 1 || !supported && $(window).scrollTop() > $('.article__content').position().top)) {
            if (targetInfo.bottom < 100) {
              options.callback(entry.target);
            }
          }
        }
      });
    }

    if (options.rootMargin !== '0px 0px 0px 0px') {
      this.observer = new IntersectionObserver(observeEntries, options);
    } else {
      this.observer = new IntersectionObserver(observeEntries, thresholds);
    }

    this.sentinels.forEach((sentinel) => this.observer.observe(sentinel));
  }

  module.exports = scrollTrigger;
}(jQuery, this));
