import cookies from '../../helpers/cookies';

const noNewsletterCTA = {
  init() {
    if ($('body').hasClass('single-post')) {
      var fromEmail = cookies.getCookie('fromEmail');
      var noFlyout = cookies.getCookie('noFlyout');
      if (fromEmail || window.location.search.indexOf('utm_medium=email') > -1) {
        $('.mobile-ad-nav--fb-button').addClass('show');
      }

      if (fromEmail || window.location.search.indexOf('utm_medium=email') > -1) {
        this.hideNewsletterCTAS(2);
        $(window).on('newPostLoaded', () => {
          this.hideNewsletterCTAS(2);
        });
      }

      if (noFlyout) {
        this.hideNewsletterCTAS(1);
        $(window).on('newPostLoaded', () => {
          this.hideNewsletterCTAS(1);
        });
      }
    }
  },
  hideNewsletterCTAS(ctas) {
    const currentArticleID = $('body').data('current-article');
    const theArticle = $(`.postid-${currentArticleID}`);

    // Hide flyout
    theArticle.find('.email-submit.flyout').addClass('newsletterCTATest');

    // Hide newsletter cta at the bottom of articles
    if (ctas > 1) {
      theArticle.find('.email-submit-article-footer').addClass('newsletterCTATest');
    }
  },
};

export default noNewsletterCTA;
