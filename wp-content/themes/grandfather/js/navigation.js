(function (document, window, index) {
  let elSelector = '.nav__main-menu--secondary',
    elClassHidden = 'nav__main-menu--secondary--hidden',
    bodyElement = document.querySelector('body'),
    subNavClosed = 'subnav-closed',
    throttleTimeout = 25,
    element = document.querySelector(elSelector);

  if (!element) return true;

  let dHeight = 0,
    wHeight = 0,
    wScrollCurrent = 0,
    wScrollBefore = 0,
    wScrollDiff = 0,

    hasElementClass = function (element, className) {
      return element.classList ? element.classList.contains(className) : new RegExp(`(^| )${className}( |$)`, 'gi').test(element.className);
    },
    addElementClass = function (element, className) {
      element.classList ? element.classList.add(className) : element.className += ` ${className}`;
    },
    removeElementClass = function (element, className) {
      element.classList ? element.classList.remove(className) : element.className = element.className.replace(new RegExp(`(^|\\b)${className.split(' ').join('|')}(\\b|$)`, 'gi'), ' ');
    },

    throttle = function (delay, fn) {
      let last,
        deferTimer;
      return function () {
        let context = this,
          args = arguments,
          now = +new Date();
        if (last && now < last + delay) {
          clearTimeout(deferTimer);
          deferTimer = setTimeout(() => {
            last = now;
            fn.apply(context, args);
          }, delay);
        } else {
          last = now;
          fn.apply(context, args);
        }
      };
    };

  window.addEventListener('scroll', throttle(throttleTimeout, () => {
    dHeight = document.body.offsetHeight;
    wHeight = window.innerHeight;
    wScrollCurrent = window.pageYOffset;
    wScrollDiff = wScrollBefore - wScrollCurrent;

    // scrolled to the very top; element sticks to the top
    if (window.outerWidth < 700) {
      if (wScrollCurrent <= 0) {
        removeElementClass(element, elClassHidden);
        removeElementClass(bodyElement, subNavClosed);
      } else if (wScrollDiff > 0 && hasElementClass(element, elClassHidden)) {
        // scrolled up; element slides in
        removeElementClass(element, elClassHidden);
        removeElementClass(bodyElement, subNavClosed);
      } else if (wScrollDiff < 0) {
        // scrolled down
        if (wScrollCurrent + wHeight >= dHeight && hasElementClass(element, elClassHidden)) {
          // scrolled to the very bottom; element slides in
          removeElementClass(element, elClassHidden);
          removeElementClass(bodyElement, subNavClosed);
        } else {
          // scrolled down; element slides out
          addElementClass(element, elClassHidden);
          addElementClass(bodyElement, subNavClosed);
        }
      }
    }

    wScrollBefore = wScrollCurrent;
  }));
}(document, window, 0));
