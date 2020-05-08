import throttle from '../../helpers/throttle';

(function ($) {
  function getVideoGroupSetSize(currentvideoGroupSet) {
    const windowWidth = window.innerWidth;
    let newVideoGroupSet = (windowWidth > 1000) ? 4 : Math.floor(windowWidth / 250);
    /*
      Iterate through right arrows if videoGroupSet size changed
      and add active class to each right arrow if:
      - Window is above 1000px and there are more than 4 items (videoGroupSet = 4)
      - Window is between 750px and 1000px and there are more than 3 items (videoGroupSet = 3)
      - Window is between 500px and 750px and there are more than 3 items (videoGroupSet = 2)
      - Window is below 500px and there are more than 2 items (videoGroupSet = 1)
     */
    if (currentvideoGroupSet !== newVideoGroupSet) {
      $('body').find('.video-arrow-right').each((i, e) => {
        let lastIndex = parseInt($(e).siblings('.secondary-videos-content').children().last()
            .attr('id')) + 1,
          movedOver = parseInt($(e).parent('.secondary-videos-overflow').attr('class').match(/move-(\d+)/g)[0].substr(5));
        if (lastIndex > newVideoGroupSet && movedOver + newVideoGroupSet < lastIndex) {
          $(e).addClass('active');
        } else {
          $(e).removeClass('active');
        }
      });
    }
    return newVideoGroupSet;
  }

  // Initializes the video group set size
  let videoGroupSet = getVideoGroupSetSize(0);

  // On resize, check to see if we've moved past the breakpoints and set new size
  $(window).resize(throttle.triggerThrottle(500, () => {
    videoGroupSet = getVideoGroupSetSize(videoGroupSet);
  }));

  $(document).ready(() => {
    function removeActiveClasses($parentDiv) {
      // Remove "move-(number of posts to move over) class from clicked element's parent
      $parentDiv.removeClass((index, className) => (className.match(/move-(\d+)/g) || []).join(' '));
    }

    function paginate($clicked) {
      const $parentDiv = $clicked.parent('.secondary-videos-overflow');
      const lastIndex = $clicked.siblings('.secondary-videos-content').find('.secondary-videos-item.last-index').attr('id');

      // Add move-(number of posts to move over) class to parent of clicked arrow
      if ($clicked.hasClass('video-arrow-right')) {
        /*
          If window is over 600, move over 4
          If window is between 400px and 600px, move over 3 items
          If window is below 400px, move over 2 items
        */
        const nextIndex = parseInt($parentDiv.attr('class').match(/move-(\d+)/g)[0].substr(5)) + videoGroupSet;
        $clicked.siblings('.video-arrow-left').addClass('active');

        // If there are items left, we're not on the last set
        if (lastIndex - nextIndex >= 0) {
          removeActiveClasses($parentDiv);
          $parentDiv.addClass(`move-${nextIndex}`);

          // Remove right arrow's active class depending on window width (4, 3, or 2 items left)
          if (lastIndex - nextIndex < videoGroupSet) {
            $clicked.removeClass('active');
          } else {
            $clicked.addClass('active');
          }
        }
      } else if ($clicked.hasClass('video-arrow-left')) {
        /*
          If window is over 600, move back 4
          If window is between 400px and 600px, move back 3 items
          If window is below 400px, move back 2 items
        */
        let previousIndex = (parseInt($parentDiv.attr('class').match(/move-(\d+)/g)[0].substr(5)) - videoGroupSet < 0) ? 0 : parseInt($parentDiv.attr('class').match(/move-(\d+)/g)[0].substr(5)) - videoGroupSet;

        if (lastIndex > videoGroupSet - 1) {
          $clicked.siblings('.video-arrow-right').addClass('active');
        }

        // If previousIndex is 0, we are back at beginning. Remove left arrow active class
        removeActiveClasses($parentDiv);
        $parentDiv.addClass(`move-${previousIndex}`);
        if (previousIndex === 0) {
          $clicked.removeClass('active');
        } else {
          $clicked.addClass('active');
        }
      }
    }

    $('.video-arrow').click(function (event) {
      paginate($(this));
    });
  });
}(jQuery, this));
