const profile = {
  init() {
    if (app.wm.page == 'profile') {
      this.trying_checkbox();
      this.trying_checkbox_visibility();
      this.set_input_blur();
      this.bt_update();
      this.add_referral_invite();
      this.add_referral_textbox();
      this.remove_referral_invite();
      this.birthday_valid = [];
      new Clipboard('.referrer-form__copy-url'); // eslint-disable-line no-new
    }
  },
  is_trying_checked() {
    let state = false;
    const checkboxes = $('.hidden_trying');

    for (let i = checkboxes.length - 1; i >= 0; i--) {
      const val = checkboxes.eq(i).val();
      if (val == 1) {
        state = true;
      }
    }

    return state;
  },
  is_trying_visible() {
    let state = false;
    const checkboxes = $('.hidden_trying');

    for (let i = checkboxes.length - 1; i >= 0; i--) {
      if (
        !checkboxes
          .eq(i)
          .parent()
          .parent()
          .hasClass('hide')
      ) {
        state = true;
      }
    }

    return state;
  },
  trying_checkbox() {
    $(document).on('click', '.user-child-form__styled-checkbox', function () {
      if (!$(this).hasClass('fa-check')) {
        $(this).addClass('fa-check');
        var index = $(this).data('index');
        // console.log(index);
        $(`#child_${index}_trying`).val(1);
        $(this)
          .parent()
          .addClass('user-child-form__styled-checkbox--no-padding');
        $(this)
          .parent()
          .parent()
          .parent()
          .parent()
          .find('tr:first-child')
          .addClass('hide');
      } else {
        $(this).removeClass('fa-check');
        var index = $(this).data('index');
        $(`#child_${index}_trying`).val(0);
        $(this)
          .parent()
          .removeClass('user-child-form__styled-checkbox--no-padding');
        $(this)
          .parent()
          .parent()
          .parent()
          .parent()
          .find('tr:first-child')
          .removeClass('hide');
      }
    });
  },
  trying_checkbox_visibility() {
    for (
      var i = $('.user-child-form input[type=text]').length - 1;
      i >= 0;
      i--
    ) {
      if (
        $('.user-child-form input[type=text]')
          .eq(i)
          .val() != ''
      ) {
        $('.user-child-form input[type=text]')
          .eq(i)
          .parent()
          .parent()
          .parent()
          .parent()
          .find('tr:last-child')
          .addClass('hide');
      }
    }

    for (var i = $('.hidden_trying').length - 1; i >= 0; i--) {
      if (
        $('.hidden_trying')
          .eq(i)
          .val() == '1'
      ) {
        $('.hidden_trying')
          .eq(i)
          .parent()
          .addClass('user-child-form__styled-checkbox--no-padding');
        $('.hidden_trying')
          .eq(i)
          .parent()
          .parent()
          .parent()
          .parent()
          .find('tr:first-child')
          .addClass('hide');
      }
    }
  },
  hide_trying(elem, val) {
    var val = $(elem).val();
    if (
      val == ''
      && app.profile.is_trying_checked() == false
      && app.profile.is_trying_visible() == false
    ) {
      $(elem)
        .parent()
        .parent()
        .parent()
        .parent()
        .find('tr:last-child')
        .removeClass('hide');
    } else {
      $(elem)
        .parent()
        .parent()
        .parent()
        .parent()
        .find('tr:last-child')
        .addClass('hide');
    }
  },
  date_validation(elem) {
    const val = $(elem).val();
    const index = $($(elem)[0]).data('index');
    // console.log(index);
    if (app.profile.is_valid_date(val) || val == '') {
      $('.user-child-form input[type="text"]').data('index', index);
      app.profile.birthday_valid[index] = true;
      app.profile.show_message(elem);
      $('.button-primary').removeAttr('disabled');
      $('.button-primary').removeClass('disabled');
    } else {
      // console.log('invalid');
      app.profile.birthday_valid[index] = false;
      app.profile.show_message(elem);
      $('.button-primary').attr('disabled', 'disabled');
      $('.button-primary').addClass('disabled');
    }
  },
  set_input_blur() {
    $(document).on('blur', '.user-child-form input[type=text]', function () {
      app.profile.date_validation($(this));
      app.profile.bt_update();
    });

    $(document).on('blur', '.profile-location input[type=text]', function () {
      app.profile.zip_validation($(this));
    });

    $(document).on('keyup', '.user-child-form input[type=text]', function () {
      app.profile.hide_trying($(this));
    });

    $(document).on('blur', '.referrer-form__email-textarea', function () {
      app.profile.email_validation($(this));
    });
  },
  is_valid_date(dateString) {
    // First check for the pattern
    if (!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString)) return false;

    // Parse the date parts to integers
    const parts = dateString.split('/');
    const day = parseInt(parts[1], 10);
    const month = parseInt(parts[0], 10);
    const year = parseInt(parts[2], 10);

    // Check the ranges of month and year
    if (year < 1000 || year > 3000 || month == 0 || month > 12) return false;

    const monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    // Adjust for leap years
    if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)) { monthLength[1] = 29; }

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
  },
  show_message(elem) {
    const index = $($(elem)[0]).data('index');
    if (app.profile.birthday_valid[index] == true) {
      elem
        .parent()
        .find('.invalid-bday-notice')
        .hide();
    } else {
      elem
        .parent()
        .find('.invalid-bday-notice')
        .show();
    }
  },
  zip_validation(elem) {
    const val = $(elem).val();
    // console.log(val);
    if (val.match(/^\d{5}$/)) {
      $('.profile-location__invalid-zip-notice').hide();
      $('.button-primary').removeAttr('disabled');
      $('.button-primary').removeClass('disabled');
    } else {
      $('.profile-location__invalid-zip-notice').show();
      $('.button-primary').attr('disabled', 'disabled');
      $('.button-primary').addClass('disabled');
    }
  },
  bt_update() {
    const dates = [];
    const email = $('.profile-email input[type=text]').val();
    const postup_data = {
      address: email,
    };
    if ($('#user_saved').val() == 'false') {
      postup_data.channel = 'E';
      postup_data.status = 'N';
      postup_data.full_account = true;
    }

    for (var i = 0; i < $('.user-child-form input[type=text]').length; i++) {
      const val = $('.user-child-form input[type=text]')
        .eq(i)
        .val();
      if (val != '') {
        dates.push(val);
      }
    }
    for (var i = 0; i < dates.length; i++) {
      dates[i] = dates[i].split('/');
      const year = dates[i][dates[i].length - 1];
      const month = dates[i][dates[i].length - 3];
      const day = dates[i][dates[i].length - 2];
      dates[i] = new Date(year, month - 1, day);
    }

    for (var i = 0; i < dates.length; i++) {
      const bt_index = i + 1;
      const bt_bday = `child_${bt_index}_birthdate`;
      postup_data[bt_bday] = `${dates[i].toISOString().split('.')[0]}+00:00`;
    }
    // _bt.person.set(postup_data);
    app.postup.add_user(postup_data);
  },
  add_referral_invite() {
    $(document).on('click', '.referrer-form__add-more', () => {
      const email_val = $('.referrer-form__email-input').val();
      let full_email_list = $('#referrer_email_list').val();
      const separator = full_email_list != '' ? ',' : '';
      full_email_list = full_email_list + separator + email_val;
      $('.referrer-form__email-list').append(`<span>${email_val}</span>`);
      $('.referrer-form__email-input').val('');
      $('#referrer_email_list').val(full_email_list);
    });
  },
  add_referral_textbox() {
    $(document).on('focus', '.referrer-form__email-textarea', () => {
      $('.referrer-form__email-textarea').keypress((e) => {
        if (e.keyCode === 0 || e.keyCode === 32 || e.keyCode === 44) {
          e.preventDefault();
          app.profile.add_referral_email();
        }
      });
    });
  },
  add_referral_email(email_val) {
    var email_val = $('.referrer-form__email-textarea').val();
    if (email_val != '') {
      if (app.profile.is_valid_email(email_val)) {
        if ($('.referrer-form__no-emails').is(':visible')) {
          $('.referrer-form__no-emails').hide();
        }

        let full_email_list = $('#referrer_email_list').val();
        if (full_email_list != '') {
          var email_array = full_email_list.split(',');
        } else {
          var email_array = [];
        }
        email_array.push(email_val);
        full_email_list = email_array.join();
        $('.referrer-form__email-list').append(`<span class="referrer-form__invite-email-address referrer-form__invite-email-address--new-invite">${
          email_val
        }<i class="fas icon-cancel"></i></span>`);
        $('.referrer-form__email-textarea').val('');
        $('#referrer_email_list').val(full_email_list);
        $('.referrer-form__valid-message').hide();
      } else {
        $('.referrer-form__valid-message').show();
      }
    }
  },
  remove_referral_invite() {
    $(document).on(
      'click',
      '.referrer-form__invite-email-address--new-invite i',
      function () {
        const email = $(this)
          .parent()
          .text();
        let full_email_list = $('#referrer_email_list').val();
        const email_array = full_email_list.split(',');
        const index = email_array.indexOf(email);
        email_array.splice(index, 1);
        full_email_list = email_array.join();
        $('#referrer_email_list').val(full_email_list);
        $(this)
          .parent()
          .remove();
      },
    );
  },
  is_valid_email(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) { // eslint-disable-line no-useless-escape
      return true;
    }
    return false;
  },
  email_validation(elem) {
    const val = $(elem).val();
    if (app.profile.is_valid_email(val) || val == '') {
      app.profile.add_referral_email();
      $('.referrer-form__valid-message').hide();
      $('.button-primary').removeAttr('disabled');
      $('.button-primary').removeClass('disabled');
    } else {
      $('.referrer-form__valid-message').show();
      $('.button-primary').attr('disabled', 'disabled');
      $('.button-primary').addClass('disabled');
    }
  },
};

export default profile;
