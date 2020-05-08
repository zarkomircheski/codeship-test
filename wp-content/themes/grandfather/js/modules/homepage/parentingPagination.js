(function ($) {
  $(document).ready(() => {
    const $links = $('.parenting-link');

    /*
      At 750px the parenting navigation begins to overflow off the page
      This function centers whatever li is passed to it
    */
    function centerNavItem($currentIndex) {
      let currentWidth = window.innerWidth;
      if (currentWidth <= 750) {
        let leftLinks = 0,
          i = 0;

        while (i < $currentIndex) {
          leftLinks = leftLinks + $links[i].scrollWidth + 10;
          i++;
        }
        if (currentWidth > 600) {
          currentWidth -= 40;
        }

        $('.parenting-nav ul').css('margin-left', `${((currentWidth - ($links[$currentIndex].scrollWidth + 10)) / 2) - leftLinks}px`);
      }
    }

    if ($links.length > 0) {
      centerNavItem(parseInt($('.parenting-link.active').attr('id')));
    }

    function removeActiveClasses($currentActiveLink, $parentDiv) {
      const $currentActiveSection = $('.parenting-section.active');

      // Remove classes from current section
      $currentActiveLink.removeClass('active');
      $currentActiveSection.removeClass('active');
      $parentDiv.removeClass((index, className) => (className.match(/move-([0-5])/g) || []).join(' '));
    }

    /*
     When a arrow or li is clicked change the clicked item to red
     and move the new content items into view. If an arrow can no longer paginate
     left or right change the arrow color to black
    */
    function paginate(clicked) {
      const $currentActiveLink = $('.parenting-link.active');
      // Stop anything from trigger if you click the current displayed section
      if (clicked.id !== $currentActiveLink.attr('id')) {
        const $parentDiv = $('.parenting-overflow');

        // Add classes to current section
        if ($(clicked).hasClass('parenting-arrow-right')) {
          const nextIndex = parseInt($currentActiveLink.attr('id')) + 1;
          if (nextIndex < 6) {
            removeActiveClasses($currentActiveLink, $parentDiv);
            centerNavItem(nextIndex);

            var $section = $(`.parenting-section.index-${nextIndex}`);
            $parentDiv.addClass(`move-${nextIndex}`);
            $(`#${nextIndex}`).addClass('active');
            $section.addClass('active');
            if (nextIndex == 5) {
              $('.parenting-arrow-right').addClass('stop');
            } else {
              $('.parenting-arrow.stop').removeClass('stop');
            }
          }
        } else if ($(clicked).hasClass('parenting-arrow-left')) {
          const previousIndex = parseInt($currentActiveLink.attr('id')) - 1;
          if (previousIndex > -1) {
            removeActiveClasses($currentActiveLink, $parentDiv);
            centerNavItem(previousIndex);

            var $section = $(`.parenting-section.index-${previousIndex}`);
            $parentDiv.addClass(`move-${previousIndex}`);
            $(`#${previousIndex}`).addClass('active');
            $section.addClass('active');
            if (previousIndex == 0) {
              $('.parenting-arrow-left').addClass('stop');
            } else {
              $('.parenting-arrow.stop').removeClass('stop');
            }
          }
        } else {
          removeActiveClasses($currentActiveLink, $parentDiv);
          centerNavItem(parseInt($(clicked).attr('id')));

          var $section = $(`.parenting-section.index-${clicked.id}`);
          $('.parenting-overflow').addClass(`move-${clicked.id}`);
          $(clicked).addClass('active');
          $section.addClass('active');
          if (clicked.id == 0) {
            $('.parenting-arrow.stop').removeClass('stop');
            $('.parenting-arrow-left').addClass('stop');
          } else if (clicked.id == 5) {
            $('.parenting-arrow.stop').removeClass('stop');
            $('.parenting-arrow-right').addClass('stop');
          } else {
            $('.parenting-arrow.stop').removeClass('stop');
          }
        }
      }
    }

    $('.parenting-link, .parenting-arrow').click(function (event) {
      paginate(this);
    });
  });
}(jQuery, this));
