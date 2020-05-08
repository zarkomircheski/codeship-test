const throttle = {
  init() {},

  triggerThrottle(delay, fn) {
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
  },
};

export default throttle;
