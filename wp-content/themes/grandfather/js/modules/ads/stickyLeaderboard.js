import cookies from '../../helpers/cookies';

let removeSticky = ($leaderboard, $body) => {
  // Wait a second so the transition can work
    setTimeout(() => {
      $(window).off('scroll.stickyLeaderboard');
      $leaderboard.removeClass('sticky below-header');
      $body.removeClass('ad-product-small');
    }, 100);
    return false;
  },

  triggerSticky = (newContentEvent, element) => {
    if ($('body').hasClass('single-post')) {
      let $window = $(window),
        $body = $('body'),
        leaderboardIndex = $('article').length - 1,
        $leaderboard = $('.article__leaderboard-ad').eq(leaderboardIndex),
        $stickyLeaderboard = $('.article__leaderboard-ad.sticky.below-header').eq(leaderboardIndex - 1),
        $currentArticle = $leaderboard.closest('article'),
        headerHeight = $('.nav__main-menu--top').height(),
        isFirst = !($currentArticle.hasClass('next-article')),
        triggerStickyPos = isFirst ? headerHeight + 20 : $leaderboard.parent().offset().top - 20,
        previousPosition = $window.scrollTop(),
        contentPush = 'ad-product-small',
        isSticky = true;

      if ($currentArticle.data('stuck') !== 'true' && $body.data('currentArticle') == $currentArticle.attr('id')) {
        $currentArticle.data('stuck', 'true');

        // remove any active scroll events loaded in by previous sticky leaderboards
        $window.off('scroll.stickyLeaderboard');
        $body.removeClass(contentPush);

        // add stop class if there is already a sticky leaderboard
        if (typeof $stickyLeaderboard !== 'undefined') {
          $stickyLeaderboard.addClass('stop');
        }

        // On the first page the header should start of position absolute
        if (isFirst) {
          $body.addClass(contentPush);
        }

        let stickyHandler = function (e) {
          let currentPosition = $window.scrollTop();
          if (isSticky) {
            if (currentPosition >= triggerStickyPos) {
              $leaderboard.addClass('sticky below-header');
              $body.addClass(contentPush);
            } else {
              $leaderboard.removeClass('sticky below-header');
              $body.removeClass(contentPush);
            }
          }
          previousPosition = currentPosition;
        };

        // wait for creative code to load in, so we can check refresh variable correctly
        window.googletag = window.googletag || {};
        window.googletag.cmd = window.googletag.cmd || [];

        googletag.cmd.push(() => {
          googletag.pubads().addEventListener('slotRenderEnded', (event) => {
            // if we have lead1 and refresh was suppressed or the user came
            // from email, prep unstick function
            if (event.slot.getAdUnitPath().includes('lead1') && isSticky && (!window.mobile_refresh_lead1)) {
              googletag.cmd.push(() => {
                googletag.pubads().addEventListener('impressionViewable', (event) => {
                  if (event.slot.getAdUnitPath().indexOf('/72233705/z_lead1/') > -1) {
                    setTimeout(() => {
                      isSticky = removeSticky($leaderboard, $body);
                    }, 5000);
                  }
                });
              });

              // if the viewability event does not fire use this as a fall back
              setTimeout(() => {
                isSticky = removeSticky($leaderboard, $body);
              }, 15000);
            }
          });
        });
        $window.on('scroll.stickyLeaderboard', stickyHandler);
      }
    }
  };

if (window.outerWidth < 600) {
  $(window).on('dfpMappingLoaded', triggerSticky());
  $(window).on('newPostLoaded', (event, element) => {
    triggerSticky(event, element);
  });
}
