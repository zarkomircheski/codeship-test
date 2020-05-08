import cookies from './cookies';

const modal = {
  init() {
    this.survey();
  },
  survey() {
    $(document).on('surveyActive', () => {
      let survey = '<div class="site-modal__screen">';
      survey += '<div class="site-modal__modal">';
      survey += '<a id="surveyLink" href="" target="_blank" rel="noopener noreferrer">';
      survey += '<img src="https://images.fatherly.com/wp-content/uploads/2017/11/Image-uploaded-from-iOS.jpg" alt="Survey Callout Image"></a>';
      survey += '<a class="site-modal__close"><i class="fas icon-cancel" aria-hidden="true"></i></a>';
      survey += '</div></div>';
      $('.site-footer').first().append(survey);
      $('#surveyLink').attr('href', window.surveyUrl);
      const fth_survey_cookie = cookies.read_cookie('hide_survey');
      if (!fth_survey_cookie) {
        cookies.create_cookie('hide_survey', true, 7);
        setTimeout(() => {
          app.modal.add_survey();
        }, 2000);
      }
    });
  },
  add_survey() {
    $('.site-modal__screen').fadeIn(() => {
      $('.site-modal__modal').fadeIn(() => {
        const click_event = app.wm.device == 'mobile' ? 'touchstart' : 'click';
        $(document).on(click_event, '.site-modal__close, .site-modal__screen', () => {
          $('.site-modal__modal').fadeOut(() => {
            $('.site-modal__screen').fadeOut(() => {});
          });
        });
      });
    });
  },
};

export default modal;
