import triggerAjaxLoad from './helpers/ajaxLoad';
import ScrollTrigger from './scrollTrigger';

// Set up params that will not differ based on list state
let params = {
  action: 'fth_ajax_load_more',
  tax: 'list',
};
let currentScrollTrigger = null;
let firstLoad = true;
let loadedItems = 0;
window.insertionOffsetDown = 0;
window.insertionOffsetUp = 0;
function loadListItems(loadOptions) {
  const defaultOptions = {
    direction: 'down',
    button: '.list-load-more-next:last',
    list: $('article:last'),
  };

  // Use default options if none are set
  loadOptions = $.extend({}, defaultOptions, loadOptions);

  // Grab object of the newest article and pagination button
  let $insertedModules = [];
  const $loadMore = $(loadOptions.button);
  let $list = $(loadOptions.list);

  // Get the module id of items loaded in the insertion que
  $('[data-module-id]').each(function (e) {
    var $moduleID = $(this).data('module-id');
    if (!$insertedModules.includes($moduleID)) {
      $insertedModules.push($moduleID);
    }
  });
  $(document).on('listContentLoaded', () => {
    var $numberDown = $('.list-items > [data-module-id]').last().nextAll().length;
    var $numberUp = $('.list-items > [data-module-id]').first().prevAll().length;
    window.insertionOffsetDown = $numberDown;
    window.insertionOffsetUp = $numberUp;
  });
  // Check if the latest article is a list and make sure there are items to load
  if ($list.hasClass('list') && $loadMore.length > 0) {
    let index = $loadMore.attr('data-index');
    const listBody = $list.find('.list-body');
    params.id = loadOptions.list[0].id;
    params.index = $loadMore.attr('data-index');
    params.amount = 10;
    params.excluded_modules = $insertedModules;
    // Disconnect scrollTrigger that called this function
    if (currentScrollTrigger !== null) {
      currentScrollTrigger.observer.disconnect();
      $('#sentinel-list').remove();
    }

    // Set correct params from loading list items before current list item
    if (loadOptions.direction === 'up') {
      params.direction = 'up';
      params.insertion_offset = window.insertionOffsetUp + 1;
      if (index < 10) {
        params.amount = index;
        params.index = 0;
      } else {
        params.amount = 10;
        params.index = index - 10;

        // Update pagination button with new index
        $loadMore.attr('data-index', params.index);
      }
    } else {
      params.direction = 'down';
      params.insertion_offset = window.insertionOffsetDown;
    }

    // Trigger ajax load on page load
    triggerAjaxLoad('/wp-admin/admin-ajax.php', 'GET', params, (res) => {
      if (res) {
        loadedItems += 1;
        if (loadOptions.direction === 'down') {
          listBody.append(res.data.html);
          $loadMore.attr('data-index', parseInt(index) + 10);

          // Add new pinterest buttons
          const newListItems = document.getElementsByClassName('list-items');
          window.parsePinBtns(newListItems[newListItems.length - 1]);
        } else {
          // Append content above without the page jumping
          const container = document.createElement('div');
          container.classList = 'list-items list-items-hide';
          container.innerHTML = res.data.html;

          $('.list-load-more-prev').after(container);
          let $container = $(container);

          // Scroll the height of the new div so it appears as if the content does not jump
          $container.removeClass('list-items-hide');
          const y = $(window).scrollTop();
          $(window).scrollTop(y + $container.innerHeight());

          if (params.index === 0) {
            $loadMore.remove();
          }
          // Add new pinterest buttons
          window.parsePinBtns(document.getElementsByClassName('list-items')[0]);
        }
        let listEvent = new CustomEvent('listContentLoaded', {
          detail: listBody.find('.list-items:last')
        });
        window.dispatchEvent(listEvent);

        // Check if there is any more content to load
        if ($loadMore.hasClass('no-ajax') && res.data.more) {
          $loadMore.find('a').attr('href', res.data.more);
        }
        else if (!res.data.more) {
          $loadMore.remove();
        // If we hit max amount of ajax loads stop scrollTrigger from setting up another listener
        } else {
          // Set up scroll trigger to load in more list items
          let sentinel = document.createElement('div');
          sentinel.id = 'sentinel-list';
          sentinel.className = 'sentinel-list';
          if (loadOptions.direction === 'down') {
            var forthItem = $('.list-items:last .list-item')[4];
            $(forthItem).append(sentinel);
            const options = {
              callback: loadListItems,
              type: 2,
              intersection: 0,
            };
            // Pass in current list we are appending scroll trigger to, so we know where to append the content
            currentScrollTrigger = new ScrollTrigger([document.querySelector('#sentinel-list')], { list: loadOptions.list }, options);
          }
          // Always add new href in case there a load problem on the next set of problems
          if (loadOptions.direction !== 'up') {
            $loadMore.find('a').attr('href', res.data.more);
          }

          // Stop more then 10 ajax loads from appending to the page
          if (loadedItems >= 9) {
            $loadMore.addClass('no-ajax');
            $loadMore.find('a').attr('href', res.data.more);
          }
        }

        // Set up scroll trigger to change url
        let currentState = '';
        const loadNewUrl = function (li) {
          const $li = $(li);
          const url = $li.attr('data-slug');
          if (url !== currentState) {
            document.title = $li.parent().find('.list-item-title').text();
            window.history.pushState({}, '', $li.attr('data-slug'));
          }
          currentState = url;
        };
        const triggerItems = Array.prototype.slice.call(document.getElementsByClassName('trigger'));
        let scrollHistory = new ScrollTrigger(triggerItems, '.list-item', {
          callback: loadNewUrl,
          type: 3,
          width: 0,
          threshold: [0, 0.1, 0.9, 1],
        });
        $('.trigger').removeClass('trigger');

        // Set up scroll trigger to change url back to gallery parent
        if (firstLoad) {
          const loadParentUrl = function (li) {
            const $li = $(li).parent().parent();
            window.history.pushState({}, $li.attr('data-tite'), $li.attr('data-url'));
          };
          firstLoad = false;

          const triggerParent = Array.prototype.slice.call($('article:last .feature__hero-image'));
          let scrollParent = new ScrollTrigger(triggerParent, '.feature__hero-image', {
            callback: loadParentUrl,
            type: 3,
            width: 0,
          });
        }
      }
    });
  }
}

// Load in previous list items if user clicks on load previous button
$('.list-load-more-prev').on('click touchstart', (e) => {
  // If the maximum amount of items have been loaded onto a page stop ajax loads
  if (!$(e.target).closest('.list-load-more-prev').hasClass('no-ajax')) {
    loadListItems({
      list: $(e.target).closest('article'),
      direction: 'up',
      button: '.list-load-more-prev',
    });
  }
});

function startPagination() {
  // Leave link back to the top if the on top list item
  let $prev = $('.list-load-more-prev');
  if ($prev.attr('data-index') != 0) {
    $prev.find('a').removeAttr('href');
  }

  loadListItems();
}


$(window).on('load', () => {
  startPagination();
});

$(window).on('newContentLoaded', () => {
  startPagination();
});
