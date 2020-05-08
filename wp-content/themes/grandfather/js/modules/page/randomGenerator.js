(function ($) {
  let $wordbox = $('.random-generator-output-container');
  let $items = null;
  let $children = null;
  var wordlist = $('.random-generator').length > 0 ? JSON.parse($('.random-generator').attr('data-output')) : null;

  function buildSlotItem(text) {
    return $('<div>').addClass('random-generator-output-text')
      .text(text);
  }

  function buildSlotContents($container, wordlist) {
    $items = wordlist.map(buildSlotItem);
    $container.append($items);
  }

  function popPushNItems($container, n) {
    $children = $container.find('.random-generator-output-text');
    $children.slice(0, n).insertAfter($children.last());

    if (n === $children.length) {
      popPushNItems($container, 1);
    }
  }

  // After the slide animation is complete, we want to pop some items off
  // the front of the container and push them onto the end. This is
  // so the animation can slide upward infinitely without adding
  // inifinte div elements inside the container.
  function rotateContents($container, n) {
    setTimeout(() => {
      popPushNItems($container, n);
      $container.css({ top: 0 });
    }, 300);
  }

  function randomSlotIndex(max) {
    var randIndex = (Math.floor(Math.random() * max));
    return (randIndex > 10) ? randIndex : randomSlotIndex(max);
  }

  function animate() {
    var wordIndex = randomSlotIndex(wordlist.length);
    $wordbox.animate({ top: -wordIndex * $('.random-generator-output-text').height() }, 500, 'swing', () => {
      rotateContents($wordbox, wordIndex);
    });
  }

  $('body').on('touchend click', '.random-generator-button', (e) => {
    e.preventDefault();
    animate();
  });

  if ($('.single-random_generator').length > 0) {
    // Create first animation affect on load
    buildSlotContents($wordbox, wordlist);
    buildSlotContents($wordbox, wordlist);
    buildSlotContents($wordbox, wordlist);
    buildSlotContents($wordbox, wordlist);
    animate();
  }
}(jQuery, this));
