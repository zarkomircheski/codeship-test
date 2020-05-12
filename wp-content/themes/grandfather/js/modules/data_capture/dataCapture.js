import cookies from '../../helpers/cookies';
import triggerAjaxLoad from '../../helpers/ajaxLoad';

// Use this count to differentiate each kid
let count = 2;
const newsletterLanding = $('.newsletter--signup-landing');
let recipientID = newsletterLanding.length > 0 && newsletterLanding.attr('data-recip') ? newsletterLanding.attr('data-recip') : null;
let referrerID = newsletterLanding.length > 0 && newsletterLanding.attr('data-referrer') ? newsletterLanding.attr('data-referrer') : null;
let touchmoved = false;
let $weights = {
};
let userEmail = null;

let moduleId;

// Get parsely UUID
const parsely_cookies = decodeURIComponent(cookies.getCookie('_parsely_visitor'));
let jsonObj = '';

try {
  jsonObj = JSON.parse(parsely_cookies);
} catch (error) {
  jsonObj = { id: 'error-accessing-uuid' };
}


function validateEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; // eslint-disable-line no-useless-escape
  return re.test(email);
}

function triggerOverlay(event) {
  if (event) {
    event.preventDefault();
  }
  // Make sure flyout is viewable when overlay is clicked
  $('.email-submit.flyout').addClass('show');

  let $footerSubscribe = $('.newsletter__button');
  $(`.${$footerSubscribe.attr('data-form')}`).addClass('show');
  $('.email-submit-form-input').focus();
}


$('body').on('submit', '.email-submit-form', (e) => {
  e.preventDefault();
  let $form = $(e.currentTarget);
  let email = $form.find('.email-submit-input, .email-submit-form-input').val();

  if (PARSELY && PARSELY.config && PARSELY.config.parsely_site_uuid) {
    jsonObj = { id: PARSELY.config.parsely_site_uuid };
  }

  if (validateEmail(email)) {
    // Setup data used for postup signup
    const postupData = {
      address: email,
      externalId: email,
      channel: 'E',
      status: 'N',
      signup_url: window.location.href.substring(0, 255),
      list_id: $form.attr('data-list') ? $form.attr('data-list') : 52,
      parsely_uuid: jsonObj.id,
      multi_variant: cookies.getCookie('2018multi'),
      module_location: $form.attr('data-ev-loc'),
      referrer_id: referrerID,
    };

    userEmail = email;

    // Send data to backend so curl requests can be made to postup
    triggerAjaxLoad('/wp-content/themes/grandfather/parts/postup-put.php', 'POST', postupData, (res) => {
      let response = JSON.parse(res);
      if (response.recipientId !== undefined) {
        recipientID = response.recipientId;
        let $dataForm = $form.parent().find('+ .data-collection');
        if ($dataForm.length > 0) {
          $dataForm.addClass('show');
          $form.parent().addClass('hide');
          // Trigger overlay when data collection is shown
          // This logic only applies to the flyout module
          if ($form.parent().hasClass('flyout')) {
            triggerOverlay();
          }
        } else {
          $form.addClass('show show-success');
          $form.removeClass('show show-error');
        }

        // Add fromEmail cookie since the user has just signed up for the NL 90 days
        let expires = new Date(new Date().getTime() + (90 * 24 * 60 * 60 * 1000));
        document.cookie = `fromEmail=true; expires=${expires}; path=/;`;
      } else {
        // Display error message telling the user there was a problem with the email
        $form.addClass('show show-error');
      }
    });
  } else {
    // Display error message telling the user there was a problem with the email
    $form.addClass('show show-error');
  }
});

$('body').on('touchend click', '.newsletter__button', (e) => {
  if (touchmoved === false) {
    triggerOverlay(e);
  }
}).on('touchmove', (e) => {
  touchmoved = true;
}).on('touchstart', (e) => {
  touchmoved = false;
});

/*
 On touch devices move NL flyout to the top of the page on click.
 This prevents the keyboard from overlaying it
*/
$('body').on('touchend', '.email-submit.flyout.show:not(.overlayed)', (e) => {
  // Don't trigger overlay if user clicked on the close button
  if (touchmoved === false && !$(e.target).hasClass('email-submit-close')) {
    // Stop the overlay from trying to open a second time
    $(e.currentTarget).addClass('overlayed');
    triggerOverlay(e);
  }
}).on('touchmove', (e) => {
  touchmoved = true;
}).on('touchstart', (e) => {
  touchmoved = false;
});

