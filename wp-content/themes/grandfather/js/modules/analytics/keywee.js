const keywee = {
  init() {
    $(document).ready(() => {
      if (!window.isScroll) {
        const inEUCookie = app.cookies.read_cookie('inEU');

        if (inEUCookie !== 'true') { // we are not in the EU, cookie set on Fastly, confirm it's not set to string of true
          (function (w, d) {
            w.kwa || (w.kwa = function () {
              (w.kwa.q = w.kwa.q || []).push(arguments);
            });
            let se = d.createElement('script'),
              fs = d.scripts[0];
            se.src = '//cdn.keywee.co/dist/analytics.min.js';
            fs.parentNode.insertBefore(se, fs);
          }(window, document));

          kwa('initialize', 79);
        }
      }
    });
  },
};

export default keywee;
