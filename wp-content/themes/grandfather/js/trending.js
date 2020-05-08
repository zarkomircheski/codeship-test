import triggerAjaxLoad from './helpers/ajaxLoad';
import cookies from './helpers/cookies';

$(document).ready(() => {
  let trendingHtml = document.createElement('div');
  let $recirculation = $('.recirculation-content-trending');
  let $trending = window.innerWidth > 999 && $('.article__body-rail-ad').length > 0 && $('.sponsored__logo').length < 1;
  let $addRecirc = $recirculation.length > 0 && (!$recirculation.parent().hasClass('mobile') || window.innerWidth < 1000);

  const insertTrendingContent = function (trending) {
    if ($addRecirc) {
      // Allow mobile only recirc module to be be seen
      $recirculation.parent().removeClass('mobile');

      let recircHtml = document.createElement('div');
      let content = '';
      for (let i = 0; i < 2; i++) {
        content += '<div class="recirculation-content-item">' +
          '<div class="recirculation-content-item-image">' +
          `<a href="${trending.data[i].url}" data-ev-loc="Article - Body" data-ev-name="Recirculation - Image">` +
          `<img class="recirculation-item-image" src="${trending.data[i].thumb_url_medium}?w=60"></a></div>` +
          '<div class="recirculation-content-item-title">' +
          `<a href="${trending.data[i].url}" data-ev-loc="Article Body" data-ev-name="Recirculation - Headline">` +
          `${trending.data[i].title}</a></div></div>`;
      }
      recircHtml.innerHTML = content;
      $recirculation.append(recircHtml);
    }

    if ($trending) {
      trendingHtml.className = 'trending';
      let content = '<h2 class="trending-title">Trending Now</h2>';
      if (trending.success) {
        for (let article in trending.data) {
          content += '<a data-ev-loc="Right Rail" data-ev-name="Recirculation" data-ev-val="' +
            `${trending.data[article].url}" href="${trending.data[article].url}">` +
            '<div class="trending-item">' +
            `<img class="trending-item-image" src="${trending.data[article].thumb_url_medium}">` +
            `<div class="trending-item-title">${trending.data[article].title}</div>` +
            '</div></a>';
        }
        trendingHtml.innerHTML = content;
        $('.article__body-rail-trending').append(trendingHtml);
      }
    }
    // Make sure clicks are tracked
    $(window).trigger('trendingLoaded');
  };

  if ($trending || $addRecirc) {
    let params = {};
    let path = '/v2/analytics/posts';

    if (envConfig.parsely_secret_key) {
      params = {
        apikey: 'fatherly.com',
        secret: envConfig.parsely_secret_key,
        limit: '4',
        page: '1',
        sort: 'views',
        period_start: '24h',
      };

      path = 'https://api.parsely.com/v2/analytics/posts';
    }

    triggerAjaxLoad(path, 'GET', params, insertTrendingContent);
  }

  $(window).on('newContentLoaded', (event, element) => {
    $('article:last .article__body-rail-trending').append(trendingHtml);
  }); // triggered in load-more.js
});