// Add form so user can add an additional child
$('.data-collection-info-buttons-add').on('touchend click', (e) => {
  let newForm = `<div class="data-collection-info-form-child child-${count}">`
    + `<input type="date" name="dob-${count}" min="2000-01-01" max="2022-01-01" required>`
    + '<div class="data-collection-info-form-child-gender">'
    + `<input type="radio" name="gender-${count}" value="male" required><span>Girl</span>`
    + `<input type="radio" name="gender-${count}" value="female" required><span>Boy</span>`
    + `<input type="radio" name="gender-${count}" value="other" required><span>Other</span>`
    + `<input type="radio" name="gender-${count}" value="notSure" required><span>Not Sure</span></div></div>`;

  // Add new form before the add a child button
  $(e.currentTarget).parent().parent().find('form')
    .append(newForm);
  count++;
});

function updatePostupProfile($data, $form) {
  // Sanitize data that was sent
  let children;

  if ($data === 'no-kids') {
    children = $data;
  } else {
    children = [];

    for (let i = 0; i < $data.length; i++) {
      if (i % 2 === 0) {
        children.push({
          dob: $data[i].value,
          gender: $data[i + 1].value,
        });
      }
    }
  }

  const updateUser = {
    action: 'fth_ajax_data_collection',
    type: 'children',
    uuid: jsonObj.id,
    // you need to strigify sub arrays in an ajax request so it remains properly formatted
    userData: children,
    recipientID,
  };

  if (userEmail) {
    updateUser.email = userEmail;
  }

  // Send data to backend so curl requests can be made to postup
  triggerAjaxLoad('/wp-admin/admin-ajax.php', 'POST', updateUser, (res) => {
    if (res) {
      $form.closest('.data-collection').addClass('success');
    } else {
      $form.closest('.data-collection').addClass('error');
    }
  });
}

function submitUserAnswer(question, answer, $form, type) {
  if (moduleId === undefined) {
    moduleId = $('.data-collection').attr('data-id');
  }
  if (PARSELY && PARSELY.config && PARSELY.config.parsely_site_uuid) {
    jsonObj = { id: PARSELY.config.parsely_site_uuid };
  }

  const addSurveyInfo = {
    action: 'fth_ajax_data_collection',
    question: question.replace(/[^a-zA-Z0-9.?!, ]/g, ''),
    answer: answer.replace(/[^a-zA-Z0-9.?!, ]/g, ''),
    moduleId,
    uuid: jsonObj.id,
    type,
  };

  if ($form !== null) {
    const $dataCollection = $form.closest('.data-collection') ? $form.closest('.data-collection') : null;

    if ($dataCollection.hasClass('fatherly_iq')) {
      $dataCollection.addClass('success');
    }
  }

  // Add survey answer to db
  triggerAjaxLoad('/wp-admin/admin-ajax.php', 'POST', addSurveyInfo, (res) => {
    if ($form !== null) {
      if (JSON.parse(res) === 1) {
        $dataCollection.addClass('success');
      } else {
        $dataCollection.addClass('error');
      }
    }
  });
}

// Grab user entered information on submit
$('body').on('submit', '.data-collection-info-form', (e) => {
  e.preventDefault();
  let $form = $(e.currentTarget);
  updatePostupProfile($form.serializeArray(), $form);
});

// Grab user entered information for fatherly iq module
$('body').on('click touchend', '.fatherly_iq .data-collection-info-form-survey-answer', (e) => {
  e.preventDefault();
  let $survey = $(e.currentTarget).closest('.data-collection-info-form-survey');

  if (!touchmoved && $survey.attr('data-clicked') === 'false') {
    $survey.attr('data-clicked', true);
    let answer = $(e.target).attr('data-answer');
    let question = $survey.find('.data-collection-info-form-survey-question').attr('data-question');
    submitUserAnswer(question, answer, $survey.closest('.data-collection-info-form'), 'iq');
  }
}).on('touchmove', (e) => {
  touchmoved = true;
}).on('touchstart', (e) => {
  touchmoved = false;
});


$('body').on('click touchend', '.data-collection-info-other', (e) => {
  e.preventDefault();
  const $form = $(e.currentTarget).parent().find('form');
  updatePostupProfile('no-kids', $form);
});

// Close overlay if user clicks on just the overlay or the close button
$('body').on('touchend click', '.newsletter__overlay, .email-submit-close', (e) => {
  e.preventDefault();

  let $target = $(e.target);

  if ($target.hasClass('email-submit-close')) {
    $target.parent().removeClass('show');
    // The flyout acts differently on desktop and mobile so custom logic is needed
    if ($('.inline-tap.show').length > 0) {
      $target = $('.newsletter__overlay');
    }
  }

  if ($target.hasClass('newsletter__overlay')) {
    $target.parent().removeClass('show');
    $('.newsletter__button').removeClass('show');
    $('.mobile-ad-nav').addClass('hide');
  }

  // Add fromEmail cookie so the overlay does not show again to this user for 30 days
  let expires = new Date(new Date().getTime() + (15 * 24 * 60 * 60 * 1000));
  document.cookie = `noFlyout=true; expires=${expires}; path=/;`;
});

$('body').on('touchend click', '.data-collection-info-buttons-remove', (e) => {
  e.preventDefault();
  if (count > 2) {
    count--;
    $(`.child-${count}`).remove();
  }
});

/**
 * Registry Specific JS
 */
function calculateWinner() {
  let winner = {
    result: null,
    total: null,
  };

  for (const weight in $weights) {
    if (winner.total < $weights[weight] || winner.total === null) {
      winner.total = $weights[weight];
      winner.result = weight;
    }
  }

  return winner.result;
}

function updateWeights($currentAnswer, direction, isLast) {
  const $newWeights = JSON.parse($currentAnswer.attr('data-weights'));
  const inverse = direction === 'right' ? 1 : -1;
  for (let weight in $newWeights) {
    const { result_page } = $newWeights[weight];

    if ($weights[result_page]) {
      $weights[result_page] += ($newWeights[weight].weight * inverse);
    } else {
      $weights[result_page] = ($newWeights[weight].weight * inverse);
    }
  }
  /*
  If you finish the survey add a cookie indicating the user has completed it
  and bring the user to the winning result_page
  */
  if (isLast) {
    let date = new Date();
    // set the cookie for 30 days
    date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = `finishedSurvey=true; Path=/; expires=${date.toGMTString()};`;
    window.location.href = calculateWinner();
  }
}

function registrySubmit($currentAnswer, direction) {
  const $nextQuestionIndex = parseInt($currentAnswer.attr('id').substr(-1)) + 1;
  let $nextQuestion = $(`#pane${$nextQuestionIndex}`);

  // Show the user the next question
  $currentAnswer.removeClass('show');
  $nextQuestion.addClass('show');


  // Update counter
  let $counter = $('.data-collection-cta-counter span');
  if ($counter.attr('data-length') > $nextQuestionIndex) {
    $counter.text($nextQuestionIndex + 1);
  }

  // Get text of the question and answer
  let $question = $currentAnswer.find('.data-collection-info-form-survey-question').attr('data-question');
  let $answer = '';
  if (direction === 'right') {
    $answer = $currentAnswer.find('.data-collection-info-form-survey-answer.right').attr('data-answer');
  } else {
    $answer = $currentAnswer.find('.data-collection-info-form-survey-answer.left').attr('data-answer');
  }


  // Insert answer into db
  submitUserAnswer($question, $answer, null, 'registry');

  // Keep a running total of the weights
  updateWeights($currentAnswer, direction, $nextQuestion.hasClass('success'));
}

// Capture when the user clicks on an answer or an arrow button on the bottom of the page
$('body').on('click touchend', '.registry .data-collection-info-form-survey-answer', (e) => {
  // Get current survey question
  let $survey = $('.data-collection-info-form-slider-question.show .data-collection-info-form-survey');

  // Only allow a user to submit if they did not swipe and have not clicked on an answer before
  if (touchmoved === false && $survey.attr('data-clicked') === 'false') {
    $survey.attr('data-clicked', true);

    // Figure out if the user selected the left or right answer
    const direction = $(e.currentTarget).hasClass('right') ? 'right' : 'left';

    // Get current answer
    let $answer = $('.data-collection-info-form-slider-question.show');

    // Update direction so weight and answer are reccorded correctly
    $answer.addClass(`move-${direction}`);

    // Allow animation to finish before moving to the next question
    setTimeout(() => {
      registrySubmit($answer, direction);
    }, 500);
  }
}).on('touchmove', (e) => {
  touchmoved = true;
}).on('touchstart', (e) => {
  touchmoved = false;
});

/*
  If a user has completed the survey prompt them to share thier results.
  otherwise prompt the user to take the survey
 */
if ($('.registry_landing-template-default').length > 0) {
  if (document.cookie.indexOf('finishedSurvey') > -1) {
    $('.registry-landing-header-button-share').addClass('show');
  } else {
    $('.registry-landing-header-button-start').addClass('show');
  }
}
